<?php
$sweetiekey = 'SWKEY';

$candidates = explode("/",$_SERVER["SCRIPT_NAME"]);
$basefolder = $candidates[count($candidates)-3];

require_once '__sessionfunc.php';
require_unlogined_session();

// 正解パスワードの生成
$basefolder = trim($basefolder);
$pass = substr(hash("sha256",$basefolder.$thesweetiekey),strlen($basefolder),12);
//var_dump($pass);
$hashes[$basefolder] = password_hash($pass ,PASSWORD_BCRYPT);


// ユーザから受け取ったユーザ名とパスワード
$username = filter_input(INPUT_POST, 'username');
$password = filter_input(INPUT_POST, 'password');


// POSTメソッドのときのみ実行
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (
	// validate_token(filter_input(INPUT_POST, 'token')) &&
	// usage: rawpass , password_hash(rawpass, PASSWORD_BCRYPT)
        password_verify(
            $password,
            isset($hashes[$username])
                ? $hashes[$username]
                : '$2y$10$abcdefghijklmnopqrstuv' // ユーザ名が存在しないときだけ極端に速くなるのを防ぐ
        )
    ) {
        // 認証が成功したとき
        // セッションIDの追跡を防ぐ
        session_regenerate_id(true);
        // ユーザ名をセット
        $_SESSION['username'] = $username;
        // ログイン完了後に / に遷移
        header('Location: index.php');
        exit;
    }
    // 認証が失敗したとき
    // 「403 Forbidden」
    http_response_code(403);
}

header('Content-Type: text/html; charset=UTF-8');

?>
<!doctype html>
<html lang="en">
  <head>
  <meta charset="utf-8">
  <title>Sweetie Login</title>
  <link href="jq/jquery-ui.css" rel="stylesheet">
  <link rel="stylesheet" href="cm/doc/docs.css">
  <style>
body{
  font: 120% "Trebuchet MS", sans-serif;
  margin: 10px;
}
  </style>
  </head>
  <body>
<h1>Sweetie Login</h1>
<form method="post" action="">
  Username: <input type="text" name="username" value="">
  Password: <input type="password" name="password" value="">
    <input type="hidden" name="token" value="<?=h(generate_token())?>">
    <input type="submit" value="Login">
</form>
<?php if (http_response_code() === 403): ?>
<p style="color: red;">Username OR Password is wrong.</p>
<?php endif; ?>


