<?php
require_once "pdo.php";
include "util.php";

$check = new check;

?><!DOCTYPE html>
<html>
  <head><title>Pat's Log</title>
    <?php include "head.php"; ?>
  </head>
  <body>
    <?php
    if ( isset($_SESSION['user_id']) ) {
      echo "<h1>Welcome" . " " . $_SESSION['name'] . "</h1>\n";
      $check->flash_messages();
      echo "<p><a href='form.php'>Add New Workout</a></p>";

      $sql = "SELECT * FROM Workouts WHERE user_id = :uid ORDER BY Workouts.date";
      $stmt = $pdo->prepare($sql);
      $stmt->execute(array(':uid' => $_SESSION['user_id']));
      $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

      if ( $rows[0] > 0 ) {
        echo('<table border="1">'."\n");
        echo "<tr><td>";
        echo "<b>Date</b>";
        echo "</td><td>";
        echo "<b>Action</b>";
        echo "</td></tr>";
      }
      foreach ($rows as $row) {
        echo "<tr><td>";
        echo "<a style='text-decoration:none' href='view.php?workout_id=";
        echo $row['workout_id'] . "'>" . $row['date'] . "</a>";
        echo "</td><td>";
        echo "<a style='text-decoration:none' href='edit.php?workout_id=";
        echo $row['workout_id'] . "'>Edit</a>" . " / ";
        echo "<a style='text-decoration:none' href='delete.php?workout_id=";
        echo $row['workout_id'] . "'>Delete</a>";
        echo "</td></tr>";
      }

      echo "</table>";
      echo "<p><a style='text-decoration:none' href='logout.php'>Log Out</a></p>";
    }
    else {
      echo "<h1>Workout Tracker</h1>";
      echo "<a style='text-decoration:none' href='login.php'>Log In</a>";
    }
    ?>
  </body>
</html>
