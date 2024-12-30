<!DOCTYPE html>
<html>
<head>
<title>QA Images uploader</title>
</head>
<body>

<h1>Dropzone</h1>
<!-- Include Dropzone CSS from CDN -->
<link rel="stylesheet" href="/js/plugins/dropzone/dropzone.min.css">
<!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.2/min/dropzone.min.css">-->

<!-- Include Dropzone JS from CDN -->
<script src="/js/plugins/dropzone/dropzone.min.js"></script>
<!--<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.2/min/dropzone.min.js"></script>-->

<form action="" class="dropzone" id="myDropzone">
    
<input type="hidden" name="additionalParam1" id="additionalParam1" value="<?=$_REQUEST['value1']?>">
<input type="hidden" name="additionalParam2" id="additionalParam2" value="<?=$_REQUEST['value2']?>">

</form>
<script>
    Dropzone.options.myDropzone = {
        paramName: "file",
        maxFilesize: 5, // MB
        maxFiles: 5,
        acceptedFiles: ".jpg, .jpeg, .png, .gif",
        init: function () {
            this.on("success", function (file, response) {
                console.log("File uploaded:", file);
                console.log("Server response:", response);
            });
        },
        url: "phpajax/qa_image_upload_post.php", // Point to the PHP file handling uploads
        params: {
            additionalParam1: document.getElementById('additionalParam1').value,
            additionalParam2: document.getElementById('additionalParam2').value
            // Add more key-value pairs as needed
        }
        

        
        
    };
</script>



</body>
</html> 