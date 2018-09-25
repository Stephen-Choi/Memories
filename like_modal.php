<!-- content being loaded in case need to change -->
<!-- <div class="col-sm-12">
	<a href='user_home.php?page=2'>
		<div class='like_div_items'>
			<img src='images/default-user.png' class='rounded-circle search_profile_avatar'>
			<b><p class='search_username'>istch</p></b>
			<p class='search_name'>Stephen Choi</p>
		</div>
	</a>
</div> -->

<!-- displays people who have liked modal -->
<div class="modal fade" id="likes_modal" role="dialog">
	<div class="modal-dialog modal-lg">
	  <!-- Modal content-->
	  <div class="modal-content">
	    <div class="modal-header">
	      <h4 class="text-left">People who've liked this...</h4>
	      <button type="button" class="close modal-close" data-dismiss="modal">&times;</button>
	    </div>
	    <div class="modal-body">
	      
			<div class="row" id="like_container">
				
			</div>

	    </div>
	    <div class="modal-footer">
	      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	    </div>
	  </div>  
	</div>
</div>


<!-- pin submission modal -->
<div class="modal fade" id="pin_modal" role="dialog">
	<div class="modal-dialog modal-lg">
	  <!-- Modal content-->
	  <div class="modal-content">
	    <div class="modal-header">
	      <h4 class="text-left">Save This Post</h4>
	      <button type="button" class="close modal-close" data-dismiss="modal">&times;</button>
	    </div>
	    <div class="modal-body">
	      
			<form action="icon_action.php" method="POST">
				<div class="form-group">
				    <label for="pin_form">Choose a Page</label>
				    <select class="form-control" id="pin_form" name="dest_page">
						
				    </select>
			  	</div>
				<input type="hidden" name="pin_post_id" class="pin_post" value="">
			  <button type="submit" name="pin_submit" class="btn btn-primary btn-submit" style="color: white !important">Save Post</button>

			</form>

	    </div>
	    <div class="modal-footer">
	      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	    </div>
	  </div>  
	</div>
</div>

<!-- profile modal -->
<div class="modal fade" id="profile_modal" role="dialog">
	<div class="modal-dialog modal-lg">
	  <!-- Modal content-->
	  <div class="modal-content">
	    <div class="modal-header">
	      <h4 class="text-left">Update Profile</h4>
	      <button type="button" class="close modal-close" data-dismiss="modal">&times;</button>
	    </div>
	    <div class="modal-body">
	      
			<form action="icon_action.php" enctype="multipart/form-data" method="POST">
			
				<div class="form-group">
					<label for="profile_img_file">Your Image</label>
					<input type="file"  name="profile_img" class="form-control" id="profile_img_file">
				</div>
				<div class="form-group">
					<label for="profile_bio">Your Bio</label>
					<textarea name="profile_bio" class="form-control" id="profile_bio" rows="5"></textarea>
				</div>

			    <button type="submit" name="profile_submit" class="btn btn-primary btn-submit" style="color: white !important">Save Profile</button>

			</form>

	    </div>
	    <div class="modal-footer">
	      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	    </div>
	  </div>  
	</div>
</div>






