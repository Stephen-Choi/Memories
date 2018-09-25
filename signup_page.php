<?php 

include "signup.php";
include "includes/header.php";

?>
	<div class="landing_background"></div>
		<div class="container-fluid" style="position: fixed; top: 0px;">
			<div class="signup_modal text-center animated slideInUp">
				<h2>Welcome to Memories</h2>
				<h4>Experience, Share, Save</h4>
				<br>

				<!-- signup form -->
				<form action="" method="POST" autocomplete="on">
					<div class="form-row form-group">
					    <div class="col">
					      <input type="text" name="firstname" class="form-control" placeholder="First name" required>
					    </div>
					    <div class="col">
					      <input type="text" name="lastname" class="form-control" placeholder="Last name" required>
					    </div>
					</div>
					<div class="form-group">
						<input type="text" name="username" class="form-control" placeholder="Username" required>
						<?php 

							if(!empty($errors['username'])) { 
								echo "<p class='text-left error_text'>{$errors['username']}</p>";
							}

							if(!empty($errors['username_characters'])) { 
								echo "<p class='text-left error_text'>{$errors['username_characters']}</p>";
							}

						?>
					</div>
					<div class="form-group">
						<input type="email" name="email" class="form-control" placeholder="Email" required>
						 <?php 
					
							if(!empty($errors['email'])) { 
								echo "<p class='text-left error_text'>{$errors['email']}</p>";
							}

						?>
					</div>
					<div class="form-group">
						<input type="password" name="password" class="form-control" placeholder="Create a Password" required>
						<?php 

							if(empty($errors['password_length']) && empty($errors['password_characters']) && empty($check['visited'])) {
								echo "<p class='text-left error_text'>Password must be at least 8 characters</p>";
							}
					
							if(!empty($errors['password_length'])) { 
								echo "<p class='text-left error_text'>{$errors['password_length']}</p>";
							} 

							if(!empty($errors['password_characters'])) { 
								echo "<p class='text-left error_text'>{$errors['password_characters']}</p>";
							} 

						?>
					</div>
					<button type="submit" name="submit" class="btn btn-primary btn-submit">Continue</button>
					<!-- <a href="index.php">Sign Up</a> -->
				</form>
				<p id="or">OR</p>
				<p>Already have an account? <a href="login">Login</a></p>
			</div>
		</div>	

	<?php include "includes/footer.php"; ?>



