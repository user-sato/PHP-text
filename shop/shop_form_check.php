<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="utf8">
        <title></title>
    </head>
    <body>
    <?php
        require_once('../common/common.php');

        // shop_form経由でないアクセスの場合
        if(isset($_POST['onamae'])==0){
            print 'フォームへの入力が正しくありません。 <br>';
            print '<a href="./shop_form.html">戻る</a>';
            exit();
        }
        $post = sanitize($_POST);                    // まとめてエスケープ
        $submitFlag = true;                          // 送信のフラグ

        // var_dump($_POST);                            // onamae～tel までの値を取得

        $onamae = $post['onamae'];
        $email = $post['email'];
        $postal1 = $post['postal1'];
        $postal2 = $post['postal2'];
        $address = $post['address'];
        $tel = $post['tel'];

        // P295追加
        $chumon = $post['chumon'];
        $pass = $post['pass'];
        $pass2 = $post['pass2'];
        $gen = $post['gen'];
        $birth = $post['birth'];

        if($onamae == ''){
            print 'お名前が入力されていません<br><br>';
            $submitFlag = false;
        }else{
            print 'お名前<br>';
            print $onamae;
            print '<br><br>';
        }
        if(preg_match('/\A[\w\-\.]+\@[\w\-\.]+\.([a-z]+)\z/',$email) == 0){                               // 正規表現に当てはまらなかった場合、0が帰ってくる。当てはまると1が帰る。
            print 'メールアドレスが正しくありません。<br><br>';
            $submitFlag = false;
        }else{
            print 'メールアドレス<br>';
            print $email;
            print '<br><br>';
        }
        if(preg_match('/\A[0-9]+\z/',$postal1) == 0){
            print '郵便番号は半角英数字で入力してください。<br><br>';
            $submitFlag = false;
        }else{
            print '郵便番号<br>';
            print $postal1.'-'.$postal2;
            print '<br><br>';

        }
        if(preg_match('/\A[0-9]+\z/',$postal2) == 0){
            print '郵便番号は半角英数字で入力してください。<br><br>';
            $submitFlag = false;
        }
        if($address == ''){
            print '住所が入力されていません<br><br>';
            $submitFlag = false;
        }else{
            print '住所<br>';
            print $address;
            print '<br><br>';
        }
        if(preg_match('/\A\d{2,5}-?\d{2,5}-?\d{4,5}\z/',$tel) == 0){
            print '電話番号を正しく入力してください<br><br>';
            $submitFlag = false;
        }else{
            print '電話番号<br>';
            print $tel;
            print '<br><br>';
        }

        if($chumon == 'chumontouroku'){
            if($pass == ''){
                print 'パスワードが入力されていません。<br><br>';
                $submitFlag = false;
            }
            if($pass != $pass2){
                print 'パスワードが一致しません<br><br>';
                $submitFlag = false;
            }

            print '性別<br>';
            if($gen == 'male'){
                print '男性';
            }else{
                print '女性';
            }
            print '<br><br>';

            print '生まれ年<br>';
            print $birth;
            print '年代';
            print '<br><br>';
        }

        if($submitFlag == true){
            print '<form method="post" action="./shop_form_done.php">';
            print '<input type="hidden" name="onamae" value="'.$onamae.'">';
            print '<input type="hidden" name="email" value="'.$email.'">';
            print '<input type="hidden" name="postal1" value="'.$postal1.'">';
            print '<input type="hidden" name="postal2" value="'.$postal2.'">';
            print '<input type="hidden" name="address" value="'.$address.'">';
            print '<input type="hidden" name="tel" value="'.$tel.'">';
            print '<input type="hidden" name="chumon" value="'.$chumon.'">';
            print '<input type="hidden" name="pass" value="'.$pass.'">';
            print '<input type="hidden" name="gen" value="'.$gen.'">';
            print '<input type="hidden" name="birth" value="'.$birth.'">';
            print '<input type="button" onclick="history.back()" value="戻る">';
            print '<input type="submit" value="OK">';
            print '</form>';
        }else{
            print '<input type="button" onclick="history.back()" value="戻る">';
        }
        
    ?>
    </body>
</html>