<?php
session_start();

//Session kontrolu için.
if (!isset($_SESSION['email']) || !isset($_SESSION['kullanici_id'])) {
    header("Location: login.php");
    exit();
}

include_once 'databaseconn.php';

//GÜNCELLEME İŞLEMİ

if (isset($_POST['update'])) {
    $not_id = $_POST['not_id'];
    $not_baslik = $_POST['not_baslik'];
    $not_icerik = $_POST['not_icerik'];
    $kullanici_id = $_SESSION['kullanici_id'];
  
    // Fotoğrafı işleme
    $fotoYuklendi = false;
    $yeniDosyaAdi = '';
  
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
      $fotoTmpPath = $_FILES['foto']['tmp_name'];
      $fotoName = $_FILES['foto']['name'];
      $fotoExt = pathinfo($fotoName, PATHINFO_EXTENSION);
  
      // Kullanıcının klasör yolunu belirleyin
      $klasor = 'uploads/' . $kullanici_id;
  
 
      if (!file_exists($klasor)) {
        mkdir($klasor, 0777, true);
      }
  
    
      $yeniDosyaAdi = $not_id . '.' . $fotoExt;
  
  
      $kaydetKonum = $klasor . '/' . $yeniDosyaAdi;
      move_uploaded_file($fotoTmpPath, $kaydetKonum);
  
      $fotoYuklendi = true;
    }
  
   
    $query = "UPDATE `notlar` SET `not_baslik` = ?, `not_icerik` = ?";
    $params = [$not_baslik, $not_icerik];
    
    
    if ($fotoYuklendi) {
      $query .= ", `fotograf` = ?";
      $params[] = $yeniDosyaAdi;
    }
  
    $query .= " WHERE `not_id` = ? AND `kullanici_id` = ?";
    $params[] = $not_id;
    $params[] = $kullanici_id;
  
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);
  }
  



if (isset($_POST['add'])) {
    $not_baslik = $_POST['not_baslik'];
    $not_icerik = $_POST['not_icerik'];
    $kullanici_id = $_SESSION['kullanici_id'];

    // Fotoğrafı işleme
    $fotoYuklendi = false;
    $yeniDosyaAdi = '';

    if (isset($_FILES['foto']) && $_FILES['foto']['error'] === UPLOAD_ERR_OK) {
        $fotoTmpPath = $_FILES['foto']['tmp_name'];
        $fotoName = $_FILES['foto']['name'];
        $fotoExt = pathinfo($fotoName, PATHINFO_EXTENSION);

        // Kullanıcının klasör yolunu   
        $klasor = 'uploads/' . $kullanici_id;

        // Klasörü oluşturun (eğer mevcut değilse)
        if (!file_exists($klasor)) {
            mkdir($klasor, 0777, true);
        }

        // Yeni dosya adını belirleyin (not_id ile eşleştirme)
        $query = "INSERT INTO `notlar` (`not_baslik`, `not_icerik`, `kullanici_id`) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($query);
        if ($stmt->execute([$not_baslik, $not_icerik, $kullanici_id])) {
            $not_id = $pdo->lastInsertId();
            $yeniDosyaAdi = $not_id . '.' . $fotoExt;

            // Fotoğrafı kaydedin
            $kaydetKonum = $klasor . '/' . $yeniDosyaAdi;
            move_uploaded_file($fotoTmpPath, $kaydetKonum);

            $fotoYuklendi = true;
        }
    }

    // Notu veritabanına ekleyin
    if ($fotoYuklendi) {
        $query = "UPDATE `notlar` SET `fotograf` = ? WHERE `not_id` = ?";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$yeniDosyaAdi, $not_id]);
    } else {
        $query = "INSERT INTO `notlar` (`not_baslik`, `not_icerik`, `kullanici_id`) VALUES (?, ?, ?)";
        $stmt = $pdo->prepare($query);
        $stmt->execute([$not_baslik, $not_icerik, $kullanici_id]);
    }

  
   
}

$query = "SELECT * FROM notlar WHERE kullanici_id=? ORDER BY not_id DESC";
$stmt = $pdo->prepare($query);
$stmt->execute([$_SESSION['kullanici_id']]);
$notlar = $stmt->fetchAll();





?>




<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php include_once 'css.php'; ?>
    <link rel="stylesheet" href="style.css">
    <title>Online Not Defteri</title>
</head>

