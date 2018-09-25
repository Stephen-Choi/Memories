<?php include "includes/db.php"; ?>

<?php 

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

if(isset($_GET['keyword'])) {
	global $connection;

	$search_value = mysqli_real_escape_string($connection, $_GET['keyword']) . "%";

	$search_query = "SELECT user_id, username, fullname, avatar FROM users WHERE username LIKE ? OR fullname LIKE ? OR lastname LIKE ?";

	$search_stmt = mysqli_prepare($connection, $search_query);

	if($search_stmt === FALSE) { 
		die(mysqli_error($connection)); 
	}

	mysqli_stmt_bind_param($search_stmt, 'sss', $search_value, $search_value, $search_value);
	mysqli_stmt_execute($search_stmt);
	$search_result = mysqli_stmt_get_result($search_stmt);

	if($search_result === FALSE) {
		die(mysqli_error($connection)); 
	}

	$search_div = "";
	while($row = mysqli_fetch_assoc($search_result)) {
		// $user_info[] = $row;
		$fullname = $row['fullname'];
		$username = $row['username'];
		$avatar = $row['avatar'];

		if(empty($avatar)) {
			$profile_img = "images/default-user.png";
		} else {
			$profile_img = "profile_images/" . $avatar;
		}

		$search_user_id = $row['user_id'];
		$search_user_page_id = get_user_page_id($search_user_id, "main");

		if(strlen($fullname) > 17) {
			$fullname = substr($fullname, 0, 17) . '...';
		}

		$fullname = ucwords($fullname);

		if(strlen($username) > 13) {
			$username = substr($username, 0, 13) . '...';
		}

		// image = {$row['avatar']}
		$search_div .= "
		<a href='user_home.php?page={$search_user_page_id}'>
			<div class='search_div_items'>
				<img src='{$profile_img}' class='rounded-circle search_profile_avatar'>
				<b><p class='search_username'>{$username}</p></b>
				<p class='search_name'>{$fullname}</p>
			</div>
		</a>
		";
	}

	if(!empty($search_div)) {
		// echo json_encode($user_info);
		echo $search_div;
	} else {
		$search_div = "

		<div class='search_div_items'>
			<b><p id='search_fail'>No Results</p></b>
		</div>

		";
		echo $search_div;
	}

	mysqli_stmt_close($search_stmt);
}
