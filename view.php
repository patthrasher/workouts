<?php
require_once "pdo.php";
include "util.php";

$check = new check;
$move = new movements;

$check->not_logged_in();
$check->wid_not_set();
$check->wid_not_valid();

$sql = "SELECT * FROM Workouts WHERE workout_id = :wid";
$stmt = $pdo->prepare($sql);
$stmt->execute(array(':wid' => htmlentities($_GET['workout_id'])));
$row = $stmt->fetchAll(PDO::FETCH_ASSOC);

$date = $row[0]['date'];
$workout_id = $row[0]['workout_id'];

?><!DOCTYPE html>
<html>
  <head><title>View a Workout</title>
    <?php include "head.php"; ?>
  </head>
  <body>
    <h1>Workout for <?= $date; ?>
    </h1>
    <?php
      global $pdo;
      global $table_data;

      $no_data = True;
      $move->view_db_call('Strength');
      if ( count($table_data) > 0 ) {
        $no_data = False;
        echo "<p><b>Strength Training</b></p>";
        $move->view_labels('Movement', 'Sets', 'Reps');
        for ($i=0; $i<count($table_data); $i++) {
          $td = $table_data[$i]; // for brevity
          $move->build_table($td['strength_movement'], $td['sets'], $td['reps']);
        }
      }
       echo "</table><br>";
        $move->view_db_call('Cardio');
        if ( count($table_data) > 0 ) {
          $no_data = False;
          echo "<p><b>Cardio</b></p>";
          $move->view_labels('Movement', 'Minutes', 'Intensity');
          for ($i=0; $i<count($table_data); $i++) {
            $td = $table_data[$i];
            $move->build_table($td['cardio_movement'], $td['minutes'], $td['intensity']);
          }
        }
        if ( $no_data ) {
          echo "<p>There are no movements added for this workout</p>\n";
        }
    echo "</table><br>";
    ?>
    <p><a style='text-decoration:none' href='edit.php?workout_id=<?php echo htmlentities($_GET['workout_id']); ?>'>Edit </a>
    /<a style='text-decoration:none' href='index.php'> Done</a></p>
  </body>
</html>
