<!DOCTYPE html>
<html lang="ja">
    <head>
        <title></title>
        <meta charset="utf8">
    </head>
    <body>
        <?php 
            require_once('../common/common.php');
        ?>
        ダウンロードしたい注文日を選んでください<br>
        <form method="post" action="./order_download_done.php">
            <?php 
                pulldown_year();
            ?>
            年
            <?php 
                pulldown_month();
            ?>
            月
            <?php 
                pulldown_day();
            ?>
            日<br>
            <br>
            <input type="submit" value="ダウンロードへ">
        </form>
    </body>
</html>