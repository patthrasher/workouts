<?php
include "util.php"; 

$check = new check;
$delete = new other;

$check->not_logged_in();
$check->wid_not_set();
$check->wid_not_valid();
$check->cancel();

$stmt = $pdo->prepare("SELECT workout_id, user_id, workouts.date FROM Workouts
                        WHERE workout_id = :wid");
$stmt->execute(array(':wid' => $_GET['workout_id']));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ( $row == false ) {
  $check->set_error('Could not load workout', 'index.php');
}
$delete->delete_workout();

?><!DOCTYPE html>
<html>
  <head><title>Deleting</title>
    <?php require_once "head.php"; ?>
  </head>
  <body>
    <h1>Deleting Workout</h1>
    <p>Date: <?= htmlentities($row['date']); ?></p>
    <form method="post">
      <input type="submit" name="delete" value="Delete">
      <input type="submit" name="cancel" value="Cancel">
      <input type="hidden" name="hidden_id" value="<?= $row['workout_id']; ?>">
    </form>
  </body>
</html>
