<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>View</title>
    <link rel="stylesheet" type="text/css" href="../design.css">
  </head>

  <body>

    <h1>View</h1>
    <?php

  $db = new SQLite3("../phpliteadmin/testdb");// testdb は、データベースのファイル名
  if (isset($_GET['delete']) && $_GET['delete']=="yes"){
      $db->query("delete from mytable where id = ".$_GET['id']);
      echo "Deleted!! <br>";
  }
  if (isset($_POST['m'])) {
      $db->query("insert into mytable (mes, dt) values ( '"
                 . $_POST['m'] . "' , '" . date("Y-m-d H:i:s") . "'); ");
  }
//  $sql = "select id, mes, dt from miura   where (id > 3) and (id < 6)  ;";
//  $sql = "select id, mes, dt from miura where dt like '%15:3%' ;";
  $sql = "select id, mes, dt from mytable ;";
  $results = $db->query( $sql );
  echo "<table border=1>";
  while ($row = $results->fetchArray()) {
    //var_dump($row);
    echo "<tr>";
    echo "<td> <a href=\"edit.php?id=".$row['id']."\"> ".$row["id"]."</a> </td>";
    echo "<td>".$row["mes"]."</td>";
    echo "<td>".$row["dt"]."</td>";
    echo "<td><a href=\"view.php?id=".$row["id"]."&delete=yes\">Delete</a></td>";
    echo "</tr>";
  }
  echo "</table>";  
  $db->close();   ?>
 <form action="view.php" method="post">  <br>Insert 
 <input type="text" name="m" size="40" maxlength="220">
 <input type="submit" value="Insert!">
 </form>
    
    <a href="index.php">index.php</a>
  </body>
</html>