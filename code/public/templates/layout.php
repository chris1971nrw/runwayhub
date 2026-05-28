<!DOCTYPE html>
<html lang="<?php echo $data['lang'] ?? 'de'; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $data['title'] ?? 'RunwayHub'; ?> - RunwayHub</title>
    
    <?php if ($data['description'] ?? false): ?>
    <meta name="description" content="<?php echo htmlspecialchars($data['description']); ?>">
    <?php endif; ?>

    <?php if ($data['keywords'] ?? false): ?>
    <meta name="keywords" content="<?php echo htmlspecialchars($data['keywords']); ?>">
    <?php endif; ?>

    <link rel="canonical" href="<?php echo $data['canonical'] ?? ''; ?>">
    
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
                <li><a href="/tracking">Tracking</a></li>
                <li><a href="/weather">Wetter</a></li>
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
        <p>&copy; 2026 RunwayHub - Virtual Airline Management System</p>
        <p><a href="/about">Über uns</a> | <a href="/contact">Kontakt</a> | <a href="/privacy">Datenschutz</a></p>
    </footer>
    
    <script src="/assets/js/main.js"></script>
</body>
</html>
