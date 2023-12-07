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
<html>
    <head>
        <title></title>
    </head>
    <body>
        <?php 
        try{
            require_once('../common/common.php'); // インクルード

            $post = sanitize($_POST);             // 一気にエスケープ

            $onamae = $post['onamae'];
            $email = $post['email'];
            $postal1 = $post['postal1'];
            $postal2 = $post['postal2'];
            $address = $post['address'];
            $tel = $post['tel'];

            print $onamae.'様<br>';
            print 'ご注文ありがとうございました。<br>';
            print $email.'にメールをお送りしましたのでご確認ください。<br>';
            print '商品は以下の住所に発送させていただきます。<br>';
            print '〒'.$postal1.'-'.$postal2.'<br>';
            print $address.'<br>';
            print 'TEL:'.$tel.'<br>';

            $honbun = '';
            $honbun .= $onamae."様\n\n";
            $honbun .= "このたびはご注文ありがとうござました。\n\n";
            $honbun .= "ご注文商品\n";
            $honbun .= "-------------\n";
            
            $cart = $_SESSION['cart'];            // SESSION変数からカートに入っている商品の商品コードを取り出す。
            $kazu = $_SESSION['kazu'];            // SESSION変数からカート内に入れた各商品の数を取り出す。

            $max = count($cart);                  // 商品コード総数

            /* **** デバッグ用 ***** */
            // var_dump($cart);
            // print '<br>';
            // var_dump($kazu);
            // print '<br>';
            // var_dump($max);
            // print '<br>';


            $dbn = 'mysql:dbname=shop;host=localhost;charset=utf8';
            $user = 'root';
            $pass = '';

            $dbh = new PDO($dbn,$user,$pass);                              // インスタンス化
            $dbh->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);  // オプションの設定的なこと。

            for($i=0;$i < $max;$i++){
                $sql = 'SELECT name,price FROM mst_product WHERE code=:code';
                $stmt = $dbh->prepare($sql);
                $stmt->bindValue(':code',$cart[$i]);

                $stmt->execute();

                $rec = $stmt->fetch(PDO::FETCH_ASSOC);          // DB実行結果を一行取得

                $name = $rec['name'];
                $price = $rec['price'];                         // 商品の値段
                $kakaku[] = $price;                             // INSERTで使う。
                $suryo = $kazu[$i];                             // 商品の種類数
                $shokei = $price * $suryo;                      // 商品ごとの小計

                $honbun .= $name.' ';
                $honbun .= $price.'円 x ';
                $honbun .= $suryo.'個 = ';
                $honbun .= $shokei."円\n";
            }
            /*********** テーブルロック(dat_sales,dat_sales_product) ************* */
            $sql = 'LOCK TABLES dat_sales WRITE,dat_sales_product WRITE, dat_member WRITE';
            $stmt=$dbh->prepare($sql);
            $stmt->execute();
            /******************************************************************** */



            /* ******** 購入した商品かな、登録処理 ******************************** */
            $sql = 'INSERT INTO dat_sales(code_member,name,email,postal1,postal2,address,tel) VALUES(?,?,?,?,?,?,?)';
            $stmt = $dbh->prepare($sql);
            $data = array();
            $data[]=$_SESSION['member_code'];                          // メンバーコード入れるべきじゃないか？あん？
            $data[]=$onamae;
            $data[]=$email;
            $data[]=$postal1;
            $data[]=$postal2;
            $data[]=$address;
            $data[]=$tel;

            $stmt->execute($data);

            $sql = 'SELECT LAST_INSERT_ID()';
            $stmt = $dbh->prepare($sql);
            $stmt->execute($data);
            $rec = $stmt->fetch(PDO::FETCH_ASSOC);
            $lastcode = $rec['LAST_INSERT_ID()'];

            for($i=0;$i<$max;$i++){
                $sql = 'INSERT INTO dat_sales_product(code_sales,code_product,price,quantity) VALUES(?,?,?,?)';
                $stmt = $dbh->prepare($sql);
                $data = array();
                $data[] = $lastcode;
                $data[] = $cart[$i];
                $data[] = $kakaku[$i];
                $data[] = $kazu[$i];
                $stmt->execute($data);
            }

            /* *********** テーブルロック解除 ************ */
            $sql = 'UNLOCK TABLES';
            $stmt = $dbh->prepare($sql);
            $stmt->execute();
            /******************************************* */

            $dbh = null;                                        // DB切断

            /************* メール本文 ******************* */
            $honbun.="送料は無料です。\n";
            $honbun.="--------------\n";
            $honbun.="\n";
            $honbun.="代金は以下の口座にお振込みください。\n";
            $honbun.="ろくまる銀行 やさい支店 普通口座 1234567\n";
            $honbun.="入金確認が取れ次第、梱包、発送させていただきます。\n";
            $honbun.="\n";
            $honbun.= '会員登録が完了しました。<br>';
            $honbun.= "次回からメールアドレスとパスワードでログインしてください。\n";
            $honbun.= "ご注文が簡単にできるようになります。\n";
            $honbun.= "\n";
            $honbun.="□□□□□□□□□□□□□□□□□□□□□□□□□□\n";
            $honbun.="  ～安心野菜のろくまる農園～\n";
            $honbun.="\n";
            $honbun.="茨城県常総市1-2-3\n";
            $honbun.="電話　：090-1111-2222\n";
            $honbun.="メール：info@abc.com\n";
            $honbun.="□□□□□□□□□□□□□□□□□□□□□□□□□□\n";

            /* ****** メール送るらしい(客向け) **************/
            $title = 'ご注文ありがとうございます。';
            $header = 'From:info@rokumarunouen.co.jp';
            $honbun = html_entity_decode($honbun,ENT_QUOTES,'UTF-8');
            mb_language('Japanese');
            mb_internal_encoding('UTF-8');
            mb_send_mail($email,$title,$honbun,$header);


            /* ****** メール送るらしい(店向け) **************/
            $title = '注文がありました。';
            $header = 'From:'.$email;
            $honbun = html_entity_decode($honbun,ENT_QUOTES,'UTF-8');
            mb_language('Japanese');
            mb_internal_encoding('UTF-8');
            mb_send_mail('info@rokumarunouen.co.jp',$title,$honbun,$header);


            // print '<br>';
            // print nl2br($honbun);
        }catch(Exception $e){                     // DB障害対策とのことだが、DB使ってなくない？→DBが動いてなかったらこっちに入るらしい。なるほど。
            print 'エラーですわ:'.$e->getMessage();
            exit();
        }
        ?>
        
        <br>
        <a href="shop_list.php">商品画面へ</a>
    </body>
</html>