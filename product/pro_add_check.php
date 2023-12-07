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
        require_once('../common/common.php');          // サニタイズ処理のinclude

        $post = sanitize($_POST);                      // サニタイズ処理コール

        $pro_name = $post['name'];                     // 
        $pro_price = $post['price'];
        $pro_gazou = $_FILES['gazou'];                         // POSTじゃない！

        // ここで、inputへ入力されたものに対して処理をする。(安全対策：命令が入力されてもただの文字列として扱い命令を無効化する)
        // →サニタイズというらしい。クロスサイトスクリプティング攻撃対策
        // 231130 サニタイズ処理を共通化したので以下はコメントアウト
        // $pro_name = htmlspecialchars($pro_name,ENT_QUOTES,'UTF-8');
        // $pro_price = htmlspecialchars($pro_price,ENT_QUOTES,'UTF-8');

        if($pro_name==''){
            print '商品名が入力されていません<br/>';
        }else{
            print '商品名：';
            print $pro_name;
            print '<br/>';
        }

        if(preg_match('/\A[0-9]+\z/',$pro_price) == 0){                                    // 失敗の戻り値が0。
            print '価格をちゃんと入力してください。<br/>';
        }else{
            print '価格：';
            print $pro_price.'円';
            print '<br/>';
        }

        if($pro_gazou['size'] > 0){
            if($pro_gazou['size'] > 1000000){
                print '画像が大きすぎます';
            }
            else{
                move_uploaded_file($pro_gazou['tmp_name'],'./gazou/'.$pro_gazou['name']);
                print '<img src="./gazou/'.$pro_gazou['name'].'">';
                print '<br>';
            }
        }

        if($pro_name=='' || preg_match('/\A[0-9]+\z/',$pro_price) == 0  || $pro_gazou['size'] > 1000000){                   // \Aは文字列の始端。\zは文字列の終端。バリデーション(入力チェック)では\A～\zを使うのが一般的とのこと。
            print '<form>';
            print '<input type="button" onclick="history.back()" value="戻る">';
            print '</form>';
        }else{
            print '<br><p>上記の商品を追加します。</p>';
            print '<form method="post" action="pro_add_done.php">';
            print '<input type="hidden" name="name" value="'.$pro_name.'"></input>';
            print '<input type="hidden" name="pass" value="'.$pro_price.'"></input>';
            print '<input type="hidden" name="gazou" value="'.$pro_gazou['name'].'"></input>';
            print '<br?>';
            print '<input type="button" onclick="history.back()" value="戻る">';
            print '<input type="submit" value="OK">';
            print '</form>';
        }


        ?>
    </body>
</html>