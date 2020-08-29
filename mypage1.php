<?php
    function returnimg($day){
        global $img;
        if(catchTrue($day)){
        return '<th>'.$img.'</th>';
        }
        return '<th></th>';
    }
    function catchTrue($day){
        global $ym;
        $ymm ="";
        $ymm .= $ym.'-'.$day;
        global $a;
        if($ymm == $a){
        return TRUE;
        }
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

    $weeks = [];
    $stamps = [];
    $week = '';
    $stamp ='';
    $a = date('Y-m-j');
    $img = '<img src="https://3.bp.blogspot.com/-p1j5JG0kN8I/Wn1ZUJ3CbuI/AAAAAAABKK4/hKPhQjTXXv0o3QXh1J0rQ4TaFqGqUGu7ACLcBGAs/s800/animal_smile_kuma.png
    " alt="" width = "50px" height="50px">';
    $week .=str_repeat('<td></td>', $youbi);
    $stamp .=str_repeat('<th></th>', $youbi);
    for($day = 1; $day <= $day_count; $day++, $youbi++){
        $date = $ym.'-'.$day;
        $stamp .=returnimg($day);
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
    // echo $day_count;
    // echo $youbi;

    // echo "<BR>";
    
    // echo $html_title;
    // echo $timestamp;
    // echo "<br>";
    // echo $ym;

    // echo "<br>";
    // echo "<br>";

    // $time = mktime();
    // var_dump(date('Y/m/d/h/i/s', $time));
    // $te = date('w', $timestamp);
    // echo $te;
    // var_dump($weeks);
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
</body>
</html>