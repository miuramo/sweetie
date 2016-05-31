<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>アンケート</title>
    <link rel="stylesheet" type="text/css" href="../design.css">
  </head>

  <body>

    <h1>アンケート</h1>
    <form action="save.php" method="post">

      <table border="1">
        <tr>
          <td align="right"><b> メッセージ：</b></td>
          <td><input type="text" name="message" size="80" maxlength="220"></td>
        </tr>
        <tr>
          <td></td>
          <td>
            <input type="submit" value="送信">
            <input type="reset" value="リセット">
          </td>
        </tr>
      </table>

    </form>

    <br>
    <a href="view.php">これまでに送信されたデータをみる</a>
    
  </body>
</html>