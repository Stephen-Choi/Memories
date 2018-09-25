<?php 

//constants

function my_errorlog($msg) {
	error_log($msg . "\n", 3, "errors.log");
}

//check if username exists 
function username_exists($username) {
	global $connection;

	$username_exists_query = "SELECT username FROM users WHERE username = ?";

	$username_stmt = mysqli_prepare($connection, $username_exists_query);
	mysqli_stmt_bind_param($username_stmt, "s", $username);
	mysqli_stmt_execute($username_stmt);
	mysqli_stmt_store_result($username_stmt);

	if (mysqli_stmt_num_rows($username_stmt) > 0) {
		mysqli_stmt_close($username_stmt);
		return true;
	} else {
		mysqli_stmt_close($username_stmt);
		return false;
	}
}

function invalid_username($username) {
	if (preg_match('/[\'^()}{~?><>,|=_+¬-]/', $username) || preg_match('/\s/',$username)) {
		return true;
	} else {
		return false; 
	}
}

function get_user_pass($username) {
	global $connection;

	$user_query = "SELECT password FROM users WHERE username = ?";

	$user_stmt = mysqli_prepare($connection, $user_query);
	mysqli_stmt_bind_param($user_stmt, "s", $username);
	mysqli_execute($user_stmt);
    mysqli_stmt_bind_result($user_stmt, $password);
    mysqli_stmt_fetch($user_stmt);

    return $password;

    /* close statement */
    mysqli_stmt_close($stmt);
}

function email_exists($email) {
	global $connection;

	$email_exists_query = "SELECT email FROM users WHERE email = ?";

	$email_stmt = mysqli_prepare($connection, $email_exists_query);
	mysqli_stmt_bind_param($email_stmt, "s", $email);
	mysqli_stmt_execute($email_stmt);
	mysqli_stmt_store_result($email_stmt);

	if (mysqli_stmt_num_rows($email_stmt) > 0) {
		mysqli_stmt_close($email_stmt);
		return true;
	} else {
		mysqli_stmt_close($email_stmt);
		return false;
	}

	mysqli_stmt_close($email_stmt);
}

function password_length_short($password) {

	if(strlen($password) < 8) {
		return true;
	} else {
		return false;
	}
}

