<?php

namespace App\Providers\Filament;

use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use App\Filament\Pages\Dashboard;
use App\Filament\Widgets\ReportStats;
use App\Filament\Widgets\ReportChart;
use App\Filament\Widgets\ReportListWidget;
use App\Filament\Widgets\RevenueChart;
use App\Filament\Widgets\DeadlineWidget;

class AdminPanelProvider extends PanelProvider
{
    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->login()
            ->colors([
                'primary' => Color::Amber,
            ])
            ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
            ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
            ->pages([
                Dashboard::class,
            ])
            ->widgets([
                DeadlineWidget::class,
                ReportStats::class,
                ReportChart::class,
                RevenueChart::class,
                ReportListWidget::class,
                Widgets\AccountWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->authMiddleware([
                Authenticate::class,
            ])
            ->brandName('P24 Admin')
            ->favicon(null)
            ->navigationGroups([
                NavigationGroup::make()
                    ->label('Reports')
                    ->items([
                        NavigationItem::make('Neue Reports')
                            ->icon('heroicon-o-document-text')
                            ->url('/admin/reports'),
                        NavigationItem::make('In Bearbeitung')
                            ->icon('heroicon-o-document-text')
                            ->url('/admin/in-progress-reports'),
                        NavigationItem::make('Halteranfrage versendet')
                            ->icon('heroicon-o-document-text')
                            ->url('/admin/holder-inquiry-received-reports'),
                        NavigationItem::make('Halterabfrage zurück')
                            ->icon('heroicon-o-document-text')
                            ->url('/admin/completed-reports'),
                    ]),
            ]);
    }
}
