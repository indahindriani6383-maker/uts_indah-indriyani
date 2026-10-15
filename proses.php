
<?php
session_start();

require_once __DIR__ . "/data/menu.php";
require_once __DIR__ . "/includes/fungsi.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: index.php");
    exit;
}

$aksi = $_POST["aksi"] ?? "";

if (!isset($_SESSION["keranjang"])) {
    $_SESSION["keranjang"] = [];
}

if ($aksi === "simpan_kategori") {
    $kategori = $_POST["kategori"] ?? "";

    if (!in_array($kategori, $kategoriTersedia, true)) {
        $_SESSION["flash"] = "Kategori tidak valid.";
    } else {
        setcookie("kategori_favorit", $kategori, [
            "expires" => time() + (7 * 24 * 60 * 60),
            "path" => "/",
            "httponly" => true,
            "samesite" => "Lax"
        ]);

        $_SESSION["flash"] =
            "Kategori favorit berhasil disimpan.";
    }

    header("Location: index.php");
    exit;
}

if ($aksi === "tambah") {
    $kode = $_POST["kode"] ?? "";
    $jumlahInput = $_POST["jumlah"] ?? "";

    if (!isset($menu[$kode])) {
        $_SESSION["flash"] = "Kode menu tidak valid.";
    } elseif (
        !is_string($jumlahInput) ||
        !preg_match('/\A[0-9]+\z/', $jumlahInput) ||
        (int)$jumlahInput < 1 ||
        (int)$jumlahInput > 10
    ) {
        $_SESSION["flash"] =
            "Jumlah harus bilangan bulat 1 sampai 10.";
    } else {
        $jumlah = (int)$jumlahInput;
        $jumlahLama =
            $_SESSION["keranjang"][$kode] ?? 0;
        $jumlahBaru = $jumlahLama + $jumlah;

        if ($jumlahBaru > 10) {
            $_SESSION["flash"] =
                "Gagal! Jumlah setiap menu maksimal 10. " .
                "Keranjang tidak berubah.";
        } else {
            $_SESSION["keranjang"][$kode] = $jumlahBaru;
            $_SESSION["flash"] =
                "Pesanan berhasil ditambahkan.";
        }
    }

    header("Location: index.php");
    exit;
}

if ($aksi === "hapus") {
    $kode = $_POST["kode"] ?? "";

    if (isset($_SESSION["keranjang"][$kode])) {
        unset($_SESSION["keranjang"][$kode]);
        $_SESSION["flash"] = "Item berhasil dihapus.";
    } else {
        $_SESSION["flash"] = "Item tidak ditemukan.";
    }

    header("Location: keranjang.php");
    exit;
}

if ($aksi === "kosongkan") {
    $_SESSION["keranjang"] = [];
    $_SESSION["flash"] = "Keranjang berhasil dikosongkan.";

    header("Location: keranjang.php");
    exit;
}

$_SESSION["flash"] = "Aksi tidak dikenal.";
header("Location: index.php");
exit;
?>
