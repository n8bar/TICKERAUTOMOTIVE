<?php

function load_content_items(string $dirPath): array
{
    $items = [];
    $pattern = rtrim($dirPath, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR . '*.html';
    $files = glob($pattern);

    if (!$files) {
        return $items;
    }

    natsort($files);

    foreach ($files as $file) {
        $basename = pathinfo($file, PATHINFO_FILENAME);
        $title = str_replace('_', ' ', $basename);
        $title = preg_replace('/\s+/', ' ', $title);
        $slug = strtolower($basename);
        $slug = preg_replace('/[^a-z0-9]+/i', '-', $slug);
        $slug = trim($slug, '-');
        $content = trim((string) file_get_contents($file));

        $items[] = [
            'basename' => $basename,
            'title' => $title,
            'slug' => $slug ?: strtolower($title),
            'content' => $content,
        ];
    }

    return $items;
}

function render_content_text(string $content): string
{
    $escaped = htmlspecialchars($content, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
    return nl2br($escaped);
}
