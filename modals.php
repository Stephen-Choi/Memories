<div class="col-sm-12 col-md-3"> <!-- need the margin-left on smaller screens -->
	<div class="main_content_side" style="background-color: #fff; height: 70vh;">
		<p class="text-center" style="padding-top: 12px; font-size: 23px; font-weight: 600;">Upload Content</p>
		<br>
		  <!-- <button type="button" class="btn btn-info btn-lg" data-toggle="modal" data-target="#image_modal">Open Modal</button> -->
		<div class="upload_div">
			<a href="#upload_img" data-toggle="modal" data-target="#image_modal">
				<div class="text-center">
					<p class="upload_desc">Upload a Photo</p>
					<i class="fas fa-camera upload_icons"></i>
				</div>
			</a>
		</div>
		<br>
		<div class="text-center upload_div">
			<a href="#upload_vid" data-toggle="modal" data-target="#video_modal">
				<div class="text-center">
					<p class="upload_desc">Upload a Video</p>
					<i class="fas fa-video upload_icons"></i>
				</div>
			</a>
		</div>
		<br>
		<div class="text-center upload_div">
			<a href="#upload_msg" data-toggle="modal" data-target="#text_modal">
				<div class="text-center">
					<p class="upload_desc">Write a Message</p>
					<i class="fas fa-comment-alt upload_icons"></i>
				</div>
			</a>
		</div>
	</div>
</div>

<!-- Image Modal -->
<div class="modal fade" id="image_modal" role="dialog">
	<div class="modal-dialog modal-lg">	    
	  <!-- Modal content-->
	  <div class="modal-content">
	    <div class="modal-header">
	      <h4 class="text-left">Upload Your Photo</h4>
	      <button type="button" class="close modal-close" data-dismiss="modal">&times;</button>
	    </div>
	    <div class="modal-body">
	      <form action="" method="POST" enctype="multipart/form-data">
			<div class="form-group">
				<input type="file" name="upload_image" id="upload_image" class="form-control" onchange="img_readURL(this);" required>
				<img alt="Image Display Here" id="image_temp" src="#" style="display: none; min-height: 400px;" width="100%"/>
			</div>
			<div class="form-group">
				<input type="text" name="image_title" class="form-control" placeholder="Title">
			</div>
			<div class="form-group">
				<textarea class="form-control" name="image_caption" placeholder="Add a Caption" rows="5"></textarea>
			</div>
			<button type="submit" name="image_submit" id="image_submit" class="btn btn-primary btn-submit">Upload</button>
	      </form>
	    </div>
	    <div class="modal-footer">
	      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	    </div>
	  </div>
	</div>
</div>

<!-- Video Modal -->
<div class="modal fade" id="video_modal" role="dialog">
	<div class="modal-dialog modal-lg">
	  <!-- Modal content-->
	  <div class="modal-content">
	    <div class="modal-header">
	      <h4 class="text-left">Upload Your Video</h4>
	      <button type="button" class="close modal-close" data-dismiss="modal">&times;</button>
	    </div>
	    <div class="modal-body">
	      <form action="" method="POST" enctype="multipart/form-data">
			<div class="form-group">
				<input type="file" name="upload_video" id="upload_video" class="form-control" onchange="video_readURL(this)" required>
				<iframe src="" width="100%" style="display: none; min-height: 400px;" id="video_temp"></iframe>
			</div>
			<div class="form-group">
				<input type="text" name="video_title" class="form-control" placeholder="Title">
			</div>
			<div class="form-group">
				<textarea class="form-control" name="video_caption" placeholder="Add a Caption" rows="5"></textarea>
			</div>
			<button type="submit" name="video_submit" id="video_submit" class="btn btn-primary btn-submit" style="color: white !important">Upload</button>
	      </form>
	    </div>
	    <div class="modal-footer">
	      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	    </div>
	  </div>
	</div>
</div>

<!-- Text Modal -->
<div class="modal fade" id="text_modal" role="dialog">
	<div class="modal-dialog modal-lg">
	  <!-- Modal content-->
	  <div class="modal-content">
	    <div class="modal-header">
	      <h4 class="text-left">Create a Message</h4>
	      <button type="button" class="close modal-close" data-dismiss="modal">&times;</button>
	    </div>
	    <div class="modal-body">
	      <form action="" method="POST">
			<div class="form-group">
				<input type="text" name="text_title" class="form-control" placeholder="Title">
			</div>
			<div class="form-group">
				<textarea class="form-control" name="message" placeholder="Your Message Here..." rows="5" required></textarea>
			</div>
			<button type="submit" name="text_submit"class="btn btn-primary btn-submit" style="color: white !important">Upload</button>
	      </form>
	    </div>
	    <div class="modal-footer">
	      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	    </div>
	  </div>  
	</div>
</div>


