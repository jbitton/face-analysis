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
							$request = new Http_Request2('https://westus.api.cognitive.microsoft.com/emotion/v1.0/recognize');
							$url = $request->getUrl();

							$headers = array(
								// Request headers
								'Content-Type' => 'application/octet-stream',
								'Ocp-Apim-Subscription-Key' => '7f3289ade580439c8c64268a94ee45dc',
							);
							$request->setHeader($headers);

							$parameters = array(
								// Request parameters
							);

							$url->setQueryVariables($parameters);

							$request->setMethod(HTTP_Request2::METHOD_POST);


							//Request body
							$request->setBody(file_get_contents($_FILES['image']['tmp_name']));

							try
							{
								$response = $request->send();
								$emotion_string = $response->getBody();
							}
							catch (HttpException $ex)
							{
								$emotion_string = $ex;
							}

							// FOR THE COMPUTER VISION API
							$computer_vision_string = "";
							$request_2 = new Http_Request2('https://westus.api.cognitive.microsoft.com/vision/v1.0/analyze');
							$url_2 = $request->getUrl();

							$headers_2 = array(
								// Request headers
								'Content-Type' => 'application/octet-stream',
								'Ocp-Apim-Subscription-Key' => 'f9836bbf61464fb4ae0055ed57d44dfb',
							);
							$request_2->setHeader($headers_2);

							$parameters_2 = array(
								// Request parameters
							);

							$url_2->setQueryVariables($parameters_2);

							$request_2->setMethod(HTTP_Request2::METHOD_POST);


							//Request body
							$request_2->setBody(file_get_contents($_FILES['image']['tmp_name']));

							try
							{
								$response_2 = $request_2->send();
								$computer_vision_string = $response_2->getBody();
							}
							catch (HttpException $ex_2)
							{
								$computer_vision_string = $ex_2;
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
					<p>fill this area with results from json response from emotion API</p><br>
					<p><?php echo $emotion_string; ?></p>
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
					<p>fill this area with results from json response from computer vision API</p><br>
					<p><?php echo $computer_vision_string; ?></p>
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