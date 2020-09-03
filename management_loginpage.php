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
                height: 30px;
                background-color: orange;
                margin-bottom: 20px;
            }
            label, input{
                display: block;
            }
            input{
                margin-bottom: 10px;
                padding: 5px;
            }
            a{
                text-decoration: none;
                float: right;
            }
            body{
                background-color: peachpuff;
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
        <a href="logoutpage.php">ログアウト</a>
        <div class="message">
        <?php
        if (isset($_POST["managerID"]) && isset($_POST["managerPASS"])){
          $managerID=$_POST["managerID"];
          $managerPASS=$_POST["managerPASS"];
          //あらかじめ決めたパスワードと一致していたときにログイン
          if ($managerID=="oneteam" && $managerPASS="oneteam2"){
              //管理者用ページにリダイレクト
              header("Location: managementpage1.php");
          }
       
        }elseif($_POST["managerID"]="" && $_POST["managerPASS"]=""){
          echo '<br>IDとパスワードを入力してください。管理者しかログインできません。';
        
        }else{
          echo '<br>管理者用ページにログインできません。IDかパスワードが間違っています！';
        }  

        ?>
        </div>
</body>
</html>