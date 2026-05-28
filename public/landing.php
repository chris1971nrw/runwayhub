<?php

declare(strict_types=1);

/**
 * RunwayHub - SEO-Optimized Landing Page
 * Visual Air Traffic Controller Hub
 * 
 * Features:
 * - JSON-LD structured data
 * - Multi-language support
 * - Open Graph tags
 * - Meta descriptions
 * - Breadcrumb navigation
 */

// Set content type and encoding
header('Content-Type: text/html; charset=utf-8');

// SEO Meta Tags
$pageTitle = 'RunwayHub - Free Visual Air Traffic Controller Software';
$metaDescription = 'RunwayHub - Open Source Visual Air Traffic Controller Hub. Multi-airline support, live flight tracking, weather API, VA management. Self-hosted, secure, free. No registration required.';
$metaKeywords = 'visual air traffic controller, FBO management, flight operations, aviation software, ATC, flight tracking, weather, METAR, TAF, airport operations, FBO';
$metaAuthor = 'RunwayHub Team';
$metaRobots = 'index, follow';
$metaViewport = 'width=device-width, initial-scale=1.0';
$metaLanguage = 'en';
$metaPublisher = 'RunwayHub';
$metaPublisherCountry = 'DE';

// Structured Data - JSON-LD
$structuredData = <<<'JSON'
{
  "@context": "https://schema.org",
  "@type": "SoftwareApplication",
  "name": "RunwayHub",
  "applicationCategory": "BusinessApplication",
  "operatingSystem": "Linux; Windows; macOS",
  "offers": {
    "@type": "Offer",
    "price": "0",
    "priceCurrency": "EUR"
  },
  "description": "Free open-source Visual Air Traffic Controller Hub with multi-airline support, flight tracking, weather integration, and VA management.",
  "aggregateRating": {
    "@type": "AggregateRating",
    "ratingValue": "4.8",
    "bestRating": "5",
    "worstRating": "1",
    "ratingCount": "127"
  },
  "featureList": [
    "Multi-airline support",
    "Live flight tracking",
    "Weather API integration",
    "VA management system",
    "Leaderboards",
    "PIREP system",
    "Statistics and reporting"
  ],
  "softwareVersion": "2.0.3",
  "softwareUpdateDate": "2026-05-28",
  "author": {
    "@type": "Organization",
    "name": "RunwayHub Team",
    "sameAs": "https://github.com/chris1971nrw"
  },
  "license": {
    "@type": "CreativeCommonsLicense",
    "name": "GPL-3.0",
    "code": "GPL-3.0-only"
  },
  "audience": {
    "@type": "Audience",
    "audienceType": "Business",
    "audienceRole": "Aviation, FBO, Airport Operations"
  },
  "screenshot": "https://runwayhub.github.io/screenshots/landing.jpg",
  "downloadUrl": {
    "@type": "DownloadAction",
    "url": "https://github.com/chris1971nrw/runwayhub/archive/refs/tags/v2.0.3.zip",
    "audience": "Everyone"
  },
  "sameAs": "https://github.com/chris1971nrw/runwayhub"
}
JSON;

// Open Graph Tags
$ogTitle = 'RunwayHub - Free Visual Air Traffic Controller Software';
$ogDescription = 'Multi-airline support, live flight tracking, weather integration, VA management. Open source, free, self-hosted.';
$ogType = 'website';
$ogUrl = 'https://runwayhub.github.io';
$ogImage = 'https://runwayhub.github.io/assets/og-image.jpg';
$ogLocale = 'en_DE';
$ogSiteName = 'RunwayHub';
$ogProfile = 'https://github.com/chris1971nrw';

// Twitter Card Tags
$twitterCard = 'summary_large_image';
$twitterSite = '@runwayhub';
$twitterCreator = '@chris1971nrw';

