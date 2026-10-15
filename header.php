
<?php
if (!isset($judul)) {
    $judul = "Pemesanan Kantin Kampus";
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, initial-scale=1.0">
    <title><?= aman($judul) ?></title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>

<header>
    <h1>Kantin Kampus</h1>
    <p>Sistem Pemesanan Makanan dan Minuman</p>

    <nav>
        <a href="index.php">Daftar Menu</a>
        <a href="keranjang.php">Keranjang</a>
    </nav>
</header>

<main>
<?php
$flash = ambilFlash();

if ($flash !== null):
?>
    <p class="pesan">
        <?= aman($flash) ?>
    </p>
<?php endif; ?>
