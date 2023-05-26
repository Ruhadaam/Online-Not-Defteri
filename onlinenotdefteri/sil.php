<?php include_once 'databaseconn.php';?>


<?php 
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $not_id = $_POST['not_id'];
  
    $silme_sorgusu = $pdo->prepare('DELETE FROM notlar WHERE not_id = ?');
    $silme_sorgusu->execute([$not_id]);
  
    header('Location: index.php');
    exit();
  }

?>