<!DOCTYPE html>
<html lang="<?php echo $data['lang'] ?? 'de'; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#1e3a8a">
    
    <!-- Primary Meta Tags -->
    <title><?php echo $data['title'] ?? 'RunwayHub'; ?> - Virtual Airline Manager</title>
    <meta name="title" content="<?php echo $data['title'] ?? 'RunwayHub - Virtual Airline Manager'; ?>">
    <meta name="description" content="<?php echo $data['description'] ?? 'Professional Virtual Airline Management Software. Manage flights, fleets, bookings, pilots and aviation operations.'; ?>">
    <meta name="keywords" content="<?php echo $data['keywords'] ?? 'virtual airline, flight management, aviation software, PIREP, flight tracking, fleet management, open source aviation'; ?>">
    <meta name="author" content="RunwayHub Team">
    <meta name="robots" content="index, follow">
    <link rel="canonical" href="<?php echo $data['canonical'] ?? 'https://chris1971nrw.github.io/runwayhub/'; ?>">
    
    <!-- Open Graph / Facebook -->
    <meta property="og:type" content="website">
    <meta property="og:url" content="<?php echo $data['canonical'] ?? 'https://chris1971nrw.github.io/runwayhub/'; ?>">
    <meta property="og:title" content="<?php echo $data['title'] ?? 'RunwayHub'; ?>">
    <meta property="og:description" content="<?php echo $data['description'] ?? 'Professional Virtual Airline Management Software'; ?>">
    <meta property="og:image" content="https://chris1971nrw.github.io/runwayhub/assets/og-image.jpg">
    
    <!-- Twitter -->
    <meta property="twitter:card" content="summary_large_image">
    <meta property="twitter:url" content="<?php echo $data['canonical'] ?? 'https://chris1971nrw.github.io/runwayhub/'; ?>">
    <meta property="twitter:title" content="<?php echo $data['title'] ?? 'RunwayHub'; ?>">
    <meta property="twitter:description" content="<?php echo $data['description'] ?? 'Professional Virtual Airline Management Software'; ?>">
    <meta property="twitter:image" content="https://chris1971nrw.github.io/runwayhub/assets/twitter-card.jpg">
    
    <!-- Structured Data JSON-LD -->
    <script type="application/ld+json">
    {
      "@context": "https://schema.org",
      "@type": "SoftwareApplication",
      "name": "RunwayHub",
      "alternateName": ["RunwayHub - Virtual Airline Manager"],
      "description": "Professional Virtual Airline Management Software",
      "applicationCategory": "BusinessApplication",
      "operatingSystem": "Web-based",
      "offers": {
        "@type": "Offer",
        "price": "0",
        "priceCurrency": "EUR"
      },
      "publisher": {
        "@type": "Organization",
        "name": "RunwayHub",
        "logo": {
          "@type": "ImageObject",
          "url": "https://chris1971nrw.github.io/runwayhub/assets/logo.png"
        }
      },
      "copyrightNotice": "© <?php echo date('Y'); ?> RunwayHub",
      "datePublished": "2026-05-01T00:00:00+02:00",
      "dateModified": "<?php echo date('c'); ?>"
    }
    </script>
    
    <link rel="stylesheet" href="/assets/css/main.css">
</head>
<body>
    <header>
        <nav>
            <a href="/" class="logo">🛫 RunwayHub</a>
            <ul>
                <li><a href="/dashboard">Dashboard</a></li>
                <li><a href="/flights">Flüge</a></li>
                <li><a href="/aircrafts">Flotte</a></li>
                <li><a href="/pilots">Piloten</a></li>
                <li><a href="/bookings">Buchungen</a></li>
                <li><a href="/weather">Wetter</a></li>
                <li><a href="/tracking">Tracking</a></li>
            </ul>
            <nav class="language-switch">
                <a href="/dashboard" lang="de">DE</a> | 
                <a href="/en/dashboard" lang="en">EN</a>
            </nav>
        </nav>
    </header>
    
    <main>
        <?php echo $data['content'] ?? ''; ?>
    </main>
    
    <footer>
        <p>&copy; <?php echo date('Y'); ?> RunwayHub - Virtual Airline Management System</p>
        <p><a href="/about">Über uns</a> | <a href="/contact">Kontakt</a> | <a href="/privacy">Datenschutz</a></p>
    </footer>
    
    <script src="/assets/js/main.js"></script>
</body>
</html>