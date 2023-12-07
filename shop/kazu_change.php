<?php
    session_start();
    session_regenerate_id(true); // これなんだっけ

    require_once('../common/common.php');     // 共通サブのinclude

    $post = sanitize($_POST);                 // まとめてエスケープ

    $max = $post['max'];                      // 1相対
    $cart = $_SESSION['cart'];

    // 数量の更新
    for($i = 0;$i < $max;$i++ ){

        // 数量のテキストボックス入力値を精査する処理
        if(preg_match("/\A[0-9]+\z/",$post['kazu'.$i]) == 0 ){
            print '数量に誤りがあります。<br>';
            print '<a href="./shop_cartlook.php">カートに戻る</a>';
            exit();
        }
        if($post['kazu'.$i] < 1 || 10 < $post['kazu'.$i]){
            print '数量は必ず1個以上、10個までです。<br>';
            print '<a href="shop_cartlook.php">カートに戻る</a>';
            exit();
        }
        $kazu[] = $post['kazu'.$i];          // shop_cartlookからもらってきたkazuで$_SESSION変数を更新
    }

    // 削除処理
    for($i = $max;$i >= 0;$i--){              // 配列を削除すると前詰めになるので後ろから検索して削除する。
        if(isset($post['sakujo'.$i])){        // trueなら
            array_splice($kazu,$i,1);
            array_splice($cart,$i,1);
        }   
    }

    $_SESSION['cart'] = $cart;                // SESSION内のcartを更新する。
    $_SESSION['kazu'] = $kazu;                // SESSION内のkazuを更新する。 ... 配列の代入はこれでいいのか...。


    header('Location:./shop_cartlook.php');   // 元のページに遷移
    exit();                                   // 処理終了。()ちゃんとつける。
?>