<?php

namespace Aesis\Prompts;

use Aesis\Prompts\Services\PromptService;
use Illuminate\Support\Facades\Blade;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

final class PromptsServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        $package
            ->name('laravel-prompts')
            ->hasConfigFile('prompts');
    }

    public function register(): void
    {
        parent::register();

        $this->app->singleton(PromptService::class);
    }

    public function boot(): void
    {
        parent::boot();

        Blade::directive('prompt', static function (string $expression): string {
            return "<?php echo app(\\Aesis\\Prompts\\Services\\PromptService::class)->renderPart({$expression}); ?>";
        });
    }
}
