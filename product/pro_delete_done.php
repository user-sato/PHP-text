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
            $pro_code = $_POST['code'];
            $pro_gazou = $_POST['gazou'];

            $host = 'mysql:dbname=shop;host=localhost;charset=utf8';
            $user = 'root';
            $pass = '';
            $dbh = new PDO($host,$user,$pass);

            $dbh->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

            $sql = 'DELETE FROM mst_product WHERE code=:code';
            $stmt = $dbh->prepare($sql);

            $stmt->bindValue(':code',$pro_code);
            
            $stmt->execute();
            $dbh = NULL;

            if($pro_gazou != ''){
                unlink('./gazou/'.$pro_gazou);
            }

            print '<p>データを削除しました。</p>' ;
            //print '<input type="button" onclick="history.back()" value="戻る">';          // これだとlistに戻らん。
            print '<a href="./pro_list.php">戻る</a>';
       
        }catch(Exception $e){
            print '<p>エラーですわ！！：'.$e->getMessage();
            exit();
        }

 ?>
    </body>
</html>