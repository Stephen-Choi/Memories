
// liking functions
$(".heart-icons").click(function() {

	var like_icon = $(this);
	var post_id = $(like_icon).data("post");
	// not liked yet
	if($(this).hasClass('far')) {
		$.ajax({
			url: 'icon_action.php',
			type: 'POST',
			data: 'liked_post='+post_id,
			success: function() {
				console.log("made it liked");
				$(like_icon).removeClass('far');
				$(like_icon).addClass('fas');
				$(like_icon).css({
					"color": "#c50a1e"
				})
			},
			error: function(err) {
				console.log(err);
			}
		});
	} else {
		$.ajax({
			url: 'icon_action.php',
			type: 'POST',
			data: 'unliked_post='+post_id,
			success: function() {
				console.log("made it unliked");
				$(like_icon).removeClass('fas');
				$(like_icon).addClass('far');
				$(like_icon).css({
					"color": "black"
				})
			},
			error: function(err) {
				console.log(err);
			}
		});
	}
});

// VIEW LIKES
$(".view_likes").click(function() {
	var like_post = $(this);
	var post_id = $(like_post).data("likepost");

	$.ajax({
		url: 'icon_action.php',
		type: 'GET',
		data: 'view_likes_post='+post_id,
		success: function(data) {
			$("#like_container").html(data);
		}, 
		error: function(err) {
			console.log(err);
		}
	});
});

// pin change color shit
	// $(this).removeClass('far');
	// $(this).addClass('fas');
	// $(this).css({
	// 	"color": "#7c46b9"
	// })

// search 
$(document).ready(function() {
	$("#search_input").keyup(function() {
		var val = $(this).val();
		if(!(val === "") && val.charAt(0) !== " "){
			$.ajax({
				url: 'search.php',
				type: 'GET',
				data: 'keyword='+val,
				success: function(data) {
					// var arr = JSON.parse(data);
					// if (typeof(arr) !== typeof(true)) {
					// 	for(var i = 0; i < arr.length; ++i) {
					// 		console.log(arr[i].username);
					// 		console.log(arr[i].firstname);
					// 		console.log(arr[i].lastname);
					// 	}
					// }
					
					$("#search_div").html(data);
					if($("#search_div").children().length > 0) {
						$("#tilted").css("display", "block");
					} else {
						$("#tilted").css("display", "none");
					}
					if($("#search_div").children().length > 8) {
						$(".search_area").css({
							"height": "408px"
						})
						$("#search_div").css({
							"height": "400px",
							"overflow-y": "scroll",
							"overflow-x": "hidden"
						})
					} else {
						$("#search_div").css({
							"overflow": "hidden"
						})
					}
				},
				error: function(err) {
					console.log(err);
				}
			});
		} else {
			$("#tilted").css("display", "none");
			$("#search_div").html("");
			$("#search_div").css({
				"overflow": "hidden"
			})
		}
	});
})

// deleting posts
$(document).ready(function() {
	$(".delete_icons").click(function() {

		var result = confirm("Confirm Delete");

		if(result) {
			var del_id = $(this).data("delete");
			var del_media = $(this).data("media");

			var delete_data = {};
			delete_data.post_id = del_id;
			delete_data.media = del_media;

			$.ajax({
				url: 'delete.php',
				type: 'POST',
				data: delete_data,
				success:function() {
					location.reload();
				},
				error:function(err) {
					console.log(err);
				}
			});
		}
	});
})

// edit media 
$(document).ready(function(){
	$(".edit_icons").click(function() {

		var edit_id = $(this).data("edit");
		var media = $(this).data("media");

		var edit_data = {};
		edit_data.edit_id = edit_id;
		edit_data.media = media;

		var cur_val = new Array();

		$.ajax({
			url: 'edit.php',
			type: 'GET',
			data: edit_data,
			success:function(data) {
				cur_val = data;
				console.log(cur_val);
				var title = cur_val.title;
				var caption = cur_val.caption;

				// image
				if(media == 1) {
					var image = cur_val.image;
					var image_id = cur_val.media_id;
					$('.image_edit').attr('src', image);
					$(".edit_image_post_id").val(edit_id);
					$(".edit_image_id").val(image_id);
				} else if(media == 2) {
					var video = cur_val.video;
					var video_id = cur_val.media_id;
					$('.video_edit').attr('src', video);
					$(".edit_video_post_id").val(edit_id);
					$(".edit_video_id").val(video_id);
				} else {
					$(".edit_text_post_id").val(edit_id);
				}

				$('.edit_title').val(title);
				$('.edit_msg').html(caption);
			},
			dataType:"json",
			error:function(err) {
				console.log(err);
			}
		});
	});
});

// comment submission
$("form").bind("keypress", function (e) {
    if (e.keyCode == 13) {
    	e.preventDefault();
        var comment = $(this).find('input')[0].value;
        var post = $(this).find('input')[1].value;

        $(this).find(".add_comment").val("");

        var comment_data = {};
        comment_data.comment = comment;
        comment_data.post = post;

        if(comment) {
        	//submit comment 
        	$.ajax({
        		url: 'comments.php',
				type: 'POST',
				data: comment_data,
				success:function(data) {
					if(data) {
						var user = data;
						$("#" + post).append(

						"<p class='comment_username'>" + user + " </p>" +
						"<p class='comment_content'>" + comment +
						 "</p><br>"
						);
					}
				},
				error:function(err) {
					console.log(err);
				}
        	});
        } else {
        	return false;
        }
    }
});




