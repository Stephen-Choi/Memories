<?php 

session_start();

if(!isset($_SESSION['user'])) {
	header("location: login");
}

include "includes/header.php";
include "includes/db.php";
include "includes/functions.php";
// echo $_SESSION['fullname'];

$user_id = $_SESSION['user_id'];
$page_id = 0;

?>

<!-- color
#feec3a     yellow
#3f50b5     purple
#de4b4b      red
#222222    black
#f2f2f2      light not white   (good for hover) 
#e4e0d6    might be better for white
#ffcb33 orange
#95bac4    teal 
#3e6cae   blue  -->

<div class="container-fluid">

	<?php include "nav.php"; ?>

	<br>
	<br>
	
	<div class="row">
		
		<div class="col-sm-12 col-md-6 offset-md-2 main_content_area">
			<?php display_newsfeed($user_id); ?>
		</div>
		
		<?php include "friend_bar.php"; ?>

	</div>
</div>

<?php 

include "includes/footer.php";

?>