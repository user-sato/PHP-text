<?php                                                           // <?php の前や、? > の後にスペースや改行があると headerがエラーになってしまう。ページ遷移ができなくなる。
    session_start();
    session_regenerate_id(true);                       // 合言葉を更新しているらしい？セッションID盗難対策とのこと。ようわからん。
    if(isset($_SESSION['login']) == false){
        print 'ログインされていません。';
        print '<a href="../staff_login/staff_login.html">ログイン画面へ</a>';
        exit();
    }

    // 修正画面遷移
    if(isset($_POST['edit']) == true){
        if(isset($_POST['staff_code']) == false){                                               // 前画面でラジオボタンの入力がなかったら
            header('Location:staff_ng.php');                                                    // NG画面へ遷移    
        }else{                                                                                  // ラジオボタンの入力があった場合。
            header('Location:staff_edit.php?staffcode='.$_POST['staff_code']);                  // スタッフ修正画面へ遷移
        }
                                                                                                // headerで送れるのはGETだけ。POSTは無理らしい。POSTやるならsessionを使うらしい。
                                                                                                // パラメータを複数送る時は%で区切りましょう。

        // chatGPTによると、exit()は入れたほうがいいらしい。↓
        /* *************************************************************
        header() の後に exit() を使用しない場合、
        ページ遷移後もスクリプトが継続して実行される可能性があります。
        これは、余分な処理が行われ、無駄なリソースが消費される可能性があります。
        また、ユーザーエクスペリエンスの観点からも、適切なページ遷移が行われると
        同時にスクリプトが終了することが望ましいことがあります。
        ****************************************************************/
        exit();
    }
    // 削除画面遷移
    if(isset($_POST['delete']) == true){
        if(isset($_POST['staff_code']) == false){                                               // 前画面でラジオボタンの入力がなかったら
            header('Location:staff_ng.php');                                                    // NG画面へ遷移    
        }else{
            header('Location:staff_delete.php?staffcode='.$_POST['staff_code']);                // スタッフデータ削除画面へ遷移
            exit();
        }
    }
    // 追加画面遷移
    if(isset($_POST['add']) == true){
            header('Location:staff_add.php');                                                   // スタッフデータ追加画面へ遷移
            exit();
    }
    // 参照画面遷移
    if(isset($_POST['disp']) == true){
        if(isset($_POST['staff_code']) == false){                                               // 前画面でラジオボタンの入力がなかったら
            header('Location:staff_ng.php');                                                    // NG画面へ遷移    
        }else{
            header('Location:staff_disp.php?staffcode='.$_POST['staff_code']);                // スタッフデータ削除画面へ遷移
            exit();
        }
    }

?>