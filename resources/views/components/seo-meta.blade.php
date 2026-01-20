@props([
    'title' => null,
    'description' => null,
    'keywords' => null,
    'image' => null,
    'type' => 'website',
    'noindex' => false,
    'canonical' => null,
    'schema' => null,
])

@php
    $siteUrl = config('seo.site_url');
    $siteName = config('seo.site_name');

    $metaTitle = $title ?? config('seo.default.title');
    $metaDescription = $description ?? config('seo.default.description');
    $metaKeywords = $keywords ?? config('seo.default.keywords');
    $metaImage = $image ? (str_starts_with($image, 'http') ? $image : $siteUrl . $image) : $siteUrl . config('seo.og.image');
    $metaRobots = $noindex ? 'noindex, nofollow' : config('seo.default.robots');
    $canonicalUrl = $canonical ?? url()->current();
@endphp

{{-- Primary Meta Tags --}}
<meta name="title" content="{{ $metaTitle }}">
<meta name="description" content="{{ $metaDescription }}">
<meta name="keywords" content="{{ $metaKeywords }}">
<meta name="author" content="{{ config('seo.default.author') }}">
<meta name="robots" content="{{ $metaRobots }}">
<link rel="canonical" href="{{ $canonicalUrl }}">

{{-- Open Graph / Facebook --}}
<meta property="og:type" content="{{ $type }}">
<meta property="og:url" content="{{ $canonicalUrl }}">
<meta property="og:title" content="{{ $metaTitle }}">
<meta property="og:description" content="{{ $metaDescription }}">
<meta property="og:image" content="{{ $metaImage }}">
<meta property="og:image:width" content="{{ config('seo.og.image_width') }}">
<meta property="og:image:height" content="{{ config('seo.og.image_height') }}">
<meta property="og:site_name" content="{{ $siteName }}">
<meta property="og:locale" content="{{ config('seo.og.locale') }}">

{{-- Twitter --}}
<meta name="twitter:card" content="{{ config('seo.twitter.card') }}">
<meta name="twitter:url" content="{{ $canonicalUrl }}">
<meta name="twitter:title" content="{{ $metaTitle }}">
<meta name="twitter:description" content="{{ $metaDescription }}">
<meta name="twitter:image" content="{{ $metaImage }}">
@if(config('seo.twitter.site'))
<meta name="twitter:site" content="{{ config('seo.twitter.site') }}">
@endif

{{-- Additional Meta --}}
<meta name="theme-color" content="#10b981">
<meta name="msapplication-TileColor" content="#10b981">

{{-- Structured Data --}}
@if($schema)
<script type="application/ld+json">
{!! json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) !!}
</script>
@endif
