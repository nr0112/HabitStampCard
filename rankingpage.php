<!-- ランキング掲示板ページ -->

<?php
    require_once("pdo.php");
    $pdo = pdo_connect();

    $select = "SELECT * FROM db_users";
    $stmt = $pdo -> query($select);
    $results = $stmt->fetchAll();
    foreach($results as $row){
        echo $row["username"];
        echo "<br>";
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