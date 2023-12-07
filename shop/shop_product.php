<?php
    session_start();
    session_regenerate_id(true);                       // 合言葉を更新しているらしい？セッションID盗難対策とのこと。ようわからん。
    if(isset($_SESSION['member_login']) == false){
        print 'ようこそゲスト様';
        print '<a href="../staff_login/member_login.html">会員ログイン</a>';
        print '<br>';
        print '<br>';
    }else{
        print 'ようこそ、'.$_SESSION['member_name'].'様<br>';
        print '<a href="./member_logout.php">ログアウト</a>';
        print '<br>';
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
                //$pro_code = $_POST['pro_code'];                                // 前画面から送られてきたやつをここで取得。
                $pro_code = $_GET['procode'];                                // 前画面から送られてきたやつをここで取得。

                // DB接続
                $dsn = 'mysql:dbname=shop;host=localhost;charset=utf8';
                $user = 'root';
                $password = '';
                $dbh = new PDO($dsn,$user,$password);                              // インスタンス化。dbhはポインタらしい。new PDO の戻り値はアドレス。
                $dbh->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);      // PDOのsetAttributeメソッド。属性のセッティングを行っている。ポインタなので、アローでアクセス。

                // DBからデータ取り出す(SQL作成)
                $sql = 'SELECT name,price,gazou FROM mst_product WHERE code=:pro_code';        // SQLセット
                $stmt = $dbh->prepare($sql);                                       // PDOのprepareメソッド ポインタだからアロー演算子(->)を使うらしい。
                $stmt->bindValue(':pro_code',$pro_code);                       // プレイスホルダ(:pro_code)に値をセット。
                $stmt->execute();                                                  // PDPのexecuteメソッド ポインタだからアロー演算子(->)を使うらしい。

                $dbh=null;                                                         // DB切断。stmtにデータ取り出したら即切断。

                $rec = $stmt->fetch(PDO::FETCH_ASSOC);                             // SQLの結果を取得。
                $pro_name = $rec['name'];                                          // スタッフの名前を取得。'name'ってなに？→DBから取得してきたテーブルのカラム名。
                $pro_price = $rec['price'];                                        // 価格を取得。
                $pro_gazou = $rec['gazou'];                                        // 画像名を取得。

                $disp_gazou = '';

                if($pro_gazou != ''){
                    $disp_gazou = '<img src="../product/gazou/'.$pro_gazou.'">';
                }

                print '<a href="shop_cartin.php?procode='.$pro_code.'">カートに入れる</a>';
                print '<br><br>';
            }
            catch(Exception $e){
                print 'ただいま障害により大変ご迷惑をおかけしております。'.$e->getMessage();
                exit();
            }
        ?>
        商品参照<br>
        <br>
        商品コード<br>
        <?php print $pro_code; ?><br>
        商品名<br>
        <?php print $pro_name; ?><br>
        価格<br>
        <?php print $pro_price; ?>円<br>
        <?php print $disp_gazou; ?><br>

        <input type="button" onclick="history.back()" value="戻る">
    </body>
</html>