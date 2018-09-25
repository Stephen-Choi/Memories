<?php 

// mb size
define('MB', 1048576);

if(isset($_POST['image_submit'])) {

	if(isset($_FILES['upload_image'])) {

		$success = 1;

		// check if file is an image
		$check = exif_imagetype($_FILES["upload_image"]["tmp_name"]);
		if($check == false) {
			echo "File is not an image ";
			$success = 0;
		}

		if($_FILES["upload_image"]["size"] > 5*MB) {
		    echo "Sorry, your image size is too large. ";
		    $success = 0;
		}

		if($success == 0) {
			echo "failed";
		} else {
			$image = md5(uniqid($_FILES["upload_image"]["name"], true));
			$extension = pathinfo($_FILES["upload_image"]["name"], PATHINFO_EXTENSION);
			$image .= "." . $extension;
			$image_temp = $_FILES["upload_image"]["tmp_name"];
			$dir = "post_images/";
			$target_file = $dir . basename($image);

			// saves copy of file into my directory
			if(move_uploaded_file($image_temp, $target_file)) {

				$upload_image_query = "INSERT INTO images(image) VALUES ('$image')";

				$upload_image_result = mysqli_query($connection, $upload_image_query);

				if(!$upload_image_result) {
					die("upload failed " . mysqli_error($upload_image_result));
				} else {
					// handle post 
					$caption = mysqli_real_escape_string($connection, $_POST['image_caption']);
					$title = mysqli_real_escape_string($connection, $_POST['image_title']);
					$image_id = get_image_id($image);
					$user_id = $_SESSION['user_id'];
					$user = $_SESSION['user'];
					$media_type = 1;

					$upload_image_post_query = "INSERT INTO user_posts(user_id, username, page_id, title, caption, media_type, media_id) ";
					$upload_image_post_query .= "VALUES (?, ?, ?, ?, ?, ?, ?)";

					$upload_stmt = mysqli_prepare($connection, $upload_image_post_query);

					if($upload_stmt === FALSE) { 
						die(mysqli_error($connection)); 
					}

					mysqli_stmt_bind_param($upload_stmt, 'isissii', $user_id, $user, $page_id, $title, $caption, $media_type, $image_id);

					$result = mysqli_stmt_execute($upload_stmt); 

					if(!$result) {
						die("error " . mysqli_stmt_error($upload_stmt));
					}

					header("Location: user_home.php?page={$page_id}");
				}
			}
		}
	} else {
		echo "nothing to submit";
	}
}

if(isset($_POST['video_submit'])) {
	if(isset($_FILES['upload_video'])) {
		// check if video 
		$finfo = finfo_open(FILEINFO_MIME_TYPE);
		$mime = finfo_file($finfo, $_FILES['upload_video']['tmp_name']);

		if(preg_match('/video\/*/', $mime) || preg_match('/audio\/*/', $mime)) {
			
			$success = 1; 
			finfo_close($finfo);

			if($_FILES["upload_video"]["size"] > 10*MB) {
			    echo "Sorry, your video size is too large. ";
			    $success = 0;
			}

			if($success == 0) {
				echo "failed";
			} else {
				$video = md5(uniqid($_FILES["upload_video"]["name"], true));
				$extension = pathinfo($_FILES["upload_video"]["name"], PATHINFO_EXTENSION);
				$video .= "." . $extension;
				$video_temp = $_FILES["upload_video"]["tmp_name"];
				$dir = "post_videos/";
				$target_file = $dir . basename($video);

				// saves copy of file into my directory
				if(move_uploaded_file($video_temp, $target_file)) {
					$video_query = "INSERT INTO videos(video) VALUES('$video')";
					$video_result = mysqli_query($connection, $video_query);

					if(!$video_result) {
						die("failed " . mysqli_error($video_result));
					} else {
						// handle post 
						$caption = mysqli_real_escape_string($connection, $_POST['video_caption']);
						$title = mysqli_real_escape_string($connection, $_POST['video_title']);
						$video_id = get_video_id($video);
						$user_id = $_SESSION['user_id'];
						$user = $_SESSION['user'];
						$media_type = 2;

						$upload_video_post_query = "INSERT INTO user_posts(user_id, username, page_id, title, caption, media_type, media_id) ";
						$upload_video_post_query .= "VALUES (?, ?, ?, ?, ?, ?, ?)";

						$upload_stmt = mysqli_prepare($connection, $upload_video_post_query);

						if($upload_stmt === FALSE) { 
							die(mysqli_error($connection)); 
						}

						mysqli_stmt_bind_param($upload_stmt, 'isissii', $user_id, $user, $page_id, $title, $caption, $media_type, $video_id);

						$result = mysqli_stmt_execute($upload_stmt); 

						if(!$result) {
							die("error " . mysqli_stmt_error($upload_stmt));
						}

						header("Location: user_home.php?page={$page_id}");
					}
				}
			}
		} else {
			echo "file is not a video";
		}
	} else {
		echo "nothing to submit";
	}
}

