<!-- 管理者ページ1(サイト制作者) -->
<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>管理者ページ</title>
    </head>
    <body>
        <!-- user_idを指定するとその人のカレンダーを閲覧することができる -->
        <form>
            <input type="number" name="userID">
            <input type="submit" name="user_reference" value="閲覧">
        </form>
        <?php
        if(isset($_POST["userID"])){
            $id=$_POST["userID"];
            //このidのテーブルを参照・カレンダーを表示できる
        }
        ?>
    </body>
</html>