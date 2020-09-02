<!-- ランキング掲示板ページ -->

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
    $keys = array_keys($name_time);
    foreach($keys as $row){
        echo $row;
    }
?>
<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>ランキング掲示板</title>

    </head>
    <body>
    </body>
</html>