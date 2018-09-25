<?php 

include "includes/db.php";

function get_image($image_id) {
	global $connection;

	$image_query = "SELECT image FROM images WHERE image_id = $image_id";

	$image_result = mysqli_query($connection, $image_query);

	$row = mysqli_fetch_assoc($image_result);

	return $row['image'];			
}

function get_video($video_id) {
	global $connection;

	$video_query = "SELECT video FROM videos WHERE video_id = $video_id";

	$video_result = mysqli_query($connection, $video_query);

	$row = mysqli_fetch_assoc($video_result);

	return $row['video'];
}

if(isset($_GET['edit_id'])) {
	$edit_id = (int)mysqli_real_escape_string($connection, $_GET['edit_id']);
	$media_type = (int)mysqli_real_escape_string($connection, $_GET['media']);

	if($media_type != 3) {
		$edit_query = "SELECT title, caption, media_type, media_id FROM user_posts WHERE post_id = ? LIMIT 1";
	} else {
		$edit_query = "SELECT title, caption FROM user_posts WHERE post_id = ? LIMIT 1";
	}

	$edit_stmt = mysqli_prepare($connection, $edit_query);
	mysqli_stmt_bind_param($edit_stmt, "i", $edit_id);
	$result = mysqli_stmt_execute($edit_stmt);

	if ($result === false) {
		die("error in deleting");
	}

	$edit_result = mysqli_stmt_get_result($edit_stmt);

	$row = mysqli_fetch_assoc($edit_result);

	if($media_type != 3){
		if($row['media_type'] == 1) {
			$image = get_image($row['media_id']);
			$row['image'] = "post_images/" . $image;
		} else {
			$video = get_video($row['media_id']);
			$row['video'] = "post_videos/" . $video;
		}
	}

	echo json_encode($row);
}




