<?php
    session_start();
    session_regenerate_id(true);                       // 合言葉を更新しているらしい？セッションID盗難対策とのこと。ようわからん。
    if(isset($_SESSION['login']) == false){
        print 'ログインされていません。';
        print '<a href="../staff?login/staff_login.html">ログイン画面へ</a>';
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
                // DB接続
                $dsn = 'mysql:dbname=shop;host=localhost;charset=utf8';
                $user = 'root';
                $password = '';
                $dbh = new PDO($dsn,$user,$password);                              // インスタンス化。dbhはポインタらしい。だから戻り値はアドレス
                $dbh->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);      // PDOのsetAttributteメソッド。ポインタなので、アローでアクセス。

                // DBからデータ取り出す(SQL作成)
                $sql = 'SELECT code,name FROM mst_staff WHERE 1';                  // SQLセット
                $stmt = $dbh->prepare($sql);                                       // PDOのprepareメソッド ポインタだからアロー演算子(->)を使うらしい。
                $stmt->execute();                                                  // PDPのexecuteメソッド ポインタだからアロー演算子(->)を使うらしい。

                $dbh=null;                                                         // DB切断。stmtにデータ取り出したら即切断なのね。

                print 'スタッフ一覧<br>';

                print '<form method="POST" action="staff_branch.php">';
                while(true){
                    $rec = $stmt->fetch(PDO::FETCH_ASSOC);                                   // FETCH_ASSOCを指定すると、DBから取り出したデータを連想配列として扱うことができる。戻り値は連想配列。
                    if($rec==false){                                                         // DBからとりだしたデータがなくなったら。
                        break;                                                               // データ取り出しループから抜ける。
                    }
                    /****************取り出した中身を見てみる************** */
                    foreach($rec as $key => $val){                                           // fetch(PDO::FETCH_ASSOC)の戻り値は正常なら連想配列、もう取り出す物がないときはfalseを返してくる。
                        print '<script> console.log('.$key.'=>'.$val.'); </script>';
                    }
                    /**************************************************** */
                    // 取得データをもとにHTMLを出力する。
                    print '<input type="radio" name="staff_code" value="'.$rec['code'].'">'; // ラジオボタン。valueにはスタッフコード。
                    print $rec['name'];
                    print '<br/>';
                    //print 'お試し：'.implode(',',$rec).'<br>';                              // 取り出したデータを出力してみる。SELECTで抜いた結果を$recは持っている。「1,ろくまる」みたいな感じで出る。
                }
                print '<input type="submit" name="disp" value="参照">';                       // 追加画面へ遷移。ラジオボタンのcheckedついてる奴だけ送信されるらしい。
                print '<input type="submit" name="add" value="追加">';                        // 追加画面へ遷移。ラジオボタンのcheckedついてる奴だけ送信されるらしい。
                print '<input type="submit" name="edit" value="修正">';                       // 修正画面へ遷移。ラジオボタンのcheckedついてる奴だけ送信されるらしい。
                print '<input type="submit" name="delete" value="削除">';                     // 削除画面へ遷移。ラジオボタンのcheckedついてる奴だけ送信されるらしい。
                print '</form>';
            }
            catch(Exception $e){
                print 'ただいま障害により大変ご迷惑をおかけしております。エラー内容：'.$e->getMessage();
                exit();
            }
        ?>
    </body>
</html>