<?php
require_once "pdo.php";
include "util.php";

$check = new check;

$check->not_logged_in();
$check->cancel();
$check->wid_not_set();
$check->wid_not_valid();

$stmt = $pdo->prepare("SELECT * FROM Workouts WHERE workout_id = :wid");
$stmt->execute(array(':wid' => htmlentities($_GET['workout_id'])));
$row = $stmt->fetch(PDO::FETCH_ASSOC);
if ( $row == false ) {
  $check->set_error('Could not load workout', 'index.php');
}

function loadStrength($pdo, $workout_id) {
  global $pdo;
  $stmt = $pdo->prepare('SELECT * FROM Strength WHERE workout_id = :wid ORDER BY rank');
  $stmt->execute(array(':wid' => $workout_id));
  $strength_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
  return $strength_data;
}
function loadCardio($pdo, $workout_id) {
   global $pdo;
   $stmt = $pdo->prepare('SELECT * FROM Cardio WHERE workout_id = :wid  ORDER BY rank');
   $stmt->execute(array(':wid' => $workout_id));
   $cardio_data = $stmt->fetchAll(PDO::FETCH_ASSOC);
   return $cardio_data;
}

if ( isset($_POST['save']) ) {
  if ( strlen($_POST['date']) < 1 ) {
    $_SESSION['error'] = "Date field must be filled outs";
    header("Location: edit.php?workout_id=" . $row['workout_id']);
    return;
  }
  elseif ( ! strpos($_POST['date'], "/") ) {
    $_SESSION['error'] = "Date must have a /";
    header("Location: edit.php?workout_id=" . $row['workout_id']);
    return;
  }
  else {
    $check->validateStrength("Location: edit.php?workout_id=" . $row['workout_id']);
    $check->validateCardio("Location: edit.php?workout_id=" . $row['workout_id']);

    // update workout info
    $stmt = $pdo->prepare('UPDATE Workouts SET date = :dt WHERE workout_id = :wid');
    $stmt->execute(array(':dt' => $_POST['date'],
                        ':wid' => $_POST['hidden_id']));

    // wipe out old strength
    $stmt = $pdo->prepare('DELETE FROM Strength WHERE workout_id = :wid');
    $stmt->execute(array(':wid' => $_REQUEST['workout_id']));

    // insert new strengths
    $rank = 1;
    for ($i=1; $i<=9; $i++) {
      if ( ! isset($_POST['strengthMovement' . $i]) ) continue;
      if ( ! isset($_POST['sets' . $i]) ) continue;
      if ( ! isset($_POST['reps' . $i]) ) continue;
      $stmt = $pdo->prepare('INSERT INTO Strength
                            (workout_id, strength_movement, sets, reps, rank)
                            VALUES (:wid, :mv, :st, :rp, :rk)');
      $stmt->execute(array(
        ':wid' => $_REQUEST['workout_id'],
        ':mv' => $_POST['strengthMovement' . $i],
        ':st' => $_POST['sets' . $i],
        ':rp' => $_POST['reps' . $i],
        ':rk' => $rank
      ));
      $rank++;
    }
    // wipe out old cardio
    $stmt = $pdo->prepare('DELETE FROM Cardio WHERE workout_id = :wid');
    $stmt->execute(array(':wid' => $_REQUEST['workout_id']));

    // insert new cardios
    $rank2 = 1;
    for($i=1; $i<=9; $i++) {
      if ( ! isset($_POST['cardioMovement' . $i]) ) continue;
      if ( ! isset($_POST['minutes' . $i]) ) continue;
      if ( ! isset($_POST['intensity' . $i]) ) continue;
      $stmt = $pdo->prepare('INSERT INTO Cardio
                            (workout_id, cardio_movement, minutes, intensity, rank)
                            VALUES (:wid, :mv, :mn, :it, :rk)');

      $stmt->execute(array(
        ':wid' => $_GET['workout_id'],
        ':mv' => $_POST['cardioMovement' . $i],
        ':mn' => $_POST['minutes' . $i],
        ':it' => $_POST['intensity' . $i],
        ':rk' => $rank2
      ));
      $rank2++;
    }
      $_SESSION['success'] = "Workout updated";
      header("Location: index.php");
      return;
    }
  }
?><!DOCTYPE html>
<html>
  <head><title>Edit</title>
    <?php require_once "head.php"; ?>
  </head>
  <body>
    <h1>Editing Workout for <?= $row['date']; ?></h1>
    <?php $check->flash_messages(); ?>
    <form method="post">
      <input type="hidden" name="hidden_id" value="<?= htmlentities($_GET['workout_id']); ?>">
      <p><label for="date">Date:</label>
        <input type="text" name="date" value="<?= $row['date']; ?>"></p>

        <p>Add Strength: <input type="button" name="addStrength" value="+" id="addStrength"></p>
        <?php
        $strengths = loadStrength($pdo, $_REQUEST['workout_id']);
        $js_strength_num = count($strengths) + 1; // starting point for countStrength in js
        foreach ($strengths as $stren) {
          echo '<div id=strengths' . $stren['rank'] . '>
          Movement: <input type="text" name="strengthMovement' . $stren['rank'] . '" value="' . $stren['strength_movement'] . '">
          <input type="button" value="-" onclick="$(\'#strengths' . $stren['rank'] . '\').remove(); return false;">
          <br>Sets: <input type="text" name="sets' . $stren['rank'] . '" value="' . $stren['sets'] . '">
          <br>Reps: <input type="text" name="reps' . $stren['rank'] . '" value="' . $stren['reps'] . '"></div>';
        }
        ?>
        <p><div id="strength_fields"></div></p>

        <p>Add Cardio: <input type="button" name="addCardio" value="+" id="addCardio"></p>
        <?php
        $cardios = loadCardio($pdo, $_REQUEST['workout_id']);
        $js_cardio_num = count($cardios) + 1; // starting point for countCardio in js
        foreach ($cardios as $cards) {
          echo '<div id=cardios' . $cards['rank'] . '>
          Movement: <input type="text" name="cardioMovement' . $cards['rank'] . '" value="' . $cards['cardio_movement'] . '">
          <input type="button" value="-" onclick="$(\'#cardios' . $cards['rank'] . '\').remove(); return false;">
          <br>Minutes: <input type="text" name="minutes' . $cards['rank'] . '" value="' . $cards['minutes'] . '">
          <br>Intensity: <input type="text" name="intensity' . $cards['rank'] . '" value="' . $cards['intensity'] . '"></div>';
        }
        ?>
        <p><div id="cardio_fields"></div></p>

        <script type="text/javascript">
        countStrength = <?php Print($js_strength_num); ?>;
        countCardio =  <?php Print($js_cardio_num); ?>;
        $(document).ready(function() {
          window.console && console.log('Document ready called');
          $('#addStrength').click(function(event) { // strength fields
            event.preventDefault();
            if (countStrength >= 9) {
              alert("Maximum of nine position entries exceeded");
              return;
            }
            countStrength++;
            window.console && console.log('Strength NUMBER'+countStrength);
            $('#strength_fields').append(
            '<div id="strengthMovement'+countStrength+'">Movement: <input type="text" name="strengthMovement'+countStrength+'"> \
            <input type="button" value="-" onclick="$(\'#stMovement'+countStrength+'\').remove(); return false;"><br> \
            Sets: <input type="text" name="sets'+countStrength+'"><br> \
            Reps: <input type="text" name="reps'+countStrength+'"> \
            </div>');
          });

          $('#addCardio').click(function(event) { // cardio fields
            event.preventDefault();
            if (countCardio >= 9) {
              alert("Maximum of nine position entries exceeded");
              return;
            }
            countCardio++;
            window.console && console.log('Cardio NUMBER'+countCardio);
            $('#cardio_fields').append(
            '<div id="cardioMovement'+countCardio+'">Movement: <input type="text" name="cardioMovement'+countCardio+'"> \
            <input type="button" value="-" onclick="$(\'#caMovement'+countCardio+'\').remove(); return false;"><br> \
            Minutes: <input type="text" name="minutes'+countCardio+'"><br> \
            Intensity: <input type="text" name="intensity'+countCardio+'"> \
            </div>');
          });
        });
        </script>
      <input type="submit" name="save" value="Save"><input type="submit" name="cancel" value="Cancel">
    </form>
  </body>
</html>
