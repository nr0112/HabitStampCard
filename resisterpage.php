<!-- 新規登録画面(ユーザー登録・設定) -->
<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>本登録ページ</title>
        <link rel="stylesheet" href="resisterpage.css">
    </head>
    <body>

    <form action="" method="POST">
        <!--本登録用入力フォーム--> 
        <fieldset>
            <legend>基本情報</legend>
                <label>登録用メールアドレス
                    <input type="text" name="new_mail" placeholder="メールアドレス"><br>
                </label>
                <label>ユーザー名
                    <input type="text" name="username" placeholder="ユーザー名"><br>
                </label>
                <label>パスワード</label>
                    <!--type="password"だとアスタリスクで表示されるようになる-->
                    <!--パスワードは最小入力文字数を設定した方がいいかも？https://www.tagindex.com/html_tag/form/input_password.html-->
                    <input type="text" name="password" placeholder="パスワード"><br>
                    確認用パスワード<br>
                    <input type="text" name="password2" placeholder="パスワード"><br></p>
        </fieldset>

        <input type="submit" name="registration" value="アカウント作成"></p>

    </form>

    <?php

    //-----------------データベース接続設定----------------------------------
    $dsn = $dsn = 'mysql:dbname=***;host=localhost';
    $user = '***';
    $password = '***';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    //--------------------------------------------------------------------

    //登録情報をデータベースに登録する

    if (isset($_POST["new_mail"])&&isset($_POST["username"])&&isset($_POST["password"])){
        if ($_POST["password"]==$_POST["password2"]){
            //セッションの開始
            session_start();

            $mail= $_POST["new_mail"];
            $username = $_POST["username"];
            $password = password_hash($_POST["password"], PASSWORD_DEFAULT);
            $date_resister = date("Y/m/d");

            //確認用に、値をセッション変数に格納
            $_SESSION["mail_resister"] = $mail;
            $_SESSION["username_resister"] = $username;
            //ハッシュ化されたものが表示されるのを防ぐため、POSTから受け取ったそのままで格納
            $_SESSION["password_resister"] = $_POST["password"];
            $_SESSION["date_resister"] = $date_resister;
            
            try{
                $sql_insert = 'INSERT INTO db_users (mail, username, password, date_resister) VALUES(:mail, :username, :password, :date_resister)';
                $stmt_insert = $pdo -> prepare($sql_insert);
                $stmt_insert -> bindParam(':mail', $mail, PDO::PARAM_STR);
                $stmt_insert -> bindParam(':username', $username, PDO::PARAM_STR);
                $stmt_insert -> bindParam(':password', $password, PDO::PARAM_STR);
                $stmt_insert -> bindParam(':date_resister', $date_resister, PDO::PARAM_STR);
                $stmt_insert -> execute();

                //登録したidを取得(DBでauto_incrementしているもの)
                $id = $pdo -> lastInsertId();

                //表示はセッション変数じゃなくてもいいかも？(マスクの方法：https://deep-blog.jp/engineer/9574/)
                echo '登録が完了しました。<br>
                      登録メールアドレス：'. $_SESSION["mail_resister"].'<br>
                      ユーザー名：'.$_SESSION["username_resister"].'<br>
                      パスワード：'. mb_substr($_SESSION["password_resister"],0,2,"UTF-8").str_repeat("*",mb_strlen($_SESSION["password_resister"],"UTF-8")-2).'<br>
                      登録日：'.$_SESSION["date_resister"].'<br>';
                //登録完了後71行目のユーザーIDをもとにテーブルを作成
                $sql_create_table="CREATE TABLE $id"
                    ."("
                    ."date DATE,"
                    ."loginhistory TIME,"
                    ."wakeupflag int(1),"
                    ."photo VARCHAR(128),"
                    ."comment VARCHAR(128)"
                    .")"
                    ."ENGINE=InnoDB DEFAULT CHARACTER SET=utf8;";
                $stmt = $pdo->query($sql_create_table);
                //photoのところにはパスを入力する
                //上記のセッション変数を削除してからログインページに移る(セッションの値を初期化)
                $_SESSION = array();
                //セッションを破棄
                session_destroy();
                      
    ?>
    <p><a href="ログインページのURL">ログインページ</a></p>
    <?php

            //キャッチした例外を$eで参照・エラーメッセージを取得している
            }catch(Exception $e){
                echo 'データベースの接続に失敗しました';
                echo $e -> getMessage();
                die();
            }


        }else{
            echo 'パスワードが一致しません。もう一度入力してください！';
        }
        
    }elseif (isset($_POST["new_mail"])==FALSE or $_POST["new_mail"]==""){
        echo 'メールアドレスが未入力です。';

    }elseif (isset($_POST["username"])==FALSE or $_POST["username"]==""){
        echo 'ユーザー名が未入力です。';

    }elseif (isset($_POST["password"])==FALSE or $_POST["password"]==""){
        echo 'パスワードが未入力です。';

    }elseif (isset($_POST["password2"])==FALSE or $_POST["password2"]==""){
        echo '確認用パスワードが未入力です。';

    }

    
    ?>

    </body>
</html>