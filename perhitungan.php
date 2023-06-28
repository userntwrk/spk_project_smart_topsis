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
    <!-- Isi Matrisk -->
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

<?php include 'layouts_footer.php'; ?>