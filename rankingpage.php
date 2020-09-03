<!-- ランキング掲示板ページ -->


<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>ランキング掲示板</title>
        <style>
            a{
                text-decoration: none;
                float: right;
            }
            body{
                background-color: peachpuff;
            }
            .rank-set{
                display: flex;
            }
            .time{
                width: 800px;
                margin-left: 50px;
            }
            .rank1{
                color: red;
                font-weight: bold;
            }
            .rank2{
                font-weight: bold;
            }
            .box{
                background-color: ivory;
                padding: 10px 5px;
            }
            h1{
                color: red;
            }
        </style>
    </head>
    <body>
    <h1>きょうはやくおきたひとランキング</h1>
    <hr>
        <?php
            require_once("pdo.php");
            $pdo = pdo_connect();
            $date = date('Y-m-j');
            $name_time = Array();
            $select = "SELECT * FROM db_users";
            $stmt = $pdo -> query($select);
            $results = $stmt->fetchAll();
            foreach($results as $row){
                $id = $row["id"];
                $name_ID = "";
                $name_ID .= "user_ID_".$id;
                $select_wakeup = "SELECT logintime FROM $name_ID WHERE date=:date";
                $stmt_name = $pdo->prepare($select_wakeup);
                $stmt_name -> bindValue(':date', $date);
                $stmt_name ->execute();
                $logintime = $stmt_name->fetch(PDO::FETCH_COLUMN);
                $name_time[$row["username"]] = $logintime;
            }
            asort($name_time);
            $number = 0;
            foreach($name_time as $key => $row){
                $number++;
                $time = date('H:i', strtotime($row));
                echo "<div class='box'>";
                echo "<div class=rank-set>";
                echo "<div class='rank1'> $number 位　</div>";
                echo "<div class='rank2'> $key さん </div>";
                echo "</div>";
                echo "<div class='time'>  $time におきました！ </div>";
                echo "</div>";
                echo "<hr>";
            }
        ?>
    <a href="logoutpage.php">ログアウト</a>
    <a href="mypage1.php">マイページ</a>
    </body>
</html>