<?php

return [
    /*
    | Prompt files live under this directory. It should contain `layouts` and
    | `parts` directories with versioned Markdown Blade templates.
    */
    'path' => resource_path('prompts'),

    /*
    | Map logical names to their active template versions. Nested arrays and
    | dot-separated names are both supported.
    */
    'layouts' => [],
    'parts' => [],
];
