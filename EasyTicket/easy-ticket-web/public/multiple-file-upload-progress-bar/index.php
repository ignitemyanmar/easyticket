<?php
/**
 * Multiple file upload with progress bar php and jQuery
 * 
 * @author Resalat Haque
 * @link http://www.w3bees.com
 */

?>
<!doctype html>
<html lang="en">
<head>

	<meta charset="utf-8" />
	<title>Multiple File Upload with progress bar</title>

	<!-- styles -->
	<link rel="stylesheet" type="text/css" href="css/pure-min.css">
	<link rel="stylesheet" type="text/css" href="css/style.css">

</head>
<body>
<div class="container">	

	<!-- status message will be appear here -->
	<div class="status"></div>
	
	<!-- multiple file upload form -->
	<form action="upload.php" method="post" enctype="multipart/form-data" class="pure-form">
		<input type="file" name="files[]" multiple="multiple" id="files">
		<input type="submit" value="Upload" class="pure-button pure-button-primary">
	</form>
	
	<!-- progress bar -->
	<div class="progress">
		<div class="bar"></div >
		<div class="percent">0%</div >
	</div>

</div><!-- end .container -->

	<!-- javascript dependencies -->
	<script type="text/javascript" src="../multiple-file-upload-progress-bar/js/jquery.min.js"></script>
	<script type="text/javascript" src="../multiple-file-upload-progress-bar/js/jquery.form.min.js"></script>
	
	<!-- main script -->
	<script type="text/javascript" src="../multiple-file-upload-progress-bar/js/script.js"></script>

</body>
</html>