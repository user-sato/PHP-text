<?php
    session_start();
    session_regenerate_id(true);                       // 合言葉を更新しているらしい？セッションID盗難対策とのこと。ようわからん。
    if(isset($_SESSION['login']) == false){
        print 'ログインされていません。';
        print '<a href="../staff_login/staff_login.html">ログイン画面へ</a>';
        exit();
    }else{
        print 'ようこそ、'.$_SESSION['staff_name'].'さん<br>';
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>ろくまる農園</title>
    </head>
    <body>
        商品追加<br>
        <br>
        <form method="post" action="pro_add_check.php" enctype="multipart/form-data">
            商品名を入力してください。<br>
            <input type="text" name="name" style="width:200px"><br>
            価格入力してください。<br>
            <input type="price" name="price" style="width:50px"><br>
            <br>
            画像を選んでください<br>
            <input type="file" name="gazou" style="width: 400px;"><br>
            <input type="button" onclick="history.back()" value="戻る"><br>
            <input type="submit" value="OK">
        </form>
        <br>
    </body>
</html>