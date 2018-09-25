<?php 

session_start();

include "includes/db.php";

// if js worked
if(isset($_POST['post']) && isset($_POST['comment'])) {
	$post_id = (int)mysqli_real_escape_string($connection, $_POST['post']);
	$comment = mysqli_real_escape_string($connection, $_POST['comment']);
	$user_id = $_SESSION['user_id'];
	$username = $_SESSION['user'];

	$comment_query = "INSERT INTO comments(post_id, user_id, username, content) VALUES (?, ?, ?, ?)";

	$comment_stmt = mysqli_prepare($connection, $comment_query);
	mysqli_stmt_bind_param($comment_stmt, "iiss", $post_id, $user_id, $username, $comment);
	$result = mysqli_stmt_execute($comment_stmt);

	if ($result === false) {
		die("error in commenting");
	} else {
		echo "{$username}";
	}
}

