#!/usr/bin/php
<?php
if (PHP_SAPI !== 'cli') exit;


$sweetiekey = 'SWKEY';
function prompt($message = 'prompt: ', $hidden = false) {
  fwrite(STDERR, $message );
  if (strncasecmp(PHP_OS, 'WIN', 3) === 0) {
    if ($hidden) @flock(STDIN, LOCK_EX);
    $password = fgets(STDIN);
    if ($hidden) @flock(STDIN, LOCK_UN);
  } else {
    if ($hidden) system('stty -echo');
    if ($hidden) @flock(STDIN, LOCK_EX);
    $password = fgets(STDIN);
    if ($hidden) @flock(STDIN, LOCK_UN);
    if ($hidden) system('stty echo');
  }
  fwrite(STDERR, "\n");
  return trim($password);
}

function sedreplace($file, $before, $after){
  //  echo "sed -i -e 's|{$before}|{$after}|' $file";
  exec("sed -e 's|{$before}|{$after}|' $file > $file.'.bak'");
  echo "{$file} has been replaced.\n\n";
}
// print_r($argv);
if (!isset($argv[1])){
  echo "USAGE(1): ./managepass.cli.php master\n";
  echo "          (update \$masterpasshash in makefolder.php )\n\n";
  echo "USAGE(2): ./managepass.cli.php swkey\n"; 
  echo "          (update \$sweetiekey in makefolder.php and login.php)\n\n"; 
  echo "USAGE(3): ./managepass.cli.php check\n"; 
  echo "          (display password from Folder name)\n\n"; 
  exit;
  
} else {
  echo "\n";
  if ($argv[1]=="master"){
    $pass = prompt("Input Master Password: ",true);
    $hash = password_hash($pass, PASSWORD_BCRYPT);
    echo $hash."\n";
    if (password_verify($pass, $hash)){
      echo "Check...PASSED!\n";
    }
    sedreplace("makefolder.php","MASTER_PASSWORD_HASH",$hash);

    
  } else if ($argv[1]=="swkey"){
    $swkey = prompt("Input swkey(salt): ",false);
    sedreplace("makefolder.php","SWKEY",$swkey);
    sedreplace("managepass.cli.php","SWKEY",$swkey); //this file
    sedreplace("_swmain/login.php","SWKEY",$swkey);

    
  } else if ($argv[1]=="check"){
    $basefolder = prompt("Input Folder name: ",false);
    $pass = substr(hash("sha256",$basefolder.$sweetiekey),strlen($basefolder),12);
    echo "BaseFolder: {$basefolder}\n";
    echo "     Pass : {$pass}\n\n";
  }
}

