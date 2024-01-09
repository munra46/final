<?php require 'db-connect.php'; ?>
<?php
$pdo=new PDO($connect, USER, PASS);
$sql ="SELECT diary.*,category.category_name, GROUP_CONCAT(diary_image.image_path)AS image_paths
FROM diary JOIN category ON diary.category_id=category.category_id 
LEFT JOIN diary_image ON diary.diary_id=diary_image.diary_id
GROUP BY diary.diary_id 
ORDER BY diary.diary_id DESC";

$Page=5;
$current=isset($_GET['page']) ? (int)$_GET['page'] : 1;
    if($_POST){
        if (isset($_POST['update'])){
            $id = (int)$_POST['diary_id'];
            $cost = (int)$_POST['cost'];
            $stmt = $pdo->prepare('UPDATE diary SET diary_name=:name,diary_containts=:containts,diary_day=:day,
            spot=:spot,cost=:cost,category_id=:ca_id WHERE diary_id=:id');
            $stmt->bindValue(':id', $id);
            $stmt->bindValue(':name', $_POST['diary_name']);
            $stmt->bindValue(':containts', $_POST['diary_containts']);
            $stmt->bindValue(':day', $_POST['diary_day']);
            $stmt->bindValue(':spot', $_POST['spot']);
            $stmt->bindValue(':cost', $cost);
            $stmt->bindValue(':ca_id', $_POST['category_id']);
            $stmt->execute();
        }else if(isset($_POST['delete'])){
            $id=$_POST['diary_id'];
    
            // 商品テーブルから削除
            $stmt_product = $pdo->prepare('DELETE FROM diary_image WHERE diary_id = :id');
            $stmt_product->bindValue(':id', $id);
            $stmt_product->execute();
            $stmt_product = $pdo->prepare('DELETE FROM diary WHERE diary_id = :id');
            $stmt_product->bindValue(':id', $id);
            $stmt_product->execute();
        }
    }
$stmt =$pdo->query($sql);
$category=$pdo->query("SELECT * FROM category")->fetchAll(PDO::FETCH_ASSOC);

$all=$stmt->fetchAll(PDO::FETCH_ASSOC);

// ページネーションのために表示するデータを制御
$total=count($all);
$totalPages=ceil($total/$Page);
$offset=($current-1)*$Page;

// ページごとのデータを取得
$sql .=" LIMIT $offset,$Page";
$stmt=$pdo->query($sql);
$diaries=$stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>日記変更画面</title>
    <script src="js/diary_list.js"></script>
<link rel="stylesheet" href="css/diary_list.css">
</head>
<body>
    <div class="c">
        <form action="" method="POST">
            <?php
            foreach ($diaries as $row){
                echo '<form action="" method="POST">';
                echo '<input type="hidden" name="diary_id" value="'.$row["diary_id"].'">';
                $imagePaths=!empty($row['image_paths']) ? explode(',',$row['image_paths']) : [];
                echo '<div class="post">
                    <h1><span class="edit-mode" id="diary_name_'.$row['diary_id'].'">', $row['diary_name'], '</span>
                    <input type="text" name="diary_name" value="',$row['diary_name'],'" class="edit-mode" placeholder="タイトル" style="display:none;"></h1>';
                    $word= wordwrap($row['diary_containts'], 40, "<br>\n", true);
                echo '<span class="edit-mode" id="diary_containts_'.$row['diary_id'].'">', $word,'</span>
                    <input type="text" name="diary_containts" value="',$row['diary_containts'],'" class="edit-mode" placeholder="内容" style="display:none;">';                
                echo '<span class="date edit-mode" id="diary_day_'.$row['diary_id'].'">', $row['diary_day'],'</span>
                    <input type="date" name="diary_day" value="',$row['diary_day'],'" class="edit-mode" placeholder="日付" style="display:none;">';
               echo '<br>';
               foreach($imagePaths as $imagePass){
                    if(!empty($imagePass)&&file_exists($imagePass)){
                        echo '<img src="'.$imagePass.'"alt="'.$row['diary_name'].'" width="80">';
                    }
                }
                echo '<br>';
            //    else{
            //     $imagePass=$row['image_path'].".png";
            //         if(file_exists($imagePass)){
            //             echo '<img src="'.$imagePass.'"alt="'.$row['diary_name'].'" width="80">';
            //         }else{
            //             echo '';
            //         }
            //    }     
            if (!empty($row['URL'])&& filter_var($row['URL'], FILTER_VALIDATE_URL)) {
                echo '<a href="' . $row['URL'] . '" target="_blank"><span class="edit-mode" id="spot_' . $row['diary_id'] . '">' . $row['spot'] . '</span></a>';
            } else{
                echo '<span class="edit-mode" id="spot_'.$row['diary_id'].'">', $row['spot'], '</span>';
            }
               echo  '<input type="text" name="spot" value="',$row['spot'],'" class="edit-mode" placeholder="場所" style="display:none;"><br>';
               echo '<span class="edit-mode" id="cost_'.$row['diary_id'].'">', $row['cost'],'</span>
                    <input type="text" name="cost" value="',$row['cost'],'" class="edit-mode" placeholder="費用" style="display:none;">円';
                echo '<span class="category edit-mode" id="category_id_'.$row['diary_id'].'">', $row['category_name'], '</span>
                    <select name="category_id" id="category_id_'.$row['diary_id'].'" class="edit-mode" style="display:none;"><br>';
                foreach($category as $categorys){
                    echo '<option value="'.$categorys['category_id'].'">'.$categorys['category_name'].'</option>';
                }
                echo '</select>';
                ?>
                <div class="button-container">
                <button type="submit" name="update" class="btn-border">更新</button>
                </form>
                <button onclick="edit(<?php echo $row['diary_id']; ?> )"type='button' class="btn-border">編集</button>
                <!-- <form action="" method="POST">
                    <input type="hidden" name="diary_id" value="<?php echo $row['diary_id']; ?>">
                    <input type="hidden" name="diary_name" value="<?php echo $row['diary_name']; ?>">
                    <input type="hidden" name="diary_containts" value="<?php echo $row['diary_containts']; ?>">
                    <input type="hidden" name="diary_day" value="<?php echo $row['diary_day']; ?>">
                    <input type="hidden" name="spot" value="<?php echo $row['spot']; ?>">
                    <input type="hidden" name="cost" value="<?php echo $row['cost']; ?>">
                    <input type="hidden" name="category_id" value="<?php echo $row['category_id']; ?>"> -->
                    <!-- <button type="submit" name="update">更新</button>
                </form> -->
                <form action="" method="POST">
                    <input type="hidden" name="diary_id" value="<?php echo $row['diary_id']; ?>">
                    <button type="submit" name="delete" class="btn-border delete-button">削除</button>
                </form>
            </div>
            </div>
                <?php                
                echo "\n";
            }
            ?>
        </form>
        <a href="diary_add.php" class="write-diary-button">日記を書く</a>
    </div>
    <div class="pagination">
            <?php for ($i = 1; $i <= $totalPages; $i++) : ?>
                <a href="?page=<?= $i ?>" <?= $i === $current ? 'class="active"' : '' ?>><?= $i ?></a>
            <?php endfor; ?>
        </div>
    <a href="http://webservice.recruit.co.jp/">ホットペッパーグルメ Webサービス</a>
</body>
</html>
