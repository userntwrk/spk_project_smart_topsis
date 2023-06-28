<?php include "layouts.php" ?>

<div class="card">
    <div class="card-header border-transparent">
        <h3 class="card-title">Table Matriks</h3>

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

    // Retrieve criteria from the database
    $criteriaQuery = "SELECT * FROM kriteria";
    $criteriaResult = mysqli_query($conn, $criteriaQuery);
    $criteria = array();
    while ($row = mysqli_fetch_assoc($criteriaResult)) {
        $criteria[] = $row['nama'];
    }

    // Retrieve alternatives from the database
    $alternativesQuery = "SELECT * FROM alternatif";
    $alternativesResult = mysqli_query($conn, $alternativesQuery);
    $alternatives = array();
    while ($row = mysqli_fetch_assoc($alternativesResult)) {
        $alternatives[] = $row['nama'];
    }

    // Retrieve nilai from the database
    $nilaiQuery = "SELECT * FROM nilai";
    $nilaiResult = mysqli_query($conn, $nilaiQuery);
    $nilai = array();
    while ($row = mysqli_fetch_assoc($nilaiResult)) {
        $nilai[] = $row['nilai'];
    }

    // Retrieve values from the database using JOIN
    $matrixQuery = "SELECT k.nama AS kriteria, a.nama AS alternatif, n.nilai 
                    FROM kriteria k
                    CROSS JOIN alternatif a
                    LEFT JOIN nilai n ON n.id_kriteria = k.id AND n.id_alternatif = a.id";
    $matrixResult = mysqli_query($conn, $matrixQuery);
    $matrixTable = array();
    while ($row = mysqli_fetch_assoc($matrixResult)) {
        $kriteria = $row['kriteria'];
        $alternatif = $row['alternatif'];
        $nilai = $row['nilai'];
        $matrixTable[$kriteria][$alternatif] = $nilai;
    }
    ?>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table m-0">
                <thead>
                    <tr>
                        <th rowspan="2">No</th>
                        <th rowspan="2">Nama</th>
                        <th colspan="<?php echo count($alternatives); ?>">Kriteria</th>                        
                    </tr>
                    <tr>
                        <?php
                        foreach ($alternatives as $alternative) {
                            echo "<th>$alternative</th>";
                        }
                        ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $no = 1;
                    foreach ($criteria as $criterion) {
                        echo "<tr>
                                <td>$no</td>
                                <td>$criterion</td>";
                        foreach ($alternatives as $alternative) {
                            if (isset($matrixTable[$criterion][$alternative])) {
                                $nilai = $matrixTable[$criterion][$alternative];
                                echo "<td>$nilai</td>";
                            } else {
                                echo "<td></td>";
                            }
                        }                        
                        $no++;
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include "layouts_footer.php" ?>
