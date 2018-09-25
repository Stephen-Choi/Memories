<?php 

include "includes/db.php"; 

function my_errorlog($msg) {
	error_log($msg . "\n", 3, "errors.log");
}

function get_media_id($post_id) {
	global $connection;

	my_errorlog($post_id);
	$media_query = "SELECT media_id FROM user_posts WHERE post_id = $post_id";
	$result = mysqli_query($connection, $media_query);

	if(!$result) {
		die("error retrieving media_id " . mysqli_error($result));
	}

	my_errorlog("worked");

	$row = mysqli_fetch_assoc($result);
	return $row['media_id'];
}

function del_img($image_id) {
	global $connection;

	$image = "";

	$get_image_query = "SELECT image FROM images WHERE image_id = $image_id";
	$image_result = mysqli_query($connection, $get_image_query);

	if(!$image_result) {
		die("erorr getting image " . mysqli_error($image_result));
	} else {
		$row = mysqli_fetch_assoc($image_result);
		$image = $row['image'];
	}

	$delete_query = "DELETE FROM images WHERE image_id = $image_id";
	$del_result = mysqli_query($connection, $delete_query);

	if(!$del_result) {
		die("error deleting image " . mysqli_error($del_result));
	}

	$target_file = "post_images/";
	$target_file .= $image;

	$img_del = unlink($target_file);

	if(!$img_del) {
		die("failed to delete image from folder");
	}
}

function del_vid($video_id) {
	global $connection;
	
	$video = "";

	$get_video_query = "SELECT video FROM videos WHERE video_id = $video_id";
	$video_result = mysqli_query($connection, $get_video_query);

	if(!$video_result) {
		die("erorr getting image " . mysqli_error($video_result));
	} else {
		$row = mysqli_fetch_assoc($video_result);
		$video = $row['video'];
	}

	$delete_query = "DELETE FROM videos WHERE video_id = $video_id";
	$del_result = mysqli_query($connection, $delete_query);

	if(!$del_result) {
		die("error deleting video " . mysqli_error($del_result));
	}

	$target_file = "post_videos/";
	$target_file .= $video;

	$video_del = unlink($target_file);

	if(!$video_del) {
		die("failed to delete video from folder");
	}
}

if(isset($_POST['post_id'])) {
	$post_id = (int)mysqli_real_escape_string($connection, $_POST['post_id']);
	$media = (int)mysqli_real_escape_string($connection, $_POST['media']);

	// reference to media
	$media_id = get_media_id($post_id);

	my_errorlog("media_id " . $media_id);
	my_errorlog("media " .$media);
	my_errorlog("post_id " .$post_id);

	$delete_query = "DELETE FROM user_posts WHERE post_id = ? LIMIT 1";

	$delete_stmt = mysqli_prepare($connection, $delete_query);
	mysqli_stmt_bind_param($delete_stmt, "i", $post_id);
	$result = mysqli_stmt_execute($delete_stmt);

	if ($result === false) {
		die("error in deleting");
	}

	// delete content from folders if image or video 

	//images
	if($media == 1) {
		del_img($media_id);
	} else if($media == 2) {
		del_vid($media_id);
	} 
}
