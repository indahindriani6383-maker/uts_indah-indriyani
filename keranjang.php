
<?php
session_start();

require_once __DIR__ . "/data/menu.php";
require_once __DIR__ . "/includes/fungsi.php";

$judul = "Keranjang Pesanan";
$keranjang = $_SESSION["keranjang"] ?? [];

$subtotal = hitungTotal($keranjang, $menu);
$diskon = hitungDiskon($subtotal);
$totalAkhir = hitungTotalAkhir($subtotal);

require __DIR__ . "/includes/header.php";
?>

<h2>Keranjang Pesanan</h2>

<?php if (empty($keranjang)): ?>

    <p>Keranjang masih kosong.</p>
    <a href="index.php">Pesan Menu Sekarang</a>

<?php else: ?>

<table>
    <thead>
        <tr>
            <th>Nama Menu</th>
            <th>Harga</th>
            <th>Jumlah</th>
            <th>Subtotal</th>
            <th>Aksi</th>
        </tr>
    </thead>

    <tbody>
    <?php foreach ($keranjang as $kode => $jumlah): ?>
        <?php if (!isset($menu[$kode])) continue; ?>

        <?php
        $item = $menu[$kode];
        $subtotalItem = hitungSubtotal(
            $item["harga"],
            $jumlah
        );
        ?>

        <tr>
            <td><?= aman($item["nama"]) ?></td>
            <td><?= rupiah($item["harga"]) ?></td>
            <td><?= aman($jumlah) ?></td>
            <td><?= rupiah($subtotalItem) ?></td>
            <td>
                <form method="POST" action="proses.php">
                    <input type="hidden" name="aksi"
                           value="hapus">
                    <input type="hidden" name="kode"
                           value="<?= aman($kode) ?>">
                    <button type="submit">Hapus</button>
                </form>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>

<div class="ringkasan">
    <p>Subtotal: <strong><?= rupiah($subtotal) ?></strong></p>
    <p>Diskon 10%: <strong><?= rupiah($diskon) ?></strong></p>
    <h3>Total Bayar: <?= rupiah($totalAkhir) ?></h3>
</div>

<form method="POST" action="proses.php">
    <input type="hidden" name="aksi"
           value="kosongkan">
    <button type="submit"
            onclick="return confirm('Kosongkan keranjang?')">
        Kosongkan Keranjang
    </button>
</form>

<?php endif; ?>

<?php require __DIR__ . "/includes/footer.php"; ?>
