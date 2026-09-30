<?php

namespace Aesis\Prompts\Services;

use Illuminate\Support\Facades\Blade;
use InvalidArgumentException;
use RuntimeException;

final class PromptService
{
    public function renderPrompt(string $name, array $data = []): string
    {
        return $this->renderVersioned('layouts', $name, $this->activeVersion('layouts', $name), $data);
    }

    public function renderPart(string $name, ?int $version = null): string
    {
        return $this->renderVersioned(
            'parts',
            $name,
            $version ?? $this->activeVersion('parts', $name),
        );
    }

    private function activeVersion(string $directory, string $name): mixed
    {
        $versions = config("prompts.{$directory}", []);

        if (is_array($versions) && array_key_exists($name, $versions)) {
            return $versions[$name];
        }

        foreach (explode('.', $name) as $segment) {
            if (! is_array($versions) || ! array_key_exists($segment, $versions)) {
                return null;
            }

            $versions = $versions[$segment];
        }

        return $versions;
    }

    private function renderVersioned(string $directory, string $name, mixed $version, array $data = []): string
    {
        $segments = explode('.', $name);

        foreach ($segments as $segment) {
            if ($segment === '' || ! preg_match('/^[A-Za-z0-9][A-Za-z0-9_-]*$/', $segment)) {
                throw new InvalidArgumentException("Invalid prompt name [{$name}].");
            }
        }

        if ($name === '') {
            throw new InvalidArgumentException("Invalid prompt name [{$name}].");
        }

        if (! is_int($version) || $version < 1) {
            throw new InvalidArgumentException("Prompt [{$name}] has no active version.");
        }

        $filename = array_pop($segments)."_v{$version}.md.blade.php";
        $relativePath = implode(DIRECTORY_SEPARATOR, [...$segments, $filename]);
        $path = rtrim((string) config('prompts.path', resource_path('prompts')), DIRECTORY_SEPARATOR)
            .DIRECTORY_SEPARATOR.$directory.DIRECTORY_SEPARATOR.$relativePath;

        if (! is_file($path)) {
            throw new RuntimeException("Prompt file [{$path}] does not exist.");
        }

        $template = file_get_contents($path);

        if ($template === false) {
            throw new RuntimeException("Prompt file [{$path}] could not be read.");
        }

        $phpTagMarker = '__PROMPT_PHP_TAG_'.hash('sha256', $template).'__';
        $template = str_replace(
            ['<?', '?>'],
            [$phpTagMarker.'OPEN', $phpTagMarker.'CLOSE'],
            $template,
        );

        $rendered = Blade::render($template, $data, deleteCachedView: true);

        return str_replace(
            [$phpTagMarker.'OPEN', $phpTagMarker.'CLOSE'],
            ['<?', '?>'],
            $rendered,
        );
    }
}
