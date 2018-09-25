<div class="hidden-sm-down col-md-3">
	<div class="main_content_side friendbar">
		<br>
		<h4 class="text-center">Following</h4>
	
		<?php 

		$should_overflow = display_friendbar($user_id); 

		if($should_overflow) {
		?>

		<script type="text/javascript">
			$(".friendbar").css({
				overflowY: 'scroll',
				overflowX: 'hidden'
			});
		</script>

		<?php } ?>
		<!-- <div class='search_div_items' style="width: auto;">
			<img src='images/default-user.png' class='rounded-circle search_profile_avatar'>
			<b><p class='search_username'>{$username}</p></b>
			<p class='search_name'>{$fullname}</p>
		</div> -->
	</div>
</div>
