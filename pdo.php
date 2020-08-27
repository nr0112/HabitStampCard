<?php
//-----------------データベース接続関数----------------------------------

function pdo_connect(){
    $dsn = $dsn = 'mysql:dbname=***;host=localhost';
    $user = '***';
    $password = '***';
    $pdo = new PDO($dsn, $user, $password, array(PDO::ATTR_ERRMODE => PDO::ERRMODE_WARNING));
    return $pdo;
}
//--------------------------------------------------------------------
?>