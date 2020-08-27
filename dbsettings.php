<!-- データベース設定 -->

<?php

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
