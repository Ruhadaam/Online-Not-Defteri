<?php
include_once 'databaseconn.php';
?>
<?php

if (isset($_POST['login'])) {

    $email = $_POST['email'];
    $sifre = md5($_POST['sifre']);

    // Kullanıcı bilgilerini sorgulamak için.
    $sorgu = "SELECT * FROM kullanici_tbl WHERE mail=:mail AND sifre=:sifre";
    $stmt = $pdo->prepare($sorgu);
    $stmt->bindParam(':mail', $email);
    $stmt->bindParam(':sifre', $sifre);
    $stmt->execute();

    // Eğer kullanıcı bilgisi varsa, kullanıcının mailini ve id'sini session olarak saklamak için.
    if ($stmt->rowCount() > 0) {
        $kullanici = $stmt->fetch();
        $kullanici_id = $kullanici['kullanici_id'];
        // Kullanıcının adını session olarak sakla
        session_start();
        $_SESSION['email'] = $email;
        $_SESSION['kullanici_id'] = $kullanici_id;

       
        header("Location: index.php");
        exit();
    } else {
        echo '<div class="alert alert-danger alert-dismissible">
                    
            <h5><i class="icon fas fa-ban"></i> HATA!</h5>
           Mail veya şifre hatalı.
        </div>';
    }
}


//Üye kaydı gerçekleştirmek için
if (isset($_POST['save'])) {

    $ad= $_POST['ad'];
    $mail= $_POST['mail'];
    $password = md5($_POST['password']);

    $query = "INSERT INTO `kullanici_tbl` (`ad_soyad`, `mail`, `sifre`) VALUES (?, ?, ?)";
    $stmt = $pdo->prepare($query);
    if ($stmt->execute([$ad, $mail, $password])) {
        echo '<div class="alert alert-success alert-dismissible show" role="alert">
        <h5><i class="icon fas fa-check"></i> BAŞARILI</h5>
        İşlem başarıyla gerçekleşti.
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>';

    echo '<script>
        setTimeout(function() {
            $(".alert").alert("close");
        }, 3000);
    </script>';
} else {
    echo '<div class="alert alert-danger alert-dismissible show" role="alert">
        <h5><i class="icon fas fa-ban"></i> HATA!</h5>
        Kayıtlı kullanıcı!
        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
            <span aria-hidden="true">&times;</span>
        </button>
    </div>';
}
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="loginstyle.css">
    <?php include_once 'css.php'; ?>
    <title>Giriş Yap</title>
</head>
<body>
    <section class="vh-100 gradient-custom">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                    <div class="card bg-dark    " style="border-radius: 1rem;">
                        <div class="card-body p-5 ">

                            <div class="mb-md-5 mt-md-4 pb-5">

                                <h2 class="fw-bold mb-2 text-uppercase text-center" style="color:white;">online not
                                    defteri</h2>


                                <form method="POST">
                                    <div class="form-outline  mb-4">
                                        <input type="email" name="email" class="form-control form-control-lg"
                                            placeholder="Email" />
                                    </div>

                                    <div class="form-outline  mb-4">
                                        <input type="password" name="sifre" class="form-control form-control-lg"
                                            placeholder="Şifre" />
                                    </div>

                                    <div class="d-grid">
                                        <button class="btn btn-outline-light" type="submit" name="login">Giriş
                                            Yap</button><br><br>
                                        <a class="btn btn-block btn-outline-light" href="google.php"
                                            style="background-color: #dd4b39;" type="submit"><i
                                                class="fab fa-google me-2"></i> Google ile giriş yap</a>
                                    </div>
                                </form>



                            </div>

                            <div class="text-center">
                                <p class="mb-0" style="color:white;">Henüz bir hesabın yok mu ?</p>
                                <a href="#!" class=" fw-bold" data-toggle="modal" data-target="#modal-default">
                                    kaydol</a>
                            </div>

                            <div class="modal fade" id="modal-default">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title" style="color:black;">Kaydol</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <form method="POST">
                                                <div class="form-outline  mb-4"><br>
                                                    <input type="text" name="ad" class="form-control form-control-lg"
                                                        placeholder="Ad Soyad" />
                                                </div>

                                                <div class="form-outline  mb-4"><br>
                                                    <input type="email" name="mail" class="form-control form-control-lg"
                                                        placeholder="Email" />
                                                    <br>
                                                </div>

                                                <div class="form-outline  mb-4">
                                                    <input type="password" name="password"
                                                        class="form-control form-control-lg" placeholder="Şifre" />
                                                    <br>
                                                </div>
                                                <div class="modal-footer justify-content-between">
                                                    <button type="button" class="btn btn-default"
                                                        data-dismiss="modal">Kapat</button>
                                                    <button type="submit" class="btn btn-success"
                                                        name="save">Kaydol</button>
                                                </div>
                                            </form>
                                        </div>

                                    </div>
                                    <!-- /.modal-content -->
                                </div>
                                <!-- /.modal-dialog -->
                            </div>
                            <!-- /.modal -->



                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    </div>
    </div>

    </div>
    </div>
    </div>
    </div>
    </div>
    </section>
</body>
<footer>
    <?php include_once 'js.php'; ?>
</footer>

</html>