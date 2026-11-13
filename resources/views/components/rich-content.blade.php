@props(['content' => ''])

@php
    $html = (string) $content;
    $looksLikeHtml = preg_match('/<(p|br|div|h[1-6]|ul|ol|li|strong|em|b|i|u|a|img|blockquote|table|span)\b/i', $html) === 1;
@endphp

{!! $looksLikeHtml ? $html : nl2br(e($html)) !!}
