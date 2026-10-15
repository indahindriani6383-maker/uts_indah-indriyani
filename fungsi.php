
<?php

function rupiah($angka)
{
    return "Rp" . number_format($angka, 0, ",", ".");
}

function aman($teks)
{
    return htmlspecialchars(
        (string)$teks,
        ENT_QUOTES,
        "UTF-8"
    );
}

function hitungSubtotal($harga, $jumlah)
{
    return $harga * $jumlah;
}

function hitungTotal($keranjang, $menu)
{
    $total = 0;

    foreach ($keranjang as $kode => $jumlah) {
        if (isset($menu[$kode])) {
            $total += hitungSubtotal(
                $menu[$kode]["harga"],
                $jumlah
            );
        }
    }

    return $total;
}

function hitungDiskon($subtotal)
{
    return $subtotal >= 50000
        ? $subtotal * 0.10
        : 0;
}

function hitungTotalAkhir($subtotal)
{
    return $subtotal - hitungDiskon($subtotal);
}

function ambilFlash()
{
    $pesan = $_SESSION["flash"] ?? null;
    unset($_SESSION["flash"]);

    return $pesan;
}
?>
