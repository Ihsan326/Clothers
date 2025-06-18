<?php
if (!isset($conn)) {
    require_once '../config/koneksi.php';
    $db = new Database();
    $conn = $db->getConnection();
}

$kategori_result = $conn->query("SELECT id_kategori, nama_kategori FROM kategori");
$brand_result = $conn->query("SELECT id_brand, nama_brand FROM brand");

$kondisi_options = ['BRAND NEW', 'PRELOVED', 'DEFLECT', 'DEAD STOCK'];
$target_kategori = ['MEN', 'WOMEN', 'KIDS', 'UNISEX'];

$hargaMinVal = isset($_GET['harga_min']) ? (int)$_GET['harga_min'] : 0;
$hargaMaxVal = isset($_GET['harga_max']) ? (int)$_GET['harga_max'] : 5000000;
?>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.0/nouislider.min.css">

<div class="filter-bar">
    <form method="GET" action="filter_page.php">

        <!-- Kategori -->
        <label for="kategori">Kategori</label>
        <select name="kategori" id="kategori">
            <option value="">Semua</option>
            <?php while ($kat = $kategori_result->fetch_assoc()): ?>
                <option value="<?= $kat['id_kategori'] ?>" <?= isset($_GET['kategori']) && $_GET['kategori'] == $kat['id_kategori'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($kat['nama_kategori']) ?>
                </option>
            <?php endwhile; ?>
        </select>

        <label for="target_kategori">Gender</label>
        <select name="target_kategori" id="target_kategori">
            <option value="">Semua</option>
            <?php foreach ($target_kategori as $target): ?>
                <option value="<?= $target ?>" <?= isset($_GET['produk']) && $_GET['target_kategori'] == $target ? 'selected' : '' ?>>
                    <?= $target ?>
                </option>
            <?php endforeach; ?>
        </select>

        <!-- Brand -->
        <label for="brand">Brand</label>
        <select name="brand" id="brand">
            <option value="">Semua</option>
            <?php while ($br = $brand_result->fetch_assoc()): ?>
                <option value="<?= $br['id_brand'] ?>" <?= isset($_GET['brand']) && $_GET['brand'] == $br['id_brand'] ? 'selected' : '' ?>>
                    <?= htmlspecialchars($br['nama_brand']) ?>
                </option>
            <?php endwhile; ?>
        </select>

        <!-- Kondisi -->
        <label for="kondisi">Kondisi</label>
        <select name="kondisi" id="kondisi">
            <option value="">Semua</option>
            <?php foreach ($kondisi_options as $kondisi): ?>
                <option value="<?= $kondisi ?>" <?= isset($_GET['kondisi']) && $_GET['kondisi'] == $kondisi ? 'selected' : '' ?>>
                    <?= $kondisi ?>
                </option>
            <?php endforeach; ?>
        </select>

        <style>
            /* Slider container */
            #slider-harga {
                max-width: 220px;
                margin: 15px auto 10px;
            }

            /* noUiSlider Track */
            .noUi-target {
                height: 6px;
                background: #ddd;
                border-radius: 4px;
                border: none;
                box-shadow: none;
            }

            .noUi-connect {
                background: #bfa98f;
            }

            /* noUiSlider Handle (fix agar bulat dan kecil) */
            div#slider-harga .noUi-handle {
                width: 14px;
                height: 14px;
                top: -4px;
                border-radius: 50%;
                background: #fff;
                border: 2px solid #bfa98f;
                box-shadow: none;
                cursor: pointer;
                right: -8px;
            }

            .noUi-handle::before,
            .noUi-handle::after {
                display: none !important; /* buang garis double */
            }

            /* Output label rapi */
            .price-values {
                display: flex;
                justify-content: space-between;
                font-size: 13px;
                font-weight: 500;
                color: #444;
                padding: 0 5px;
                margin-top: 5px;
            }
        </style>

        <!-- noUiSlider -->
        <label style="font-weight: 600;">Rentang Harga (Rp)</label>
        <div id="slider-harga"></div>

        <div class="price-values">
            <span>Min: <span id="minOutput"><?= $hargaMinVal ?></span></span>
            <span>Max: <span id="maxOutput"><?= $hargaMaxVal ?></span></span>
        </div>

        <input type="hidden" name="harga_min" id="harga_min" value="<?= $hargaMinVal ?>">
        <input type="hidden" name="harga_max" id="harga_max" value="<?= $hargaMaxVal ?>">


        <br><br>
        <button type="submit" class="btn">Find it</button>
    </form>
</div>

<!-- noUiSlider Script -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.0/nouislider.min.js"></script>
<script>
    const slider = document.getElementById('slider-harga');

    noUiSlider.create(slider, {
        start: [<?= $hargaMinVal ?>, <?= $hargaMaxVal ?>],
        connect: true,
        step: 50000,
        range: {
            'min': 0,
            'max': 5000000
        },
        format: {
            to: value => Math.round(value),
            from: value => Number(value)
        }
    });

    const minOutput = document.getElementById('minOutput');
    const maxOutput = document.getElementById('maxOutput');
    const hargaMinInput = document.getElementById('harga_min');
    const hargaMaxInput = document.getElementById('harga_max');

    slider.noUiSlider.on('update', function (values) {
        const [minVal, maxVal] = values.map(v => parseInt(v));
        minOutput.textContent = minVal;
        maxOutput.textContent = maxVal;
        hargaMinInput.value = minVal;
        hargaMaxInput.value = maxVal;
    });
</script>
