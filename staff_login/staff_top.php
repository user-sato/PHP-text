<?php
    session_start();
    //session_regenerate_id(true);                       // 合言葉を更新しているらしい？セッションID盗難対策とのこと。ようわからん。
    if(isset($_SESSION['login']) == false){
        print 'ログインされていません。';
        print '<a href="../staff_login/staff_login.html">ログイン画面へ</a>';
        exit();
    }else{
        print 'ようこそ、'.$_SESSION['staff_name'].'さん<br>';
        // var_dump($_SESSION);                          // $_SESSIONが持っている値を見たかった。
    }
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>ろくまる農園</title>
    </head>
    <body>
        <form method="post" action="./staff_login_check.php"></form>
            <p>ショップ管理トップメニュー</p>
            <a href="../staff/staff_list.php">スタッフ管理</a>
            <br>
            <a href="../product/pro_list.php">商品管理</a>
            <br>
            <a href="../order/order_download.php">注文ダウンロード</a>
            <br>
            <a href="./staff_logout.php">ログアウト</a>
        </form>
    </body>
</html>