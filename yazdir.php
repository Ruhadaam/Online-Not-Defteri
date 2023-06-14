<?php
$db = 'mysql:host=localhost;dbname=onlinenot';
$username = 'root';
$password = '';
$options = array(
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_EMULATE_PREPARES => false,
);

try {
    $pdo = new PDO($db, $username, $password, $options);
} catch (PDOException $e) {
    echo "Bağlantı hatası: " . $e->getMessage();
}





?>



<?php

if (isset($_POST['add'])) {
    $not_baslik = $_POST['not_baslik'];
    $not_icerik = $_POST['not_icerik'];
    $kullanici_id = $_SESSION['kullanici_id'];

    $query = "INSERT INTO `notlar` (`not_baslik`, `not_icerik`, `kullanici_id`) VALUES (?, ?, ?)";
    $stmt = $pdo->prepare($query);
    if ($stmt->execute([$not_baslik, $not_icerik, $kullanici_id])) {

        echo '<script>
                setTimeout(function() {
                  $(".alert-success").alert("close");
                }, 3000);
              </script>';
        echo '<div class="alert alert-success alert-dismissible">
                İşlem başarıyla gerçekleşti.
              </div>';
    } else {
        echo '<script>
                setTimeout(function() {
                  $(".alert-danger").alert("close");
                }, 3000);
              </script>';
        echo '<div class="alert alert-danger alert-dismissible">
                İşlem tamamlanmadı!
              </div>';
    }

    
    header('Location: index.php');
    exit();
}


?>

<nav class="navbar navbar-expand-lg bg-dark p-3">
    <div class="collapse navbar-collapse text-center">
   
    </div>

        <ul class="navbar-nav ml-auto">
            <li class="nav-item">
        <a class="btn btn-outline-danger  ml-3 my-2 my-sm-0" href="logout.php">Çıkış</a>
        </li>
        </ul>

    </div>

<!-- Not Ekleme Modal -->
<div class="modal fade" id="modal-lg-add">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" style="color:black;">Not Ekle</h4>
                        
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="card card-success">
                            <div class="card-header">

                            </div>

                            <div class="card-body">
                                <form method="POST">
                                    <div class="form-outline mb-4">
                                        <label for="not_baslik">Not Başlık</label>
                                        <input type="text" name="not_baslik" id="not_baslik" class="form-control" />
                                    </div>

                                    <div class="form-outline mb-4">
                                        <label for="not_icerik">Not İçerik</label>
                                        <textarea class="form-control" name="not_icerik" id="not_icerik"
                                            rows="3"></textarea>
                                    </div>

                                    <div class="modal-footer justify-content-between">
                                        <button type="button" class="btn btn-danger" data-dismiss="modal">Kapat</button>
                                        <button type="submit" class="btn btn-success" name="add">Ekle</button>
                                    </div>
                                </form>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>








</nav>