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

        $pro_code = $post['code'];
        $pro_name = $post['name'];
        $pro_price = $post['price'];
        $pro_gazou_old = $post['gazou_old'];
        $pro_gazou_new = $_FILES['gazou_new'];

        // ここで、inputへ入力されたものに対して処理をする。(安全対策：命令が入力されてもただの文字列として扱い命令を無効化する)
        // →サニタイズというらしい。クロスサイトスクリプティング攻撃対策
        // $pro_code = htmlspecialchars($pro_code,ENT_QUOTES,'UTF-8');
        // $pro_name = htmlspecialchars($pro_name,ENT_QUOTES,'UTF-8');
        // $pro_price = htmlspecialchars($pro_price,ENT_QUOTES,'UTF-8');

        if($pro_name==''){
            print '商品名が入力されていません<br/>';
        }else{
            print 'スタッフ名：';
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

        if($pro_gazou_new['size'] > 0 && $pro_gazou_new['size'] < 1000000){
            move_uploaded_file($pro_gazou_new['tmp_name'],'./gazou/'.$pro_gazou_new['name']);     // tempに仮置きされているファイルを./gazouに移動させる。
            print '<img src="./gazou/'.$pro_gazou_new['name'].'">';                               // 画像として出力
            print '<br>';                                                                         // 改行
        }

        if($pro_name=='' || preg_match('/\A[0-9]+\z/',$pro_price) == 0 || $pro_gazou_new['size'] >= 1000000){
            print '<form>';
            print '<input type="button" onclick="history.back()" value="戻る">';
            print '</form>';
        }else{
            print '<br><p>上記の内容で修正します。</p>';
            print '<form method="post" action="pro_edit_done.php">';
            print '<input type="hidden" name="code" value="'.$pro_code.'"></input>';
            print '<input type="hidden" name="name" value="'.$pro_name.'"></input>';
            print '<input type="hidden" name="price" value="'.$pro_price.'"></input>';
            print '<input type="hidden" name="gazou_new" value="'.$pro_gazou_new['name'].'"></input>';
            print '<input type="hidden" name="gazou_old" value="'.$pro_gazou_old.'"></input>';
            print '<br?>';
            print '<input type="button" onclick="history.back()" value="戻る">';
            print '<input type="submit" value="OK">';
            print '</form>';
        }


        ?>
    </body>
</html>