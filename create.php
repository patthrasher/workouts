<?php
require_once "pdo.php";
include "util.php";

$check = new check;
$salt = 'Buh*loone_';

if ( isset($_POST['create']) ) {
  if ( strlen($_POST['username']) < 1 || strlen($_POST['email']) < 1 || strlen($_POST['password']) < 1 ) {
    $check->set_error('All fields must be filled out', 'create.php');
  }
  elseif ( ! strpos($_POST['email'], '@') ) {
    $check->set_error("Email must have an '@'", 'create.php');
  }
  else {
    $together = $salt . $_POST['password'];
    $hashed = hash('md5', $together);

    $stmt = $pdo->prepare('INSERT INTO Users (name, email, password) VALUES (:nm, :em, :ps)');
    $stmt->execute(array(
      ':nm' => $_POST['username'],
      ':em' => $_POST['email'],
      ':ps' => $hashed
    ));
    $_SESSION['success'] = 'Profile Created!';
    header("Location: login.php");
    return;
  }
}
?><!DOCTYPE html>
<html>
  <head>
    <title>Create User</title>
    <?php include "head.php" ?>
  </head>
  <body>
    <h1>Create an account!</h1>
    <?php $check->flash_messages(); ?>
    <form method="post">
      <p><label for="username">Username: </label>
        <input type="text" name="username"></p>
      <p><label for="email">Email: </label>
        <input type="text" name="email">
      <p><label for="password">Password: </label>
        <input type="password" name="password"></p>
      <input type="submit" name="create" value="Create"><a href="login.php" style="text-decoration:none"> Cancel</a>
    </form>
  </body>
</html>
