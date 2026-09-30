<?php

use Aesis\Prompts\Services\PromptService;
use Illuminate\Support\Facades\Blade;

beforeEach(function (): void {
    config()->set('prompts.path', __DIR__.'/../Fixtures/prompts');
    config()->set('prompts.layouts', ['sample.greeting' => 2]);
    config()->set('prompts.parts', ['sample.signature' => 1]);
});

it('renders an active layout with data', function (): void {
    expect(app(PromptService::class)->renderPrompt('sample.greeting', ['name' => 'Ada']))
        ->toBe("Hello, Ada!\n");
});

it('renders a named part using the Blade directive', function (): void {
    expect(Blade::render("@prompt('sample.signature')"))
        ->toBe("Regards, Ada.\n");
});

it('renders a part at an explicit version', function (): void {
    expect(app(PromptService::class)->renderPart('sample.signature', 2))
        ->toBe("Sincerely, Ada.\n");
});

it('rejects invalid prompt names', function (): void {
    app(PromptService::class)->renderPrompt('../secret');
})->throws(InvalidArgumentException::class, 'Invalid prompt name');

it('rejects prompt names without an active version', function (): void {
    app(PromptService::class)->renderPrompt('sample.missing');
})->throws(InvalidArgumentException::class, 'has no active version');
