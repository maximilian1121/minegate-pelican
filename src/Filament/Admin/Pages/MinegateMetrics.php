<?php

namespace Maximilian1121\Minegate\Filament\Admin\Pages;

use App\Enums\TablerIcon;
use BackedEnum;
use Filament\Pages\Page;
use Maximilian1121\Minegate\Services\MinegateClient;

class MinegateMetrics extends Page
{
    protected static string|BackedEnum|null $navigationIcon = TablerIcon::ChartBar;

    protected string $view = 'minegate::filament.admin.pages.minegate-metrics';

    public ?array $status = null;

    public static function getNavigationLabel(): string
    {
        return 'Minegate Metrics';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Minegate';
    }

    public function getTitle(): string
    {
        return 'Minegate Metrics';
    }

    public static function canAccess(): bool
    {
        return user()?->isRootAdmin() ?? false;
    }

    public function mount(): void
    {
        $this->pollStatus();
    }

    public function pollStatus(): void
    {
        $this->status = app(MinegateClient::class)->fetchStatus();

    }
}