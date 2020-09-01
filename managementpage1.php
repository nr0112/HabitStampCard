<!-- 管理者ページ1(サイト制作者) -->
<!DOCTYPE html>
<html lang="ja">
    <head>
        <meta charset="UTF-8">
        <title>管理者ページ</title>
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
        <!-- user_idを指定するとその人のカレンダーを閲覧することができる -->
        <form>
            <input type="number" name="userID">
            <input type="submit" name="user_reference" value="閲覧">
        </form>
        
        <?php
        
        if(isset($_POST["userID"])){
            // スタンプをつける関数
            function return_img($day, $user_name){
                global $img;
                if(catchTrue($day, $user_name)){
                return '<th>'.$img.'</th>';
                }
                return '<th></th>';
            }
            // スタンプをつけるかどうか判定する関数
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

            //今日の日付だけを見る
            // function testshow($user_name){
            //     require_once("pdo.php");
            //     $pdo = pdo_connect();
            //     global $day;
            //     $select = "SELECT * FROM $user_name WHERE date=:date AND wakeupflag=1";
            //     $stmt = $pdo->prepare($select);
            //     $stmt ->bindValue(':date', $day);
            //     $stmt->execute();
            //     $buf = $stmt->fetchAll();
            //     foreach ($buf as $row){
            //         var_dump($row);
            //         echo "<br>";
            //     }
            //     return;
            // }
            
            //全ての日付を参照
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
            
            $id = $_POST["userID"];
            $name_ID = "";
            $name_ID .= "user_ID_".$id;
        
            login($name_ID);
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
                $date = $ym.'-'.$dayx;
                $stamp .=return_img($day, $name_ID);
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
        }else{
            echo '閲覧したいユーザーのIDを入力してください';
        }
        ?>
         
        <div class="container">
        <h3><a href="?ym=<?php echo $prev; ?>">&lt;</a> <?php echo$html_title; ?> <a href="?ym=<?php echo $next; ?>">&gt;</a></h3>
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
     </body>
</html>