if(isset($_POST['text_submit'])) {
	$title = mysqli_real_escape_string($connection, $_POST['text_title']);
	$message = mysqli_real_escape_string($connection, $_POST['message']);
	$user_id = $_SESSION['user_id'];
	$user = $_SESSION['user'];
	$media_type = 3;

	$text_query = "INSERT INTO user_posts(user_id, username, page_id, title, caption, media_type) VALUES (?, ?, ?, ?, ?, ?)";
	$text_stmt = mysqli_prepare($connection, $text_query);

	if($text_stmt === FALSE) {
		die(mysqli_error($connection));
	}

	mysqli_stmt_bind_param($text_stmt, "isissi", $user_id, $user, $page_id, $title, $message, $media_type);

	$result = mysqli_stmt_execute($text_stmt);

	if(!$result) {
		die("error " . mysqli_stmt_error($text_stmt));
	}

	header("Location: user_home.php?page={$page_id}");
}

// UPDAITNG FEATURES

//image edit
if(isset($_POST['edit_image_submit'])) {

	if(isset($_FILES['edit_image']) && !(empty($_FILES['edit_image']['tmp_name']))) {

		$success = 1;

		// check if file is an image
		$check = exif_imagetype($_FILES["edit_image"]["tmp_name"]);
		if($check == false) {
			echo "File is not an image ";
			$success = 0;
		}

		if($_FILES["edit_image"]["size"] > 5*MB) {
		    echo "Sorry, your image size is too large. ";
		    $success = 0;
		}

		if($success == 0) {
			echo "failed";
		} else {
			$image = md5(uniqid($_FILES["edit_image"]["name"], true));
			$extension = pathinfo($_FILES["edit_image"]["name"], PATHINFO_EXTENSION);
			$image .= "." . $extension;
			$image_temp = $_FILES["edit_image"]["tmp_name"];
			$dir = "post_images/";
			$target_file = $dir . basename($image);

			// saves copy of file into my directory
			if(move_uploaded_file($image_temp, $target_file)) {

				// retrieve image data 
				$edit_image_id = (int)mysqli_real_escape_string($connection, $_POST['image_id']);

				$cur_img = get_image($edit_image_id);

				unlink("post_images/" . $cur_img);

				$edit_image_query = "UPDATE images SET image = '$image' WHERE image_id = $edit_image_id";

				$edit_image_result = mysqli_query($connection, $edit_image_query);

				if(!$edit_image_result) {
					die("upload failed " . mysqli_error($upload_image_result));
				} else {
					// handle post 
					$caption = mysqli_real_escape_string($connection, $_POST['edit_image_message']);
					$title = mysqli_real_escape_string($connection, $_POST['edit_image_title']);
					$post_id = mysqli_real_escape_string($connection, $_POST['post_id']);
					$image_id = get_image_id($image);

					$update_image_query = "UPDATE user_posts SET title = ?, caption = ?, media_id = ? WHERE post_id = ?";

					$update_stmt = mysqli_prepare($connection, $update_image_query);

					if($update_stmt === FALSE) { 
						die(mysqli_error($connection)); 
					}

					mysqli_stmt_bind_param($update_stmt, 'ssii', $title, $caption, $image_id, $post_id);

					$result = mysqli_stmt_execute($update_stmt); 

					if(!$result) {
						die("error " . mysqli_stmt_error($update_stmt));
					}

					header("Location: user_home.php?page={$page_id}");
				}
			}
		}
	} else {
		$caption = mysqli_real_escape_string($connection, $_POST['edit_image_message']);
		$title = mysqli_real_escape_string($connection, $_POST['edit_image_title']);
		$post_id = mysqli_real_escape_string($connection, $_POST['post_id']);

		$update_image_query = "UPDATE user_posts SET title = ?, caption = ? WHERE post_id = ?";

		$update_stmt = mysqli_prepare($connection, $update_image_query);

		if($update_stmt === FALSE) { 
			die(mysqli_error($connection)); 
		}

		mysqli_stmt_bind_param($update_stmt, 'ssi', $title, $caption, $post_id);

		$result = mysqli_stmt_execute($update_stmt); 

		if(!$result) {
			die("error " . mysqli_stmt_error($update_stmt));
		}

		header("Location: user_home.php?page={$page_id}");
	}
}

