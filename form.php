<?php
require_once "pdo.php";
include "util.php";

$check = new check;

$check->not_logged_in();
$check->cancel();

if ( isset($_POST['add']) ) {
  if ( strlen($_POST['date']) < 1 ) {
    $_SESSION['error'] = "Date field must be filled out";
    header("Location: form.php");
    return;
  }
  elseif ( ! strpos($_POST['date'], "/") ) {
    $_SESSION['error'] = "Date must have a /";
    header("Location: form.php");
    return;
  }
  else {
    $check->validateStrength('Location: form.php');
    $check->validateCardio('Location: form.php');

    // insert data
    $stmt = $pdo->prepare('INSERT INTO Workouts (user_id, date) VALUES (:uid, :dat)');
    $stmt->execute(array(
      ':uid' => $_SESSION['user_id'],
      ':dat' => $_POST['date']
    ));
    $workout_id = $pdo->lastInsertId();

    $rank = 1;
    for ($i=1;$i<=9;$i++) {
      if ( ! isset($_POST['strengthMovement' . $i]) ) continue;
      if ( ! isset($_POST['sets' . $i]) ) continue;
      if ( ! isset($_POST['reps' . $i]) ) continue;

      $stmt = $pdo->prepare('INSERT INTO Strength (workout_id, strength_movement, sets, reps, rank)
                                VALUES (:wid, :sm, :se, :re, :rk)');
      $stmt->execute(array(
        ':wid' => $workout_id,
        ':sm' => $_POST['strengthMovement' . $i],
        ':se' => $_POST['sets' . $i],
        ':re' => $_POST['reps' . $i],
        ':rk' => $rank
      ));
      $rank++;
    }
    $rank2 = 1;
    for ($i=1;$i<=9;$i++) {
      if ( ! isset($_POST['cardioMovement' . $i]) ) continue;
      if ( ! isset($_POST['minutes' . $i]) ) continue;
      if ( ! isset($_POST['intensity' . $i]) ) continue;

      $stmt = $pdo->prepare('INSERT INTO Cardio (workout_id, cardio_movement, minutes, intensity, rank)
                              VALUES (:wid, :cm, :mi, :it, :rk)');
      $stmt->execute(array(
        ':wid' => $workout_id,
        ':cm' => $_POST['cardioMovement' . $i],
        ':mi' => $_POST['minutes' . $i],
        ':it' => $_POST['intensity' . $i],
        ':rk' => $rank2
      ));
      $rank2++;
    }

    $_SESSION['success'] = "Workout added";
    header("Location: index.php");
    return;
  }
}

?><!DOCTYPE html>
<html>
  <head><title>Add</title>
    <?php include "head.php"; ?>
  </head>
  <body>
    <h1>Add Workout For <?= $_SESSION['name']; ?></h1>
    <?php $check->flash_messages(); ?>
    <form method="post">
      <p><label for="date">Date:</label>
      <input type="text" name="date"></p>

      <p>Add Strength: <input type="button" name="addStrength" value="+" id="addStrength"></p>
      <p><div id="strength_fields"></div></p>

      <p>Add Cardio: <input type="button" name="addCardio" value="+" id="addCardio"></p>
      <p><div id="cardio_fields"></div></p>

      <script type="text/javascript">
      countStrength = 0;
      countCardio = 0;
      $(document).ready(function() {
        window.console && console.log('Document ready called');
        $('#addStrength').click(function(event) { // strength fields
          event.preventDefault();
          if (countStrength >= 9) {
            alert("Maximum of nine strength entries exceeded");
            return;
          }
          countStrength++;
          window.console && console.log('Strength NUMBER'+countStrength);
          $('#strength_fields').append(
            '<div id="stMovement'+countStrength+'">Movement: <input type="text" name="strengthMovement'+countStrength+'"> \
            <input type="button" value="-" onclick="$(\'#stMovement'+countStrength+'\').remove(); return false;"><br> \
            Sets: <input type="text" name="sets'+countStrength+'"><br> \
            Reps: <input type="text" name="reps'+countStrength+'"> \
            </div>');
          });

        $('#addCardio').click(function(event) { // cardio fields
          event.preventDefault();
          if (countCardio >= 9) {
            alert("Maximum of nine cardio entries exceeded");
            return;
          }
          countCardio++;
          window.console && console.log('Cardio NUMBER'+countCardio);
          $('#cardio_fields').append(
            '<div id="caMovement'+countCardio+'">Movement: <input type="text" name="cardioMovement'+countCardio+'"> \
            <input type="button" value="-" onclick="$(\'#caMovement'+countCardio+'\').remove(); return false;"><br> \
            Minutes: <input type="text" name="minutes'+countCardio+'"><br> \
            Intensity: <input type="text" name="intensity'+countCardio+'"> \
            </div>');
          });
        });
      </script>
      <input type="submit" name="add" value="Add"><input type="submit" name="cancel" value="Cancel">
    </form>
  </body>
</html>
