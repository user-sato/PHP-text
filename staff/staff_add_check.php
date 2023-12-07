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

        $staff_name = $post['name'];
        $staff_pass = $post['pass'];
        $staff_pass2 = $post['pass2'];

        // ここで、inputへ入力されたものに対して処理をする。(安全対策：命令が入力されてもただの文字列として扱い命令を無効化する)
        // →サニタイズというらしい。クロスサイトスクリプティング攻撃対策
        // $staff_name = htmlspecialchars($staff_name,ENT_QUOTES,'UTF-8');
        // $staff_pass = htmlspecialchars($staff_pass,ENT_QUOTES,'UTF-8');
        // $staff_pass2 = htmlspecialchars($staff_pass2,ENT_QUOTES,'UTF-8');

        if($staff_name==''){
            print 'スタッフ名が入力されていません<br/>';
        }else{
            print 'スタッフ名：';
            print $staff_name;
            print '<br/>';
        }

        if($staff_pass==''){
            print 'パスワードが入力されていません。<br/>';
        }

        if($staff_pass!=$staff_pass2){
            print 'パスワードが一致しません。<br/>';
        }

        if($staff_name=='' || $staff_pass=='' || $staff_pass!=$staff_pass2){
            print '<form>';
            print '<input type="button" onclick="history.back()" value="戻る">';
            print '</form>';
        }else{
            $staff_pass = md5($staff_pass);
            print '<form method="post" action="staff_add_done.php">';
            print '<input type="hidden" name="name" value="'.$staff_name.'"></input>';
            print '<input type="hidden" name="pass" value="'.$staff_pass.'"></input>';
            print '<br?>';
            print '<input type="button" onclick="history.back()" value="戻る">';
            print '<input type="submit" value="OK">';
            print '</form>';
        }


        ?>
    </body>
</html>