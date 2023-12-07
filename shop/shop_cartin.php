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
                //$pro_code = $_POST['pro_code'];                            // 前画面から送られてきたやつをここで取得。
                $pro_code = $_GET['procode'];                                // 前画面から送られてきたやつをここで取得。

                if(isset($_SESSION['cart']) == true){                        // 
                    if(in_array($pro_code,$_SESSION['cart'])){               // すでにカートに追加済？(重複を防ぐ)
                        print 'その商品はすでにカートに入っています。<br>';
                        print '<a href="shop_list.php">商品一覧に戻る</a>';
                        exit();
                    }
                    $cart = $_SESSION['cart'];                               // 前回の注文をcart配列に取り出す。
                    $kazu = $_SESSION['kazu'];                               // 前回の個数をkazu配列に取り出す。
                }
                // var_dump($_SESSION['cart']);                              // デバッグ用
                // print '<br>';                                             // デバッグ用

                $cart[] = $pro_code;                                         // 今回の注文をcart配列に追記する。
                $kazu[] = 1;                                                 // 個数を追記。
                // var_dump($cart);                                          // デバッグ用
                // print '<br>';                                             // デバッグ用
                $_SESSION['cart'] = $cart;                                   // 前回と今回の注文を$_SESSIONに記憶する。
                $_SESSION['kazu'] = $kazu;                                   // 前回と今回の注文を$_SESSIONに記憶する。

                // foreach($cart as $key => $val){
                //     print $val;
                //     print '<br/>';
                // }
            }
            catch(Exception $e){
                print 'ただいま障害により大変ご迷惑をおかけしております。'.$e->getMessage();
                exit();
            }
        ?>
        カートに追加しました。<br>
        <br>
        <a href="./shop_list.php">商品一覧に戻る</a>
    </body>
</html>