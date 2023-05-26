<?php
// Oturumu başlat
session_start();

// Oturumu sonlandır
session_destroy();

// Kullanıcıya bir mesaj göster
echo "Çıkış yapıldı.";

// Kullanıcıyı giriş sayfasına yönlendir
header("Location: login.php");
exit;
?>
