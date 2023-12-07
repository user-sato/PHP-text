<?php
    require_once('../common/common.php');          // サニタイズ処理のinclude

    $post = sanitize($_POST);                      // サニタイズ処理コール

    $staffCode = $post['code'];
    $staffPass = $post['pass'];
    

    // $staffCode = htmlspecialchars($staffCode,ENT_QUOTES,'UTF-8');    // エスケープ！
    // $staffPass = htmlspecialchars($staffPass,ENT_QUOTES,'UTF-8');    // エスケープ！

    $staffPass = md5($staffPass);                                    // md5で暗号化！暗号化後のpassがDBに格納されてるんで、それと比較するのにmd5暗号化が必要なんかな。

    $dsn = 'mysql:dbname=shop;charset=utf8;host=localhost';
    $user = 'root';
    $pass = '';
    try{
        $dbh = new PDO($dsn,$user,$pass);                                 // インスタンス化
        $dbh->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
    
        $sql = 'SELECT name FROM mst_staff WHERE code=:code AND password=:password';
        $stmt = $dbh->prepare($sql);                                      // SQLをセット。戻り値がPDOStatement
        $stmt->bindValue(':code',$staffCode);                             // プレイスホルダ。
        $stmt->bindValue(':password',$staffPass);                         // プレイスホルダ。
    
        $stmt->execute();                                                 // SQL実行
        $dbd = null;                                                      // DB切断
    
        $rec = $stmt->fetch(PDO::FETCH_ASSOC);                            // SQLの実行結果取得
        if($rec == false){                                                // データを取り出せなかった ＝ パスかコードが間違えている。
            // print '<p>スタッフコードか、パスワードが間違えています</p>';
            // print '<a href="./staff_login.html">戻る</a>';
            echo json_encode($rec);                                       // 戻り値
            exit;                                                         // 処理終了
        }else{                                                            // dbからデータが取得できた場合(入力されたIDとパスワードが一致した場合。)
            //header('location:./staff_top.php');
            session_start();
            $_SESSION['login'] = 1;
            $_SESSION['staff_name'] = $rec['name'];                       // ログイン名をクッキーに保存
            $_SESSION['staff_pass'] = $staffPass;                         // パスをクッキーに保存
            echo json_encode(true);                                       // 戻り値
            exit;                                                         // 処理終了
        }
    }catch(Exception $e){
        // print '<p>なんかエラーおきてますよ：'.$e->getMessage().'</p>';
        echo json_encode($e->getMessage());
    }
  

?>