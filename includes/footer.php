</body>


<script type="text/javascript" src="js/script.js"></script>
<script type="text/javascript" src="js/bootstrap_js/popper.js"></script>
<script type="text/javascript" src="js/bootstrap_js/bootstrap.min.js"></script>
<script type="text/javascript">

<?php  if(isset($_SESSION['user'])) {  ?>
// PINTEREST PIN OPTION
$(".pin").click(function() {
	var pin_post = $(this);
	var post_id = $(pin_post).data("pinpost");

	$(".pin_post").val(post_id);

	var user = "<?php echo $_SESSION['user']; ?>";

	$.ajax({
		url: 'icon_action.php',
		type: 'GET',
		data: 'pin_user='+user,
		success: function(data) {
			$("#pin_form").html(data);
		}, 
		error: function(err) {
			console.log("failed");
			console.log(err);
		}
	});
});


<?php } ?>

</script>
</html>