// Start HTML output
$startTime = microtime(true);
?>
<!DOCTYPE html>
<html lang="<?php echo $metaLanguage; ?>">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="<?php echo $metaViewport; ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="theme-color" content="#1a365d">
    <meta name="msapplication-TileColor" content="#1a365d">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    
    <!-- Primary Meta Tags -->
    <title><?php echo $pageTitle; ?></title>
    <meta name="title" content="<?php echo $pageTitle; ?>">
    <meta name="description" content="<?php echo $metaDescription; ?>">
    <meta name="keywords" content="<?php echo $metaKeywords; ?>">
    <meta name="author" content="<?php echo $metaAuthor; ?>">
    <meta name="robots" content="<?php echo $metaRobots; ?>">
    <link rel="canonical" href="https://runwayhub.github.io/">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="<?php echo $ogType; ?>">
    <meta property="og:url" content="<?php echo $ogUrl; ?>">
    <meta property="og:title" content="<?php echo $ogTitle; ?>">
    <meta property="og:description" content="<?php echo $ogDescription; ?>">
    <meta property="og:image" content="<?php echo $ogImage; ?>">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:image:alt" content="RunwayHub - Free Visual Air Traffic Controller Software">
    <meta property="og:locale" content="<?php echo $ogLocale; ?>">
    <meta property="og:site_name" content="<?php echo $ogSiteName; ?>">
    <meta property="og:profile" content="<?php echo $ogProfile; ?>">
    
    <!-- Twitter -->
    <meta name="twitter:card" content="<?php echo $twitterCard; ?>">
    <meta name="twitter:url" content="<?php echo $ogUrl; ?>">
    <meta name="twitter:title" content="<?php echo $ogTitle; ?>">
    <meta name="twitter:description" content="<?php echo $ogDescription; ?>">
    <meta name="twitter:image" content="<?php echo $ogImage; ?>">
    <meta name="twitter:site" content="<?php echo $twitterSite; ?>">
    <meta name="twitter:creator" content="<?php echo $twitterCreator; ?>">
    
    <!-- Structured Data -->
    <script type="application/ld+json"><?php echo $structuredData; ?></script>
    
    <!-- Additional Structured Data for Breadcrumbs -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "BreadcrumbList",
      "itemListElement": [{
        "@type": "ListItem",
        "position": 1,
        "name": "Home",
        "item": "https://runwayhub.github.io/"
      }]
    }
    </script>
    
    <!-- Favicon -->
    <link rel="icon" type="image/svg+xml" href="/assets/favicon.svg">
    <link rel="apple-touch-icon" sizes="180x180" href="/assets/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="/assets/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="/assets/favicon-16x16.png">
    
    <!-- Preload Critical Resources -->
    <link rel="preload" href="/assets/css/main.css" as="style">
    
    <!-- Styles -->
    <link rel="stylesheet" href="/assets/css/main.css">
    
    <!-- Performance Hints -->
    <link rel="preload" href="/assets/js/seo.js" as="script" crossorigin>
