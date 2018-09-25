<?php 

include "login.php";
include "includes/header.php";

?>

<div class="landing_background"></div>
		<div class="container-fluid" style="position: fixed; top: 0px;">
			<div class="login_modal text-center animated slideInUp">
				<h2>Welcome Back</h2>
				<br>

				<!-- signup form -->
				<form action="" method="POST" autocomplete="on">
					<div class="form-group">
						<input type="text" name="username" class="form-control" placeholder="Username" required>
					</div>

					<?php 

					if(!empty($errors['username'])) { 
						echo "<p class='text-left error_text'>{$errors['username']}</p>";
					} 

					?>

					<div class="form-group">
						<input type="password" name="password" class="form-control" placeholder="Password" required>
					
						<?php
							if(!empty($errors['password_length'])) { 
								echo "<p class='text-left error_text'>{$errors['password_length']}</p>";
							} 

							if(!empty($errors['password_characters'])) { 
								echo "<p class='text-left error_text'>{$errors['password_characters']}</p>";
							} 

							if(!empty($errors['wrong'])) { 
								echo "<p class='text-left error_text'>{$errors['wrong']}</p>";
							} 
						?>

					</div>
					<button type="submit" name="submit" class="btn btn-primary btn-submit">Log In</button>
					<!-- <a href="index.php">Sign Up</a> -->
				</form>
				<p id="or">OR</p>
				<p>Don't have an account? <a href="signup">Sign Up</a></p>
			</div>
		</div>	

	<?php include "includes/footer.php"; ?>