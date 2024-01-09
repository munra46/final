<?php require 'db-connect.php'; ?>
<?php require 'api.php'; ?>
<?php
    $category=$pdo->query("SELECT * FROM category");
    $category=$category->fetchAll(PDO::FETCH_ASSOC);
    $image_paths=[];
    $uploadOk=1;
    $click='';
    $First = !isset($_POST['search_button']);
    $selectedShopNames = isset($_POST['selectedShopNames']) ? $_POST['selectedShopNames'] : '';
$selectedUrls = isset($_POST['selectedUrls']) ? $_POST['selectedUrls'] : '';
// echo "クリックされた店舗名: " . $selectedShopNames;
// echo "URL: " . $selectedUrls;
    if($_SERVER["REQUEST_METHOD"]=='POST'){
        $diary_name=$_POST['diary_name'];
        $diary_containts=$_POST['diary_containts'];
        $diary_day=$_POST['diary_day'];
        $spot=$_POST['spot'];
        $cost=$_POST['cost'];
        $category_id=$_POST['category_id'];
        
        if(isset($_POST['search_button'])){
        //API検索
        $shopInfo=searchHOTPEPPER($_POST['spot']);
        // //取得した店舗情報
        // if(!empty($shopInfo)){
        //     echo '検索結果';
        //     foreach($shopInfo as $shop){
        //         echo '<p>店舗名:'.$shop['name'].'</p>';
        //         echo '<p>住所:'.$shop['address'].'</p>';
        //         foreach($shop['urls'] as $url){
        //             echo '<p>URL:'.$url.'</p>';
        //         }
        //     }
        // }else{
        //     echo '店舗が見つかりませんでした。';
        // }
        }else{
            if(!empty($diary_name)&&!empty($diary_containts)&&!empty($diary_day)&&!empty($spot)&&!empty($cost)&&!empty($category_id)){
                if(!empty($_FILES["diary_image"]["tmp_name"][0])){
                    // 画像の処理
                    // 画像の保存ディレクトリ
                    $uploadedCount=count($_FILES["diary_image"]["tmp_name"]);
                    if ($uploadedCount<=2) {
                        foreach($_FILES["diary_image"]["tmp_name"] as $key => $tmp_name){
                            $target_file="uploads/".basename($_FILES["diary_image"]["name"][$key]);
                            // 画像のファイルサイズを制限
                            if ($_FILES["diary_image"]["size"][$key]>500000) {
                                echo '<script>alert("画像が大きすぎます。");</script>';
                                $uploadOk=0;
                            }
                            // 許可された拡張子
                            $imageFileType=strtolower(pathinfo($target_file,PATHINFO_EXTENSION));
                            $allow=array("jpg","png");
                            if (!in_array($imageFileType, $allow)) {
                                echo '<script>alert("許可されていないファイル形式です。");</script>';
                                $uploadOk=0;
                            }

                            // 画像のアップロード
                            if ($uploadOk){
                                $target_file="uploads/".time().'_'.$key.'.'.pathinfo($_FILES["diary_image"]["name"][$key],PATHINFO_EXTENSION);
                                if (move_uploaded_file($tmp_name, $target_file)) {
                                    echo '<script>alert("画像がアップロードされました。");</script>';
                                    $image_paths[]=$target_file;
                                } else {
                                    echo '<script>alert("画像のアップロード中にエラーが発生しました。");</script>';
                                }
                            }
                        }
                    }else{
                        echo '<script>alert("画像は最大2つまで選択可能です。");</script>';
                        $uploadOk = 0;
                    }
                }
            if ($uploadOk){
                if(is_numeric($cost)){
                    // echo $_POST['selectedUrls'];
                    // $selectedUrls=isset($_POST['selectedUrls']) ? $_POST['selectedUrls'] :[];
                    // var_dump($selectedUrls);
                    if(empty($selectedUrls)){
                        $stmt=$pdo->prepare("INSERT INTO diary(diary_name,diary_containts,diary_day,spot,cost,category_id)
                        VALUES (?,?,?,?,?,?)");
                        $stmt->execute([$diary_name,$diary_containts,$diary_day,$spot,$cost,$category_id]);
                        echo '<script>alert("登録しました。");</script>';
                        // 直前に挿入した日記のdiary_idを取得
                        $diary_id = $pdo->lastInsertId();
                        foreach ($image_paths as $image_path) {
                            $stmt = $pdo->prepare("INSERT INTO diary_image (diary_id, image_path) VALUES (?, ?)");
                            $stmt->execute([$diary_id, $image_path]);
                        }
                        echo '<script>window.location.href = "diary_lists.php";</script>';
                        exit();
                    }else{
                        $stmt=$pdo->prepare("INSERT INTO diary(diary_name,diary_containts,diary_day,spot,cost,category_id,URL)
                        VALUES (?,?,?,?,?,?,?)");
                        $stmt->execute([$diary_name,$diary_containts,$diary_day,$spot,$cost,$category_id,$selectedUrls]);
                        echo '<script>alert("登録しました。");</script>';
                        // 直前に挿入した日記のdiary_idを取得
                        $diary_id = $pdo->lastInsertId();
                        foreach ($image_paths as $image_path) {
                            $stmt = $pdo->prepare("INSERT INTO diary_image (diary_id, image_path) VALUES (?, ?)");
                            $stmt->execute([$diary_id, $image_path]);
                        }
                        echo '<script>window.location.href = "diary_lists.php";</script>';
                        exit();
                    }
                }else{
                    echo'<script>alert("金額は数字です");</script>';
                }
            }else{
                echo'<script>alert("画像の登録エラーのため登録できていません");</script>';
            }
        }else{
            echo '<script>alert("画像以外の全ての項目を入力してください。");</script>';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>追加画面</title>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
    <script src=js/diary_add.js></script>
<link rel="stylesheet" href="css/diary_add.css">
</head>
<body>
    <h1>日記追加画面</h1>
    
    <form name="diaryForm" action="diary_add.php" onsubmit="return validateForm()" method="post" enctype="multipart/form-data">

            タイトル
            <input type="text" name="diary_name" size="50" value="<?= isset($_POST['diary_name']) ? $_POST['diary_name']:''; ?>"><br>
            内容
            <textarea name="diary_containts" rows="4" cols="50"><?= isset($_POST['diary_containts']) ? $_POST['diary_containts']:''; ?></textarea><br>
            日付
            <input type="date" name="diary_day" size="50" value="<?= isset($_POST['diary_day']) ? $_POST['diary_day']:''; ?>"><br>
            場所
            <input type="text" name="spot" id="spot" size="50" value="<?= isset($_POST['spot']) ? $_POST['spot']:''; ?>"><br>
            <button type="submit" name="search_button" onclick="searchAndSetSpot()" class="btn-border">検索</button> <br>   
            <div id="clickMessage"></div>
        <div id="searchResults">
        <?php
         if(!empty($shopInfo)){
             echo '<h2>検索結果</h2>';
             foreach($shopInfo as $shop){
                 echo '<p>店舗名:';
                 foreach($shop['urls'] as $url){
                    //  echo '<p>URL:'.$url.'</p>';
                    echo '<p onclick="setSpotAndHideResults(\''.$shop['name'].'|'.$url.'\')">'.$shop['name'].'</p>';
                    //echo '<p data-spot="'.$shop['name'].'|'.$url.'">'.$shop['name'].'</p>';
                    // echo '<input type="hidden" name="selectedShopNames" value="'.($click===$shop['name'] ? $shop['name']:'').'">';
                    // echo '<input type="hidden" name="selectedUrls" value="'.($click===$shop['name'] ? $url:'').'">';
                    echo '<input type="hidden" name="selectedShopNames" value="'.($selectedShopNames==$shop['name'] ? $shop['name']:'').'">';
                    echo '<input type="hidden" name="selectedUrls" value="'.($selectedUrls==$shop['name'] ? $url:'').'">';
                 
                 }
                 '</p>';
             }
        }else if(!$First){
            echo '<p id="noResults">お探しの店舗は登録されていません。</p>';
        }
        ?>
        </div>
            金額
            <input type="text" name="cost" size="50" value="<?= isset($_POST['cost']) ? $_POST['cost']:''; ?>"><br>
            カテゴリ名
            <select name='category_id'>
            <?php
            foreach($category as $categorys){
                echo '<option value="'.$categorys['category_id'].'">'.$categorys['category_name'].'</option>';
            }
            ?>
            </select><br>
            画像(jpgかpng形式のみ有効)<br>
            2枚まで選択可能※まとめて選んでください<br>
            <input type="file" name="diary_image[]" multiple><br>
            <button type="submit" class="btn-border">登録</button>
        </form>
        <br>
    <form action="diary_lists.php" method="post">
        <button type="submit" class="btn-border">戻る</button>
    </form>
    <a href="http://webservice.recruit.co.jp/">ホットペッパーグルメ Webサービス</a>
</body>
</html>