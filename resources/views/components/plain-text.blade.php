@props(['content' => '', 'limit' => null])

@php
    $text = strip_tags(html_entity_decode((string) $content, ENT_QUOTES | ENT_HTML5, 'UTF-8'));
    $text = trim(preg_replace('/\s+/u', ' ', $text));

    if ($limit !== null) {
        $text = \Illuminate\Support\Str::limit($text, (int) $limit);
    }
@endphp

{{ $text }}
