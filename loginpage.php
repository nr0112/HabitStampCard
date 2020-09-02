<!-- ログイン画面 -->
<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>ログイン&新規登録</title>
        <link rel="stylesheet" href="loginpage.css">
    </head>
    <body>
    
    <header>
        <h1>習慣スタンプカード</h1>
    </header>

    <main class="form">
        <form action="" method="POST">
            <div class="form_set">
                <div class="set">
                    <h2>ログイン<h2>
                        <h3>ユーザーID<h3>
                        <p><input type="text" name="mail" placeholder="メールアドレス"></p>
                        <h3>パスワード</h3>
                        <p><input type="text" name="password" placeholder="パスワード"></p>
                        <p><input type="submit" name="submit_login" value="ログイン"></p>
                </div>
                <div class="set">
                    <h2>新規登録<h2>
                        <h3>メールアドレス<h3>
                        <p><input type="text" name="new_mail" placeholder="メールアドレス"></p>
                        <p><input type="submit" name="submit_register" value="送信"></p>
                </div>              
            </div>
        </form>
    </main>

    <footer>
    <a href="management_loginpage.php">管理者ページ</a>

    <div class="message">
    <?php
    
    require_once("pdo.php");
    $pdo = pdo_connect();

    //セッション開始
    session_start();
    
    //-------------ログインボタンが押されたとき---------------------------------

    if (isset($_POST['submit_login'])){     
        //ログインフォームに値が入っていた場合
        if (isset($_POST["mail"])&&isset($_POST["password"])){
            $mail = $_POST["mail"];
            $password = $_POST["password"];
            
            try{
                //メールが一致する箇所を抽出
                $sql_select = 'SELECT*FROM db_users WHERE mail=:mail';
                $stmt_select = $pdo -> prepare($sql_select);
                $stmt_select -> bindParam(':mail', $mail, PDO::PARAM_STR);
                $stmt_select -> execute();
                //レコードを呼び出し元に返す
                $result = $stmt_select -> fetch(PDO::FETCH_ASSOC);
                
                //password_veryfy(認証したいパスワード,ハッシュ化されたパスワード)
                if (password_verify($password, $result['password'])){
                    //セッションにusernameを保存=識別子としてログイン状態を保持するセッション変数を作成
                    $_SESSION["username"] = $result['username'];
                    $_SESSION["ID"] = $result['id'];
                    //mypage1へリダイレクト
                    header("Location: mypage1.php");
                    //処理終了
                    exit();

                }else{
                    echo 'ログイン認証に失敗しました！ユーザーID(メールアドレス)かパスワードが間違っています。';
                }

            }catch(Exception $e){
                echo 'データベースの接続に失敗しました！';
                echo $e -> getMessage();
                die();
            }
            
        //ログインフォームに値が入っていない場合
        }else{
            echo 'ログインフォームに値を入力してください！';
        }

    //------------------新規登録ボタンが押されたとき--------------------------------------------
    }elseif (isset($_POST['submit_register'])){
        //新規登録フォームに値が入っていた場合
        //すでに登録されているメールアドレスだった場合、登録できない処理も追加したい
        //メールを送る→新規登録設定ページのURLを送る→そこで書き込んでもらう→まとめてレコードに保存
        if (isset($_POST["new_mail"])){
            $new_mail = $_POST["new_mail"];

            //--------①メール送信機能(send_test.php)----------------
            //誤動作を防ぐためにファイルキャッシュをクリア(https://pentan.info/php/clear_file_cache.html)
            clearstatcache();
            require 'phpmailer/src/Exception.php';
            require 'phpmailer/src/PHPMailer.php';
            require 'phpmailer/src/SMTP.php';
            require 'phpmailer/setting.php';
            
            // PHPMailerのインスタンス生成
            $mail = new PHPMailer\PHPMailer\PHPMailer();
            $mail->isSMTP(); // SMTPを使うようにメーラーを設定する
            $mail->SMTPAuth = true;
            $mail->Host = MAIL_HOST; // メインのSMTPサーバー（メールホスト名）を指定
            $mail->Username = MAIL_USERNAME; // SMTPユーザー名（メールユーザー名）
            $mail->Password = MAIL_PASSWORD; // SMTPパスワード（メールパスワード）
            $mail->SMTPSecure = MAIL_ENCRPT; // TLS暗号化を有効にし、「SSL」も受け入れます
            $mail->Port = SMTP_PORT; // 接続するTCPポート
            
            // メール内容設定
            $mail->CharSet = "UTF-8";
            $mail->Encoding = "base64";
            $mail->setFrom(MAIL_FROM,MAIL_FROM_NAME);
            $mail->addAddress($new_mail, '【習慣スタンプカード】仮登録者様'); //受信者（送信先）を追加する
            //    $mail->addReplyTo('xxxxxxxxxx@xxxxxxxxxx','返信先');
            //    $mail->addCC('xxxxxxxxxx@xxxxxxxxxx'); // CCで追加
            //    $mail->addBcc('xxxxxxxxxx@xxxxxxxxxx'); // BCCで追加

            //変数の変換
            //$mail->bindParam(':new_mail', $new_mail, PHPMailer::PARAM_STR);
            $mail->Subject = MAIL_SUBJECT; // メールタイトル
            $mail->isHTML(true);    // HTMLフォーマットの場合はコチラを設定します
            $body = 'この度は仮登録ありがとうございます。<br>
            まだ会員登録は完了していません。以下のURLより本登録をお願いいたします。<br> 
            https://tb-220025.tech-base.net/resisterpage.php';
            
            $mail->Body  = $body; // メール本文
            // メール送信の実行
            if(!$mail->send()) {
                echo 'メッセージは送られませんでした！';
                echo 'Mailer Error: ' . $mail->ErrorInfo;
            } else {
                echo 'メール送信完了！メールに送付されたURLから登録を行ってください！';
            }
        
        }elseif (isset($_POST["new_mail"])==FALSE){
            echo '新規登録フォームに値を入力してください！';
        }

    }else{
        echo '<br>ログインか新規登録か選んでください';
    }

    ?>
    </div>
    </footer>
    </body>
</html>