# Laravel Prompts

Versioned Markdown Blade prompt templates for Laravel applications.

## Installation

```bash
composer require curly-deni/laravel-prompts
```

Laravel discovers the service provider automatically. Publish the config when
you need to customize the prompt directory or active versions:

```bash
php artisan vendor:publish --tag=laravel-prompts-config
```

By default templates are read from `resources/prompts`. Organize them under
`layouts` and `parts`, using the logical name and version in each filename:

```text
resources/prompts/
├── layouts/welcome/greeting_v2.md.blade.php
└── parts/shared/signature_v1.md.blade.php
```

Set active versions in `config/prompts.php`. Names can be dot-separated keys or
nested arrays:

```php
'layouts' => [
    'welcome.greeting' => 2,
],
'parts' => [
    'shared.signature' => 1,
],
```

## Usage

Render a full prompt and pass data to its Blade template:

```php
use Aesis\Prompts\Services\PromptService;

$prompt = app(PromptService::class)->renderPrompt('welcome.greeting', [
    'name' => 'Ada',
]);
```

Render a reusable part from a Blade view:

```blade
@prompt('shared.signature')
```

You can select a part version explicitly with `renderPart('shared.signature', 2)`
or `@prompt('shared.signature', 2)`. Template names accept letters, numbers,
underscores, and hyphens in each dot-separated segment.

## Development

```bash
composer test
composer format
composer analyse
```

## License

MIT. See [LICENSE.md](LICENSE.md).
