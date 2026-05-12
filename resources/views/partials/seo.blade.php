@php
    $seo = $seo ?? [];
    $appName = config('app.name', 'Laravel');
    $title = $seo['title'] ?? $appName;
    $fullTitle = $title && $title !== $appName ? $title.' – '.$appName : $appName;
    $description = $seo['description'] ?? null;
    $canonical = $seo['canonical'] ?? url(request()->path() === '/' ? '/' : '/'.ltrim(request()->path(), '/'));
    $ogImage = $seo['og_image'] ?? null;
    $type = $seo['type'] ?? 'website';
    $publishedTime = $seo['published_time'] ?? null;
    $modifiedTime = $seo['modified_time'] ?? null;

    $organizationJsonLd = array_filter([
        '@context' => 'https://schema.org',
        '@type' => 'Organization',
        'name' => $appName,
        'url' => $canonical ? parse_url($canonical, PHP_URL_SCHEME).'://'.parse_url($canonical, PHP_URL_HOST) : null,
        'logo' => $ogImage,
    ]);

    $articleJsonLd = $type === 'article' ? array_filter([
        '@context' => 'https://schema.org',
        '@type' => 'BlogPosting',
        'headline' => $title,
        'mainEntityOfPage' => $canonical,
        'description' => $description,
        'image' => $ogImage,
        'datePublished' => $publishedTime,
        'dateModified' => $modifiedTime,
    ]) : null;
@endphp

<title inertia>{{ $fullTitle }}</title>
@if ($description)
    <meta name="description" content="{{ $description }}">
@endif
@if ($canonical)
    <link rel="canonical" href="{{ $canonical }}">
@endif

<meta property="og:site_name" content="{{ $appName }}">
<meta property="og:type" content="{{ $type }}">
<meta property="og:title" content="{{ $fullTitle }}">
@if ($description)
    <meta property="og:description" content="{{ $description }}">
@endif
@if ($canonical)
    <meta property="og:url" content="{{ $canonical }}">
@endif
@if ($ogImage)
    <meta property="og:image" content="{{ $ogImage }}">
@endif
@if ($publishedTime)
    <meta property="article:published_time" content="{{ $publishedTime }}">
@endif
@if ($modifiedTime)
    <meta property="article:modified_time" content="{{ $modifiedTime }}">
@endif

<meta name="twitter:card" content="{{ $ogImage ? 'summary_large_image' : 'summary' }}">
<meta name="twitter:title" content="{{ $fullTitle }}">
@if ($description)
    <meta name="twitter:description" content="{{ $description }}">
@endif
@if ($ogImage)
    <meta name="twitter:image" content="{{ $ogImage }}">
@endif

<script type="application/ld+json">{!! json_encode($organizationJsonLd, JSON_UNESCAPED_SLASHES) !!}</script>
@if ($articleJsonLd)
    <script type="application/ld+json">{!! json_encode($articleJsonLd, JSON_UNESCAPED_SLASHES) !!}</script>
@endif
