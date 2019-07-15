<?php
session_start();
require('./dbconnect.php');
if (isset($_SESSION['id']) && $_SESSION['time'] + 3600 > time()) {
	// log on
	$_SESSION['time'] = time();
	$members = $db->prepare('SELECT * FROM members WHERE id=?');
	$members->execute(array($_SESSION['id']));
	$member = $members->fetch();
} else {
	// No log on
	header('Location: login.php');
	exit();
}
// record message
//　print($member['id']);
//　print($_POST['reply_post_id']);
if (!empty($_POST)) {
	print($_POST['message']);
	if ($_POST['message'] != '') {
		$message = $db->prepare('INSERT INTO posts SET member_id=?, message=?, created=NOW()');
		$message->execute(array(
			$member['id'],
			$_POST['message'],
			// $_POST['reply_post_id']
		));
		header('Location: index.php'); exit();
	}
}

// read record!
$posts = $db->query('SELECT m.name, m.picture, p.* FROM members m, posts p WHERE m.id=p.member_id ORDER BY p.created DESC');

// res
if (isset($_REQUEST['res'])) {
	$response = $db->prepare('SELECT m.name, m.picture, p.* FROM members m,	posts p WHERE m.id=p.member_id AND p.id=? ORDER BY p.created DESC');
	$response->execute(array($_REQUEST['res']));

	$table = $response->fetch();
	$message = '@' . $table['name'] . ' ' . $table['message'];
}

// htmlspecialchars short cut!
function h($value) {
	return htmlspecialchars($value, ENT_QUOTES);
}

//　URL support
function makeLink($value) {
	return mb_ereg_replace("(https?)(://[[:alnum:]\+\$\;\?\.%,!#~*/:@&=_-]+)",'<a href="\1\2">\1\2</a>', $value);
}

?>
<!DOCTYPE html>
<html lang="ja">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta http-equiv="X-UA-Compatible" content="ie=edge">
	<title>Message Board</title>

	<link rel="stylesheet" href="style.css" />
</head>

<body>
<div id="wrap">
  <div id="head">
    <h1>Message Board</h1>
  </div>
  <div id="content">
	  <div style="text-aligin: right"><a href="logout.php">ログアウト</a></div>
		<form action="" method="post">
		<dl>
			<dt><?php echo h($member['name']); ?>さん、メッセージをどうぞ</dt>
		<dd>
		<textarea name="message" cols="50" rows="5"><?php echo h($message); ?></textarea>
		<!-- <input type="hidden" name="reply_post_id" value="<?php // echo htmlspecialchars($_REQUEST['res'], ENT_QUOTES); ?>" /> -->
		</dd>
		</dl>
		<div>
		<input type="submit" value="投稿する" />
		</div>
		</form>

		<?php
		foreach ($posts as $post):
		?>
		<div class="msg">
			<img src="./join/member_picture/<?php echo h($post['picture']); ?>" width="48" height="48" alt="<?php echo h($post['name']); ?>" />
			<p><?php echo makeLink(h($post['message'])); ?><span class="name"> (<?php echo h($post['name']); ?>) </span>
		<!--[<a href="index.php?res=<?php // echo htmlspecialchars($post['id'], ENT_QUOTES); ?>">Re</a>] --></p>
			<p class="day">
				<a href="view.php?id=<?php echo h($post['id']); ?>">
				<?php echo h($post['created']); ?>
				</a>
				<?php
				if ($_SESSION['id'] == $post['member_id']):
					?>
					[<a href="delete.php?id=<?php echo h($post['id']); ?>" style="color:#F33;">削除</a>]
					<?php
					endif;
					?>
			</p>
		</div>
		<?php
		endforeach
		?>
  </div>

</div>
</body>
</html>
