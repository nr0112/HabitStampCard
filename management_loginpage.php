<!-- 管理者ログインページ -->
<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>管理者専用ログインページ</title>
    <body>
        <form action="" method="POST">
            <input type="text" name="managerID" placeholder="管理者用ID">
            <input type="number" name="managerPASS" placeholder="管理者用パスワード">
            <input type="submit" name="manager_submit">
        </form>
    </body>
    <?php

    if (isset($_POST["managerID"]) && isset($_POST["managerPASS"])){
        $managerID=$_POST["managerID"];
        $managerPASS=$_POST["managerPASS"];
        //あらかじめ決めたパスワードと一致していたときにログイン
        if ($managerID=="oneteam_second" && $managerPASS="oneteam"){
            //管理者用ページにリダイレクト
            header("Location: managementpage");
        }
    }else{
        echo '管理者用ページにログインできません。IDかパスワードが間違っています！';
    }
    
    ?>
</html>