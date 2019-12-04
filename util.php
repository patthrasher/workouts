<?php // classes and functions
session_start();
require_once "pdo.php";

class check {

  function flash_messages() {
    if ( isset($_SESSION['error']) ) {
      echo "<p style=color:red>" . htmlentities($_SESSION['error']) . "</p>\n";
      unset($_SESSION['error']);
    }
    if ( isset($_SESSION['success']) ) {
      echo "<p style=color:green>" . htmlentities($_SESSION['success']) . "</p>\n";
      unset($_SESSION['success']);
    }
  }

  function set_error($message, $page) {
    $_SESSION['error'] = $message;
    header("Location: $page");
    exit;
  }

  function wid_not_set() { // makes sure the get parameter is there
    $check = new check;
    if ( ! isset($_GET['workout_id']) || strlen($_GET['workout_id']) < 1 ) {
      $check->set_error('Missing workout_id', 'index.php');
    }
  }

  function wid_not_valid() { // makes sure the get parameter matches the database
    global $pdo;
    $check = new check;
    $stmt = $pdo->query('SELECT workout_id FROM Workouts');
    while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
      if ( $row['workout_id'] == $_GET['workout_id'] ) {
        return true;
      }
    }
    $check->set_error('Invalid workout_id', 'index.php');
  }

  function cancel() {
    if ( isset($_POST['cancel']) ) {
      header("Location: index.php");
      exit;
    }
  }

  function not_logged_in() {
    if ( ! isset($_SESSION['user_id']) ) {
      die("ACCESS DENIED");
    }
  }

  function validateStrength($header) {
    for ($i=1;$i<=9;$i++) {
      if ( ! isset($_POST['strengthMovement' . $i]) ) continue;
      if ( ! isset($_POST['sets' . $i]) ) continue;
      if ( ! isset($_POST['reps' . $i]) ) continue;

      if ( strlen($_POST['strengthMovement' . $i]) < 1 || strlen($_POST['sets' . $i]) < 1
            || strlen($_POST['reps' . $i]) < 1 ) {
        $_SESSION['error'] = "All strength fields must be filled out";
        header($header);
        exit;
      }
      if ( ! is_numeric($_POST['sets' . $i]) || ! is_numeric($_POST['reps' . $i]) ) {
        $_SESSION['error'] = "Sets and reps must be a number";
        header($header);
        exit;
        }
      }
    }

  function validateCardio($header) {
    for ($i=1;$i<=9;$i++) {
      if ( ! isset($_POST['cardioMovement' . $i]) ) continue;
      if ( ! isset($_POST['minutes' . $i]) ) continue;
      if ( ! isset($_POST['intensity' . $i]) ) continue;

      if ( strlen($_POST['cardioMovement' . $i]) < 1 || strlen($_POST['minutes' . $i]) < 1
            || strlen($_POST['intensity' . $i]) < 1 ) {
        $_SESSION['error'] = "All cardio fields must be filled out";
        header($header);
        exit;
      }
      if ( ! is_numeric($_POST['minutes' . $i]) ) {
        $_SESSION['error'] = "Minutes must be a number";
        header($header);
        exit;
      }
    }
  }
}

class movements {

  function view_db_call($table) {
    global $pdo;
    global $table_data;
    $table_data = array();
    $stmt = $pdo->prepare("SELECT * FROM $table WHERE workout_id = :wid");
    $stmt->execute(array(':wid' => htmlentities($_GET['workout_id'])));
    while ( $util_row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
      array_push($table_data, $util_row);
    }
    return $table_data;
  }
  function build_table($movement, $sets, $reps) {
      echo "<tr><td>&nbsp";
      echo $movement;
      echo "</td><td>&nbsp";
      echo $sets;
      echo "</td><td>&nbsp";
      echo $reps;
      echo "</td></tr>";
  }

  function view_labels($label1, $label2, $label3) {
    echo "<table border='1'>";
    echo "<tr><td>";
    echo "<b>$label1 &nbsp</b>";
    echo "</td><td>";
    echo "<b>$label2 &nbsp</b>";
    echo "</td><td>";
    echo "<b>$label3 &nbsp</b>";
    echo "</td></tr>";
  }
}

class other {

  function delete_workout() {
    global $pdo;
    if ( isset($_POST['delete']) ) {

      if ( $_SESSION['user_id'] == 22 ) { // blocks test user from deleting data
         $_SESSION['error'] = "Deleting test data not allowed";
         header("Location: delete.php");
         return;
      }

      $stmt = $pdo->prepare('DELETE FROM Workouts WHERE workout_id = :wid');
      $stmt->execute(array(':wid' => $_POST['hidden_id']));
      $_SESSION['success'] = 'Workout Deleted';
      header("Location: index.php");
      exit;
    }
  }
}
?>
