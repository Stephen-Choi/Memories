<?php 

session_start();

include "includes/db.php";

$user_id = $_SESSION['user_id'];

define('MB', 1048576);

function get_user_page_id($user_id, $type) {
	global $connection; 

	$home_query = "SELECT page_id FROM user_page WHERE user_id = $user_id AND type = '$type'";

	$result = mysqli_query($connection, $home_query);

	if(!$result) {
		die("Failed to retrieve home " . mysqli_error($result));
	}

	$row = mysqli_fetch_assoc($result);

	return $row['page_id'];
}

function get_userid($username) {
	global $connection;

	$query = "SELECT user_id FROM users WHERE username = '$username'";

	$result = mysqli_query($connection, $query);

	if(!$result) {
		die("failed " . mysqli_error($result));
	}

	$row = mysqli_fetch_assoc($result);

	return $row['user_id']; 
}

function get_post_content($post_id) {
	global $connection;

	$post_content_query = "SELECT title, caption, media_type, media_id FROM user_posts WHERE post_id = $post_id";

	$post_content_result = mysqli_query($connection, $post_content_query);

	if(!$post_content_result) {
		die("failed to retrieve post contents " . mysqli_error($post_content_result));
	}

	$row = mysqli_fetch_assoc($post_content_result);

	return $row;
}

// insert into like 
if(isset($_POST['liked_post'])) {
	$post_id = mysqli_real_escape_string($connection, $_POST['liked_post']);
	$user_id = $_SESSION['user_id'];
	$username = $_SESSION['user'];
	$fullname = $_SESSION['fullname'];
	$like_query = "INSERT INTO likes(user_id, username, fullname, post_id) VALUES ('$user_id', '$username', '$fullname', '$post_id')";

	$like_result = mysqli_query($connection, $like_query);

	if(!$like_result) {
		die("failed to like " . mysqli_error($like_result));
	}
} 

// delete like 
if(isset($_POST['unliked_post'])) {
	$post_id = mysqli_real_escape_string($connection, $_POST['unliked_post']);

	$like_query = "DELETE FROM likes WHERE post_id = $post_id";

	$unlike_result = mysqli_query($connection, $like_query);

	if(!$unlike_result) {
		die("failed to like " . mysqli_error($unlike_result));
	}
}

// get all likes on a post 
if(isset($_GET['view_likes_post'])) {
	$post_id = (int)mysqli_real_escape_string($connection, $_GET['view_likes_post']);

	$get_likes = "SELECT user_id, username, fullname FROM likes WHERE post_id = $post_id";

	$get_likes_result = mysqli_query($connection, $get_likes);

	if(!$get_likes_result) {
		die("failed to get likes " . mysqli_error($get_likes_result));
	} else {
		$like_display = "";

		while($row = mysqli_fetch_assoc($get_likes_result)) {

		$user_home = get_user_page_id($row['user_id'], "main");

		$like_display .= "	
			<div class='col-sm-12'>
				<a href='user_home.php?page={$user_home}'>
					<div class='like_div_items'>
						<img src='images/default-user.png' class='rounded-circle search_profile_avatar'>
						<b><p class='search_username'>{$row['username']}</p></b>
						<p class='search_name'>{$row['fullname']}</p>
					</div>
				</a>
			</div>
			";
		}
		echo $like_display;
	}
}

// generating options of user owned pages 
if(isset($_GET['pin_user'])) {
	$username = mysqli_real_escape_string($connection, $_GET['pin_user']);

	$user_id = (int)get_userid($username);

	$get_posts_query = "SELECT page_id, type FROM user_page WHERE user_id = $user_id";

	$get_post_result = mysqli_query($connection, $get_posts_query);

	if(!$get_post_result) {
		die("failed to get posts for pin " . mysqli_error($get_post_result));
	} else {
		$page_options = "";
		while($row = mysqli_fetch_assoc($get_post_result)) {
			$type = $row['type'];
			$page = $row['page_id'];
			$page_options .= "
				<option value={$page}>{$type}</option>
			";
		}
		echo $page_options;
	}
}