<body>
    <?php include_once 'navbar.php'; ?>

    <div class="container">

        <div class="card p-5 blur-card mt-5" style="width: 100%;">
            <div class="row">
                <div class="h4 pb-2 mb-4 text-danger border-bottom border-secondary-subtle">
                    <h1 class="text-center  mb-2" style="font-family: 'Libre Baskerville', serif; color:white;">NOT
                        DEFTERİ</h1>
                </div>


            <!-- VERİLERİN LİSTELENMESİ BAŞLANGIÇ-->
                <?php $count = 0; ?>
                <?php foreach ($notlar as $not): ?>
                    <div class="col-md-4">
                        <div class="card shadow mb-3 scale">
                            <div class="card-body shadow-card"> 
                                <h3 class="card-title">
                                    <label>Not Başlık</label><br>
                                    <?php echo $not['not_baslik']; ?>
                                </h3>
                                <p class="card-text"><br>
                                    <label>Not İçerik</label>
                                    <?php echo substr($not['not_icerik'], 0, 70) . '...'; ?>
                                        <br>
                                    <?php if (!empty($not['fotograf'])): ?>
                                        <span class="information" style="opacity: 0.5; color:gray;">(Bu not bir fotoğraf
                                            içeriyor)</span>
                                    <?php endif; ?>
                                </p>

                                <div class="d-flex justify-content-between">

                                    <button type="button" class="btn btn-success" data-toggle="modal"
                                        data-target="#modal-lg-goruntule<?php print $not['not_id']; ?>">Görüntüle
                                    </button>

                                    <button type="button" class="btn btn-primary" data-toggle="modal"
                                        data-target="#modal-lg<?php print $not['not_id']; ?>">Düzenle
                                    </button>

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

            <!-- VERİLERİN LİSTELENMESİ BİTİŞ-->
      


            
                    <!-- DÜZENLEME MODAL BAŞLANGIÇ -->
                    <div class="modal fade" id="modal-lg<?php print $not['not_id']; ?>">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content" style="font-family: 'Libre Baskerville', serif;">
                                <div class="modal-header">
                                    <h4 class="modal-title">
                                        <?php print $not['not_baslik']; ?>
                                    </h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <div class="card card-primary">
                                        <div class="card-header"></div>
                                        <div class="card-body">
                                            <form method="POST" enctype="multipart/form-data">
                                                <div class="form-outline mb-4"><br>
                                                    <label>Not Başlık</label>
                                                    <input type="text" name="not_baslik" class="form-control "
                                                        placeholder="Not Başlık"
                                                        value="<?php print $not['not_baslik']; ?>" />
                                                </div>
                                                <div class="form-outline  mb-4">
                                                    <label>Not İçerik</label>
                                                    <textarea class="form-control" name="not_icerik" rows="3"
                                                        placeholder="Not"><?php print $not['not_icerik']; ?></textarea>
                                                    <br>
                                                </div>
                                                <label for="foto">Fotoğraf Seçin</label>
                                <input type="file" class="form-control-file" id="foto" name="foto" accept="image/*"><br>
                                                <div class="modal-footer justify-content-between">
                                                    <button type="button" class="btn btn-default"
                                                        data-dismiss="modal">Kapat</button>
                                                    <button type="submit" class="btn btn-primary"
                                                        name="update">Güncelle</button>
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
                    <!-- DÜZENLEME MODAL BİTİŞ -->  

                    <!-- GÖRÜNTÜLEME MODAL -->
                    <div class="modal fade" id="modal-lg-goruntule<?php print $not['not_id']; ?>">
                        <div class="modal-dialog modal-lg">
                            <div class="modal-content" style="font-family: 'Libre Baskerville', serif;">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    
                                        <div class="card-body">
                                            <?php if (!empty($not['fotograf'])): ?>
                                                <img src="uploads/<?php print $not['kullanici_id']; ?>/<?php print $not['fotograf']; ?>" style="width:100%;">
                                                <br>
                                            <?php endif; ?>
                                            
                                            <div class="container  mt-3 shadow-lg p-3 mb-5 bg-white rounded">
                                            <h4 class="text-center border-bottom mb-5">
                                                <br>
                                            <?php print $not['not_baslik']; ?>

                                            </h4>
                                            <div class="container">
                                                <?php print $not['not_icerik']; ?>
                                                </div>
                                            </div>
                                            <br>
                                            <div class="modal-footer tex-center">
                                                <button type="button" class="btn btn-success"
                                                    data-dismiss="modal">Kapat</button>
                                                
                                            </div>
                                        </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                <!-- GÖRÜNTÜLEME MODAL BİTİŞ -->


                <?php endforeach; ?>



                <!-- NOT EKLEME BUTONU BAŞLANGIÇ -->
                <div class="col-md-4 text-center pt-5 blur-card scale icon-container" data-toggle="modal"
                    data-target="#modal-lg-add" style="width:333px; height:200px;">

                    <a class="icon">
                        <i class="fa fa-solid fa-plus" style="width:100px; height:100px;"></i>
                    </a>
                </div>
                <!-- NOT EKLEME BUTONU BİTİŞ -->
          
              <!-- NOT EKLEME MODAL BAŞLANGIÇ -->
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
                            <form method="POST" enctype="multipart/form-data">
                                <div class="form-outline mb-4">
                                    <label for="not_baslik">Not Başlık</label>
                                    <input type="text" name="not_baslik" id="not_baslik" class="form-control" />
                                </div>

                                <div class="form-outline mb-4">
                                    <label for="not_icerik">Not İçerik</label>
                                    <textarea class="form-control" name="not_icerik" id="not_icerik"
                                        rows="3"></textarea>
                                </div>
                                <label for="foto">Fotoğraf Seçin</label>
                                <input type="file" class="form-control-file" id="foto" name="foto" accept="image/*"><br>


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
<!-- NOT EKLEME MODAL BİTİŞ -->



            </div>
        </div>
    </div>
   
    <footer>
<!-- "hope my death makes more cents than my life" -->
    <?php include_once 'js.php'; ?>
</footer>

</body>


</html>