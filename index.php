<!--  database conect-->
<?php
 
// Konfigurasi Database
$host   = 'localhost'; // host
$user   = 'root'; // username database
$pass   = ''; // password database
$db     = 'dbsyarbinihafec'; // nama database
 
$koneksi    = mysqli_connect($host, $user, $pass, $db);
if (!$koneksi) { //cek koneksi
    die("Tidak bisa terkoneksi ke database");
}
if (!$db) {
    echo "Database Error";
}
$pjName   = "";
$client   = "";
$pjLead   = "";
$sdate    = "";
$edate    = "";
$progress = "";
$sukses   = "";
$error    = "";

if (isset($_GET['op'])) {
  $op = $_GET['op'];
}else{
  $op = "";
}
if($op == 'delete'){
  $nomor         = $_GET['nomor'];
  $sql1       = "delete from monitoring where nomor = '$nomor'";
  $q1         = mysqli_query($koneksi,$sql1);
  if($q1){
      $sukses = "Berhasil hapus data";
  }else{
      $error  = "Gagal melakukan delete data";
  }
}
if ($op == 'edit') {
  $nomor      = $_GET['nomor'];
  $sql1       = "select * from monitoring where nomor = '$nomor'";
  $q1         = mysqli_query($koneksi, $sql1);
  $r1         = mysqli_fetch_array($q1);
  $pjName     = $r1['pjName'];
  $client     = $r1['client'];
  $pjLead     = $r1['pjLead'];
  $sdate      = $r1['sdate'];
  $edate      = $r1['edate'];
  $progress   = $r1['progress'];

  if ($pjName == '') {
      $error = "Data tidak ditemukan";
  }
}

// create
if(isset($_POST['submit'])){
  $pjName     = $_POST['pjName'];
  $client     = $_POST['client'];
  $pjLead     = $_POST['pjLead'];
  $sdate      = $_POST['sdate'];
  $edate      = $_POST['edate'];
  $progress   = $_POST['progress'];
  
  // cek isi
  if ($pjName && $client && $pjLead && $sdate && $edate && $progress){
    if ($op == 'edit') { //untuk update
      $sql1       = "update monitoring set pjName = '$pjName',client='$client',pjLead = '$pjLead',sdate='$sdate',edate = '$edate',progress='$progress'  where nomor = '$nomor'";
      $q1         = mysqli_query($koneksi, $sql1);
      if ($q1) {
          $sukses = "Data berhasil diupdate";
      } else {
          $error  = "Data gagal diupdate";
      }
  } else { //untuk insert
    $sql1 = "insert into monitoring(pjName,client,pjLead,sdate,edate,progress) values ('$pjName', '$client', '$pjLead', '$sdate', '$edate', '$progress')";    
    $q1   = mysqli_query($koneksi, $sql1);
    if($q1){
      $sukses   = "BERHASIL";
    }else{
      $error    = "GAGAL";
    }
  }
  }else{
    $error = "TIDAK BOLEH KOSONG!";
  }
}
?>


<!doctype html>
<html lang="en">
  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">

    <title>Monitoring</title>
  </head>
  <body>
    

    <div class="container mt-5">
      <!-- card -->
      <div class="card">
        <h5 class="card-header">Create/Edit</h5>
        <div class="card-body">
          <?php
            if ($error) {
              ?>
              <div class="alert alert-danger" role="alert">
                  <?php echo $error ?>
              </div>
              <?php
            }
          ?>
          <?php
            if ($sukses) {
              ?>
              <div class="alert alert-success" role="alert">
                  <?php echo $sukses ?>
              </div>
              <?php
            }
          ?>
           

          <form action="" method="POST">
            <div class="mb-3">
              <label for="pjName" class="form-label">Project Name</label>
              <input type="text" class="form-control" id="pjName" name="pjName" values =" <?php echo $pjName ?>">           
            </div>
            <div class="mb-3">
              <label for="client" class="form-label">Client</label>
              <input type="text" class="form-control" id="client" name="client" value=" <?php echo $client ?>"> 
            </div>
            <div class="mb-3">
              <label for="pjLead" class="form-label">Project Lead</label>
              <input type="text" class="form-control" id="pjLead" name="pjLead" value=" <?php echo $pjLead ?>">           
            </div>
            <div class="mb-3">
              <label for="sdate" class="form-label">Start Date</label>
              <input type="text" class="form-control" id="sDate" name="sdate" value=" <?php echo $sdate ?>"> 
            </div>
            <div class="mb-3">
              <label for="edate" class="form-label">End Date</label>
              <input type="text" class="form-control" id="eDate" name="edate" value=" <?php echo $edate ?>">           
            </div>
            <div class="mb-3">
              <label for="progress" class="form-label">progresss</label>
              <input type="text" class="form-control" id="progress" name="progress" value=" <?php echo $progress ?>"> 
            </div>
            
            <button type="submit" name="submit" value="simpan data" class="btn btn-primary">Simpan Data</button>
          </form>
        </div>
      </div>
      <!-- end card -->

      <!-- start table -->
      <div class="card">
            <div class="card-header text-white bg-secondary">
                Data Monitoring
            </div>
            <div class="card-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">#</th>
                            <th scope="col">Project Name</th>
                            <th scope="col">Client</th>
                            <th scope="col">Project Lead</th>
                            <th scope="col">Start Date</th>
                            <th scope="col">End Date</th>
                            <th scope="col">Progress</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql2   = "select * from monitoring order by nomor desc";
                        $q2     = mysqli_query($koneksi, $sql2);
                        $urut   = 1;
                        while ($r2 = mysqli_fetch_array($q2)) {
                            $nomor      = $r2['nomor'];
                            $pjName     = $r2['pjName'];
                            $client     = $r2['client'];
                            $pjLead     = $r2['pjLead'];
                            $sdate      = $r2['sdate'];
                            $edate      = $r2['edate'];
                            $progress   = $r2['progress'];

                        ?>
                            <tr>
                                <th scope="row"><?php echo $urut++ ?></th>
                                <td scope="row"><?php echo $pjName ?></td>
                                <td scope="row"><?php echo $client ?></td>
                                <td scope="row"><?php echo $pjLead ?></td>
                                <td scope="row"><?php echo $sdate ?></td>
                                <td scope="row"><?php echo $edate ?></td>
                                <td scope="row"><?php echo $progress ?></td>
                                <td scope="row">
                                    <a href="index.php?op=edit&nomor=<?php echo $nomor ?>"><button type="button" class="btn btn-success">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
                                      <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
                                      <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"/>
                                    </svg>
                                    </button></a>
                                    <a href="index.php?op=delete&nomor=<?php echo $nomor?>" onclick="return confirm('Yakin mau delete data?')"><button type="button" class="btn btn-danger">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash-fill" viewBox="0 0 16 16">
                                        <path d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 1 0z"/>
                                    </svg>
                                    </button></a>            
                                </td>
                            </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                    
                </table>
            </div>
        </div>
      </div>
      <!-- end table -->



    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js" integrity="sha384-7+zCNj/IqJ95wo16oMtfsKbZ9ccEh31eOz1HGyDuCQ6wgnyJNSYdrPa03rtR1zdB" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.min.js" integrity="sha384-QJHtvGhmr9XOIpI6YVutG+2QOK9T+ZnN4kzFN1RtK3zEFEIsxhlmWl5/YESvpZ13" crossorigin="anonymous"></script>
    -->
  </body>
</html>