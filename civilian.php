<?php
session_start();
$cstrong = True;
$token = bin2hex(openssl_random_pseudo_bytes(64, $cstrong));
if (!isset($_SESSION['token'])) {
        $_SESSION['token'] = $token;
}

include('./classes/DB.php');
include('./classes/Login.php');

if (Login::isLoggedIn()) {
        $userid = Login::isLoggedIn();
} else {
        die('Not logged in');
}
?>


<div class="header">
	<div class="headerwrap">
		<img src="https://i.imgur.com/l28E6wJ.png" />
		<h1 class="namer"> OPP Hub:</h1>
		<input type="radio" name="style" id="light" value="light" onchange="changeback();" checked /> Light
		<input type="radio" name="style" id="dark" onchange="changeback();" value="dark" /> Dark
		<h1 class="headtime" id="timer"></h1>
	</div>
</div>


<div class="navbar">
	<select name="status">
		<option value="dashboard">Dashboard</option>
		<option value="profile">Profile</option>
		<option value="admin">Admin</option>
	</select>
</div>

<h1>Current Calls:</h1>

<div id="datacontainer">

</div>

<style>
body{
	background: #ccc;
}

.header {
	background: #fff;
	position: fixed;
	top: 0;
	right: 0;
	left: 0; 	
}

.header img {
	width: auto;
	height: 80px;
	float: left;
}

.headerwrap {
	max-width: 1600px;
	margin: 0 auto;
}

.header .namer {
	float: left;
}

.header .headtime {
	float: right;
}

.lighter {
	background: #ccc; 
}

.darker {
	background: #333; 
	color: white;
}
</style>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
	
	/*
	$("#datacontainer").load("retrieve-dataopp.php");
	var refreshId = setInterval(function() {
		$("#datacontainer").load('retrieve-dataopp.php');
	}, 4000);
	$.ajaxSetup({
		cache: false
	});
	*/
	function changeback(){
		if (document.getElementById('light').checked) {
			$('body').removeClass('darker');
			$('body').addClass('lighter');
		} else if (document.getElementById('dark').checked) {
			$('body').removeClass('lighter');
			$('body').addClass('darker');
		}
	}
	setInterval(function() {
		var currentTime = new Date();
		var currentHours = currentTime.getHours();
		var currentMinutes = currentTime.getMinutes();
		var currentSeconds = currentTime.getSeconds();
		currentMinutes = (currentMinutes < 10 ? "0" : "") + currentMinutes;
		currentSeconds = (currentSeconds < 10 ? "0" : "") + currentSeconds;
		var timeOfDay = (currentHours < 12) ? "AM" : "PM";
		currentHours = (currentHours > 12) ? currentHours - 12 : currentHours;
		currentHours = (currentHours == 0) ? 12 : currentHours;
		var currentTimeString = currentHours + ":" + currentMinutes + " " + timeOfDay;
		document.getElementById("timer").innerHTML = currentTimeString;
	}, 1000);
});
</script>