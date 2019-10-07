<?php // not currently used
require_once "pdo.php";
session_start();
header('Content-Type: application/json; charset=utf-8');
$stmt = $pdo->prepare("SELECT * FROM Workouts WHERE user_id = :uid");
$stmt->execute(array(':uid' => $_SESSION['user_id']));
$rows = array();
while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
  $rows[] = $row;
}

echo json_encode($rows, JSON_PRETTY_PRINT);
?>
