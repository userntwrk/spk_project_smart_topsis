<?php
include "layouts.php";
include "koneksi.php";

$result = mysqli_query($conn, "SELECT * FROM kriteria");
$result = mysqli_num_rows($result);
?>

<div class="card">
    <div class="card-header border-transparent">
        <h3 class="card-title">Normalisasi Terbobot</h3>

        <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
            </button>
            <button type="button" class="btn btn-tool" data-card-widget="remove">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
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
                        for ($n = 1; $n <= $result; $n++) {
                            echo "<th>C{$n}</th>";
                        }
                        ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 0;
                    $sql = mysqli_query($conn, "SELECT * FROM alternatif ORDER BY id ASC");

                    while ($result = mysqli_fetch_assoc($sql)) {

                        echo "<tr><td>" . (++$i) . "</td><td>$result[nama]</td>";
                        $idalt = $result['id'];

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

                            echo "<td align='center'>" . round(($dn['nilai'] / sqrt($nilai_kuadrat)), 6) . "</td>";

                        }
                        echo "</tr>\n";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php include "layouts_footer.php"; ?>