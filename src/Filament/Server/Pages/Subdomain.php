<?php

namespace Maximilian1121\Minegate\Filament\Server\Pages;

use App\Enums\TablerIcon;
use App\Models\Server;
use BackedEnum;
use Filament\Facades\Filament;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Maximilian1121\Minegate\Models\ServerData;
use Maximilian1121\Minegate\Services\MinegateClient;

class Subdomain extends Page
{
    protected static string|BackedEnum|null $navigationIcon = TablerIcon::Globe;

    // Same sort value as Network on purpose. Core nav items get discovered
    // before plugin ones, and the sort is stable, so tying the number
    // slots Subdomain in right after Network instead of reshuffling anything.
    protected static ?int $navigationSort = 7;

    protected string $view = 'minegate::filament.server.pages.subdomain';

    public ?string $subdomain = null;
    public ?string $host = null;
    public ?string $port = null;
    /** @var array{
     *     status: string,
     *     root_domain: string,
     *     uptime_seconds: int,
     *     uptime: string
     * }|false $health
     */
    public array|false $health = false;


    public static function getNavigationLabel(): string
    {
        return 'Subdomains';
    }

    public function getTitle(): string
    {
        return 'Subdomains via Minegate';
    }

    /**
     * @return array{
     *     status: string,
     *     root_domain: string,
     *     uptime_seconds: int,
     *     uptime: string
     * }
     */
    public function minegateHealth(): array
    {
        $healthResponse = app(MinegateClient::class)->checkHealth();

        if ($healthResponse) {
            $this->health = $healthResponse;
            return $this->health;
        }


        $this->health = [
            'status' => 'down',
            'root_domain' => 'unknown',
            'uptime_seconds' => 0,
            'uptime' => '0s',
        ];
        return $this->health;
    }


    public function mount(): void
    {
        $this->subdomain = $this->record()->subdomain;
        if (isset($this->subdomain)) {
            $route = app(MinegateClient::class)->getRoute($this->subdomain);

            if ($route) {
                $this->host = $route['host'] ?? null;
                $this->port = $route['port'] ?? null;
            } else {
                $minegate_health = $this->minegateHealth();

                if ($minegate_health['status'] == 'ok') {
                    $data = $this->record();
                    $data->subdomain = null;
                    $data->save();

                    Notification::make()
                        ->title("Subdomain not found!")
                        ->danger()
                        ->send();

                    $this->redirect(request()->header('Referer'));
                }
            }
        } else {
            $this->host = null;
            $this->port = null;
        }
    }

    // public function save(): void
    // {
    //     $data = $this->record();
    //     $data->subdomain = $this->subdomain;
    //     $data->save();

    //     Notification::make()
    //         ->title('Subdomain saved')
    //         ->success()
    //         ->send();
    // }

    public ?string $newSubdomain = null;
    public ?string $newHost = null;
    public ?int $newPort = null;

    public function createSubdomain(): void
    {
        $this->validate([
            'newSubdomain' => ['required', 'string', 'max:255'],
            'newHost' => ['required', 'string', 'ipv4'],
            'newPort' => ['required', 'integer', 'min:1', 'max:65535'],
        ]);

        $existing_route_data = app(MinegateClient::class)->getRoute($this->newSubdomain);

        if ($existing_route_data) {
            Notification::make()
                ->title("That subdomain was already taken!")
                ->danger()
                ->send();
        } else {
            $route = app(MinegateClient::class)->createRoute($this->newSubdomain, $this->newHost, $this->newPort);
            if ($route) {
                $data = $this->record();
                $data->subdomain = $this->newSubdomain;
                $data->save();

                Notification::make()
                    ->title("Successfully created subdomain!")
                    ->success()
                    ->send();

                $this->redirect(request()->header('Referer'));
            }
        }
    }

    public function deleteSubdomain(): void
    {
        $data = $this->record();
        if (isset($data) && filled($data->subdomain)) {
            app(MinegateClient::class)->deleteRoute($data->subdomain);
        }

        $existing_route_data = app(MinegateClient::class)->getRoute($data?->subdomain);

        if ($existing_route_data) {
            Notification::make()
                ->title("Failed to remove subdomain!")
                ->danger()
                ->send();
        } else {
            if ($data) {
                $data->subdomain = null;
                $data->save();
            }

            Notification::make()
                ->title("Successfully removed subdomain!")
                ->success()
                ->send();

            $this->redirect(request()->header('Referer'));
        }
    }

    public function updateSubdomain(): void
    {
        // Validate the inputs just like you did for creation
        $this->validate([
            'host' => ['required', 'string', 'ipv4'],
            'port' => ['required', 'integer', 'min:1', 'max:65535'],
        ]);

        if (empty($this->subdomain)) {
            return;
        }

        // Call the updateRoute method on your MinegateClient
        $updatedRoute = app(MinegateClient::class)->updateRoute(
            $this->subdomain,
            $this->host,
            $this->port
        );

        if ($updatedRoute) {
            Notification::make()
                ->title("Successfully updated routing!")
                ->success()
                ->send();
        } else {
            Notification::make()
                ->title("Failed to update route.")
                ->danger()
                ->send();
        }
    }

    protected function record(): ServerData
    {
        /** @var Server $server */
        $server = Filament::getTenant();

        return ServerData::firstOrCreate(['server_id' => $server->id]);
    }
}