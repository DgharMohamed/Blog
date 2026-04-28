<?php
if (!isset($pageTitle)) {
    $pageTitle = 'AlgoWire';
}
if (!isset($activePage)) {
    $activePage = '';
}
?>
<!DOCTYPE html>
<html lang="en" data-theme="dark">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Decoding the Future">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/base.css">
    <link rel="stylesheet" href="css/navbar.css">
    <link rel="stylesheet" href="css/components.css">
</head>
<body>
    <div class="announcement-banner" id="announcementBanner">
        <div class="container"><span>- Decoding the Future -</span></div>
    </div>

    <header class="navbar" id="navbar">
        <div class="container navbar__inner">
            <a href="index.php" class="navbar__logo">
                <span class="navbar__logo-icon">&gt;_</span>
                <span class="navbar__logo-text">Algo<span class="navbar__logo-dot">.</span>Wire</span>
            </a>

            <button class="navbar__toggle" id="navToggle" aria-label="Toggle navigation">
                <span></span><span></span><span></span>
            </button>

            <nav class="navbar__nav" id="navMenu">
                <a href="index.php" class="navbar__link <?= $activePage === 'index' ? 'active' : '' ?>">Articles</a>
                <a href="category.php" class="navbar__link <?= $activePage === 'category' ? 'active' : '' ?>">Categories</a>
                <a href="authors.php" class="navbar__link <?= $activePage === 'authors' ? 'active' : '' ?>">Authors</a>
                <a href="about.php" class="navbar__link <?= $activePage === 'about' ? 'active' : '' ?>">About</a>
                <a href="contact.php" class="navbar__link <?= $activePage === 'contact' ? 'active' : '' ?>">Contact</a>
            </nav>

            <div class="navbar__actions">
                <button class="navbar__icon-btn" id="themeToggle" aria-label="Toggle theme">
                    <svg class="icon-sun" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="5"/><line x1="12" y1="1" x2="12" y2="3"/><line x1="12" y1="21" x2="12" y2="23"/><line x1="4.22" y1="4.22" x2="5.64" y2="5.64"/><line x1="18.36" y1="18.36" x2="19.78" y2="19.78"/><line x1="1" y1="12" x2="3" y2="12"/><line x1="21" y1="12" x2="23" y2="12"/><line x1="4.22" y1="19.78" x2="5.64" y2="18.36"/><line x1="18.36" y1="5.64" x2="19.78" y2="4.22"/></svg>
                    <svg class="icon-moon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 12.79A9 9 0 1 1 11.21 3 7 7 0 0 0 21 12.79z"/></svg>
                </button>
                <a href="admin/login.php" class="navbar__admin-btn">Admin</a>
            </div>
        </div>
    </header>
