<?php 

session_start();

if(!isset($_SESSION['user'])) {
	header("location: login");
}

include "includes/header.php";
include "includes/db.php";
include "includes/functions.php";

// settings for editing posts
$edit_post_id = 0;
$edit_image_id = 0;
$edit_video_id = 0;

// if come from index.php, $page_id is preset 
$display = 1;
$display_modal = 1;
$title_message = "";

// if navigated from somewhere else then update page_id
if(isset($_GET['page'])) {
	$temp = (int)mysqli_real_escape_string($connection, $_GET['page']);

	if($temp == 0) {
		header('location: index.php');
	}

	if($temp > 0) {
		$page_id = $temp;	
		$display = 1;
		
		// need to check error 
		$page_user_id = get_user_id_from_page($page_id);
		$home_page_id = get_user_page_id($_SESSION['user_id'], "main");
		
		if ($home_page_id == $page_id) {
			$display_modal = 1; 
			$title_message = "Home Page";
		}
		else if($page_user_id == $_SESSION['user_id']) {
			$display_modal = 1; 
			$title_message = get_page_type($page_id);
		} else {
			$display_modal = 0;
			$page_info = get_user_info($page_user_id);
			$page_username = $page_info['username'];
			$page_avatar = $page_info['avatar'];

			if(empty($page_avatar)) {
				$profile_img = "images/default-user.png";
			} else {
				$profile_img = "profile_images/" . $page_avatar;
			}

			$page_desc = $page_info['description'];
		}
	} else {
		$display = 0;
	}
}

// handles uploading all new post content
include "upload_content.php";

?>

<div class="container-fluid">

	<?php include "nav.php"; 

	if($display_modal == 0) {

		include "user_info.php";
	}

	?>

	<br>

	<h1 class="text-center" style="text-align: center;"><?php echo $title_message; ?>
		<?php if($display == 1 && $display_modal == 1) { ?>
		<a href="#privacy" data-toggle="modal" data-target="#privacy_modal">
			<i class="fas fa-cog privacy_setting"></i>
		</a>
		<?php } ?>
	</h1>
	<br>
	<div class="row">
		<div class="col-sm-12 col-md-6 offset-md-2 main_content_area">
			<?php 

				// check privacy 
				$public = is_public($page_id);

				// if users' home page
				if($display == 1 && $display_modal == 1) {
					display_content($page_id, 1, $user_id);
				} else if($display == 1 && $public == 1) {
					display_content($page_id, 0, $user_id);
				} else if ($display == 1 && $public == 0) {
					echo "<div class='main-content-item'>
							<h5 class='text-center' style='padding-top:25px'>This page is private</h5>
						</div>";
				} else {
					echo "invalid page requested";
				}
			?>
		</div>

<br>
<br>
		
		<?php 
			if($display_modal == 1) {
				include "modals.php"; 
			} else {
				include "friend_bar.php";
			}
		?>
	      
	</div>
</div>
<br>
<br>

<script type="text/javascript" src="js/image_video.js"></script>
<?php 
include "includes/footer.php";
?>

