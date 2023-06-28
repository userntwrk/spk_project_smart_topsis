<?php
include "layouts.php";
include "koneksi.php";

$sql = "SELECT * FROM alternatif JOIN nilai ON alternatif.id = nilai.id_alternatif";
$result = mysqli_query($conn, $sql);
?>

<div class="card">
    <div class="card-header border-transparent">
        <h3 class="card-title">Data Alternatif</h3>

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
                        <th>ID</th>
                        <th>Name</th>
                        <th>Status</th>
                        <th>Nilai</th>
                    </tr>
                </thead>
                <?php
                while ($row = $result->fetch_array()) {
                    ?>
                    <tbody>
                        <tr>
                            <td>
                                <?php echo $row['id']; ?>
                            </td>
                            <td>
                                <?php echo $row['nama']; ?>
                            </td>
                            <td>
                                <?php echo $row['status']; ?>
                            </td>
                            <td>
                                <?php echo $row['nilai']; ?>
                            </td>
                        </tr>
                    </tbody>
                <?php } ?>
            </table>
        </div>
        <!-- /.table-responsive -->
    </div>
</div>
<!-- /.card -->

<?php include "layouts_footer.php"; ?>