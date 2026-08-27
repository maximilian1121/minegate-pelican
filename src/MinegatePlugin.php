<?php

namespace Maximilian1121\Minegate;

use App\Contracts\Plugins\HasPluginSettings;
use App\Traits\EnvironmentWriterTrait;
use Filament\Contracts\Plugin;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Panel;

class MinegatePlugin implements Plugin, HasPluginSettings
{
    use EnvironmentWriterTrait;

    public function getId(): string
    {
        return 'minegate';
    }

    public function register(Panel $panel): void
    {
        match ($panel->getId()) {
            'server' => $panel->discoverPages(
                plugin_path($this->getId(), 'src/Filament/Server/Pages'),
                'Maximilian1121\\Minegate\\Filament\\Server\\Pages'
            ),
            'admin' => $panel->discoverPages(
                plugin_path($this->getId(), 'src/Filament/Admin/Pages'),
                'Maximilian1121\\Minegate\\Filament\\Admin\\Pages'
            ),
            default => null,
        };
    }

    public function boot(Panel $panel): void
    {
        //
    }

    public function getSettingsFormData(): array
    {
        return config('minegate');
    }

    public function getSettingsForm(): array
    {
        return [
            TextInput::make('api_host')
                ->label('Minegate API Host')
                ->placeholder('http://minegate-api:8080')
                ->helperText('Internal address only. This must be reachable directly from the panel server (Docker network, Tailscale, localhost, etc), not a public URL.')
                ->required(),
        ];
    }

    public function saveSettings(array $data): void
    {
        $this->writeToEnvironment([
            'MINEGATE_API_HOST' => $data['api_host'],
        ]);

        Notification::make()
            ->title('Settings saved')
            ->success()
            ->send();
    }
}