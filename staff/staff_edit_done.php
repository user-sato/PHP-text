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
        <?php                                                           // WEBさーばで実行される。Apatchで処理する。

            try{
                require_once('../common/common.php');          // サニタイズ処理のinclude

                $post = sanitize($_POST);                      // サニタイズ処理コール
        
                $staff_code = $post['code'];
                $staff_name = $post['name'];
                $staff_pass = $post['pass'];

                // 前のページでサニタイズしてるからやんなくてよくない？
                // $staff_name = htmlspecialchars($staff_name,ENT_QUOTES,'UTF-8');
                // $staff_pass = htmlspecialchars($staff_pass,ENT_QUOTES,'UTF-8');

                $dsn = 'mysql:dbname=shop;host=localhost;charset=utf8';
                $user = 'root';
                $pass = '';
                $dbh = new PDO($dsn,$user,$pass);                             // インスタンス化(PDOクラスをもとに作ったオブジェクト)。 「インスタンスはオブジェクトであるが、オブジェクトはインスタンスではない。」みたいな違いがある。
                $dbh->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION); // これをやるとcatchにスムーズに入る？とのこと。エラー時にExceptionにスロー(投げる)する？

                // DBデータ更新。
                $sql = 'UPDATE mst_staff SET name=:name, password=:pass WHERE code=:code'; // ユーザ情報を更新する。 *最初は削除して追加すればいいの...?と思ってしまった。UPDATEが最適です。
                $stmt = $dbh->prepare($sql);                                               // executeにより実行されるSQLをここでセットする。(prepare) 戻り値としてPDOStatement オブジェクトを返す。
                                                                                           // stmtにはPDOStatementオブジェクトのアドレスが格納されるので、stmtを用いてPDOStatementオブジェクトのメソッドを使うことができる。
                $stmt->bindValue(':code',$staff_code);                                     // プレイスホルダでcodeをセット
                $stmt->bindValue(':name',$staff_name);                                     // プレイスホルダでnameをセット
                $stmt->bindValue(':pass',$staff_pass);                                     // プレイスホルダでpassをセット

                

                $stmt->execute();                                                          // PDOStatementオブジェクト:executeをコール(SQL実行)
                $dbh = null;                                                               // DB切断処理

                print 'データを更新しました。<br/>';
            }catch(Exception $e){
                print 'ただいま障害により大変ご迷惑をおかけしております。エラー内容：'.$e->getMessage();    // エラー内容を出力する。
                exit(); // 強制終了
            }

        ?>
        <a href="staff_list.php">戻る</a>
    </body>
</html>