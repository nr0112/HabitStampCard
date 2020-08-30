<?php
        //---------------テーブル1------------------------------------------
        // $sql = "CREATE TABLE IF NOT EXISTS db_users"
        // ."("
        // ."id INT AUTO_INCREMENT PRIMARY KEY,"
        // ."mail TEXT,"
        // ."username varchar(20),"
        // ."password TEXT,"
        // ."wakeup TEXT,"
        // ."date_resister TEXT"
        // .");";
        // $stmt = $pdo -> query($sql);
        //---------------------------------------------------------------------
    function return_img($day, $user_name){
        global $img;
        if(catchTrue($day, $user_name)){
        return '<th>'.$img.'</th>';
        }
        return '<th></th>';
    }
    function catchTrue($day, $user_name){
        global $timestamp;
        $ymj ="";
        $ymj = date('Y-m-j', mktime(0, 0, 0, date('m', $timestamp), $day, date('Y', $timestamp)));
        require_once("pdo.php");
        $pdo = pdo_connect();
        $select = "SELECT * FROM $user_name WHERE date=:date";
        $stmt = $pdo->prepare($select);
        $stmt ->bindValue(':date', $ymj);
        $stmt->execute();
        $results = $stmt->fetchAll();
        if(count($results) != 0){
        return TRUE;
        }
    }
    function login($user_name){
        // ログイン処理
        require_once("pdo.php");
        $pdo = pdo_connect();
        $wakeupflag = 1;
        $comment = "";
        $photo = "";
        $select = "SELECT * FROM $user_name WHERE date=:date";
        $stmt = $pdo->prepare($select);
        $stmt ->bindValue(':date', date('Y-m-j'));
        $stmt->execute();
        $results = $stmt->fetchAll();
        // echo "<br>".count($results)."<br>";
        // 今日ログインした情報がなかったら
        if(count($results) == 0)
        {
        $insert_tmp = "INSERT INTO $user_name(date, logintime, comment, photo, wakeupflag) VALUES (now(), now(), :comment, :photo, :wakeupflag)";
        $login = $pdo -> prepare($insert_tmp);
        $login -> bindParam(":comment", $comment, PDO::PARAM_STR);
        $login -> bindParam(":photo", $photo, PDO::PARAM_STR);
        $login -> bindParam (":wakeupflag", $wakeupflag, PDO::PARAM_INT);
        $login -> execute();
        }
        return;
    }
    function testshow($user_name){
        require_once("pdo.php");
        $pdo = pdo_connect();
        global $day;
        $select = "SELECT * FROM $user_name WHERE date=:date AND wakeupflag=1";
        $stmt = $pdo->prepare($select);
        $stmt ->bindValue(':date', $day);
        $stmt->execute();
        $buf = $stmt->fetchAll();
        foreach ($buf as $row){
            var_dump($row);
            echo "<br>";
        }
        return;
    }
    function full_testshow($user_name){
        require_once("pdo.php");
        $pdo = pdo_connect();
        $select = "SELECT *FROM $user_name";
        $stmt = $pdo ->query($select);
        $buf = $stmt->fetchAll();
        foreach ($buf as $row){
            echo $row["date"];
            echo "<br>";
        }
        return;
    }
    date_default_timezone_set('Asia/Tokyo');

    if(isset($_GET['ym'])){
        $ym = $_GET['ym'];
    }else{
        $ym = date('Y-m');
    }
    $timestamp = strtotime($ym . '-01');
    if($timestamp === false){
        $ym = date('Y-m');
        $timestamp = strtotime($ym . '-01');
    }
    $today = date('Y-m-j');
    $html_title = date('Y年n月', $timestamp);
    $prev = date('Y-m', mktime(0, 0, 0, date('m', $timestamp)-1, 1, date('Y', $timestamp)));
    $next = date('Y-m', mktime(0, 0, 0, date('m', $timestamp)+1, 1, date('Y', $timestamp)));
    $day_count = date('t', $timestamp);
    $youbi = date('w', mktime(0, 0, 0, date('m', $timestamp), 1, date('Y', $timestamp)));
    
    $wakeuptime_def = $_POST["wakeuptime"];
    $user_name = "datebase_1";
    $id = 1;
    $name_ID = "";
    $name_ID .= "user_ID_".$id;
    // echo $name_ID;

    login($user_name);
    // ここでログインしている95行目の$user_nameを変えるだけでおそらくユーザーを変えることができる。
    // full_testshow($user_name);
    $weeks = [];
    $stamps = [];
    $week = '';
    $stamp ='';
    $img = '<img src="https://3.bp.blogspot.com/-p1j5JG0kN8I/Wn1ZUJ3CbuI/AAAAAAABKK4/hKPhQjTXXv0o3QXh1J0rQ4TaFqGqUGu7ACLcBGAs/s800/animal_smile_kuma.png
    " alt="" width = "50px" height="50px">';
    $week .=str_repeat('<td></td>', $youbi);
    $stamp .=str_repeat('<th></th>', $youbi);
    for($day = 1; $day <= $day_count; $day++, $youbi++){
        $date = $ym.'-'.$day;
        $stamp .=return_img($day, $user_name);
        if($today == $date){
            $week.='<td class="today">'. $day;
        }else{
            $week .= '<td>'. $day;
        }
        $week .= '</td>';
        
        if ($youbi % 7 == 6 || $day == $day_count){
            if($day == $day_count){
                $week .= str_repeat('<td></td>', 6 - ($youbi % 7));
            }

            $weeks[]= '<tr>'. $week.'</tr>';
            $stamps[]='<tr>'. $stamp.'</tr>';
            $week = '';
            $stamp ='';
        }
    }
?>

<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="utf-8">
    <title>カレンダー</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" integrity="sha384-BVYiiSIFeK1dGmJRAkycuHAHRg32OmUcww7on3RYdg4Va+PmSTsz/K68vbdEjh4u" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital@1&display=swap" rel="stylesheet">
    <style>
        .container{
            font-family: 'Playfair Display', serif;
            margin-top: 80px;
        }
        h3 {
            margin-bottom: 30px;
        }
        td {
            height: 30px;
            text-align: center;
            width: 100px;
        }
        th {
            height: 100px;
            text-align: center;
        }
        .today {
            background: orange;
        }
        th:nth-of-type(1), td:nth-of-type(1) {
            color: red;
        }
        th:nth-of-type(7), td:nth-of-type(7) {
            color: blue;
        }
    </style>
    
</head>
<body>

        <div class="container">
    <h3><a href="?ym=<?php echo $prev; ?>">&lt;</a> <?php echo $html_title; ?> <a href="?ym=<?php echo $next; ?>">&gt;</a></h3>
        <table class="table table-bordered">
            <tr>
                <td>日</td>
                <td>月</td>
                <td>火</td>
                <td>水</td>
                <td>木</td>
                <td>金</td>
                <td>土</td>
            </tr>
            <?php
                $i = 0;
                foreach($weeks as $week){
                    echo $week;
                    echo $stamps[$i];
                    $i++;
                }
            ?>
        </table>
    </div>
    <form action="" method="POST">
                <div>
                        <th>目標時間設定<th>
                        <p><input type="time" name="wakeuptime"></p>
                        <p><input type="submit" name="submit_time" value="設定"></p>
                </div>
    </form>
</body>
</html>