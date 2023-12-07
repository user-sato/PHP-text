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
        スタッフ追加<br>
        <br>
        <form method="post" action="staff_add_check.php">
            スタッフ名を入力してください。<br>
            <input type="text" name="name" style="width:200px"><br>
            パスワードを入力してください。<br>
            <input type="password" name="pass" style="width:100px"><br>
            パスワードをもう一度入力してください。<br>
            <input type="password" name="pass2" style="width:100px"><br>
            <br>
            <input type="button" onclick="history.back()" value="戻る"><br>
            <input type="submit" value="OK">
        </form>
        <br>
        <?php
            $moji = '文字列';
            print '二重引用符だと'.$moji.'となる<br>';
            echo 'hello';
            echo 'hello<br>';

            // 配列
            $ary = array('a','b','c','d','e');
            for($i = 0;$i < 5;$i++){
                print $ary[$i].'<br>';
            }

            // 連想配列
            $ary_ren = array(
                '茨城' => '水戸',   // 'キー' => '値'
                '栃木' => '宇都宮',
                '群馬' => '高崎'
            );
            print $ary_ren['茨城'].'<br>';   // キーを指定すると、中身が取り出せる。これなら水戸。

            foreach($ary_ren as $key){       // これだと、中身が取り出せる。「水戸」「宇都宮」「高崎」
                print $key.'<br>';
            }

            foreach($ary_ren as $key => $value){       // これだと、キーと中身が取り出せる。
                print 'キー：'.$key.' 値：'.$value.'<br>';
            }

            while($p = current($ary_ren)){
                print key($ary_ren).'の県庁所在地は'.$p.'です。<br>';
                next($ary_ren);
            }

        ?>
    </body>
</html>