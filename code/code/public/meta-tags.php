<?php
/**
 * Dynamic Meta Tags Generator for RunwayHub
 * Provides SEO-optimized meta tags for different pages
 */

// Page metadata
$pageTypes = [
    'home' => [
        'title' => 'RunwayHub - Professional Flight Tracking & Dashboard',
        'description' => 'Monitor flights, track weather, manage airport operations with RunwayHub. Professional VAA dashboard with live flight tracking and weather integration.',
        'keywords' => 'flight tracking, VAA dashboard, airport operations, weather widget, flight board, air traffic control',
        'canonical' => '/index.php',
        'og' => [
            'title' => 'RunwayHub - Professional Flight Tracking & Dashboard',
            'description' => 'Monitor flights, track weather, manage airport operations with RunwayHub',
            'image' => '/images/logo.png',
            'url' => 'https://runwayhub.io',
            'type' => 'website'
        ]
    ],
    'dashboard' => [
        'title' => 'Dashboard | RunwayHub',
        'description' => 'VAA dashboard with live flight tracking, weather integration, and airport management tools.',
        'keywords' => 'VAA dashboard, flight tracking, airport management, runway monitoring',
        'canonical' => '/dashboard.php',
        'og' => [
            'title' => 'VAA Dashboard - RunwayHub',
            'description' => 'VAA dashboard with live flight tracking and weather integration',
            'image' => '/images/dashboard-preview.jpg',
            'url' => 'https://runwayhub.io/dashboard.php',
            'type' => 'website'
        ]
    ],
    'flights' => [
        'title' => 'Flight Tracking | RunwayHub',
        'description' => 'Track live flights with detailed information, status updates, and historical data.',
        'keywords' => 'live flight tracking, flight status, flight history, flight notifications',
        'canonical' => '/flights.php',
        'og' => [
            'title' => 'Live Flight Tracking - RunwayHub',
            'description' => 'Track live flights with detailed information and status updates',
            'image' => '/images/flight-icon.svg',
            'url' => 'https://runwayhub.io/flights.php',
            'type' => 'website'
        ]
    ],
    'weather' => [
        'title' => 'Weather Widget | RunwayHub',
        'description' => 'Professional weather widget for airports and flight operations.',
        'keywords' => 'weather widget, airport weather, flight weather, aviation weather',
        'canonical' => '/weather-widget.html',
        'og' => [
            'title' => 'Weather Widget - RunwayHub',
            'description' => 'Professional weather widget for airports and flight operations',
            'image' => '/images/weather-icon.svg',
            'url' => 'https://runwayhub.io/weather-widget.html',
            'type' => 'website'
        ]
    ]
];

?>
<title><?=$pageTypes[$pageTitle ?? 'home']['title']?></title>
<meta name="description" content="<?=$pageTypes[$pageTitle ?? 'home']['description']?>">
<meta name="keywords" content="<?=$pageTypes[$pageTitle ?? 'home']['keywords']?>">
<link rel="canonical" href="<?=$pageTypes[$pageTitle ?? 'home']['canonical']?>">

<!-- OpenGraph -->
<meta property="og:title" content="<?=$pageTypes[$pageTitle ?? 'home']['og']['title']?>">
<meta property="og:description" content="<?=$pageTypes[$pageTitle ?? 'home']['og']['description']?>">
<meta property="og:image" content="<?=$pageTypes[$pageTitle ?? 'home']['og']['image']?>">
<meta property="og:url" content="<?=$pageTypes[$pageTitle ?? 'home']['og']['url']?>">
<meta property="og:type" content="<?=$pageTypes[$pageTitle ?? 'home']['og']['type']?>">

<!-- Twitter Cards -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="<?=$pageTypes[$pageTitle ?? 'home']['og']['title']?>">
<meta name="twitter:description" content="<?=$pageTypes[$pageTitle ?? 'home']['og']['description']?>">
<meta name="twitter:image" content="<?=$pageTypes[$pageTitle ?? 'home']['og']['image']?>">

<!-- Schema.org Structured Data -->
<script type="application/ld+json">
{
  "@context": "https://schema.org",
  "@type": "WebSite",
  "name": "RunwayHub",
  "url": "https://runwayhub.io",
  "potentialAction": {
    "@type": "SearchAction",
    "target": "/search.php?q={search_term_string}",
    "query-input": "required name=search_term_string"
  }
}
</script>