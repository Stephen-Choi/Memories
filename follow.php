<?php include "includes/db.php"; ?>

<?php 

if(isset($_POST['followstate'])) {
	$following = (int)mysqli_real_escape_string($connection, $_POST['followstate']);
	$user_id = (int)mysqli_real_escape_string($connection, $_POST['user_id']);
	$follow_id = (int)mysqli_real_escape_string($connection, $_POST['follow_id']);

	if($following) {
		// unfollow = delete entry from table 
		$unfollow_query = "DELETE FROM following_users WHERE user_id = $user_id AND following_id = $follow_id LIMIT 1";

		$unfollow_result = mysqli_query($connection, $unfollow_query);

		if(!$unfollow_result) {
			die("failed to unfollow " . mysqli_error($unfollow_result));
		}

		echo "unfollowed";
	} else {
		// follow
		$follow_query = "INSERT INTO following_users(user_id, following_id) VALUES ('$user_id', '$follow_id')";

		$follow_result = mysqli_query($connection, $follow_query);

		if(!$follow_result) {
			die("failed to follow " . mysqli_error($follow_result));
		}

		echo "followed";
	}
}