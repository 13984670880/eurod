<?php
/**
 * Jcrop image cropping plugin for jQuery
 * Cropping and saving script
 * From Codelixir
 */

if ($_SERVER['REQUEST_METHOD'] == 'POST')
{
	$jpeg_quality = 90;

	echo '<pre>';
	print_r($_POST);
	echo '</pre>';

	$src = 'images/flowers.jpg';
	$img_r = imagecreatefromjpeg($src);
	$dst_r = imagecreatetruecolor($_POST['tw'], $_POST['th']);

	imagecopyresampled($dst_r,$img_r,0,0,$_POST['x'],$_POST['y'],$_POST['tw'],$_POST['th'],$_POST['w'],$_POST['h']);
	imagejpeg($dst_r,time().'.jpg',$jpeg_quality);

	header('Content-type: image/jpeg');
	imagejpeg($dst_r,null,$jpeg_quality);
	exit;
}

// If not a POST request, display page below:

?><html>
	<head>
		<title>Jcrop</title>
		<script src="js/jquery.min.js"></script>
		<script src="js/jquery.Jcrop.js"></script>
		<link rel="stylesheet" href="css/jquery.Jcrop.css" type="text/css" />

		<script language="Javascript">

			$(function(){

				$('#cropbox').Jcrop({
					//aspectRatio: 1,
					onChange: showPreview,
					onSelect: showPreview,					
					onSelect: updateCoords
				});

				$('.preview').click(function(){
					
					$('.preview').parent().css('border','2px solid black');
					$(this).parent().css('border','2px solid red');
					$('#th').val($(this).parent().height());
					$('#tw').val($(this).parent().width());
				})

			});

			function updateCoords(c)
			{
				$('#x').val(c.x);
				$('#y').val(c.y);
				$('#w').val(c.w);
				$('#h').val(c.h);
			};

			function checkCoords()
			{
				if (parseInt($('#w').val())) return true;
				alert('Please select a crop region then press submit.');
				return false;
			};

			function showPreview(coords)
			{
				if (parseInt(coords.w) > 0)
				{
					$('.preview').each(function(){
						var rx = $(this).parent().width() / coords.w;
						var ry = $(this).parent().height() / coords.h;
						
						$(this).css({
							width: Math.round(rx * 500) + 'px',
							height: Math.round(ry * 370) + 'px',
							marginLeft: '-' + Math.round(rx * coords.x) + 'px',
							marginTop: '-' + Math.round(ry * coords.y) + 'px'
						});

					});


				}
			}			

		</script>

	</head>

	<body>

	<div id="outer">
		<div class="jcExample">
			<div class="article">
				<h1>Jcrop</h1>

				<!-- This is the image we're attaching Jcrop to -->
				<img src="images/flowers.jpg" id="cropbox" />

				<!-- This is the form that our event handler fills -->
				<form action="crop.php" method="post" onsubmit="return checkCoords();">
					<input type="hidden" id="x" name="x" />
					<input type="hidden" id="y" name="y" />
					<input type="hidden" id="w" name="w" />
					<input type="hidden" id="h" name="h" />
					<input type="hidden" id="tw" name="tw" />
					<input type="hidden" id="th" name="th" />			
					Select a crop image size below then click: <input type="submit" value="Crop Image" />
				</form>
			</div>

			<div style="width:80px;height:60px;overflow:hidden;border: 2px solid black;margin: 2px;"><img src="images/flowers.jpg" class="preview" /></div>
			<div style="width:200px;height:150px;overflow:hidden;border: 2px solid black;margin: 2px;"><img src="images/flowers.jpg" class="preview" /></div>	
			<div style="width:400px;height:300px;overflow:hidden;border: 2px solid black;margin: 2px;"><img src="images/flowers.jpg" class="preview" /></div>	
			<div style="width:500px;height:350px;overflow:hidden;border: 2px solid black;margin: 2px;"><img src="images/flowers.jpg" class="preview" /></div>
		</div>
	</div>
	</body>
</html>