<!-- edit image modal -->
<div class="modal fade" id="edit_modal_1" role="dialog">
	<div class="modal-dialog modal-lg">
	  <!-- Modal content-->
	  <div class="modal-content">
	    <div class="modal-header">
	      <h4 class="text-left">Edit your Image Post</h4>
	      <button type="button" class="close modal-close" data-dismiss="modal">&times;</button>
	    </div>
	    <div class="modal-body">
	      <form action="" method="POST" enctype="multipart/form-data">
	      	<input type="text" name="post_id" class="edit_image_post_id">
	      	<input type="text" name="image_id" class="edit_image_id">
	      	<div class="form-group">
	      		<input type="file" name="edit_image" id="edit_image_file" class="form-control" onchange="img_readURL_edit(this);">
	      		<img alt="Image Display Here" class="image_edit" src="" style="max-height: 500px; width: 100%;" />
	      	</div>
			<div class="form-group">
				<input type="text" name="edit_image_title" class="form-control edit_title" placeholder="Title">
			</div>
			<div class="form-group">
				<textarea class="form-control edit_msg" name="edit_image_message" placeholder="Your Message Here..." rows="5"></textarea>
			</div>
			<button type="submit" name="edit_image_submit"class="btn btn-primary btn-submit" style="color: white !important">Update</button>
	      </form>
	    </div>
	    <div class="modal-footer">
	      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	    </div>
	  </div>
	</div>
</div>

<!-- edit video modal -->
<div class="modal fade" id="edit_modal_2" role="dialog">
	<div class="modal-dialog modal-lg">
	  <!-- Modal content-->
	  <div class="modal-content">
	    <div class="modal-header">
	      <h4 class="text-left">Edit your Video Post</h4>
	      <button type="button" class="close modal-close" data-dismiss="modal">&times;</button>
	    </div>
	    <div class="modal-body">
	      <form action="" method="POST" enctype="multipart/form-data">
	      	<input type="text" name="post_id" class="edit_video_post_id">
	      	<input type="text" name="video_id" class="edit_video_id">
	      	<div class="form-group">
				<input type="file" name="edit_video" id="upload_video" class="form-control" onchange="video_readURL_edit(this)">
				<video src="" style="max-height: 500px; width: 100%;" class="video_edit" controls>
				</video>
			</div>
			<div class="form-group">
				<input type="text" name="edit_video_title" class="form-control edit_title" placeholder="Title">
			</div>
			<div class="form-group">
				<textarea class="form-control edit_msg" name="edit_video_message" placeholder="Your Message Here..." rows="5" required></textarea>
			</div>
			<button type="submit" name="edit_video_submit" class="btn btn-primary btn-submit" style="color: white !important">Update</button>
	      </form>
	    </div>
	    <div class="modal-footer">
	      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	    </div>
	  </div>
	</div>
</div>

<!-- edit text modal -->
<div class="modal fade" id="edit_modal_3" role="dialog">
	<div class="modal-dialog modal-lg">
	  <!-- Modal content-->
	  <div class="modal-content">
	    <div class="modal-header">
	      <h4 class="text-left">Edit your Message</h4>
	      <button type="button" class="close modal-close" data-dismiss="modal">&times;</button>
	    </div>
	    <div class="modal-body">
	      <form action="" method="POST">
	      	<input type="text" name="post_id" class="edit_text_post_id">
			<div class="form-group">
				<input type="text" name="edit_text_title" class="form-control edit_title" placeholder="Title">
			</div>
			<div class="form-group">
				<textarea class="form-control edit_msg" name="edit_text_message" placeholder="Your Message Here..." rows="5" required></textarea>
			</div>
			<button type="submit" name="edit_text_submit"class="btn btn-primary btn-submit" style="color: white !important">Update</button>
	      </form>
	    </div>
	    <div class="modal-footer">
	      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	    </div>
	  </div>
	</div>
</div>


<!-- privacy Modal -->
<div class="modal fade" id="privacy_modal" role="dialog">
	<div class="modal-dialog modal-lg">
	  <!-- Modal content-->
	  <div class="modal-content">
	    <div class="modal-header">
	      <h4 class="text-left">Privacy Settings</h4>
	      <button type="button" class="close modal-close" data-dismiss="modal">&times;</button>
	    </div>
	    <div class="modal-body">
	      <form action="" method="POST">

	      	<?php $public = is_public($page_id); ?>

			<!-- sql call to check what status is checked="checked" -->
			<div class="form-check form-check-inline">
			  <input class="form-check-input" id="public" type="radio" name="privacy" value="public" <?php if($public == 1) { echo "checked"; } ?>>
			  <label class="form-check-label" for="public">Public</label>
			</div>
			<div class="form-check form-check-inline">
			  <input class="form-check-input" id="private" type="radio" name="privacy" value="private" <?php if($public == 0) { echo "checked"; } ?>>
			  <label class="form-check-label" for="private">Private</label>
			</div>

			<br>
			<br>

			<button type="submit" name="privacy_submit"class="btn btn-primary btn-submit" style="color: white !important">Update Settings</button>
	      </form>
	    </div>
	    <div class="modal-footer">
	      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	    </div>
	  </div>  
	</div>
</div>



