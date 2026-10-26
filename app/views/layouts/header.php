<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'SPK Umrah — Persada Al-Amin' ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@400;600;700&family=DM+Sans:wght@300;400;500;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/style.css">
    <?= $extraCss ?? '' ?>
</head>
<body class="<?= $bodyClass ?? '' ?>">

<nav class="navbar">
    <div class="container navbar-inner">
        <a href="<?= BASE_URL ?>/" class="brand">
            <span class="brand-icon">☪</span>
            <span class="brand-text">Persada <strong>Al-Amin</strong></span>
        </a>
        <ul class="nav-links">
            <li><a href="<?= BASE_URL ?>/">Beranda</a></li>
            <li><a href="<?= BASE_URL ?>/paket">Paket Umrah</a></li>
            <li><a href="<?= BASE_URL ?>/konsultasi/mulai" class="btn-nav">Konsultasi SPK</a></li>
            <?php if (Session::isLoggedIn()): ?>
                <?php if (Session::isAdmin()): ?>
                    <li><a href="<?= BASE_URL ?>/admin/dashboard">Admin Panel</a></li>
                <?php endif; ?>
                <li><a href="<?= BASE_URL ?>/logout">Keluar</a></li>
            <?php else: ?>
                <li><a href="<?= BASE_URL ?>/app/views/auth/login.php">Masuk</a></li>
            <?php endif; ?>
        </ul>
        <button class="hamburger" id="hamburger" aria-label="Menu">
            <span></span><span></span><span></span>
        </button>
    </div>
</nav>

<main class="main-content">