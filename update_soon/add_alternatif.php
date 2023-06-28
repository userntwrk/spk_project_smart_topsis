<?php include "layouts.php"; ?>
<!DOCTYPE html>
<html>
<head>
	<title>Tambah Alternatif</title>
	<link rel="stylesheet" href="assets/css/bootstrap.css">	
</head>
<body>
	<div class="container">
		<h2>Tambah Alternatif</h2>
		<form action="proses_add_alternatif.php" method="GET">
			<label for="">Id</label>
			<input type="text" name="id" required="" class="form-control">

			<label for="">Nama</label>
			<input type="text" name="nama" required="" class="form-control">

			<label for="">Status</label>
			<input type="number" name="status" required="" class="form-control"><br>
			
            <label for="">Presentase Keuntungan</label>
			<input type="number" name="presentase_keuntungan" required="" class="form-control">			

            <label for="">Tempo Pembayaran</label>
			<input type="number" name="tempo_pembayaran" required="" class="form-control">			

            <label for="">Kualitas Produk</label>
			<input type="number" name="kualitas_produk" required="" class="form-control">			

            <label for="">Layanan Kualitas</label>
			<input type="number" name="layanan_kualitas" required="" class="form-control">			

            <label for="">Bonus</label>
			<input type="number" name="bonus" required="" class="form-control">			

            <br>
			<input type="submit" value="Tambahkan" class="btn btn-success">	
		</form>
	</div>
</body>
</html>
<?php include "layouts_footer.php"; ?>		