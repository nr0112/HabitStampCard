<?php
if(isset($_GET['date'])){
    $date = $_GET['date'];
}else{
    $date = date('Y-m-j');
}
echo $date;
?>