<!-- 管理者ログインページ -->
<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>管理者専用ログインページ</title>
        <style>
            legend{
                font-size: 20px;
                width: 200px;
                height: 25px;
                background-color: skyblue;
            }
            label, input{
                display: block;
                padding-top: 10px;
                padding-bottom: 10px;
            }
        </style>
    </head>
    <body>
        <form action="" method="POST">
            <legend>管理者専用ログイン</legend>
                <label><input type="text" name="managerID" placeholder="管理者用ID"></label>
                <label><input type="text" name="managerPASS" placeholder="管理者用パスワード"></label>
                <label><input type="submit" name="manager_submit"></label>
        </form>
    </body>
    <?php

    if (isset($_POST["managerID"]) && isset($_POST["managerPASS"])){
        $managerID=$_POST["managerID"];
        $managerPASS=$_POST["managerPASS"];
        //あらかじめ決めたパスワードと一致していたときにログイン
        if ($managerID=="oneteam_second" && $managerPASS="oneteam"){
            //管理者用ページにリダイレクト
            header("Location: https://tb-220025.tech-base.net/managementpage.php");
        }

    }elseif($_POST["managerID"]="" && $_POST["managerPASS"]=""){
        echo 'IDとパスワードを入力してください。管理者しかログインできません。';

    }else{
        echo '管理者用ページにログインできません。IDかパスワードが間違っています！';
    }
    
    ?>
</html>