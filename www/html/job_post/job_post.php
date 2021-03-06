<?php
session_start();
require_once '../function.php';

// 未ログイン or ログイン後1時間経過の場合再ログイン
if (isset($_SESSION['id']) && $_SESSION['time'] + 3600 > time()) {
    $_SESSION['time'] = time();
} else {
    header("Location: ../index.php");
    exit();
}

// 選択肢に使用する連想配列
$cities = array(1 => "シドニー", 2 => "メルボルン", 3 => "ケアンズ", 4 => "ゴールドコースト", 5 => "ブリズベン", 6 => "パース", 7 => "キャンベラ", 8 => "アデレード");
$languages = array(1 => "英語力必要無し", 2 => "必要な英語力（低）", 3 => "日常会話レベルの英語力", 4 => "必要な英語力（高）");


// 送信ボタン押されたら
if (isset($_REQUEST["post"])) {

    $member_id = $_SESSION['id'];
    $name = filter_input(INPUT_POST, 'name');
    $city = filter_input(INPUT_POST, 'city', FILTER_VALIDATE_INT);
    $wage = filter_input(INPUT_POST, 'wage', FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $language = filter_input(INPUT_POST, 'language', FILTER_VALIDATE_INT);
    $rating = filter_input(INPUT_POST, 'rating', FILTER_VALIDATE_INT);
    $detail = filter_input(INPUT_POST, 'detail');

    // リロード時の送信防止のため照合番号を確認
    if (isset($_REQUEST["chkno"]) && isset($_SESSION["chkno"]) && ($_REQUEST["chkno"] == $_SESSION["chkno"])) {

        $notification = $name . 'の情報が追加されました。';
        require_once '../pdo_connect.php';

        try {
            require_once '../pdo_connect.php';

            $dbh->beginTransaction();


            $stmt = $dbh->prepare('INSERT INTO job_data SET member_id=?, name=?, city_no=?, wage=?, language_no=?, rating=?, detail=?, created=NOW()+INTERVAL 9 HOUR');

            $stmt->bindValue(1, $member_id, PDO::PARAM_INT);
            $stmt->bindValue(2, $name, PDO::PARAM_STR);
            $stmt->bindValue(3, $city, PDO::PARAM_INT);
            $stmt->bindValue(4, $wage, PDO::PARAM_STR);
            $stmt->bindValue(5, $language, PDO::PARAM_INT);
            $stmt->bindValue(6, $rating, PDO::PARAM_INT);
            $stmt->bindValue(7, $detail, PDO::PARAM_LOB);
            $stmt->execute();


            // 掲示板に新着情報掲載
            $message = $dbh->prepare('INSERT INTO posts SET member_id=1, message=?, created=NOW()+INTERVAL 9 HOUR');
            $message->bindValue(1, $notification, PDO::PARAM_STR);
            $message->execute();


            $dbh->commit(); // 実行

            header('Location: ../bulletin_board/index.php');
            exit();
        } catch (PDOException $e) {
            $dbh->rollBack();
            echo $e->getMessage();
            exit();
        }
        $dbh = null;
    }
}

// 新しいトークンをセット
$_SESSION["chkno"] = $chkno = mt_rand();

?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">
    <title>仕事情報投稿</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/style.css">
    <!-- ファビコン -->
    <link rel="shortcut icon" href="../images/favicon.png" type="image/vnd.microsoft.icon">
    <link rel="icon" href="../images/favicon.png" type="image/vnd.microsoft.icon">
    <!-- font awesome -->
    <script src="https://kit.fontawesome.com/82342a278b.js" crossorigin="anonymous"></script>
</head>

<body>
    <!-- header -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#"></a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item active">
                        <a class="nav-link active" href="#">投稿フォーム<span class="sr-only">(current)</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../job_find/job_find.php">検索フォーム</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="../bulletin_board/index.php">掲示板</a>
                    </li>
                    <li class="nav-item dropdown active">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                            <?= $_SESSION['name'] ?>
                        </a>
                        <div class="dropdown-menu" aria-labelledby="navbarDropdownMenuLink">
                            <a class="dropdown-item" href="../logout.php">ログアウト</a>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- /header -->

    <!-- Page Content -->
    <div class="container">
        <div class="row">

            <div class="col-lg-7">
                <div class="card card-outline-secondary my-4">
                    <div class="card-header h4 py-3">
                        仕事情報投稿フォーム
                    </div>
                    <div class="card-body">
                        <form method="post">
                            <!-- リロード時の処置のためのhidden -->
                            <input name="chkno" type="hidden" value="<?= $chkno; ?>">

                            <div class="form-group">
                                <label for="Name">企業・店の名前<span class="text-danger"> *</span></label>
                                <input name="name" type="text" class="form-control form-control-sm" id="Name" value="<?= $name; ?>" autofocus required>
                            </div>

                            <div class="form-group">
                                <label for="City">都市<span class="text-danger"> *</span></label>
                                <select name="city" class="form-control form-control-sm" id="City" required>
                                    <option value="" disabled selected>選択してください</option>
                                    <?php foreach ($cities as $key => $value) : ?>
                                        <option value="<?= $key; ?>" <?php if ($city == "{$key}") echo 'selected' ?>><?= $value; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="Wage">時給 ($)<span class="text-danger">*</span></label>
                                <!-- 1以下の数字と0.1より細かい値は記入できない -->
                                <input type="number" placeholder="0.00" step="0.01" min="1" max="99" name="wage" class="form-control form-control-sm" id="Wage" value="<?= h($wage); ?>" required>
                            </div>

                            <div class="form-group">
                                <label for="Language">英語使用頻度<span class="text-danger"> *</span></label>
                                <select name="language" class="form-control form-control-sm" id="Language" required>
                                    <option value="" disabled selected>選択してください</option>
                                    <?php foreach ($languages as $key => $value) : ?>
                                        <option value="<?= $key; ?>" <?php if ($language == "{$key}") echo 'selected' ?>><?= $value; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="Rating">おすすめ度<span class="text-danger"> *</span></label>
                                <select name="rating" class="form-control form-control-sm" id="Rating" required>
                                    <option value="" disabled selected>選択してください</option>
                                    <?php for ($i = 1; $i <= 5; $i++) : ?>
                                        <option value="<?= $i; ?>" <?php if ($rating == "{$i}") echo 'selected' ?>><?= str_repeat('⭐️', $i) ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>

                            <div class="form-group">
                                <label for="Detail">詳細情報</label>
                                <textarea rows="6" cols="60" name="detail" class="form-control form-control-sm" placeholder="「場所や給与に関しての詳細」「実際に働いてみて感じたこと」などを自由にご記入下さい" id="Detail"><?= h($detail); ?></textarea>
                            </div>

                            <hr>
                            <button type="submit" class="button" name="post">情報を投稿する</button>
                            <button name="action" value="clear" type="button" class="clear-button btn btn-light float-right">リセット</button>
                        </form>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="card mt-5">
                    <h4 class="card-title-point">"詳細情報" に記入していただきたいこと</h4>
                    <div class="card-body font-small">
                        <p class="card-text">フォームに書くことができなかった情報をご記入ください。求職者にとって気になる項目の例はこちら</p>
                        <ul>
                            <li>働いてみて感じた職場の雰囲気</li>
                            <li>週何日、一日何時間のシフトか</li>
                            <li>職場へのアクセス</li>
                        </ul>
                        <p class="mb-0">ご協力いただきありがとうございます。</p>
                    </div>
                </div>

                <div class="card mt-5">
                    <h4 class="card-title-check">記入する上での注意点</h4>
                    <div class="card-body font-small">
                        <p class="card-text">情報を共有する上で・・</p>
                        <ul>
                            <li>実名などの個人のプライバシーに関すること</li>
                            <li>特定の企業様への批判</li>
                            <li>企業の秘密情報漏洩</li>
                        </ul>
                        <p class="mb-0">上記のような書き込みは避けるようお願い致します。見つけ次第、運営者が削除します。</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="py-5 bg-white mt-5">
        <div class="container">
            <p class="m-0 text-center text-secondary">Copyright &copy; WooJob 2021</p>
        </div>
    </footer>
    <!-- Optional JavaScript -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js" integrity="sha384-ZMP7rVo3mIykV+2+9J3UJ46jBk0WLaUAdn689aCwoqbBJiSnjAK/l8WvCWPIPm49" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js" integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous"></script>
    <script src="../js/form-reset.js"></script>
</body>

</html>