
<?php
session_start();

require_once __DIR__ . "/data/menu.php";
require_once __DIR__ . "/includes/fungsi.php";

$judul = "Daftar Menu Kantin";
$keranjang = $_SESSION["keranjang"] ?? [];

$kataKunci = trim($_GET["cari"] ?? "");
$kategoriCookie = $_COOKIE["kategori_favorit"] ?? "";

$kategoriGET = $_GET["kategori"] ?? null;

if (
    $kategoriGET !== null &&
    in_array($kategoriGET, $kategoriTersedia, true)
) {
    $kategoriAktif = $kategoriGET;
} elseif (
    in_array($kategoriCookie, $kategoriTersedia, true)
) {
    $kategoriAktif = $kategoriCookie;
} else {
    $kategoriAktif = "";
}

require __DIR__ . "/includes/header.php";
?>

<h2>Daftar Menu</h2>

<form method="GET" action="index.php">
    <label>Cari menu:</label>
    <input type="text" name="cari"
           value="<?= aman($kataKunci) ?>"
           placeholder="Nama makanan/minuman">
    <button type="submit">Cari</button>
</form>

<form method="GET" action="index.php">
    <label>Filter kategori:</label>
    <select name="kategori">
        <option value="">Semua Kategori</option>

        <?php foreach ($kategoriTersedia as $kat): ?>
            <option value="<?= aman($kat) ?>"
                <?= $kategoriAktif === $kat
                    ? "selected" : "" ?>>
                <?= aman($kat) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <button type="submit">Filter</button>
</form>

<form method="POST" action="proses.php">
    <input type="hidden"
           name="aksi" value="simpan_kategori">

    <label>Simpan kategori favorit selama 7 hari:</label>
    <select name="kategori">
        <?php foreach ($kategoriTersedia as $kat): ?>
            <option value="<?= aman($kat) ?>"
                <?= $kategoriAktif === $kat
                    ? "selected" : "" ?>>
                <?= aman($kat) ?>
            </option>
        <?php endforeach; ?>
    </select>
    <button type="submit">Simpan Favorit</button>
</form>

<table>
    <thead>
        <tr>
            <th>Kode</th>
            <th>Nama Menu</th>
            <th>Kategori</th>
            <th>Harga</th>
            <th>Jumlah</th>
            <th>Aksi</th>
        </tr>
    </thead>

    <tbody>
    <?php
    $ditemukan = false;

    foreach ($menu as $kode => $item):
        if (
            $kataKunci !== "" &&
            stripos($item["nama"], $kataKunci) === false
        ) {
            continue;
        }

        if (
            $kategoriAktif !== "" &&
            $item["kategori"] !== $kategoriAktif
        ) {
            continue;
        }

        $ditemukan = true;
    ?>
        <tr>
            <td><?= aman($kode) ?></td>
            <td><?= aman($item["nama"]) ?></td>
            <td><?= aman($item["kategori"]) ?></td>
            <td><?= rupiah($item["harga"]) ?></td>

            <td>
                <form method="POST" action="proses.php">
                    <input type="hidden" name="aksi"
                           value="tambah">
                    <input type="hidden" name="kode"
                           value="<?= aman($kode) ?>">
                    <input type="number" name="jumlah"
                           min="1" max="10"
                           value="1" required>
                    <button type="submit">Pesan</button>
                </form>
            </td>
            <td>
                <a href="keranjang.php">Lihat Keranjang</a>
            </td>
        </tr>
    <?php endforeach; ?>

    <?php if (!$ditemukan): ?>
        <tr>
            <td colspan="6">
                Menu tidak ditemukan.
                Silakan coba kata kunci lain.
            </td>
        </tr>
    <?php endif; ?>
    </tbody>
</table>

<?php require __DIR__ . "/includes/footer.php"; ?>
