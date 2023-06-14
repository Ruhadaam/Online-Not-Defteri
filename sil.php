<?php
include_once 'databaseconn.php';

$not_id = $_POST['not_id'];

// Silme işlemini gerçekleştirin
$query = "SELECT kullanici_id, fotograf FROM notlar WHERE not_id = :not_id";
$stmt = $pdo->prepare($query);
$stmt->bindParam(':not_id', $not_id);
$stmt->execute();
$result = $stmt->fetch(PDO::FETCH_ASSOC);

if ($result) {
    $kullanici_id = $result['kullanici_id'];
    $fotograf = $result['fotograf'];

    // Dosya yolu
    $dosyaYolu = 'uploads/' . $kullanici_id . '/' . $fotograf;

    // Dosyayı sil (sadece fotoğraf bilgisi mevcutsa)
    if (!empty($fotograf) && file_exists($dosyaYolu)) {
        if (unlink($dosyaYolu)) {
            // Fotoğraf dosyası başarıyla silindi
        } else {
            echo "Fotoğraf dosyası silinirken bir hata oluştu.";
        }
    }

    // Veritabanından notu sil
    $query = "DELETE FROM notlar WHERE not_id = :not_id";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':not_id', $not_id);
    if ($stmt->execute()) {
        echo "Not başarıyla silindi.";
    } else {
        echo "Not silme hatası: " . $stmt->errorInfo();
    }
} else {
    echo "Not bulunamadı.";
}

// Veritabanı bağlantısını kapatın
$pdo = null;
header("Location: index.php");
?>
