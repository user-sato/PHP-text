<?php
/* **************************************************  */
/* カート内の商品を、$_SESSIONをクリアすることで削除する。 */
/* cookieの有効期限をリセットして通信を終える             */
/* *************************************************** */
    session_start();                                      // 
    $_SESSION = array();                                // 使ってたセッション変数をカラにする。$_SESSION内をすべてクリアする。
                                                        // カート内の商品を削除！！！！！
    //unset($_SESSION['login'],$_SESSION['staff_name'],$_SESSION['staff_pass']);
                                                          // unset使って$_SESSIONの中身をカラにしてもいい。

    if(isset($_COOKIE[session_name()]) == true){          // session_nameでブラウザに保存されているセッション名(ふつうは「PHPSESSID」)を取得する。
                                                          // $_COOKIE[session_name()]で、セッション名に紐づいたセッションIDが取得できるみたい。
        //setcookie(session_name(),'',time()-42000,'/');  // -420000秒(約-12時間)がログアウト時に設定する値としてスタンダードらしい...。指定しなくてもログアウトできるし、確実なログアウトのためなら-1を指定すればいいのになぜ？って感じだが。
                                                          // 第3パラ：-420000を指定している第3パラはセッションの有効期限を表す。
                                                          // 第4パラ：クッキーの有効範囲。'/'はwebサイト全体を表す。
        setcookie(session_name(),'',time()-1,'/');
    }
    session_destroy();
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <script src="../javascript/_common/scripts/jquery-3.4.1.min.js"></script>
        <title>ろくまる農園</title>
    </head>
    <body>
        <p>カート内を空にしました。</p>
        <a href="../staff_login/staff_login.html">ログイン画面へ</a>
    </body>
</html>