function invalid_password($password) {
	if (preg_match('/[\'^()}{~?><>,|=_+¬-]/', $password) || preg_match('/\s/',$password)) {
		return true;
	} else {
		return false; 
	}
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

function get_fullname($username) {
	global $connection;

	$query = "SELECT fullname FROM users WHERE username = '$username'";

	$result = mysqli_query($connection, $query);

	if(!$result) {
		die("failed " . mysqli_error($result));
	}

	$row = mysqli_fetch_assoc($result);

	return $row['fullname']; 
}

function register_user($username, $email, $firstname, $lastname, $password) {
	global $connection;

	$fullname = $firstname . " " . $lastname;

	$query = "INSERT INTO users(username, email, firstname, lastname, fullname, password) VALUES (?, ?, ?, ?, ?, ?)";

	$stmt = mysqli_prepare($connection, $query);
	mysqli_stmt_bind_param($stmt, "ssssss", $username, $email, $firstname, $lastname, $fullname, $password);
	$result = mysqli_stmt_execute($stmt); 

	if(!result) {
		die("error " . mysqli_stmt_error($stmt));
	} else {
		$_SESSION['user'] = $username;
		$_SESSION['user_id'] = get_userid($username);
		$user_id = $_SESSION['user_id'];

		// create a home page for new user
		$create_home_query = "INSERT INTO user_page(user_id, type) VALUES ('$user_id', 'main')";

		$result = mysqli_query($connection, $create_home_query);

		if(!$result) {
			die("error creating home page " . mysqli_error($result));
		}

		header('Location: index.php');
	}
}

// FUNCTIONS FOR UPLOADING CONTENT

function get_image_id($image) {
	global $connection;

	$image_query = "SELECT image_id FROM images WHERE image = '$image'";

	$image_result = mysqli_query($connection, $image_query);

	$row = mysqli_fetch_assoc($image_result);

	return $row['image_id'];
}

function get_image($image_id) {
	global $connection;

	$image_query = "SELECT image FROM images WHERE image_id = $image_id";

	$image_result = mysqli_query($connection, $image_query);

	$row = mysqli_fetch_assoc($image_result);

	return $row['image'];			
}

function get_video_id($video) {
	global $connection;

	$video_query = "SELECT video_id FROM videos WHERE video = '$video'";

	$video_result = mysqli_query($connection, $video_query);

	$row = mysqli_fetch_assoc($video_result);

	return $row['video_id'];
}

function get_video($video_id) {
	global $connection;

	$video_query = "SELECT video FROM videos WHERE video_id = $video_id";

	$video_result = mysqli_query($connection, $video_query);

	$row = mysqli_fetch_assoc($video_result);

	return $row['video'];
}

// should be used for all profile searches 
function get_user_page_id($user_id, $type) {
	global $connection; 

	$home_query = "SELECT page_id FROM user_page WHERE user_id = ? AND type = ?";

	$home_stmt = mysqli_prepare($connection, $home_query);

	if($home_stmt === FALSE) { 
		die(mysqli_error($connection)); 
	}

	mysqli_stmt_bind_param($home_stmt, "is", $user_id, $type);

	mysqli_stmt_execute($home_stmt);
	$home_results = mysqli_stmt_get_result($home_stmt);

	if($home_results === FALSE) {
		die(mysqli_error($connection)); 
	}

	$row = mysqli_fetch_assoc($home_results);

	return $row['page_id'];
}

// used for finding sub pages of user
// mmm may be an issue here check back soon 
function get_page_id($user_id) {
	global $connection; 

	$home_query = "SELECT page_id FROM user_page WHERE user_id = $user_id";

	$result = mysqli_query($connection, $home_query);

	if(!$result) {
		die("Failed to retrieve home " . mysqli_error($result));
	}

	$row = mysqli_fetch_assoc($result);

	return $row['page_id'];
}

function get_user_id_from_page($page_id) {
	global $connection;

	$query = "SELECT user_id FROM user_page WHERE page_id = $page_id";

	$result = mysqli_query($connection, $query);

	if (!$result) {
		die("failed to find user_id from page " . mysqli_error($result));
	}

	$row = mysqli_fetch_assoc($result);

	return $row['user_id'];
}

function get_page_type($page_id) {
	global $connection;

	$query = "SELECT type FROM user_page WHERE page_id = $page_id";

	$result = mysqli_query($connection, $query);

	if (!$result) {
		die("failed to find user_id from page " . mysqli_error($result));
	}

	$row = mysqli_fetch_assoc($result);

	return $row['type'];
}

function get_user_info($user_id) {
	global $connection;

	$query = "SELECT username, avatar, description FROM users WHERE user_id = $user_id";

	$result = mysqli_query($connection, $query);

	if (!$result) {
		die("failed to find username from user_id " . mysqli_error($result));
	}

	$row = mysqli_fetch_assoc($result);

	return $row;
}

function get_comments($post_id) {
	global $connection; 

	$comments = "";

	$comment_query = "SELECT username, content FROM comments WHERE post_id = $post_id ORDER BY date";

	$comment_results = mysqli_query($connection, $comment_query);

	if(!$comment_results) {
		die("failed to query comments " . mysqli_error($comment_results));
	}

	if(mysqli_num_rows($comment_results) == 0) {
		$comments = 
		"
		<div class='comments' id='{$post_id}'>
		</div>
		<form>
			<input class='add_comment' type='text' name='comment' placeholder='Add a comment...'>
			<input type='hidden' name='post_id' value='{$post_id}'>
		</form>
		";
		return $comments;
	} else {
		$comments = "<div class='comments' id='{$post_id}'>";
		while ($row = mysqli_fetch_assoc($comment_results)) {
			$comment_username = $row['username'];
			$comment_content = $row['content'];

			$comments .= 

			"<p class='comment_username'>{$comment_username}</p>
			 <p class='comment_content'>{$comment_content}</p>
			 <br>
			";
		}
		$comments .= 
		"
			</div>
			<form>
				<input class='add_comment' type='text' name='comment' placeholder='Add a comment...'>
				<input type='hidden' name='post_id' value='{$post_id}'>
			</form>
		";
		return $comments;
	}
}

function get_liked_icon($post_id, $user_id) {
	global $connection;

	$liked_query = "SELECT like_id FROM likes WHERE post_id = $post_id AND user_id = $user_id";

	$liked_result = mysqli_query($connection, $liked_query);

	if(!$liked_result) {
		die("failed to find likes " . mysqli_error($liked_result));
	}

	if(mysqli_num_rows($liked_result)) {
		return "<i class='fas fa-heart heart-icons liked' data-post='{$post_id}'></i>";
	} else {
		return "<i class='far fa-heart heart-icons' data-post='{$post_id}'></i>";
	}
}

function get_pin_icon($post_id, $user_id) {
	global $connection;

	$pinned_query = "SELECT pin_id FROM pinned WHERE post_id = $post_id AND user_id = $user_id";

	$pinned_result = mysqli_query($connection, $pinned_query);

	if(!$pinned_result) {
		die("failed to find pin " . mysqli_error($pinned_result));
	}

	if(mysqli_num_rows($pinned_result)) {
		return "<a href='#pin' data-toggle='modal' data-target='#pin_modal' data-pinpost='{$post_id}' class='pin'>
				<i class='fas fa-bookmark bookmark-icons pinned'></i>
				</a>";
	} else {
		return "<a href='#pin' data-toggle='modal' data-target='#pin_modal' data-pinpost='{$post_id}' class='pin'>
				<i class='far fa-bookmark bookmark-icons'></i>
				</a>";
	}
}

function get_likes($post_id) {
	global $connection; 

	$get_like_query = "SELECT like_id FROM likes WHERE post_id = $post_id";

	$like_result = mysqli_query($connection, $get_like_query);

	if(!$like_result) {
		die("failed to retrieve like amount " . mysqli_error($like_result));
	}

	$num = mysqli_num_rows($like_result);
	if($num) {
		return "<a href='#likes' data-toggle='modal' data-target='#likes_modal' data-likepost='{$post_id}' class='view_likes'><p class='p-inline like_num'>{$num} likes</p></a>
				<br>";
	} else {
		return "";
	}
}

function get_avatar($user_id) {
	global $connection; 

	$get_avatar = "SELECT avatar FROM users WHERE user_id = $user_id";

	$avatar_result = mysqli_query($connection, $get_avatar);

	if(!$avatar_result) {
		die("failed to retrieve avatar " . mysqli_error($avatar_result));
	}

	$num = mysqli_num_rows($avatar_result);
	if($num) {
		$row = mysqli_fetch_assoc($avatar_result);
		return $row['avatar'];
	} else {
		return "";
	}
}

// display content 
function display_content($page_id, $home, $user_id) {
	global $connection;

	$display_query = "SELECT post_id,username,date,title,caption,media_type,media_id FROM user_posts WHERE page_id = $page_id ORDER BY date DESC";

	$display_result = mysqli_query($connection, $display_query);

	if(!$display_result) {
		die("failed to display posts " . mysqli_error($display_result));
	}

	if(mysqli_num_rows($display_result) == 0) {
		echo $display_content = "
			<div class='main-content-item'>
				<h5 class='text-center' style='padding-top:25px'>No Posts to Show</h5>
			</div>
		"; 
	} else {
		$page_user_id = get_user_id_from_page($page_id);
		$avatar = get_avatar($page_user_id);

		if(empty($avatar)) {
			$profile_img = "images/default-user.png";
		} else {
			$profile_img = "profile_images/" . $avatar;
		}

		while ($row = mysqli_fetch_assoc($display_result)) {
			$post_id = $row['post_id'];
			$page_username = $row['username'];
			$title = $row['title'];
			$caption = $row['caption'];
			$media_type = $row['media_type'];
			$media_id = $row['media_id'];
			$date = $row['date'];
			$content_display = "";
			$title_display = "";
			$caption_display = "";

			// retrieve comments using post_id 
			$comments = get_comments($post_id);

			// LIKE + PIN ICONS CREATED HERE
			// check if post is liked 
			$like_icon = get_liked_icon($post_id, $user_id);

			$like_num = get_likes($post_id);

			$pin = get_pin_icon($post_id, $user_id);

			if(!empty($title)) {
				$title_display = 
				"<h4>{$title}</h4>";
			}

			if($media_id == 0) {
				$caption_display = 
				"<h5 class='text-center'>{$caption}</h5>";
			} else if(!empty($caption)) {
				$caption_display = 
				"<p>{$caption}</p>";
			} 
			

			// media_type == 1 for image, 2 for video, 3 for text
			if ($media_type == 1) {
				$image = get_image($media_id);
				$imageUrl = "post_images/" . $image;
				$edit_modal = "#edit_modal_1";

				$content_display = 
				"<div class='main-content-item'>
					<img src='{$profile_img}' class='rounded-circle content_profile_avatar'> 
					<p class='p-inline content_username'>{$page_username}</p>";

				if($home == 1) {
					$content_display .= "
						<span class='ud_icons'>
							<a href='#edit_modal' data-toggle='modal' data-target='{$edit_modal}' class='edit_modal_link' data-state='1'><i class='fas fa-edit edit_icons' data-edit={$post_id} data-media={$media_type}></i>
							</a>
							<i class='fas fa-trash-alt delete_icons' data-delete={$post_id} data-media={$media_type}></i>
						</span>
					";
				}	

				$content_display .= "
					<br>
					<br>
					{$title_display}
					{$caption_display}
					<img src='{$imageUrl}' class='content_image'>
					{$like_icon}
					{$pin}
					<br>
					<br>
					{$like_num}
				";

				$content_display .= $comments;

				$content_display .= 
				"</div>
				<br>
				<br>";

				echo $content_display;
			} else if ($media_type == 2) {
				$video = get_video($media_id);
				$videoUrl = "post_videos/" . $video;
				$edit_modal = "#edit_modal_2";

				$content_display = 
				"<div class='main-content-item'>
					<img src='{$profile_img}' class='rounded-circle content_profile_avatar'> 
					<p class='p-inline content_username'>{$page_username}</p>";

				if($home == 1) {
					$content_display .= "
						<span class='ud_icons'>
							<a href='#edit_modal' data-toggle='modal' data-target='{$edit_modal}' class='edit_modal_link' data-state='2'><i class='fas fa-edit edit_icons' data-edit={$post_id} data-media={$media_type}></i>
							</a>
							<i class='fas fa-trash-alt delete_icons' data-delete={$post_id} data-media={$media_type}></i>
						</span>
					";
				}

				$content_display .= "
					<br>
					<br>
					{$title_display}
					{$caption_display}
					<video src='{$videoUrl}' class='content_videos' controls></video>
					{$like_icon}
					{$pin}
					<br>
					<br>
					{$like_num}
				";

				$content_display .= $comments;

				$content_display .= 
				"</div>
				<br>
				<br>";

				echo $content_display;
			} else if ($media_type == 3) {
				$edit_modal = "#edit_modal_3";
				$content_display = 
				"<div class='main-content-item'>
					<img src='{$profile_img}' class='rounded-circle content_profile_avatar'> 
					<p class='p-inline content_username'>{$page_username}</p>";

					if($home == 1) {
					$content_display .= "
						<span class='ud_icons'>
							<a href='#edit_modal' data-toggle='modal' data-target='{$edit_modal}' class='edit_modal_link' data-state='3'><i class='fas fa-edit edit_icons' data-edit={$post_id} data-media={$media_type}></i>
							</a>
							<i class='fas fa-trash-alt delete_icons' data-delete={$post_id} data-media={$media_type}></i>
						</span>
					";
				}	

				$content_display .= "
					<br>
					<br>
					{$title_display}
					{$caption_display}
					{$like_icon}
					{$pin}
					<br>
					<br>
					{$like_num}
				";

				$content_display .= $comments;

				$content_display .= 
				"</div>
				<br>
				<br>";

				echo $content_display;
			}
		}
	}
}

// shows list of all user created pages 
function display_user_pages($user_id) {
	global $connection;

	$pages_query = "SELECT page_id, type FROM user_page WHERE user_id = '$user_id' AND NOT type = 'main'";

	$result = mysqli_query($connection, $pages_query);

	if(!$result) {
		die("failed to find user_pages " . mysqli_error($result));
	} else {
		$pages_result = "";

		while($row = mysqli_fetch_assoc($result)) {
			$pages_result .= 
			"<a class='dropdown-item' href='user_home.php?page={$row['page_id']}'>{$row['type']}</a>";
		}

		$pages_result .= 

		"
			<a class='dropdown-item' href='#new_page' data-toggle='modal' data-target='#new_page_modal'>
				<b>new page</b>&nbsp;
				<i class='fas fa-plus text-right plus'></i>
			</a>
		";

		echo $pages_result;
	}

}

// following features 

function is_following_user($user_id, $following_id) {
	global $connection;

	$follow_query = "SELECT user_id FROM following_users WHERE user_id = $user_id AND following_id = $following_id LIMIT 1";

	$follow_result = mysqli_query($connection, $follow_query);

	if(!$follow_result) {
		die("failed to find following info " . mysqli_error($follow_result));
	}

	if(mysqli_num_rows($follow_result) > 0) {
		return 1;
	} else {
		return 0;
	}
}

function get_media_id($post_id) {
	global $connection;

	$media_query = "SELECT media_id FROM user_posts WHERE post_id = $post_id";
	$result = mysqli_query($connection, $media_query);

	if(!$result) {
		die("error retrieving media_id " . mysqli_error($result));
	}

	$row = mysqli_fetch_assoc($result);
	return $row['media_id'];
}

// privacy 

function is_public($page_id) {
	global $connection;

	$privacy_query = "SELECT status FROM user_page WHERE page_id = $page_id";
	$result = mysqli_query($connection, $privacy_query);

	if(!$result) {
		die("error retrieving privacy " . mysqli_error($result));
	}

	$row = mysqli_fetch_assoc($result);

	if($row['status'] == "public") {
		return 1;
	} else {
		return 0;
	}
}

// pinterest pin function (helper)
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

// display friend sidebar 
function display_friendbar($user_id) {
	global $connection; 

	$friend_query = "SELECT following_id FROM following_users WHERE user_id = $user_id";

	$friend_result = mysqli_query($connection, $friend_query);

	if(!$friend_result) {
		die("failed to get friends " . mysqli_erorr($friend_result));
	}

	$friend_content = "";

	$row_count = mysqli_num_rows($friend_result);

	if($row_count > 10) {
		$should_overflow = 1;
	} else {
		$should_overflow = 0;
	}

	if($row_count > 0) {
		while($row = mysqli_fetch_assoc($friend_result)) {
			$friend_id = (int)$row['following_id'];

			$friend_info_query = "SELECT username, fullname, avatar FROM users WHERE user_id = $friend_id";

			$friend_info_result = mysqli_query($connection, $friend_info_query);

			if(!$friend_info_result) {
				die("failed to get friends " . mysqli_erorr($friend_info_result));
			}

			$friend = mysqli_fetch_assoc($friend_info_result);
			$username = $friend['username'];
			$fullname = $friend['fullname'];
			$avatar = $friend['avatar'];

			if(empty($avatar)) {
				$profile_img = "images/default-user.png";
			} else {
				$profile_img = "profile_images/" . $avatar;
			}


			$friend_user_page_id = get_user_page_id($friend_id, "main");


			$friend_content .= "
		
			<a href='user_home.php?page={$friend_user_page_id}'>
				<div class='search_div_items' style='width: auto'>
					<img src='{$profile_img}' class='rounded-circle search_profile_avatar'>
					<b><p class='search_username'>{$username}</p></b>
					<p class='search_name'>{$fullname}</p>
				</div>
			</a>

			";
		}
	} else {
		$friend_content .= "<p class='text-center'>No Friends to Display</p>";
	}

	echo $friend_content;
	return $should_overflow;
}

function display_newsfeed($user_id) {
	global $connection;

	$newsfeed_query = "SELECT post_id,user_posts.user_id,username,title,caption,media_type,media_id FROM user_posts INNER JOIN following_users ON user_posts.user_id = following_users.following_id WHERE following_users.user_id = $user_id ORDER BY user_posts.date DESC";

	$newsfeed_result = mysqli_query($connection, $newsfeed_query);

	if(!$newsfeed_result) {
		die("failed to retrieve newsfeed " . mysqli_error($newsfeed_result));
	}

	if(mysqli_num_rows($newsfeed_result) == 0) {
		echo $display_content = "
			<div class='main-content-item'>
				<h5 class='text-center' style='padding-top:25px'>No Posts to Show</h5>
			</div>
		"; 
	} else {

		while($row = mysqli_fetch_assoc($newsfeed_result)) {
			$page_user_id = $row['user_id'];
			$post_id = $row['post_id'];
			$page_username = $row['username'];
			$title = $row['title'];
			$caption = $row['caption'];
			$media_type = $row['media_type'];
			$media_id = $row['media_id'];
			$content_display = "";
			$title_display = "";
			$caption_display = "";

			// get image
			$avatar = get_avatar($page_user_id);

			if(empty($avatar)) {
				$profile_img = "images/default-user.png";
			} else {
				$profile_img = "profile_images/" . $avatar;
			}

		
			// retrieve comments using post_id 
			$comments = get_comments($post_id);

			// LIKE + PIN ICONS CREATED HERE
			// check if post is liked 
			$like_icon = get_liked_icon($post_id, $user_id);

			$like_num = get_likes($post_id);

			$pin = get_pin_icon($post_id, $user_id);

			if(!empty($title)) {
				$title_display = 
				"<h4>{$title}</h4>";
			}

			if($media_id == 0) {
				$caption_display = 
				"<h5 class='text-center'>{$caption}</h5>";
			} else if(!empty($caption)) {
				$caption_display = 
				"<p>{$caption}</p>";
			} 
			

			// media_type == 1 for image, 2 for video, 3 for text
			if ($media_type == 1) {
				$image = get_image($media_id);
				$imageUrl = "post_images/" . $image;
				$edit_modal = "#edit_modal_1";

				$content_display = 
				"<div class='main-content-item'>
					<img src='{$profile_img}' class='rounded-circle content_profile_avatar'> 
					<p class='p-inline content_username'>{$page_username}</p>";	

				$content_display .= "
					<br>
					<br>
					{$title_display}
					{$caption_display}
					<img src='{$imageUrl}' class='content_image'>
					{$like_icon}
					{$pin}
					<br>
					<br>
					{$like_num}
				";

				$content_display .= $comments;

				$content_display .= 
				"</div>
				<br>
				<br>";

				echo $content_display;
			} else if ($media_type == 2) {
				$video = get_video($media_id);
				$videoUrl = "post_videos/" . $video;
				$edit_modal = "#edit_modal_2";

				$content_display = 
				"<div class='main-content-item'>
					<img src='{$profile_img}' class='rounded-circle content_profile_avatar'> 
					<p class='p-inline content_username'>{$page_username}</p>";

				$content_display .= "
					<br>
					<br>
					{$title_display}
					{$caption_display}
					<video src='{$videoUrl}' class='content_videos' controls></video>
					{$like_icon}
					{$pin}
					<br>
					<br>
					{$like_num}
				";

				$content_display .= $comments;

				$content_display .= 
				"</div>
				<br>
				<br>";

				echo $content_display;
			} else if ($media_type == 3) {
				$edit_modal = "#edit_modal_3";
				$content_display = 
				"<div class='main-content-item'>
					<img src='{$profile_img}' class='rounded-circle content_profile_avatar'> 
					<p class='p-inline content_username'>{$page_username}</p>";

				$content_display .= "
					<br>
					<br>
					{$title_display}
					{$caption_display}
					{$like_icon}
					{$pin}
					<br>
					<br>
					{$like_num}
				";

				$content_display .= $comments;

				$content_display .= 
				"</div>
				<br>
				<br>";

				echo $content_display;
			}
		}
	}
}









