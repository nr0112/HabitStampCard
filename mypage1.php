<?php
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
        $select = "SELECT wakeupflag FROM $user_name WHERE date=:date";
        $stmt = $pdo->prepare($select);
        $stmt ->bindValue(':date', $ymj);
        $stmt->execute();
        $results = $stmt->fetch(PDO::FETCH_COLUMN);
        if($results == 1){
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
        $wakeupflag = wakeupflag_update($user_name);
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
    function wakeuptime_set($user_name, $wakeuptime_def){
        require_once("pdo.php");
        $pdo = pdo_connect();
        $id = substr($user_name, 8);
        // user_ID_1という形から1という形に加工

        $update_wakeup = "UPDATE db_users SET wakeup=:wakeup WHERE id=:id";
        $stmt = $pdo->prepare($update_wakeup);
        $stmt -> bindParam(':wakeup', $wakeuptime_def);
        $stmt -> bindValue(':id', $id, PDO::PARAM_INT);
        $stmt -> execute();
    }
    function wakeupflag_update($user_name){
        require_once("pdo.php");
        $pdo = pdo_connect();
        $flag  = 0;
        if(wakeup_get($user_name) > date('H:i')){
            $flag = 1;
        }elseif(wakeup_get($user_name) == ""){
            echo "ERROR";
        }
        return $flag;
        // $update_flag = "UPDATE $user_name SET wakeupflag=:wakeupflag WHERE date=:date";
        // $stmt = $pdo ->prepare($update_flag);
        // $stmt ->bindParam(':wakeupflag', $flag, PDO::PARAM_INT);
        // $stmt ->bindValue(':date', date('Y-m-j'));
        // $stmt ->execute();
    }
    function wakeup_get($user_name){
        require_once("pdo.php");
        $pdo = pdo_connect();
        $results = "";
        $id = substr($user_name, 8);
        $select = "SELECT * FROM db_users WHERE id=:id";
        $stmt = $pdo->prepare($select);
        $stmt ->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt ->execute();
        $buf = $stmt->fetchAll();
        foreach($buf as $row){
            $results = $row["wakeup"];
        }
        return $results;
    }
    function get_username($user_ID){
        require_once("pdo.php");
        $pdo = pdo_connect();
        $id = substr($user_ID, 8);
        $select = "SELECT * FROM db_users WHERE id=:id";
        $stmt = $pdo->prepare($select);
        $stmt ->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt ->execute();
        $buf = $stmt->fetchAll();
        foreach($buf as $row){
            $results = $row["username"];
        }
        return $results;
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
    
    session_start();

    $id = $_SESSION['ID'];
    $name_ID = "";
    $name_ID .= "user_ID_".$id;
    // echo $name_ID;
    $int_id = substr($name_ID, 8);


    if(isset($_POST["submit_time"])){
        $wakeuptime_def = $_POST["wakeuptime"];
        // echo $wakeuptime_def;
        $now_time = date('H:i');
        wakeuptime_set($name_ID, $wakeuptime_def);
    }
    login($name_ID);
    echo get_username($name_ID);
    echo "<br>おきるじかん";
    echo wakeup_get($name_ID);
    echo "<br>きょうはおきれたね！えらい！";
    echo wakeupflag_update($name_ID);
    // ここでログインしている$user_nameを変えるだけでおそらくユーザーを変えることができる。
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
        $day_mypage2 = "<a href="."mypage2.php?date=".$date.">".$day."</a>";
        $stamp .=return_img($day, $name_ID);
        if($today == $date){
            $week.='<td class="today">'. $day_mypage2;
        }else{
            $week .= '<td>'. $day_mypage2;
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
        footer{
            float: right;
        }
    </style>
    
</head>
<body>
    <form action="" method="POST">
            <div class="wakeup">
                <th>もくひょうじかんせってい<th>
                <p><input type="time" name="wakeuptime"></p>
                <p><input type="submit" name="submit_time" value="せってい"></p>
            </div>
    </form>
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
    <footer>
        
        <a href="rankingpage.php">ランキングページ</a>
        <a href="logoutpage.php">ログアウト</a>
    </footer>
</body>
</html>