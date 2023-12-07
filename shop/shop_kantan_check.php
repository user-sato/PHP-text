<?php
session_start();
session_regenerate_id(true);
if(isset($_SESSION['member_login'])==false){
    print 'ログインされていません<br>';
    print '<a href="shop_list.php">商品一覧へ</a>';
    exit();
}
?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf8">
        <title></title>
    </head>
    <body>
    <?php

    $code = $_SESSION['member_code'];

    $dsn = 'mysql:dbname=shop;charset=utf8;host=localhost';
    $user = 'root';
    $pass = '';

    try{
        $dbh = new PDO($dsn,$user,$pass);                                 // インスタンス化
        $dbh->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    
        $sql = 'SELECT * FROM dat_member WHERE code=:code';
        $stmt = $dbh->prepare($sql);                                      // SQLをセット。戻り値がPDOStatement
        $stmt->bindValue(':code',$code);                                  // プレイスホルダ。
    
        $stmt->execute();                                                 // SQL実行
        $dbh = null;                                                      // DB切断
    
        $rec = $stmt->fetch(PDO::FETCH_ASSOC);                            // SQLの実行結果取得
        
        $onamae = $rec['name'];
        $email = $rec['email'];
        $postal1 = $rec['postal1'];
        $postal2 = $rec['postal2'];
        $address = $rec['address'];
        $tel = $rec['tel'];

        print 'お名前<br>';
        print $onamae;
        print '<br><br>';

        print 'メールアドレス<br>';
        print $email;
        print '<br><br>';

        print '郵便番号<br>';
        print $postal1.'-'.$postal2;
        print '<br><br>';

        print '住所<br>';
        print $address;
        print '<br><br>';

        print '電話番号<br>';
        print $tel;
        print '<br><br>';

    }catch(Exception $e){
        print 'エラー：'.$e->getMessage();
        exit();
    }

    print '<form method="post" action="./shop_kantan_done.php">';
    print '<input type="hidden" name="onamae" value="'.$onamae.'">';
    print '<input type="hidden" name="email" value="'.$email.'">';
    print '<input type="hidden" name="postal1" value="'.$postal1.'">';
    print '<input type="hidden" name="postal2" value="'.$postal2.'">';
    print '<input type="hidden" name="address" value="'.$address.'">';
    print '<input type="hidden" name="tel" value="'.$tel.'">';
    print '<input type="button" onclick="history.back()" value="戻る">';
    print '<input type="submit" value="OK">';
    print '</form>';
    ?>
    </body>
</html>