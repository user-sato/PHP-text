<?php
    session_start();
    session_regenerate_id(true);                       // 合言葉を更新しているらしい？セッションID盗難対策とのこと。ようわからん。
    if(isset($_SESSION['member_login']) == false){
        print 'ようこそゲスト様';
        print '<a href="../shop/member_login.html">会員ログイン</a>';
        print '<br>';
    }else{
        print 'ようこそ、'.$_SESSION['member_name'].'様<br>';
        print '<a href="./member_logout.php">ログアウト</a>';
        print '<br>';
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
                $sql = 'SELECT code,name,price FROM mst_product WHERE 1';          // SQLセット
                $stmt = $dbh->prepare($sql);                                       // PDOのprepareメソッド ポインタだからアロー演算子(->)を使うらしい。
                $stmt->execute();                                                  // PDPのexecuteメソッド ポインタだからアロー演算子(->)を使うらしい。

                $dbh=null;                                                         // DB切断。stmtにデータ取り出したら即切断なのね。

                print '商品一覧<br>';

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
                    print '<a href="shop_product.php?procode='.$rec['code'].'">';
                    print $rec['name'].'：';
                    print $rec['price'].'円';
                    print '</a>';
                    print '<br/>';
                    //print 'お試し：'.implode(',',$rec).'<br>';                              // 取り出したデータを出力してみる。SELECTで抜いた結果を$recは持っている。「1,ろくまる」みたいな感じで出る。
                }
                print '<br>';
                print '<a href="shop_cartlook.php">カートを見る';
            }
            catch(Exception $e){
                print 'ただいま障害により大変ご迷惑をおかけしております。エラー内容：'.$e->getMessage();
                exit();
            }
        ?>
    </body>
</html>