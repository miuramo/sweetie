<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>保存しました</title>
    <link rel="stylesheet" type="text/css" href="../design.css">
  </head>

  <body>

    <h1>保存しました</h1>
    <?php

if (isset($_POST['message'])){
  $db = new SQLite3("../phpliteadmin/testdb");// testdb は、データベースのファイル名
  $sql = "insert into `mytable` (mes, dt) values ( '".$_POST['message']."' , '". date("Y-m-d H:i:s") . "');";
  $ret = $db->exec($sql);

  $db->close();
}

    ?>
    <a href="index.php">index.php</a>

  </body>
</html>