<?php
require_once "util.php";
$check = new check;
$salt = 'Buh*loone_';

$check->cancel();

if ( isset($_POST['email']) && isset($_POST['password']) ) { // error checked with Javascript first
  if ( strlen($_POST['email']) < 1 || strlen($_POST['password']) < 1 ) {
    $check->set_error('Email and password are required', 'login.php');
  }
  elseif ( ! strpos($_POST['email'], '@') ) {
    $check->set_error("Email must have '@'", 'login.php');
  }
  else { // error check password with database
      $hash_check = hash('md5', $salt . $_POST['password']);
      $sql = "SELECT user_id, name, email, password FROM Users WHERE email = :em AND password = :pw";
      $stmt = $pdo->prepare($sql);
      $stmt->execute(array(':em' => $_POST['email'], ':pw' => $hash_check));
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      if ( $row !== false ) {
        $_SESSION['user_id'] = $row['user_id'];
        $_SESSION['name'] = $row['name'];
        header("Location: index.php");
        return;
      }
      else {
        $_SESSION['error'] = "Incorrect email or password";
        header("Location: login.php");
        return;
    }
  }
}
?>
<script>
// in browser email validation
function doValidate() {
  console.log('Validating...');
  try {
    pw = document.getElementById('id_password').value;
    em = document.getElementById('id_email').value;
    console.log("Validating pw="+pw);
    console.log("Validation em="+em);
    if (pw == null || pw == "") { // checks that password is not blank
      alert("Both fields must be filled out");
      return false;
    }
    if (em.indexOf("@") == -1) { // checks that email has @ sign
      alert ("Invalid email address");
      return false;
    }
    return true;
  } catch(e) {
    return false;
  }
  return false;
}
</script>

<!DOCTYPE html>
<html>
  <head><title>Workout Login</title>
    <?php include "head.php"; ?>
  </head>
  <body>
    <h1>Please Log In</h1>
    <?php $check->flash_messages(); ?>
    <form method="post">
      <p><label for="email">Email</label>
      <input type="text" name="email" id="id_email"></p>
      <p><label for="password">Password</label>
      <input type="password" name="password" id="id_password"></p>
      <input type="submit" onclick="return doValidate();" name="login" value="Log in">
      <input type="submit" name="cancel" value="Cancel">
    </form>
    <a href="create.php" style="text-decoration:none">Not a user yet?</a>
  </body>
</html>
