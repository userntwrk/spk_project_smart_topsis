<?php include "layouts.php"; ?>

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
    <!-- Isi Matriks -->
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

                    while ($da = mysqli_fetch_assoc($a)) {
                        echo "<tr><td>" . (++$i) . "</td><td>$da[nama]</td>";
                        $idalt = $da['id'];

                        //ambil nilai

                        $n = mysqli_query($conn, "SELECT * FROM nilai WHERE id_alternatif='$idalt' ORDER BY id ASC");

                        while ($dn = mysqli_fetch_assoc($n)) {
                            $idk = $dn['id_kriteria'];

                            //nilai kuadrat

                            $nilai_kuadrat = 0;
                            $k = mysqli_query($conn, "SELECT * FROM nilai WHERE id_kriteria='$idk' ");
                            while ($dkuadrat = mysqli_fetch_assoc($k)) {
                                $nilai_kuadrat = $nilai_kuadrat + ($dkuadrat['nilai'] * $dkuadrat['nilai']);
                            }

                            //hitung jml alternatif
                            $jml_alternatif = mysqli_query($conn, "SELECT * FROM alternatif");
                            $jml_a = mysqli_num_rows($jml_alternatif);

                            //nilai bobot kriteria (rata")
                            $bobot = 0;
                            $tnilai = 0;

                            $k2 = mysqli_query($conn, "SELECT * FROM nilai WHERE id_kriteria='$idk' ");
                            while ($dbobot = mysqli_fetch_assoc($k2)) {
                                $tnilai = $tnilai + $dbobot['nilai'];
                            }
                            $bobot = $tnilai / $jml_a;

                            //nilai bobot input
                            $sql = mysqli_query($conn, "SELECT * FROM kriteria WHERE id='$idk'");
                            $nbot = mysqli_fetch_assoc($sql);
                            $bot = $nbot['bobot'];

                            echo "<td align='center'>" . round(($dn['nilai'] / sqrt($nilai_kuadrat)) * $bot, 6) . "</td>";

                        }
                        echo "</tr>\n";

                    }
                    ?>

                </tbody>
            </table>
        </div>
    </div>
</div>

<?php
// Ranking based on preference value
$alternativeValues = array(); // Initialize the array
$a = mysqli_query($conn, "SELECT * FROM alternatif ORDER BY id ASC");

while ($da = mysqli_fetch_assoc($a)) {
    $idalt = $da['id'];

    // Arrays to store the alternative values
    $alternativeValues[$idalt] = 0;

    $n = mysqli_query($conn, "SELECT * FROM nilai WHERE id_alternatif='$idalt' ORDER BY id ASC");

    while ($dn = mysqli_fetch_assoc($n)) {
        $idk = $dn['id_kriteria'];

        // Value normalization
        $nilai_kuadrat = 0;
        $k = mysqli_query($conn, "SELECT * FROM nilai WHERE id_kriteria='$idk' ");
        while ($dkuadrat = mysqli_fetch_assoc($k)) {
            $nilai_kuadrat = $nilai_kuadrat + ($dkuadrat['nilai'] * $dkuadrat['nilai']);
        }

        $jml_alternatif = mysqli_query($conn, "SELECT * FROM alternatif");
        $jml_a = mysqli_num_rows($jml_alternatif);

        $bobot = 0;
        $tnilai = 0;

        $k2 = mysqli_query($conn, "SELECT * FROM nilai WHERE id_kriteria='$idk' ");
        while ($dbobot = mysqli_fetch_assoc($k2)) {
            $tnilai = $tnilai + $dbobot['nilai'];
        }

        $bobot = $tnilai / $jml_a;

        $sql = mysqli_query($conn, "SELECT * FROM kriteria WHERE id='$idk'");
        $nbot = mysqli_fetch_assoc($sql);
        $bot = $nbot['bobot'];

        // Calculate the normalized value
        $normalizedValue = round(($dn['nilai'] / sqrt($nilai_kuadrat)) * $bot, 6);

        // Accumulate the normalized value for each alternative
        $alternativeValues[$idalt] += $normalizedValue;
    }
}

// Ranking based on preference value
arsort($alternativeValues);
$ranking = array_keys($alternativeValues);

echo "<div class='card mt-3'>";
echo "<div class='card-header border-transparent'>";
echo "<h3 class='card-title'>Ranking</h3>";
echo "</div>";
echo "<div class='card-body p-0'>";
echo "<div class='table-responsive'>";
echo "<table class='table m-0'>";
echo "<thead>";
echo "<tr>";
echo "<th>No</th>";
echo "<th>Nama</th>";
echo "</tr>";
echo "</thead>";
echo "<tbody>";
$rank = 1;
foreach ($ranking as $index) {
    $query = mysqli_query($conn, "SELECT * FROM alternatif WHERE id = '$index'");
    $alternatif = mysqli_fetch_assoc($query);
    echo "<tr>";
    echo "<td>{$rank}</td>";
    echo "<td>{$alternatif['nama']}</td>";
    echo "</tr>";
    $rank++;
}
echo "</tbody>";
echo "</table>";
echo "</div>";
echo "</div>";
echo "</div>";

include 'layouts_footer.php';
?>
