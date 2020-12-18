<?php
require_once '../function.php';

// データ取得ロジック呼び出し
// include_once('model.php');

// フォームで選ばれた$_GETの値を関数使用し検索
// $jobData = getJobData($_GET);

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

    <title>条件検索</title>
    <!-- Bootstrap読み込み -->

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/style.css">
</head>

<body>

    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top">
        <div class="container">
            <a class="navbar-brand" href="#">WooJob</a>
            <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ml-auto">
                    <li class="nav-item active">
                        <a class="nav-link" href="#">Home
                            <span class="sr-only">(current)</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Services</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Contact</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- Page Content -->
    <div class="container">

        <div class="row">

            <div class="col-lg-4">
                <div class="px-3">

                    <h1 class="">条件検索フォーム</h1>
                    <p class="my-4"><small>条件を指定し検索ボタンをクリックしてください</small></p>

                    <!-- 条件検索フォーム  -->
                    <form method="get">

                        <div class="form-group">
                            <label for="Name">企業・店の名前</label>
                            <input name="name" class="form-control form-control-sm" id="Name" value="<?= isset($_GET['name']) ? h($_GET['name']) : '' ?>">
                        </div>

                        <div class="form-group">
                            <label for="City">地域</label>
                            <select name="city" class="form-control form-control-sm" id="City">
                                <option value="0" <?= empty($_GET['city']) ? 'selected' : '' ?>>選択しない</option>
                                <option value="1" <?= isset($_GET['city']) && $_GET['city'] == '1' ? 'selected' : '' ?>>シドニー</option>
                                <option value="2" <?= isset($_GET['city']) && $_GET['city'] == '2' ? 'selected' : '' ?>>メルボルン</option>
                                <option value="3" <?= isset($_GET['city']) && $_GET['city'] == '3' ? 'selected' : '' ?>>ケアンズ</option>
                                <option value="4" <?= isset($_GET['city']) && $_GET['city'] == '4' ? 'selected' : '' ?>>ゴールドコースト</option>
                                <option value="5" <?= isset($_GET['city']) && $_GET['city'] == '5' ? 'selected' : '' ?>>ブリズベン</option>
                                <option value="6" <?= isset($_GET['city']) && $_GET['city'] == '6' ? 'selected' : '' ?>>パース</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="Wage">時給</label>
                            <select name="wage" class="form-control form-control-sm" id="Wage">
                                <option value="0" <?= empty($_GET['wage']) ? 'selected' : '' ?>>選択しない</option>
                                <option value="15" <?= isset($_GET['wage']) && $_GET['wage'] == '15' ? 'selected' : '' ?>>15ドル以上</option>
                                <option value="20" <?= isset($_GET['wage']) && $_GET['wage'] == '20' ? 'selected' : '' ?>>20ドル以上</option>
                                <option value="25" <?= isset($_GET['wage']) && $_GET['wage'] == '25' ? 'selected' : '' ?>>25ドル以上</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="Lang">英語使用頻度</label>
                            <select name="lang" class="form-control form-control-sm" id="Lang">
                                <option value="0" <?= empty($_GET['lang']) ? 'selected' : '' ?>>選択しない</option>
                                <option value="1" <?= isset($_GET['lang']) && $_GET['lang'] == '1' ? 'selected' : '' ?>>ほぼない</option>
                                <option value="2" <?= isset($_GET['lang']) && $_GET['lang'] == '2' ? 'selected' : '' ?>>たまに</option>
                                <option value="3" <?= isset($_GET['lang']) && $_GET['lang'] == '3' ? 'selected' : '' ?>>頻繁に</option>
                            </select>
                        </div>

                        <button type="submit" class="btn btn-outline-info btn-block mt-4" name="search">検索</button>
                    </form>

                </div>
            </div>
            <!-- /.col-lg-4 -->



            <div class="col-lg-8">

                <!-- <div class="card mt-4">
                    <img class="card-img-top img-fluid" src="http://placehold.it/900x400" alt="">
                    <div class="card-body">
                        <h3 class="card-title">Product Name</h3>
                        <h4>$24.99</h4>
                        <p class="card-text">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Sapiente dicta fugit fugiat hic aliquam itaque facere, soluta. Totam id dolores, sint aperiam sequi pariatur praesentium animi perspiciatis molestias iure, ducimus!</p>
                        <span class="text-warning">&#9733; &#9733; &#9733; &#9733; &#9734;</span>
                        4.0 stars
                    </div>
                </div> -->
                <!-- /.card -->

                <div class="card card-outline-secondary my-4">
                    <div class="card-header">
                        検索結果
                    </div>
                    <div class="card-body">


                        <!-- ヒットしたデータを表示する  -->
                        <?php if (isset($jobData) && count($jobData)) : ?>
                            <p class="alert alert-success"><?= count($jobData) ?>件見つかりました。</p>
                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>名前</th>
                                        <th>性別</th>
                                        <th>年齢</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($jobData as $row) : ?>
                                        <tr>
                                            <td><?= h($row['name']) ?></td>
                                            <td><?= h($row['city']) ?></td>
                                            <td><?= h($row['wage']) ?></td>
                                            <td><?= h($row['lang']) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        <?php else : ?>
                            <p class="alert alert-danger">検索対象は見つかりませんでした。</p>
                        <?php endif; ?>


                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Omnis et enim aperiam inventore, similique necessitatibus neque non! Doloribus, modi sapiente laboriosam aperiam fugiat laborum. Sequi mollitia, necessitatibus quae sint natus.</p>
                        <small class="text-muted">Posted by Anonymous on 3/1/17</small>
                        <hr>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Omnis et enim aperiam inventore, similique necessitatibus neque non! Doloribus, modi sapiente laboriosam aperiam fugiat laborum. Sequi mollitia, necessitatibus quae sint natus.</p>
                        <small class="text-muted">Posted by Anonymous on 3/1/17</small>
                        <hr>
                        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Omnis et enim aperiam inventore, similique necessitatibus neque non! Doloribus, modi sapiente laboriosam aperiam fugiat laborum. Sequi mollitia, necessitatibus quae sint natus.</p>
                        <small class="text-muted">Posted by Anonymous on 3/1/17</small>
                        <hr>
                        <a href="#" class="btn btn-success">Leave a Review</a>
                    </div>
                </div>
                <!-- /.card -->
            </div>
            <!-- /.col-lg-8 -->
        </div>
    </div>
    <!-- /.container -->

    <!-- Footer -->
    <footer class="py-5 bg-dark">
        <div class="container">
            <p class="m-0 text-center text-white">Copyright &copy; Your Website 2020</p>
        </div>
        <!-- /.container -->
    </footer>

    <!-- Bootstrap core JavaScript -->
    <script src="vendor/jquery/jquery.min.js"></script>
    <script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

</body>

</html>