<!-- データベース設定 -->

<?php

//-----------------データベース接続設定----------------------------------
    $dsn = $dsn = 'mysql:dbname=***;host=localhost';
    $user = '***';
    $password = '***';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    //--------------------------------------------------------------------

    //---------------テーブル1------------------------------------------
    $sql = "CREATE TABLE IF NOT EXISTS db_users"
    ."("
    ."id INT AUTO_INCREMENT PRIMARY KEY,"
    ."mail TEXT,"
    ."username varchar(20),"
    ."password TEXT,"
    ."date_resister TEXT"
    .");";
    $stmt = $pdo -> query($sql);
    //---------------------------------------------------------------------

    ?>
