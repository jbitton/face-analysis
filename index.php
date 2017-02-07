<!DOCTYPE HTML>
<?php
	require_once 'HTTP/Request2.php';
?>
<html>
	<head>
		<title>Face Analysis</title>
		<meta charset="utf-8" />
		<meta name="viewport" content="width=device-width, initial-scale=1" />
		<!--[if lte IE 8]><script src="assets/js/ie/html5shiv.js"></script><![endif]-->
		<link rel="stylesheet" href="assets/css/main.css" />
		<!--[if lte IE 8]><link rel="stylesheet" href="assets/css/ie8.css" /><![endif]-->
	</head>
	<body>

		<!-- Header -->
			<section id="header">
				<header>
					<h1>Face Analysis</h1>
					<p>upload an image, and see your results</p>
				</header>
				<footer>
					<a href="#banner" class="button style2 scrolly-middle">TRY IT OUT</a>
				</footer>
			</section>

		<!-- Banner -->
			<section id="banner">
				<header>
					<h2>Welcome to the Face Analysis App</h2>
				</header>
				<p>Upload an image, and we'll tell you your age, gender, emotions, and a lot more!</p><br>
				
				<form method="post" enctype="multipart/form-data">
					<div class="row 50%">
						<div class="12u$"><input type="file" class="file" name="image"/></div>
						<div class="12u$"><input type="submit" name="submit" value="Submit" /></div>
					</div>
					
					<?php
						if (isset($_POST['submit'])) {
							// FOR THE EMOTION API
							$emotion_string = "";
							// create a new HTTP_Request2 object with API url and specify the need for the post method
							$request = new Http_Request2('https://westus.api.cognitive.microsoft.com/emotion/v1.0/recognize', HTTP_Request2::METHOD_POST);
							$url = $request->getUrl();

							$headers = array(
								// request headers
								'Content-Type' => 'application/octet-stream',
								'Ocp-Apim-Subscription-Key' => '7f3289ade580439c8c64268a94ee45dc',
							);
							
							// set the header
							$request->setHeader($headers);

							// set request body to the image uploaded
							$request->setBody(file_get_contents($_FILES['image']['tmp_name']));

							try
							{
								// send request and get JSON string back
								$emotion_string = $request->send()->getBody();
							}
							catch (HttpException $exception)
							{
								// set emotion string to equal the exception
								$emotion_string = $exception;
							}

							// FOR THE FACE API
							$face_string = "";
							// create a new HTTP_Request2 object with API url and specify the need for the post method
							$request_2 = new Http_Request2('https://westus.api.cognitive.microsoft.com/face/v1.0/detect?returnFaceId=false&returnFaceLandmarks=false&returnFaceAttributes=age,gender,smile', HTTP_Request2::METHOD_POST);
							$url_2 = $request->getUrl();

							$headers_2 = array(
								// request headers
								'Content-Type' => 'application/octet-stream',
								'Ocp-Apim-Subscription-Key' => '0d88364fff184b69bfddbf394b874036',
							);
							
							// set the header
							$request_2->setHeader($headers_2);

							// set request body to the image uploaded
							$request_2->setBody(file_get_contents($_FILES['image']['tmp_name']));

							try
							{
								// send request and get JSON string back
								$face_string = $request_2->send()->getBody();
							}
							catch (HttpException $exception_2)
							{
								// set face string to equal exception
								$face_string = $exxception_2;
							}
						}
					?>
				</form>
			</section>

		<!-- Feature 1 -->
			<article id="first" class="container box style1 right">
				<a href="#" class="image fit"><img src="images/pic01.jpg" alt="" /></a>
				<div class="inner">
					<header>
						<h2>Your Results<br />
						Emotion</h2>
					</header>
					<p><?php
						// check if $emotion_string is initialized
						if (isset($emotion_string))
						{
							// check if there was an exception
							if (isset($exception)) {
								echo "<p>There has been an issue with the API. Contact support for more help</p>";
							} else {
								// turn JSON string into an associative array
								$json = json_decode($emotion_string, true);
								// check if the JSON is empty or if there was an error
								if (empty($json) || isset($json['error'])) {
									echo "<p>There has been an error. Check if you entered a valid image.</p>";
								} else {
									// output $json contents because it is valid
									echo "<p>From the analysis of your image, the most prevalent emotion is: </p>";
									$max = -999999999.0;
									$maxscore = "";
									// find the maximum score
									foreach ($json[0]['scores'] as $key => $value) {
										if(floatval($value) > floatval($max)) {
											$max = $value;
											$maxscore = $key;
										}
									}
									
									// output it to user
									echo "<p><strong>" . $maxscore . "</strong> with a " . ($max * 100) . " percent accuracy</p>";
								}
							}
						} else {
							// default message before submitting
							echo "<p>Please submit the form to populate this area</p>";
						}
					?></p>
				</div>
			</article>

		<!-- Feature 2 -->
			<article class="container box style1 left">
				<a href="#" class="image fit"><img src="images/pic02.jpg" alt="" /></a>
				<div class="inner">
					<header>
						<h2>Your Results<br />
						Age, Gender, and More</h2>
					</header>
					<p><?php
						// check if $face_string has been initialized
						if (isset($face_string))
						{
							// check if there was an exception
							if (isset($exception_2)) {
								echo "<p>There has been an issue with the API. Contact support for more help</p>";
							} else {
								// turn JSON string into a JSON object
								$json = json_decode($face_string);
								$test_arr = (array)$json;
								// check if JSON is empty or if there has been an error
								if (empty($test_arr) || isset($json->error)) {
									echo "<p>There has been an error. Check if you entered a valid image.</p>";
								} else {
									// otherwise, output results from JSON
									echo "<p>From the analysis of your image, this is what we found: </p>";
									echo "<ul><li>Age: " . $json[0]->faceAttributes->age . "</li><li>Gender: " . $json[0]->faceAttributes->gender . "								    </li><li>Smiling percentage: " . ($json[0]->faceAttributes->smile * 100) . "</li></ul>";
								}
							}
						} else {
							// default message before submitting
							echo "<p>Please submit the form to populate this area</p>";
						}
					?></p>
				</div>
			</article>

		<section id="footer">
			<div class="copyright">
				<ul class="menu">
					<li>&copy; Joanna Bitton. All rights reserved. 2017</li>
				</ul>
			</div>
		</section>

		<!-- Scripts -->
			<script src="assets/js/jquery.min.js"></script>
			<script src="assets/js/jquery.scrolly.min.js"></script>
			<script src="assets/js/jquery.poptrox.min.js"></script>
			<script src="assets/js/skel.min.js"></script>
			<script src="assets/js/util.js"></script>
			<!--[if lte IE 8]><script src="assets/js/ie/respond.min.js"></script><![endif]-->
			<script src="assets/js/main.js"></script>

	</body>
</html>