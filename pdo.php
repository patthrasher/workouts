<?php
// makes database connection
$pdo = new PDO("mysql:host=us-cdbr-iron-east-02.cleardb.net;port=3306;dbname=heroku_751359890fcb9fc", "b4b3488a98344b", "e1445148");
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

?>
