<?php
// execute "managepass.phpcli master" to initialize
$masterpasshash = 'MASTER_PASSWORD_HASH';

// execute "managepass.phpcli swkey" to initialize
$sweetiekey = 'SWKEY';
// thesweetiekey must be set at _swmain/login.php
?>

<!doctype html>
<html lang="en">
   <head>
   <meta charset="utf-8">
   <title>Sweetie Manager</title>
   <link href="_swmain/jq/jquery-ui.css" rel="stylesheet">
   <link rel="stylesheet" href="_swmain/cm/doc/docs.css">
   <style>
   body{
 font: 150% "Trebuchet MS", sans-serif;
 margin: 30px;
 }
   </style>
   </head>
   <body>
   <h1>Sweetie Manager</h1>

 <?php

   if ($_SERVER['REQUEST_METHOD'] === 'POST') {
     $src = filter_input(INPUT_POST, 'src');
     $dst = filter_input(INPUT_POST, 'dst');
     $src = trim($src); $dst = trim($dst);
     $password = filter_input(INPUT_POST, 'password');
     $beforedelete = 0;

     if (password_verify($password, $masterpasshash)){
       if (strlen($src)>2){
	 $willexec = 1;
	 if ($src == "delete" || $src == "remove"){
	   $willexec = 0;
	   if (is_dir($dst)){
	     exec("rm -fr {$dst}");
	   }
	   echo "<h1 style=\"color: blue;\">Folder [{$dst}] has been successfully deleted.</h1>";
	   echo "<a href=\"makefolder.php\">Reload</a>";
	   exit();
	 }
	 if ($src == "list"){
	   $willexec = 0;
	   $dirlist = scandir(".");
	   echo "<table border=1>";
	   foreach($dirlist as $n=>$d){
	     if (is_dir($d)){
	       if ($d == "." || $d == ".." || $d == "_swmain" ) continue;
	       echo "<tr><td>";
	       echo $d;
	       echo "</td><td>";
	       echo "<a href=\"$d\" target=\"_blank\">$d</a>";
	       echo "</td><td>";
	       echo str_replace($d,"",exec("du -sh {$d}"));
	       echo "</td></tr>";
	     }
	   }
	   echo "</table>";
	   echo "<a href=\"makefolder.php\">Reload</a>";
	   exit();
	 }
	 if (strlen($dst)>2){
	   if ($beforedelete==1){
	     if (is_dir($dst)){
	       exec("rm -fr {$dst}");
	     }
	   } else {
	     if (is_dir($dst)){
	       echo "<h1 style=\"color: orange;\">Error: Dst folder already exists.</h1>";
	       $willexec = 0;
	     }
	   }
	 } else {
	   $willexec = 0;
	 }
	 if (!is_dir($src)){
	   echo "<h1 style=\"color: orange;\">Error: Src folder does not exist.</h1>";
	   $willexec = 0;
	 }

	 if ($willexec){
	   exec("cp -r {$src} {$dst}");
	 
	   chdir($dst);
	   mkdir("img");
	   exec("ln -s ../_swmain ./_edit");
	   
	   echo "<h1>[{$dst}] successfully created from [{$src}]</h1>";
	   echo "<h1><a target=\"_blank\" href=\"{$dst}/_edit/index.php\">{$dst}</a></h1>";
	   // 正解パスワードの生成
	   $pass = substr(hash("sha256",$dst.$sweetiekey),strlen($dst),12);
	   echo "<h2>Pass: {$pass}</h2>";
	 }
       } else {
	 echo "<h1 style=\"color: orange;\">Error: No src and dst info.</h1>";
       }
     } else {
       //       echo password_hash($password, PASSWORD_BCRYPT);
       echo "<h1 style=\"color: red;\">Password is wrong</h1>";
     }
   }
   ?>

   <form method="post" action="">
   Master Password: <input type="password" name="password" value="<?=$password?>"><br>
   src folder: <input type="text" name="src" value="sample_dbbase"><br>
   dst folder: <input type="text" name="dst" value="<?=$dst?>"><br>
   <input type="submit" value="Create Folder">
   </form>
   <?php if (http_response_code() === 403): ?>
   <p style="color: red;">Password is wrong.</p>
   <?php endif; ?>


