<!-- 写真日記の投稿ページ -->
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>diary</title>
</head>
<body>
 
<?php

  require_once("pdo.php");
  $pdo =pdo_connect();
  session_start();
  $edit_comment='';
  $edit_photo='';
  $id = $_SESSION['ID'];
  $name_id = "";
  $name_id .= "user_ID_".$id;
  // ↑で$idを数字から名前に加工
  // 例：$id = 1の場合
  // 　$name_id = user_ID_1　　(str型)
  //$sql_create_table="CREATE TABLE $name_id"
  //     ."("
  //     ."date DATE,"
  //     ."logintime DATETIME,"
  //     ."wakeupflag int(1),"
  //     ."photo VARCHAR(128),"
  //     ."comment VARCHAR(128)"
  //     .")"
  //     ."ENGINE=InnoDB DEFAULT CHARACTER SET=utf8;";
  // $stmt = $pdo->query($sql_create_table);

  // 日付をもとにコメントと画像ファイルを検索する.日付は他の人から受け取るデータ

    //$date=date('Y-m-j');//test2に書き換える


  if(isset($_GET['date'])){
      $date = $_GET['date'];
  }else{
      $date = date('Y-m-j');
  }
  
  $sql = "SELECT * FROM $name_id  WHERE date=:date";
  $stmt = $pdo->prepare($sql);
  $stmt->bindParam(':date', $date);
  $stmt->execute();
        
  $results = $stmt->fetchAll();//実行結果を検索
  foreach ($results as $row)
  {
    $edit_comment=$row['comment'];
    $edit_photo=$row['photo'];
  }    
  
  if(isset($_POST["approve"]))//isset関数…変数に値がセットされていて、かつNULLでない
  {
    
    $comment=$_POST["comment"];//コメント
    if(is_uploaded_file($_FILES["photo"]["tmp_name"]))
    {
      $photo=$_FILES["photo"];
      $uni_photo = uniqid(mt_rand(), true);//ファイル名をユニーク化
      $uni_photo.= '.' . substr(strrchr($_FILES['photo']['name'], '.'), 1);//アップロードされたファイルの拡張子を取得
      $file = 'photos/'.basename($uni_photo); //保存先を指定
      //if (!empty($_FILES['photo']['name'])) {//ファイルが選択されていれば$uni_photoにファイル名を代入
      move_uploaded_file($_FILES['photo']['tmp_name'], $file); //photosディレクトリにファイル保存
      if (exif_imagetype($file)) 
      {
        //画像ファイルかのチェック
        $stmt=$pdo->prepare("UPDATE $name_id SET comment=:comment,photo=:photo WHERE date=:date");//困ったら""を使う.where=if文　日付毎にファイルがつくられている　dateが同じ時にこれを実行する
        //$sql =$pdo->prepare("INSERT into　$name_id (date,logintime,wakeupflag,photo,comment) VALUES (:date,now(),1,:photo,:comment");
        $stmt-> bindParam(':date', $date);
        $stmt-> bindParam(':comment', $comment, PDO::PARAM_STR);
        $stmt-> bindParam(':photo', $uni_photo, PDO::PARAM_STR);
        $stmt->execute();
      }
      else
      {
        echo '画像ファイルではありません<br>';
      }
    }
    else
    {
      $stmt=$pdo->prepare("UPDATE $name_id SET comment=:comment WHERE date=:date");//困ったら""を使う.where=if文　日付毎にファイルがつくられている　dateが同じ時にこれを実行する
      //$sql =$pdo->prepare("INSERT into　$name_id (date,logintime,wakeupflag,photo,comment) VALUES (:date,now(),1,:photo,:comment");
      $stmt-> bindParam(':date', $date);
      $stmt-> bindParam(':comment', $comment, PDO::PARAM_STR);
      // $stmt-> bindParam(':photo', $uni_photo, PDO::PARAM_STR);
      $stmt->execute();
    }
  }
  if(isset($_POST["delete"]))
  {

    $comment="";
    $photo="";
    $stmt=$pdo->prepare("UPDATE $name_id SET comment=:comment,photo=:photo WHERE date=:date");
    $stmt-> bindParam(':date', $date);
    $stmt-> bindParam(':comment', $comment,PDO::PARAM_STR);
    $stmt-> bindParam(':photo', $uni_photo,PDO::PARAM_STR);
    $stmt->execute();
  }

  $sql = "SELECT * FROM $name_id  WHERE date=:date";
  $stmt = $pdo->prepare($sql);
  $stmt->bindParam(':date', $date);
  $stmt->execute();
  $results = $stmt->fetchAll();//実行結果を検索
  foreach ($results as $row)
  {
    //$rowの中にはテーブルのカラム名が入る
    $show_photo=$row['photo'];
    if($show_photo=="")
    {
      echo $row['date'].'<br>';
      echo $row['comment'].'<br>';
    }
    else
    {
      echo $row['date'].'<br>';
      echo $row['comment'].'<br>';          
      echo "<img src='photos/$show_photo'width=300 height=300 alt=''><br>";
    }
        
  }
?>
 
   
  <form action="" method="post" enctype="multipart/form-data">
  <?php
    //その日付の画像が登録されているかを確認
    if($edit_photo!=null){     
        echo"<img src='photos/'".$edit_photo."width=300 height=300 alt=''></br>";
    }
  ?>

  
  <div class="item">
      <label for="comment">日記:</label>
        <textarea id="comment"  name="comment" placeholder="ここには自由にコメントを記入してください" rows="8" cols="40" style="vertical-align:middle;"></textarea> 
    <!--行数　row 文字数　cols cssコード：style=""　vertical-align 縦方向のそろえ方　middle　位置-->
    </div><br>
 
     <p>アップロード画像</p>
     <input type="file" name="photo">
     <button><input type="submit" name="approve" value="送信"></button>
  <br><br>
    <input type="submit" name="delete" value="削除"> 
  
   </form>
   <a href="loginpage.php">ログインページ</a>
   <a href="rankingpage.php">ランキングページ</a>
   <a href="logoutpage.php">ログアウト</a>
   
  </body>
</html>