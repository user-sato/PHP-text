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
        <?php
            try{
                //$staff_code = $_POST['staff_code'];                                // 前画面から送られてきたやつをここで取得。
                $staff_code = $_GET['staffcode'];                                // 前画面から送られてきたやつをここで取得。

                // DB接続
                $dsn = 'mysql:dbname=shop;host=localhost;charset=utf8';
                $user = 'root';
                $password = '';
                $dbh = new PDO($dsn,$user,$password);                              // インスタンス化。dbhはポインタらしい。new PDO の戻り値はアドレス。
                $dbh->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);      // PDOのsetAttributeメソッド。属性のセッティングを行っている。ポインタなので、アローでアクセス。

                // DBからデータ取り出す(SQL作成)
                $sql = 'SELECT name FROM mst_staff WHERE code=:staff_code';        // SQLセット
                $stmt = $dbh->prepare($sql);                                       // PDOのprepareメソッド ポインタだからアロー演算子(->)を使うらしい。
                $stmt->bindValue(':staff_code',$staff_code);                       // プレイスホルダ(:staff_code)に値をセット。
                $stmt->execute();                                                  // PDPのexecuteメソッド ポインタだからアロー演算子(->)を使うらしい。

                $dbh=null;                                                         // DB切断。stmtにデータ取り出したら即切断。

                $rec = $stmt->fetch(PDO::FETCH_ASSOC);                             // SQLの結果を取得。
                $staff_name = $rec['name'];                                        // スタッフの名前を取得。'name'ってなに？→DBから取得してきたテーブルのカラム名。
            }
            catch(Exception $e){
                print 'ただいま障害により大変ご迷惑をおかけしております。'.$e->getMessage();
                exit();
            }
        ?>
        スタッフ参照<br>
        <br>
        スタッフコード<br>
        <?php print $staff_code; ?><br>
        スタッフ名<br>
        <?php print $staff_name; ?><br>
        <input type="button" onclick="history.back()" value="戻る">
    </body>
</html>