<?php
include 'layouts.php';
include 'koneksi.php';

?>

<div class="card">
    <div class="card-header border-transparent">
        <h3 class="card-title">Hasil Normalisasi Terbobot</h3>

        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
            </button>
            <button type="button" class="btn btn-tool" data-card-widget="remove">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>

    <?php
    include "koneksi.php";
    $s = mysqli_query($conn, "SELECT * FROM kriteria ");
    $h = mysqli_num_rows($s);
    ?>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table m-0">
                <thead>
                    <tr>
                        <th rowspan="2">No</th>
                        <th rowspan="2">Nama</th>
                        <th colspan="<?php echo $h; ?>">Kriteria</th>
                    </tr>
                    <tr>
                        <?php
                        for ($n = 1; $n <= $h; $n++) {
                            echo "<th>C{$n}</th>";
                        }
                        ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 0;
                    $a = mysqli_query($conn, "SELECT * FROM alternatif ORDER BY id ASC");

                    // Arrays to store the positive and negative ideal solutions
                    $positiveIdeal = array_fill(0, $h, 0);
                    $negativeIdeal = array_fill(0, $h, PHP_INT_MAX);

                    while ($da = mysqli_fetch_assoc($a)) {
                        echo "<tr><td>" . (++$i) . "</td><td>$da[nama]</td>";
                        $idalt = $da['id'];

                        // Arrays to store the alternative values
                        $alternativeValues = array();

                        $n = mysqli_query($conn, "SELECT * FROM nilai WHERE id_alternatif='$idalt' ORDER BY id ASC");

                        while ($dn = mysqli_fetch_assoc($n)) {
                            $idk = $dn['id_kriteria'];

                            // Value normalization

                            // Calculate the sum of squared values
                            $nilai_kuadrat = 0;
                            $k = mysqli_query($conn, "SELECT * FROM nilai WHERE id_kriteria='$idk' ");
                            while ($dkuadrat = mysqli_fetch_assoc($k)) {
                                $nilai_kuadrat = $nilai_kuadrat + ($dkuadrat['nilai'] * $dkuadrat['nilai']);
                            }

                            // Calculate the number of alternatives
                            $jml_alternatif = mysqli_query($conn, "SELECT * FROM alternatif");
                            $jml_a = mysqli_num_rows($jml_alternatif);

                            // Calculate the average weight of the criteria
                            $bobot = 0;
                            $tnilai = 0;

                            $k2 = mysqli_query($conn, "SELECT * FROM nilai WHERE id_kriteria='$idk' ");
                            while ($dbobot = mysqli_fetch_assoc($k2)) {
                                $tnilai = $tnilai + $dbobot['nilai'];
                            }
                            $bobot = $tnilai / $jml_a;

                            // Get the weight of the input criteria
                            $sql = mysqli_query($conn, "SELECT * FROM kriteria WHERE id='$idk'");
                            $nbot = mysqli_fetch_assoc($sql);
                            $bot = $nbot['bobot'];

                            // Calculate the normalized value
                            $normalizedValue = round(($dn['nilai'] / sqrt($nilai_kuadrat)) * $bot, 6);

                            // Store the normalized value
                            $alternativeValues[$idk] = $normalizedValue;

                            echo "<td align='center'>$normalizedValue</td>";

                            // Find the positive ideal solution
                            if (!isset($positiveIdeal[$idk]) || $normalizedValue > $positiveIdeal[$idk]) {
                                $positiveIdeal[$idk] = $normalizedValue;
                            }

                            // Find the negative ideal solution
                            if (!isset($negativeIdeal[$idk]) || $normalizedValue < $negativeIdeal[$idk]) {
                                $negativeIdeal[$idk] = $normalizedValue;
                            }
                        }
                        echo "</tr>\n";

                        // Calculate the maximum and minimum costs
                        $maxCost = max($alternativeValues);
                        $minCost = min($alternativeValues);
                    }
                    ?>
                </tbody>
                <?php
                echo "<tr>";
                echo "<td colspan='" . ($h + 2) . "'><strong>Max Cost:</strong> $maxCost, <strong>Min Cost:</strong> $minCost</td>";
                echo "</tr>";
                ?>
            </table>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header border-transparent">
        <h3 class="card-title">Solusi Ideal Positif dan Negatif</h3>

        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
            </button>
            <button type="button" class="btn btn-tool" data-card-widget="remove">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>

    <!-- Display the positive and negative ideal solutions -->
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table m-0">
                <thead>
                    <tr>
                        <th>Kriteria</th>
                        <th>Positive Ideal</th>
                        <th>Negative Ideal</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $kriteria = mysqli_query($conn, "SELECT * FROM kriteria");
                    while ($k = mysqli_fetch_assoc($kriteria)) {
                        $idk = $k['id'];
                        echo "<tr>";
                        echo "<td>C{$k['id']}</td>";
                        echo "<td>" . (array_key_exists($idk, $positiveIdeal) ? $positiveIdeal[$idk] : '') . "</td>";
                        echo "<td>" . (array_key_exists($idk, $negativeIdeal) ? $negativeIdeal[$idk] : '') . "</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header border-transparent">
        <h3 class="card-title">Jarak Alternatif dengan Solusi Ideal</h3>

        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
            </button>
            <button type="button" class="btn btn-tool" data-card-widget="remove">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>

    <!-- Calculate the distance between alternatives and ideal solutions -->
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table m-0">
                <thead>
                    <tr>
                        <th>Alternatif</th>
                        <th>Jarak Ideal Positif</th>
                        <th>Jarak Ideal Negatif</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $alternatives = mysqli_query($conn, "SELECT * FROM alternatif");
                    while ($alt = mysqli_fetch_assoc($alternatives)) {
                        $idalt = $alt['id'];
                        echo "<tr>";
                        echo "<td>$alt[nama]</td>";

                        // Calculate the distance to positive ideal solution
                        $positiveDistance = 0;
                        foreach ($alternativeValues as $idk => $value) {
                            $positiveDistance += pow($value - $positiveIdeal[$idk], 2);
                        }
                        $positiveDistance = round(sqrt($positiveDistance), 6);

                        echo "<td>$positiveDistance</td>";

                        // Calculate the distance to negative ideal solution
                        $negativeDistance = 0;
                        foreach ($alternativeValues as $idk => $value) {
                            $negativeDistance += pow($value - $negativeIdeal[$idk], 2);
                        }
                        $negativeDistance = round(sqrt($negativeDistance), 6);

                        echo "<td>$negativeDistance</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header border-transparent">
        <h3 class="card-title">Nilai Preferensi Setiap Alternatif</h3>

        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
            </button>
            <button type="button" class="btn btn-tool" data-card-widget="remove">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>

    <!-- Calculate the preference values for each alternative -->
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table m-0">
                <thead>
                    <tr>
                        <th>Alternatif</th>
                        <th>Nilai Preferensi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $alternatives = mysqli_query($conn, "SELECT * FROM alternatif");
                    $preferenceValues = array(); // Store preference values

                    while ($alt = mysqli_fetch_assoc($alternatives)) {
                        $idalt = $alt['id'];
                        echo "<tr>";
                        echo "<td>$alt[nama]</td>";

                        // Calculate the distance to positive ideal solution
                        $positiveDistance = 0;
                        foreach ($alternativeValues as $idk => $value) {
                            $positiveDistance += pow($value - $positiveIdeal[$idk], 2);
                        }
                        $positiveDistance = round(sqrt($positiveDistance), 6);

                        // Calculate the distance to negative ideal solution
                        $negativeDistance = 0;
                        foreach ($alternativeValues as $idk => $value) {
                            $negativeDistance += pow($value - $negativeIdeal[$idk], 2);
                        }
                        $negativeDistance = round(sqrt($negativeDistance), 6);

                        // Calculate the preference value
                        $preferenceValue = $negativeDistance / ($negativeDistance + $positiveDistance);
                        $preferenceValues[$idalt] = $preferenceValue; // Store the preference value

                        echo "<td>$preferenceValue</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header border-transparent">
        <h3 class="card-title">Peringkat Alternatif</h3>

        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
            </button>
            <button type="button" class="btn btn-tool" data-card-widget="remove">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>

    <!-- Rank the alternatives based on their preference values -->
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table m-0">
                <thead>
                    <tr>
                        <th>Peringkat</th>
                        <th>Alternatif</th>
                        <th>Nilai Preferensi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    arsort($preferenceValues); // Sort preference values in descending order
                    $ranking = 1;
                    foreach ($preferenceValues as $idalt => $preferenceValue) {
                        $alternatif = mysqli_query($conn, "SELECT * FROM alternatif WHERE id = $idalt");
                        $alt = mysqli_fetch_assoc($alternatif);
                        echo "<tr>";
                        echo "<td>$ranking</td>";
                        echo "<td>$alt[nama]</td>";
                        echo "<td>$preferenceValue</td>";
                        echo "</tr>";
                        $ranking++;
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