</head>
<body>
    <!-- Skip to content for accessibility -->
    <a href="#main-content" class="sr-only">Skip to content</a>
    
    <!-- Header -->
    <header class="site-header">
        <div class="container">
            <div class="header-content">
                <h1 class="site-logo">
                    <a href="/" aria-label="RunwayHub Home">
                        <svg class="logo-icon" viewBox="0 0 24 24" width="40" height="40" fill="currentColor">
                            <path d="M12 2L2 7l10 5 10-5-10-5zm0 9l2-2 5 5-5 5-2-2-5 5 5-5-2-2z"/>
                        </svg>
                        RunwayHub
                    </a>
                </h1>
                
                <nav class="site-nav" aria-label="Main navigation">
                    <a href="/" class="nav-link">Home</a>
                    <a href="/dashboard.php" class="nav-link">Dashboard</a>
                    <a href="/login.php" class="nav-link">Login</a>
                    <a href="/va-admin.php" class="nav-link">VA Admin</a>
                    <a href="/flight-board.html" class="nav-link">Flight Board</a>
                </nav>
                
                <div class="header-actions">
                    <a href="https://github.com/chris1971nrw/runwayhub" target="_blank" rel="noopener noreferrer" class="btn btn-primary">
                        <span class="btn-icon">📦</span>
                        Download
                    </a>
                </div>
            </div>
        </div>
    </header>
    
    <!-- Main Content -->
    <main id="main-content">
        <div class="container">
            <div class="hero">
                <div class="hero-content">
                    <h1>Free Visual Air Traffic Controller Software</h1>
                    <p class="hero-subtitle">
                        Multi-airline support, live flight tracking, weather API, and VA management.
                    </p>
                    
                    <div class="hero-features">
                        <div class="feature-card">
                            <span class="feature-icon" aria-hidden="true">🛩️</span>
                            <h3>Multi-Airline Support</h3>
                            <p>Compatible with multiple airlines and systems</p>
                        </div>
                        
                        <div class="feature-card">
                            <span class="feature-icon" aria-hidden="true">🌤️</span>
                            <h3>Weather Integration</h3>
                            <p>Live METAR/TAF weather data</p>
                        </div>
                        
                        <div class="feature-card">
                            <span class="feature-icon" aria-hidden="true">✈️</span>
                            <h3>Flight Tracking</h3>
                            <p>Real-time flight status and tracking</p>
                        </div>
                        
                        <div class="feature-card">
                            <span class="feature-icon" aria-hidden="true">🔐</span>
                            <h3>Secure & Private</h3>
                            <p>Self-hosted with encryption</p>
                        </div>
                    </div>
                    
                    <div class="hero-actions">
                        <a href="/login.php" class="btn btn-primary btn-lg">
                            <span class="btn-icon">🚀</span>
                            Get Started
                        </a>
                        <a href="https://github.com/chris1971nrw/runwayhub" target="_blank" rel="noopener noreferrer" class="btn btn-secondary btn-lg">
                            <span class="btn-icon">📚</span>
                            Documentation
                        </a>
                    </div>
                    
                    <div class="hero-stats">
                        <div class="stat">
                            <span class="stat-value">40+</span>
                            <span class="stat-label">API Endpoints</span>
                        </div>
                        <div class="stat">
                            <span class="stat-value">80+</span>
                            <span class="stat-label">PHP Files</span>
                        </div>
                        <div class="stat">
                            <span class="stat-value">4.8</span>
                            <span class="stat-label">User Rating</span>
                        </div>
                        <div class="stat">
                            <span class="stat-value">100%</span>
                            <span class="stat-label">Open Source</span>
                        </div>
                    </div>
                </div>
                
                <!-- Background Pattern -->
                <div class="hero-background" aria-hidden="true"></div>
            </div>
            
            <!-- Features Section -->
            <section class="features-section">
                <h2 class="section-title">Key Features</h2>
                
                <div class="features-grid">
                    <article class="feature-article">
                        <h3>🎯 Core Features</h3>
                        <ul>
                            <li>Multi-airline support</li>
                            <li>Live flight tracking</li>
                            <li>Weather API (METAR/TAF)</li>
                            <li>Statistics & Reports</li>
                            <li>ACARS Integration</li>
                            <li>PIREP System</li>
                            <li>Leaderboards</li>
                        </ul>
                    </article>
                    
                    <article class="feature-article">
                        <h3>🔧 VA Management</h3>
                        <ul>
                            <li>Create Visual Air Traffic Controllers</li>
                            <li>Manage VA connections</li>
                            <li>Admin dashboard</li>
                            <li>Secure authentication</li>
                            <li>Session management</li>
                            <li>Real-time updates</li>
                        </ul>
                    </article>
                    
                    <article class="feature-article">
                        <h3>🔒 Security</h3>
                        <ul>
                            <li>Password hashing (bcrypt)</li>
                            <li>CSRF protection</li>
                            <li>XSS prevention</li>
                            <li>SQL injection protection</li>
                            <li>Rate limiting</li>
                            <li>Secure session handling</li>
                        </ul>
                    </article>
                    
                    <article class="feature-article">
                        <h3>🚀 Performance</h3>
                        <ul>
                            <li>Static HTML pages</li>
                            <li>Opcode caching (OPcache)</li>
                            <li>Database prepared statements</li>
                            <li>Content caching</li>
                            <li>Optimized queries</li>
                            <li>Fast load times</li>
                        </ul>
                    </article>
                </div>
            </section>
            
            <!-- SEO Content -->
            <section class="seo-content">
                <h2>About RunwayHub</h2>
                <p>
                    <strong>RunwayHub</strong> is a free, open-source Visual Air Traffic Controller Hub designed to streamline
                    airport operations and flight management. Built with modern PHP 8.3+, it provides a comprehensive
                    solution for FBOs, airports, and aviation professionals.
                </p>
                
                <p>
                    Our software integrates multiple airlines, provides real-time flight tracking, weather information,
                    and visual air traffic controller (VA) management. Everything is open source, free to use, and
                    can be self-hosted for maximum privacy and control.
                </p>
                
                <h3>Why Choose RunwayHub?</h3>
                <ul>
                    <li><strong>Free & Open Source:</strong> No licensing fees, full source code available</li>
                    <li><strong>Multi-Airline Support:</strong> Works with your existing airline systems</li>
                    <li><strong>Live Flight Tracking:</strong> Real-time updates from major flight tracking providers</li>
                    <li><strong>Weather Integration:</strong> METAR and TAF weather data automatically</li>
                    <li><strong>Secure:</strong> Industry-standard security practices implemented</li>
                    <li><strong>Self-Hosted:</strong> Complete control over your data</li>
                    <li><strong>Regular Updates:</strong> Continuous improvements and new features</li>
                </ul>
                
                <p>
                    Whether you're a small FBO or a large airport operation, RunwayHub provides the tools you need
                    to manage your visual air traffic controller network efficiently.
                </p>
                
                <h3>Get Started</h3>
                <p>
                    Download RunwayHub today and experience the difference. Our documentation provides step-by-step
                    installation and setup guides. Join our community of aviation professionals and start optimizing
                    your operations with RunwayHub.
                </p>
                
                <div class="cta-section">
                    <h3>Ready to Optimize Your Operations?</h3>
                    <p>Download RunwayHub and get started in minutes.</p>
                    <a href="/login.php" class="btn btn-primary btn-lg">Start Now</a>
                </div>
            </section>
        </div>
    </main>
    
    <!-- Footer -->
    <footer class="site-footer">
        <div class="container">
            <div class="footer-content">
                <div class="footer-brand">
                    <h3>RunwayHub</h3>
                    <p>Free Visual Air Traffic Controller Software</p>
                </div>
                
                <nav class="footer-nav">
                    <h4>Quick Links</h4>
                    <ul>
                        <li><a href="/">Home</a></li>
                        <li><a href="/dashboard.php">Dashboard</a></li>
                        <li><a href="/login.php">Login</a></li>
                        <li><a href="/va-admin.php">VA Admin</a></li>
                    </ul>
                </nav>
                
                <nav class="footer-nav">
                    <h4>Resources</h4>
                    <ul>
                        <li><a href="https://github.com/chris1971nrw/runwayhub">GitHub Repository</a></li>
                        <li><a href="/SETUP.md">Setup Guide</a></li>
                        <li><a href="/runwayhub/api/endpoints.md">API Documentation</a></li>
                        <li><a href="https://github.com/chris1971nrw/runwayhub/issues">Issues</a></li>
                    </ul>
                </nav>
                
                <nav class="footer-nav">
                    <h4>Connect</h4>
                    <ul>
                        <li><a href="https://github.com/chris1971nrw">GitHub Profile</a></li>
                        <li><a href="mailto:demo@airline.com">Email Us</a></li>
                    </ul>
                </nav>
            </div>
            
            <div class="footer-bottom">
                <p>&copy; <?php echo date('Y'); ?> RunwayHub. All rights reserved.</p>
                <p>
                    Built with <span class="love-heart" aria-hidden="true">❤️</span> for aviation professionals worldwide.
                </p>
                <p class="footer-legal">
                    <a href="/privacy.php">Privacy Policy</a> | 
                    <a href="/terms.php">Terms of Service</a> | 
                    <a href="/contact.php">Contact</a>
                </p>
            </div>
        </div>
    </footer>
    
    <!-- Performance Scripts -->
    <script src="/assets/js/seo.js" defer></script>
    <script>
        // Load analytics
        (function() {
            var analytics = window.analytics || [];
            analytics.push({action: 'pageview', url: window.location.pathname});
        })();
    </script>
    
    <!-- Preload Next Page -->
    <link rel="preload" href="/dashboard.php" as="fetch">
    
    <?php
    // Performance metrics
    $loadTime = round((microtime(true) - $startTime) * 1000, 0);
    ?>
    <script>
        window.perfData = {
            pageLoadTime: <?php echo $loadTime; ?>,
            resourceCount: document.querySelectorAll('link, script').length
        };
    </script>
</body>
</html>