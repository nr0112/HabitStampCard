<!-- ログアウト画面 -->

<!DOCTYPE html>
<html lang="ja">

    <head>
        <meta charset="UTF-8">
        <title>ログアウト画面</title>
    </head>
    
    <body>
    <div style="background-color: peachpuff">
    <?php
    session_start();

    if (isset($_SESSION["username"])){
        echo 'ログアウトしました';
    }else{
        echo 'セッションがタイムアウトしました。もういちどログインしてください！';
    }

    //---------------ログアウト----------------------------------------------------
    //visitedとdate_count以外のセッション変数(usernameのみ、場合によっては本登録で使った変数も)を削除
    //退会手続きなど、完全に削除する場合はcookieも削除すべき
    unset($_SESSION["username"]);
    //---------------------------------------------------------------------------
    ?>
    <br>
    <a href="loginpage.php"><span style="color: sienna">ログイン</span></a>
    </div>
    </body>
</html>