<?php

namespace DefStudio\FilamentMoney;

use DefStudio\FilamentMoney\Commands\FilamentMoneyCommand;
use DefStudio\FilamentMoney\Testing\TestsFilamentMoney;
use Filament\Support\Assets\Asset;
use Filament\Support\Assets\Css;
use Filament\Support\Assets\Js;
use Filament\Support\Facades\FilamentAsset;
use Filament\Support\Facades\FilamentIcon;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Number;
use Livewire\Features\SupportTesting\Testable;
use Spatie\LaravelPackageTools\Commands\InstallCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Symfony\Component\Intl\Currencies;

class FilamentMoneyServiceProvider extends PackageServiceProvider
{
    public static string $name = 'filament-money';

    public static string $viewNamespace = 'filament-money';

    public function configurePackage(Package $package): void
    {
        $package->name(static::$name)
            ->hasInstallCommand(function(InstallCommand $command) {
                $command
                    ->publishConfigFile()
                    ->publishMigrations()
                    ->askToRunMigrations()
                    ->askToStarRepoOnGitHub('defstudio/filament-money');
            });

        $configFileName = $package->shortName();

        if (file_exists($package->basePath("/../config/{$configFileName}.php"))) {
            $package->hasConfigFile();
        }

        if (file_exists($package->basePath('/../resources/lang'))) {
            $package->hasTranslations();
        }
    }

    public function packageRegistered(): void
    {
    }

    public function packageBooted(): void
    {
        // Asset Registration
        FilamentAsset::register(
            $this->getAssets(),
            $this->getAssetPackageName()
        );

        FilamentAsset::registerScriptData(
            $this->getScriptData(),
            $this->getAssetPackageName()
        );

        // Icon Registration
        FilamentIcon::register($this->getIcons());

        // Handle Stubs
        if (app()->runningInConsole()) {
            foreach (app(Filesystem::class)->files(__DIR__.'/../stubs/') as $file) {
                $this->publishes([
                    $file->getRealPath() => base_path("stubs/filament-money/{$file->getFilename()}"),
                ], 'filament-money-stubs');
            }
        }

        // Testing
        Testable::mixin(new TestsFilamentMoney);
    }

    protected function getAssetPackageName(): ?string
    {
        return 'defstudio/filament-money';
    }

    /**
     * @return array<Asset>
     */
    protected function getAssets(): array
    {
        return [
            Css::make('filament-money-styles', __DIR__.'/../resources/dist/filament-money.css'),
            Js::make('filament-money-scripts', __DIR__.'/../resources/dist/filament-money.js'),
        ];
    }

    /**
     * @return array<class-string>
     */
    protected function getCommands(): array
    {
        return [
            FilamentMoneyCommand::class,
        ];
    }

    /**
     * @return array<string>
     */
    protected function getIcons(): array
    {
        return [];
    }

    /**
     * @return array<string>
     */
    protected function getRoutes(): array
    {
        return [];
    }

    /**
     * @return array<string, mixed>
     */
    protected function getScriptData(): array
    {
        return [
            'currency' => Number::defaultCurrency(),
            'currencySymbol' => Currencies::getSymbol(Number::defaultCurrency()),
        ];
    }
}
