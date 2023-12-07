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
                $stmt->bindValue(':pro_code',$pro_code);                           // プレイスホルダ(:pro_code)に値をセット。
                $stmt->execute();                                                  // PDPのexecuteメソッド ポインタだからアロー演算子(->)を使うらしい。

                $dbh=null;                                                         // DB切断。stmtにデータ取り出したら即切断。

                $rec = $stmt->fetch(PDO::FETCH_ASSOC);                             // SQLの結果を取得。
                $pro_name = $rec['name'];                                          // スタッフの名前を取得。'name'ってなに？→DBから取得してきたテーブルのカラム名。
                $pro_price = $rec['price'];
                $pro_gazou_old = $rec['gazou'];

                $disp_gazou_old = '';                                              // DB内画像有無検査用 初期化

                if($pro_gazou_old != ''){                                          // DB内「gazou」カラムにデータがあった場合
                    $disp_gazou_old = '<img src="./gazou/'.$pro_gazou_old.'">';  // 画面出力用
                }
            }
            catch(Exception $e){
                print 'ただいま障害により大変ご迷惑をおかけしております。'.$e->getMessage();
                exit();
            }
        ?>
        スタッフ修正<br>
        <br>
        商品コード<br>
        <?php
            print $pro_code;
        ?>
        <br>
        <br>
        <form method="POST" action="pro_edit_check.php" enctype="multipart/form-data">
            <input type="hidden" name="code" value="<?php print $pro_code; ?>">                            <!-- 隠しデータ送信 -->
            <input type="hidden" name="gazou_old" value="<?php print $pro_gazou_old; ?>">                  <!-- 隠しデータ送信 -->
            商品名<br>
            <input type="text" name="name" style="width: 200px;" value="<?php print $pro_name; ?>"><br>
            価格<br>
            <input type="text" name="price" style="width: 50px;" value="<?php print $pro_price; ?>"><br>
            <?php print $disp_gazou_old ?><br>                                                             <!-- 古い画像を出力しておく -->
            画像を選んでください<br>
            <input type="file" name="gazou_new" style="width: 400px;"><br>
            <br>
            <input type="button" onclick="history.back()" value="戻る">
            <input type="submit" value="OK">
        </form>
    </body>
</html>