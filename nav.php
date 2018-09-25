<?php 

	$type = "main";
	$user_id = (int)$_SESSION['user_id'];
	$home_page_id = get_user_page_id($user_id, $type);

	// creates new page 
	if(isset($_POST['new_page_submit'])) {
		$page_name = $_POST['page_name'];

		$create_query = "INSERT INTO user_page(user_id, type) VALUES(?,?)";

		$create_stmt = mysqli_prepare($connection, $create_query);

		mysqli_stmt_bind_param($create_stmt, "is", $user_id, $page_name);
		$result = mysqli_stmt_execute($create_stmt);

		if(!$result) {
			die("failed to create " . mysqli_error($connection));
		} else {
			$new_page = get_user_page_id($user_id, $page_name);
			if(empty($new_page)) {
				die("didnt work");
			} else {
				header("Location: user_home.php?page={$new_page}");
			}
		}
	}

?>

<div class="row nav">
	<div class="col-sm-4">
		<a href="main"><h1 class="title">Memories</h1></a>
	</div>
	<div class="col-sm-4 search_container">
		<div class="search">
			<i class="fas fa-search icons" id="search-icon"></i>
			<input type="text" name="search" id="search_input" autocomplete="off">
		</div>
		<div class="search_area">
			<div id="tilted"></div>
			<div id="search_div">
				<!-- <div class="search_div_items" style="margin-top: -12px;">
					<img src="images/beautiful_layout.jpeg" class="rounded-circle profile_avatar">
					<b><p class="search_username">Istch</p></b>
					<p class="search_name">Stephen Choi</p>
				</div> -->
				<!-- <div class="search_div_items">
					<b><p class="search_username">No Results</p></b>
				</div> -->
			</div>
		</div>
	</div>
	<div class="col-sm-4">
		<div class="user-profile">
			
			<!-- display new page only if on a user page  -->
			<?php 
			if(isset($_GET['page'])) {
				$page_user_id = get_user_id_from_page($_GET['page']);
				if($page_user_id == $_SESSION['user_id']) {
			?>

			<div class="dropdown" style="display: inline">
			  <a href="" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"><h4 class="p-inline">pages</h4></a>
			  <div class="dropdown-menu page_dropdown" aria-labelledby="dropdownMenuButton">
			    <?php display_user_pages($user_id); ?>
			  </div>
			</div>

			<!-- <a href="#new_page" data-toggle="modal" data-target="#new_page_modal">
				<h4 class="p-inline" style="margin-right: 4px;">new page</h4>
				<i class="fas fa-plus text-right user-icons"></i>
			</a> -->

			<div class="modal fade" id="new_page_modal" role="dialog">
				<div class="modal-dialog modal-lg">
				  <!-- Modal content-->
				  <div class="modal-content">
				    <div class="modal-header">
				      <h4 class="text-left">Create New Page</h4>
				      <button type="button" class="close modal-close" data-dismiss="modal">&times;</button>
				    </div>
				    <div class="modal-body">
				      <form action="" method="POST">
						<div class="form-group">
							<input type="text" name="page_name" class="form-control" placeholder="Page Name" required>
						</div>
						<!-- maybe add privacy here -->
						<button type="submit" name="new_page_submit"class="btn btn-primary btn-submit" style="color: white !important">Create</button>
				      </form>
				    </div>
				    <div class="modal-footer">
				      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
				    </div>
				  </div>  
				</div>
			</div>

			<?php }} ?>
			
			&nbsp;&nbsp;&nbsp;&nbsp;


			<div class="dropdown" style="display: inline">
			  <a href="" class="dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			  	<h4 class="p-inline"><?php echo $_SESSION['user']; ?></h4>
			  	<i class="fas fa-user text-right user-icons"></i>
			  </a>
			  <div class="dropdown-menu user_dropdown text-center" aria-labelledby="dropdownMenuButton">
			    <a class='dropdown-item' href='user_home.php?page=<?php echo $home_page_id; ?>'>Home</a>
			    <a class='dropdown-item' href='#profile' data-toggle='modal' data-target='#profile_modal'>Profile</a>
			    <a class='dropdown-item' href='logout.php'>Logout</a>
			  </div>
			</div>

<!-- 
			&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

			<h4 class="p-inline" style="margin-right: 8px;">groups</h4>
			<i class="fas fa-user-friends user-icons"></i> -->

		</div>
	</div>

	<?php include "like_modal.php"; ?>

</div>