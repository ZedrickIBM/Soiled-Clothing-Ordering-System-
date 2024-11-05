<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Soiled Address</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="icon" type="image/x-icon" href="images/logo.png">
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;700&display=swap" rel="stylesheet">
    <style type="text/css">
		body{
			font-family: montserrat;
		}
        .container {
            height: 450px;
        }
		#map {
			width: 70%;
			height: 100%;
			border: 1px solid blue;
			margin: 0 auto; /* Centers horizontally */
		}
		.back-button {
            display: inline-block; /* Allows padding and margin */
            padding: 10px 15px; /* Adds space inside the button */
            font-size: 16px; /* Sets the font size */
            color: white; /* Text color */
            background-color: #231f20; /* Background color */
            border-radius: 5px; /* Rounds the corners */
            text-decoration: none; /* Removes underline */
            margin: 100px 0; /* Adds margin above and below */
			margin-left: 210px ;
            transition: background-color 0.3s ease, transform 0.3s ease; /* Smooth transition for hover effect */
        }
        /* Hover effect */
        .back-button:hover {
            background-color: #595a5a; /* Darker background on hover */
            transform: scale(1.1); /* Increases the size by 10% */
        }
    </style>
</head>
<body>
    <div class="container">
        <center><h1>Soiled in Maps</h1></center>
        <div id="map"></div>
    </div>
	<a href="aboutpage.php" class="back-button">Back</a>
    <script type="text/javascript">
        function loadMap() {
            var sol = {lat: 14.825523814503441, lng: 120.8652654816145};
            var map = new google.maps.Map(document.getElementById('map'), {
                zoom: 20, // Changed zoom level for better visibility
                center: sol
            });
        }
    </script>
    <script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBon8QUnJY2au6vA7ggrt4OH-CHfjuLo5w&callback=loadMap">
    </script>
</body>
</html>
