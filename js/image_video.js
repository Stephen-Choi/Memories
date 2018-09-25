function img_readURL(input) {
	if (input.files && input.files[0]) {
    	var reader = new FileReader();
    	reader.onload = function (e) {
    		$('#image_temp').css("display", "block");
    		$('#image_temp').attr('src', e.target.result);
    	}
    	reader.readAsDataURL(input.files[0]);
   } else {
	        $('#image_temp').css("display", "none");
		$('#image_temp').attr('src', "");
   }
}

function img_readURL_edit(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('.image_edit').css("display", "block");
            $('.image_edit').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
   } else {
        $('.image_edit').css("display", "none");
        $('.image_edit').attr('src', "");
   }
}

function video_readURL(input) {
    if (input.files && input.files[0]) {
    	var reader = new FileReader();
    	reader.onload = function (e) {
    		$('#video_temp').css("display", "block");
    		$('#video_temp').attr('src', e.target.result);
    	}
    	reader.readAsDataURL(input.files[0]);
    } else {
	        $('#video_temp').css("display", "none");
		$('#video_temp').attr('src', "");
    }
}

function video_readURL_edit(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('.video_edit').css("display", "block");
            $('.video_edit').attr('src', e.target.result);
        }
        reader.readAsDataURL(input.files[0]);
    } else {
            $('#video_edit').css("display", "none");
        $('#video_edit').attr('src', "");
    }
}