<!DOCTYPE html>
<html lang="en">
<head>
	<title></title>
	<link href="../css/croppic.css" rel="stylesheet">
	<style type="text/css">
		#cropContaineroutput {
			width: 200px;
			height: 200px;
			border:1px solid #ccc;
			position:relative; /* or fixed or absolute */
		}
	</style>
</head>
<body>
<div class="row">
	<div class="large-6 columns">
		<div id="cropContaineroutput"></div>
	</div>
	<div class="large-6 columns">&nbsp;</div>
</div>
</body>
<script src="../js/jquery.js"></script>
{{HTML::script('../../../js/croppic.js')}}
<script type="text/javascript">
	var croppicContaineroutputOptions = {
				uploadUrl:'../userphoto/php/img_save_to_file.php',
				cropUrl:'../userphoto/php/img_crop_to_file.php', 
				outputUrl:'../userphoto/php/',
				modal:false,
				loaderHtml:'<div class="loader bubblingG"><span id="bubblingG_1"></span><span id="bubblingG_2"></span><span id="bubblingG_3"></span></div> '
		}
	var cropContaineroutput = new Croppic('cropContaineroutput', croppicContaineroutputOptions);
</script>
</html>