<?php
//host名は,docker-compose.ymlのMySQLのservice名

// ドライバ呼び出しを使用して MySQL データベースに接続します
$dsn = 'mysql:host=db;dbname=php_app_db;charset=utf8';
//dbname = データベース名
//host = ホスト名またはIPアドレス
$user = 'php_learner';
$password = 'php_app';


try {
    $dbh = new PDO($dsn, $user, $password);
} catch (PDOException $e) {
    var_dump($dbh);
    echo "接続失敗: " . $e->getMessage() . "\n";
    exit();
}
