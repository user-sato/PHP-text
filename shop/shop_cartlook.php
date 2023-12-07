<?php
    session_start();
    session_regenerate_id(true);                       // 合言葉を更新しているらしい？セッションID盗難対策とのこと。ようわからん。
    if(isset($_SESSION['member_login']) == false){
        print 'ようこそゲスト様';
        print '<a href="../shop/member_login.html">会員ログイン</a>';
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
                //$pro_code = $_POST['pro_code'];                                     // 前画面から送られてきたやつをここで取得。
                // $pro_code = $_GET['procode'];                                      // 前画面から送られてきたやつをここで取得。

                if(isset($_SESSION['cart'])){
                    $cart = $_SESSION['cart'];                                            // カート内のコードを取得する。
                    $kazu = $_SESSION['kazu'];                                            // カート内の商品数量を取得する。
    
                    $maxCartIn = count($cart);                                            // cart配列の要素数を取得。カート内を画面に出力するときに使う。    
                }else{
                    $maxCartIn = 0;
                }

                // カートになにも入っていないときの処理
                if($maxCartIn == 0){
                    print 'カートに商品が入っていません。<br>';
                    print '<br>';
                    print '<a href="./shop_list.php">戻る</a>' ;
                    exit();                                                            // PHPが終了する。この前の<a>はブラウザ側でちゃんと動作する。サーバ処理とは<a>がまた別なので動く。
                }
                // DB接続
                $dsn = 'mysql:dbname=shop;host=localhost;charset=utf8';
                $user = 'root';
                $password = '';
                $dbh = new PDO($dsn,$user,$password);                                  // インスタンス化。dbhはポインタらしい。new PDO の戻り値はアドレス。
                $dbh->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);          // PDOのsetAttributeメソッド。属性のセッティングを行っている。ポインタなので、アローでアクセス。

                // DBからデータ取り出す(SQL作成)
                foreach($cart as $key => $val){
                    $sql = 'SELECT name,price,gazou FROM mst_product WHERE code=:pro_code';        // SQLセット
                    $stmt = $dbh->prepare($sql);                                       // PDOのprepareメソッド ポインタだからアロー演算子(->)を使うらしい。
                    $stmt->bindValue(':pro_code',$val);                                // プレイスホルダ(:pro_code)に値をセット。
                    $stmt->execute();                                                  // PDPのexecuteメソッド ポインタだからアロー演算子(->)を使うらしい。

                    $rec = $stmt->fetch(PDO::FETCH_ASSOC);                             // SQLの結果を取得。
                    $pro_name[] = $rec['name'];                                        // スタッフの名前を取得。'name'ってなに？→DBから取得してきたテーブルのカラム名。
                    $pro_price[] = $rec['price'];                                      // 価格を取得。

                    if($rec['gazou'] != ''){
                        $disp_gazou[] = '<img src="../product/gazou/'.$rec['gazou'].'">';
                    }else{
                        $disp_gazou[] = '';                                             // 画像なかったらその要素をカラで埋める。
                    }
                }
                /* *********** デバッグ用 ********** */
                // print_r($pro_name);
                // print '<br>';
                // print_r($pro_price);
                // print '<br>';
                /* ******************************** */

                $dbh=null;                                                         // DB切断。stmtにデータ取り出したら即切断。
            }
            catch(Exception $e){
                print 'ただいま障害により大変ご迷惑をおかけしております。'.$e->getMessage();
                exit();
            }

            // 別に↓でも問題ないが、のちのちCSSで装飾するときなどに便利なようにHTMLに処理を移す。
            //print 'カート内一覧<br>';
            // for($i = 0;$i < $maxCartIn;$i++){                                      // カート内を画面出力
            //     print ' 商品名：'.$pro_name[$i].'<br>';
            //     print ' 価格：'.$pro_price[$i].'円<br>';
            //     print  $disp_gazou[$i].'<br>';
            // }
        ?>

        <!-- ここに書いたほうが装飾しやすいとのこと。 -->
        <p>カート内一覧</p>
        <form method="post" action="./kazu_change.php">
        <table border="1">
            <tr>
                <td>商品</td>
                <td>商品画像</td>
                <td>価格</td>
                <td>数量</td>
                <td>小計</td>
                <td>削除</td>
            </tr>
            <?php for($i = 0;$i < $maxCartIn;$i++){ ?>
            <tr>
                <td><?php print $pro_name[$i]; ?></td>
                <td><?php print  $disp_gazou[$i]; ?></td>
                <td><?php print $pro_price[$i]; ?>円</td>

                <!-- 送信情報 $_POST['kazuX'] -->
                <td><input type="text" name="kazu<?php print $i ?>" value="<?php print $kazu[$i] ?>"></td>

                <td><?php print $pro_price[$i]*$kazu[$i]; ?>円</td>

                <!-- 送信情報 $_POST['sakujoX'] -->
                <td><input type="checkbox" name="sakujo<?php print $i ?>"></td>
            </tr>
            <?php } ?>
        </table>
        <!-- 送信情報 $_POST['max'] -->
        <input type="hidden" name="max" value="<?php print $maxCartIn; ?>">
        <input type="submit" value="数量変更">
        </form>

        <!-- ****** デバッグ用 ******* -->
        <!-- <?php var_dump($_SESSION['kazu']) ?>
        <br>
        <?php var_dump($_SESSION['cart']) ?> -->
        <!-- ************************ -->

        <input type="button" onclick="history.back()" value="戻る">
        <p><a href="./shop_form.html">ご購入手続きへ進む</a></p>

        <?php 
            // セッション変数にログインの跡があったら表示
            if(isset($_SESSION['member_login'])==true){
                print '<a href="shop_kantan_check.php">会員かんたん注文へ進む</a>';
            }
        ?>
    </body>
</html>