// video edit
if(isset($_POST['edit_video_submit'])) {
	if(isset($_FILES['edit_video']) && !(empty($_FILES['edit_video']['tmp_name']))) {
		// check if video 
		$finfo = finfo_open(FILEINFO_MIME_TYPE);
		$mime = finfo_file($finfo, $_FILES['edit_video']['tmp_name']);

		if(preg_match('/video\/*/', $mime) || preg_match('/audio\/*/', $mime)) {
			
			$success = 1; 
			finfo_close($finfo);

			if($_FILES["edit_video"]["size"] > 10*MB) {
			    echo "Sorry, your video size is too large. ";
			    $success = 0;
			}

			if($success == 0) {
				echo "failed";
			} else {
				$video = md5(uniqid($_FILES["edit_video"]["name"], true));
				$extension = pathinfo($_FILES["edit_video"]["name"], PATHINFO_EXTENSION);
				$video .= "." . $extension;
				$video_temp = $_FILES["edit_video"]["tmp_name"];
				$dir = "post_videos/";
				$target_file = $dir . basename($video);

				// saves copy of file into my directory
				if(move_uploaded_file($video_temp, $target_file)) {

					$edit_video_id = (int)mysqli_real_escape_string($connection, $_POST['video_id']);

					$cur_video = get_video($edit_video_id);

					unlink("post_videos/" . $cur_video);

					$edit_video_query = "UPDATE videos SET video = '$video' WHERE video_id = $edit_video_id";

					$edit_video_result = mysqli_query($connection, $edit_video_query);

					if(!$edit_video_result) {
						die("failed " . mysqli_error($edit_video_result));
					} else {
						// handle update
					$caption = mysqli_real_escape_string($connection, $_POST['edit_video_message']);
					$title = mysqli_real_escape_string($connection, $_POST['edit_video_title']);
					$post_id = mysqli_real_escape_string($connection, $_POST['post_id']);
					$video_id = get_video_id($video);

					$update_video_query = "UPDATE user_posts SET title = ?, caption = ?, media_id = ? WHERE post_id = ?";

					$update_stmt = mysqli_prepare($connection, $update_video_query);

					if($update_stmt === FALSE) { 
						die(mysqli_error($connection)); 
					}

					mysqli_stmt_bind_param($update_stmt, 'ssii', $title, $caption, $video_id, $post_id);

					$result = mysqli_stmt_execute($update_stmt); 

					if(!$result) {
						die("error " . mysqli_stmt_error($update_stmt));
					}

					header("Location: user_home.php?page={$page_id}");
				}
			}
		}
	} else {
			echo "invalid file type";
	}
} else {
		$caption = mysqli_real_escape_string($connection, $_POST['edit_video_message']);
		$title = mysqli_real_escape_string($connection, $_POST['edit_video_title']);
		$post_id = mysqli_real_escape_string($connection, $_POST['post_id']);

		$update_video_query = "UPDATE user_posts SET title = ?, caption = ? WHERE post_id = ?";

		$update_stmt = mysqli_prepare($connection, $update_video_query);

		if($update_stmt === FALSE) { 
			die(mysqli_error($connection)); 
		}

		mysqli_stmt_bind_param($update_stmt, 'ssi', $title, $caption, $post_id);

		$result = mysqli_stmt_execute($update_stmt); 

		if(!$result) {
			die("error " . mysqli_stmt_error($update_stmt));
		}

		header("Location: user_home.php?page={$page_id}");
	}
}

// text edit
if(isset($_POST['edit_text_submit'])) {
	$title = mysqli_real_escape_string($connection, $_POST['edit_text_title']);
	$message = mysqli_real_escape_string($connection, $_POST['edit_text_message']);
	$post_id = mysqli_real_escape_string($connection, $_POST['post_id']);

	$edit_text_query = "UPDATE user_posts SET title = ?, caption = ? WHERE post_id = ?";

	$update_stmt = mysqli_prepare($connection, $edit_text_query);

	if($update_stmt === FALSE) { 
		die(mysqli_error($connection)); 
	}

	mysqli_stmt_bind_param($update_stmt, "ssi", $title, $message, $post_id);

	$result = mysqli_stmt_execute($update_stmt);

	if(!$result) {
		die("error " . mysqli_stmt_error($update_stmt));
	}

	header("Location: user_home.php?page={$page_id}");
}


// privacy 

if(isset($_POST['privacy_submit'])) {

	$privacy = $_POST['privacy'];

	// make public
	if($privacy == 'public') {

		$public_query = "UPDATE user_page SET status = 'public' WHERE page_id = $page_id";

		$public_result = mysqli_query($connection, $public_query);

		if(!$public_result) {
			die("failed to update public settings " . mysqli_error($public_result));
		}

	} else {

		$private_query = "UPDATE user_page SET status = 'private' WHERE page_id = $page_id";

		$private_result = mysqli_query($connection, $private_query);

		if(!$private_result) {
			die("failed to update private settings " . mysqli_error($private_result));
		}
	}
}







?>