//copying over post to user's selected page 
if(isset($_POST['pin_submit'])) {
	$user_page = (int)mysqli_real_escape_string($connection, $_POST['dest_page']);
	$copy_post = (int)mysqli_real_escape_string($connection, $_POST['pin_post_id']);
	
	// get contents of post 
	$copy = get_post_content($copy_post);

	$user_id = $_SESSION['user_id'];
	$username = $_SESSION['user'];
	$title = $copy['title'];
	$caption = $copy['caption'];
	$media_type = $copy['media_type'];
	$media_id = $copy['media_id'];

	$copy_query = "INSERT INTO user_posts(user_id, username, page_id, title, caption, media_type, media_id) VALUES(?, ?, ?, ?, ?, ?, ?)";

	$copy_stmt = mysqli_prepare($connection, $copy_query);

	mysqli_stmt_bind_param($copy_stmt, "isissii", $user_id, $username, $user_page, $title, $caption, $media_type, $media_id);

	$result = mysqli_stmt_execute($copy_stmt);

	if(!$result) {
		die("failed to insert pinned post " . mysqli_error($connection));
	} else {
		header("Location: user_home.php?page={$user_page}");
	}
}

if(isset($_POST['profile_submit'])) {
	if(isset($_FILES['profile_img']) && !(empty($_FILES['profile_img']['tmp_name']))) {

		$success = 1;

		// check if file is an image
		$check = exif_imagetype($_FILES["profile_img"]["tmp_name"]);
		if($check == false) {
			echo "File is not an image ";
			$success = 0;
		}

		if($_FILES["profile_img"]["size"] > 5*MB) {
		    echo "Sorry, your image size is too large. ";
		    $success = 0;
		}

		if($success == 0) {
			echo "failed";
		} else {
			$image = md5(uniqid($_FILES["profile_img"]["name"], true));
			$extension = pathinfo($_FILES["profile_img"]["name"], PATHINFO_EXTENSION);
			$image .= "." . $extension;
			$image_temp = $_FILES["profile_img"]["tmp_name"];
			$dir = "profile_images/";
			$target_file = $dir . basename($image);

			// saves copy of file into my directory
			if(move_uploaded_file($image_temp, $target_file)) {

				$upload_profile_query = "UPDATE users SET avatar = '$image' WHERE user_id = $user_id";

				$upload_profile_result = mysqli_query($connection, $upload_profile_query);

				if(!$upload_profile_result) {
					die("upload profile failed " . mysqli_error($upload_profile_result));
				} else {
					// handle post 
					$bio = mysqli_real_escape_string($connection, $_POST['profile_bio']);
				
					$upload_profile_post_query = "UPDATE users set description = ? WHERE user_id = $user_id";

					$upload_stmt = mysqli_prepare($connection, $upload_profile_post_query);

					if($upload_stmt === FALSE) { 
						die(mysqli_error($connection)); 
					}

					mysqli_stmt_bind_param($upload_stmt, 's', $bio);

					$result = mysqli_stmt_execute($upload_stmt); 

					if(!$result) {
						die("error " . mysqli_stmt_error($upload_stmt));
					}

					header("Location: user_home.php?page={$page_id}");
				}
			}
		}
	} else if(isset($_POST['profile_bio'])){
		$bio = mysqli_real_escape_string($connection, $_POST['profile_bio']);
				
		$upload_profile_post_query = "UPDATE users set description = ? WHERE user_id = $user_id";

		$upload_stmt = mysqli_prepare($connection, $upload_profile_post_query);

		if($upload_stmt === FALSE) { 
			die(mysqli_error($connection)); 
		}

		mysqli_stmt_bind_param($upload_stmt, 's', $bio);

		$result = mysqli_stmt_execute($upload_stmt); 

		if(!$result) {
			die("error " . mysqli_stmt_error($upload_stmt));
		}

		header("Location: user_home.php?page={$page_id}");
	} else {
		header("Location: user_home.php?page={$page_id}");
	}
}





