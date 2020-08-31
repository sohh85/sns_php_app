<?php
session_start();
require_once 'pdo_connect.php';
require_once 'function.php';

if (!isset($_SESSION['join'])) {
	header('Location: index.php');
	exit();
}

if (!empty($_POST)) {
	header('Location: thanks.php');
	$statement = $dbh->prepare('INSERT INTO members SET name=?, email=?, password=?, picture=?, created=NOW()');
	echo $statement->execute(array(
		$_SESSION['join']['name'],
		$_SESSION['join']['email'],
		sha1($_SESSION['join']['password']),
		$_SESSION['join']['image']
	));
	unset($_SESSION['join']);

	exit();
}
?>
<!DOCTYPE html>
<html lang="ja">

<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>会員登録</title>

	<link rel="stylesheet" href="../style.css" />
</head>

<body>
	<div id="wrap">
		<div id="head">
			<h1>会員登録</h1>
		</div>

		<div id="content">
			<p>記入した内容を確認して、「登録する」ボタンをクリックしてください</p>
			<form action="" method="post">
				<input type="hidden" name="action" value="submit" />
				<dl>
					<dt>ニックネーム</dt>
					<?php echo (h($_SESSION['join']['name'])); ?>
					<dd>
					</dd>
					<dt>メールアドレス</dt>
					<?php echo (h($_SESSION['join']['email'])); ?>
					<dd>
					</dd>
					<dt>パスワード</dt>
					<dd>
						【表示されません】
					</dd>
					<dt>写真など</dt>
					<dd>
						<?php if ($_SESSION['join']['image'] !== '') : ?>
							<img src="member_picture/<?php echo (h($_SESSION['join']['image'])); ?>" style="width:200px;">
						<?php endif; ?>
					</dd>
				</dl>
				<div><a href=" index.php?action=rewrite">&laquo;&nbsp;書き直す</a> | <input type="submit" value="登録する" />
				</div>
			</form>
		</div>

	</div>
</body>

</html>