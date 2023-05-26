<?php
//SESSİON KONTROLÜ SAĞLAMAK İÇİN
session_start();
//echo $_SESSION['kullanici_id'];
if (!isset($_SESSION['email']) || !isset($_SESSION['kullanici_id'])) {
    header("Location: login.php");
    exit();
}
?>
<?php include_once 'databaseconn.php';  ?>


<?php
// VERİ TABANI BAĞLANTISI İÇİN  




//NOTLAR TABLOSUNDAKİ VERİLERİ ÇEKMEK İÇİN
$query = "SELECT * FROM notlar WHERE kullanici_id=? ORDER BY not_id DESC";
$stmt = $pdo->prepare($query);
$stmt->execute([$_SESSION['kullanici_id']]);
$notlar = $stmt->fetchAll();

  
//VERİYİ GÜNCELLEMEK İÇİN


// Sorgudaki parametreleri POST verilerine bağlamak
if (isset($_POST['update'])) {
    $not_id = $_POST['not_id'];
    $not_baslik = $_POST['not_baslik'];
    $not_icerik = $_POST['not_icerik'];
    $kullanici_id = $_SESSION['kullanici_id']; // aktif kullanıcının ID'sini kullanıyoruz

    $query = "UPDATE notlar SET not_baslik=?, not_icerik=? WHERE not_id=? AND kullanici_id=?";
    $stmt = $pdo->prepare($query);
    $stmt->execute([$not_baslik, $not_icerik, $not_id, $kullanici_id]);
    
}



// Sorguyu çalıştırmak ve güncelleme işlemini tamamlamak
/*if ($stmt->execute()) {
    echo "Not güncellendi.";
} else {
    echo "Güncelleme hatası: " . $stmt->errorInfo();
}*/

// Veri tabanı bağlantısını kapatmak
$pdo = null;


?>





<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <?php include_once 'css.php'; ?>
        <title>Online Not Defteri</title>
</head>

<body>
    <?php include_once 'navbar.php'; ?>

    <div class="container">
    
    <div class="card p-5 blur-card mt-5" style="width: 100%;">
        <div class="row">
        <h1 class="text-center  mb-5" style="font-family: 'Libre Baskerville', serif; color:white;">NOT DEFTERİ</h1>
            <?php $count = 0; ?>
            <?php foreach ($notlar as $not): ?>
                <div class="col-md-4 ">
                    <div class="card shadow mb-3 scale ">
                        <div class="card-body shadow-card">
                            <h3 class="card-title">
                                <label>Not Başlık</label><br>
                                <?php echo $not['not_baslik']; ?>
                            </h3>
                            <p class="card-text"><br>
                                <label>Not İçerik</label>
                                <?php echo $not['not_icerik']; ?>
                            </p>
                            <div class="d-flex justify-content-between">
                                <button type="button" class="btn btn-primary" data-toggle="modal"
                                        data-target="#modal-lg<?php print $not['not_id']; ?>">Düzenle
                                </button>

                                <div class="modal fade" id="modal-lg<?php print $not['not_id']; ?>">
                                    <div class="modal-dialog modal-lg">
                                        <div class="modal-content " style="font-family: 'Libre Baskerville', serif;">
                                            <div class="modal-header">
                                                <h4 class="modal-title"><?php print $not['not_baslik']; ?></h4>
                                                <button type="button" class="close" data-dismiss="modal"
                                                        aria-label="Close">
                                                    <span aria-hidden="true">&times;</span>
                                                </button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="card card-primary">
                                                    <div class="card-header">

                                                    </div>

                                                    <div class="card-body">
                                                        <form method="POST">
                                                            <div class="form-outline mb-4"><br>
                                                                <label>Not Başlık</label>
                                                                <input type="text" name="not_baslik"
                                                                       class="form-control "
                                                                       placeholder="Not Başlık"
                                                                       value="<?php print $not['not_baslik']; ?>"/>
                                                            </div>


                                                            <div class="form-outline  mb-4">
                                                                <label>Not İçerik</label>
                                                                <textarea class="form-control" name="not_icerik"
                                                                          rows="3"
                                                                          placeholder="Not"><?php print $not['not_icerik']; ?></textarea>
                                                                <br>
                                                            </div>
                                                            <div class="modal-footer justify-content-between">
                                                                <button type="button" class="btn btn-default"
                                                                        data-dismiss="modal">Kapat
                                                                </button>
                                                                <button type="submit" class="btn btn-primary"
                                                                        name="update">Güncelle
                                                                </button>
                                                                <input type="hidden" name="not_id"
                                                                       value="<?php print $not['not_id']; ?>">
                                                            </div>
                                                        </form>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <form method="POST" action="sil.php">
                                    <input type="hidden" name="not_id" value="<?php echo $not['not_id']; ?>">
                                    <button type="submit" name="delete" class="btn btn-sm btn-danger">Sil</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <?php $count++; ?>
                <?php if ($count % 3 == 0): ?>
        </div>
        <div class="row">
            <?php endif; ?>
        <?php endforeach; ?>

        <?php if ($count == 0): ?>
            <p>Kayıt bulunamadı.</p>
        <?php endif; ?>

        <div class="col-md-4 text-center pt-5 shadow-card scale icon-container" style="width:333px; height:200px;">

        <a class="text-center  icon" data-toggle="modal" data-target="#modal-lg-add">
  <i class="fa fa-solid fa-plus" style="width:100px; height:100px;"></i>
</a>

            
        </div>
    </div>
</div>
</div>


</body>
<footer>
       <?php  include_once 'js.php'; ?>
    </footer>

</html>