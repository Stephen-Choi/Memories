<!-- available variables 
$page_user_id == $_SESSION['user_id'] -->

<?php 
	$following = is_following_user($_SESSION['user_id'], $page_user_id);
	$follow_text = "";

	if($following) {
		$follow_text = "Following";
	} else {
		$follow_text = "Follow";
	}
?>

<!-- include right after nav on other mans page -->
<br>
<div class="row">
	<div class="col-md-3 offset-md-2">
		<img src="<?php echo $profile_img; ?>" class="rounded-circle profile_picture">
	</div>
	<div class="col-md-5">
		<h1 class="text-left" style="display: inline"><?php echo $page_username; ?></h1>
		<div style="margin-left: 20px; display: inline; position: relative; top: -9.5px;">
			<button class="btn_status <?php if($following) { echo "btn_following"; } else { echo "btn_follow"; } ?>" data-followstate="<?php echo $following; ?>"><?php echo $follow_text; ?></button>
		</div>
		<p><?php echo $page_desc; ?></p>
	</div>
</div>
<br>

<script type="text/javascript">

	$(".btn_status").click(function() {
		var followstate = $('.btn_status').data('followstate');
		var user_id = <?php echo $_SESSION['user_id']; ?>;
		var follow_id = <?php echo $page_user_id; ?>;

		var follow_data = {};
		follow_data.followstate = followstate;
		follow_data.user_id = user_id;
		follow_data.follow_id = follow_id;

		$.ajax({
			url: 'follow.php',
			type: 'POST',
			data: follow_data,
			success: function(data) {
				var follow_status = data;
				follow_status = follow_status.trim(); 
				if (follow_status == 'unfollowed') {
					$(".btn_status").html("Follow");
					$(".btn_status").removeClass('btn_following');
					$(".btn_status").addClass('btn_follow');
					$('.btn_status').data('followstate', '0');
				} else if (follow_status == 'followed') {
					$(".btn_status").html("Following");
					$(".btn_status").removeClass('btn_follow');
					$(".btn_status").addClass('btn_following');
					$('.btn_status').data('followstate', '1');
				} else {
					console.log("fucked");
				}
			},
			error: function(err) {
				console.log(err);
			}
		});
	});
</script>















