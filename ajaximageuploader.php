<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Bootstrap 3.4 Example</title>

    <?php
    include_once('common_header.php');
    ?>
</head>
<body>

<div class="container">


    <div class="well">
       <div class="qa-image-wrapper">
			<ul id="ajax-img-up" class="d-flex justify-content-center" style="width:80px">
			    <li>

			    </li>

			    <li class="addimg-btn" tabindex="2">
			        <label class="input-group-btn">

			            <span class="fa fa-plus"></span> <input type="file" tabindex="2" name="file" id="upfiles" style="display: none;" i d="gallery-photo-add" multiple >

			       </label>
			    </li>
			</ul>
		</div>
    </div>



</div>

<!-- Bootstrap JS and jQuery (make sure to include jQuery before Bootstrap JS) -->
<script src="https://code.jquery.com/jquery-3.3.1.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>

<!-- AJAX PHP IMAGES UP -->


<script>

    $(document).ready(function(){



        $(document).on('click', '#ajax-img-up .delete-btn', function() {

		    var imgToDeletePath = $(this).closest('li').find("img").attr('src');
		    var thisLi = $(this).closest('li');



		    alert(imgToDeletePath);

           $.ajax({
              url: 'phpajax/deletepicajx.php',
              type: 'post',
              data: {action: 'deletepic', pictodelete: imgToDeletePath},


              success: function(response){
                 if(response != 0){

					swal("Success!", response, "success");
					//alert(response);
					thisLi.remove();

                 }else{
                   alert('Error deleting picture');
                }
              },
           });



	});

	var picid = 1;

    $("#upfiles").change(function(){

        var fd = new FormData();
        var files = $('#upfiles')[0].files;

		//alert(files.length);

        // Check file selected or not
        if(files.length > 0 ){
            
           fd.append('file',files[0]);


           $.ajax({
              url: 'phpajax/uploadimageajx.php',
              type: 'post',
              data: fd,
              contentType: false,
              processData: false,
              success: function(response){

                  if(response == 2){
                     $('#loading-overlay').removeClass('is-active');
                     $("#ajax-img-up .fa-plus").removeClass("rotate");
                     swal("Error!", "Please select at least (500 X 500) size picture", "error");
                  }else if(response != 0){
                     $('#loading-overlay').removeClass('is-active');
                     $("#ajax-img-up .fa-plus").removeClass("rotate");
					 //alert(response);
					 $('#ajax-img-up li:last').before('<li class="picbox"><span class="delete-btn fa fa-trash"></span><img src="'+response+'"><input type="hidden" name="imgfiles[]" value="'+response+'"></li>');

					 picid++;

					 //alert(response);
                 }else{
                   $('#loading-overlay').removeClass('is-active');
                    $("#ajax-img-up .fa-plus").removeClass("rotate");
                    alert('file not uploaded');
                 }
              },
           });
        }else{
              swal("Error!", "Please select a file", "error");
        }
   });


    });


</script>


</body>
</html>






