<?php
    session_start();
    session_regenerate_id(true);                       // 合言葉を更新しているらしい？セッションID盗難対策とのこと。ようわからん。
    if(isset($_SESSION['login']) == false){
        print 'ログインされていません。';
        print '<a href="../staff_login/staff_login.html">ログイン画面へ</a>';
        exit();
    }else{
        print 'ようこそ、'.$_SESSION['staff_name'].'さん';
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

                $pro_name = $post['name'];
                $pro_price = $post['pass'];
                $pro_gazou_name = $post['gazou'];                 // 画像名だけ取得。

                // $pro_name = htmlspecialchars($pro_name,ENT_QUOTES,'UTF-8');
                // $pro_price = htmlspecialchars($pro_price,ENT_QUOTES,'UTF-8');
                // $pro_gazou_name = htmlspecialchars($pro_gazou_name,ENT_QUOTES,'UTF-8');     // これ推奨？外からの入力だからやっといたほうがいいんだろうな。

                // print $pro_name.'<br>'.$pro_price.'<br>';

                // DBへの接続
                //$dsn = 'mysql:dbname = shop;host=localhost;charset=utf8';   // =にスペースがついていると、エラー。dbが見つからないエラーが出る。
                $dsn = 'mysql:dbname=shop;host=localhost;charset=utf8';
                $user = 'root';
                $pass = '';
                $dbh = new PDO($dsn,$user,$pass);
                $dbh->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);     // これをやるとcatchにスムーズに入る？とのこと。エラー時にExceptionにスロー(投げる)する？

                // DBへデータ追加。
                $sql = 'INSERT INTO mst_product(name,price,gazou) VALUES(?,?,?)'; // ここで実行するSQLを設定。??はプレースホルダという。変数的なもの。
                $stmt = $dbh->prepare($sql);
                $data[] = $pro_name;                                              // 順番が大事。VALUESにはdataの代入順にセットされる。
                $data[] = $pro_price;                                             // data[0],data[1]というように指定ができるが、ここでは省略しているらしい。
                $data[] = $pro_gazou_name;                                        // data[0],data[1]というように指定ができるが、ここでは省略しているらしい。
                $stmt->execute($data);                                            // これでDBへ命令を出す。ここの引数で

                $dbh = null;                                                      // DB切断処理

                print '商品名：';
                print $pro_name;
                print 'を追加しました。<br/>';

            }catch(Exception $e){
                print 'ただいま障害により大変ご迷惑をおかけしております。エラー内容：'.$e->getMessage();    // エラー内容を出力する。
                exit(); // 強制終了
            }

        ?>
        <a href="pro_list.php">戻る</a>
    </body>
</html>