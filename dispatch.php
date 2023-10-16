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

if (isset($_POST['send'])) {
	
	if (!isset($_POST['nocsrf'])) {
        die("INVALID TOKEN");
    }
	
    if ($_POST['nocsrf'] != $_SESSION['token']) {
        die("INVALID TOKEN");
    }
	
	if($_POST['body'] == "" || $_POST['location'] == "" || $_POST['postal'] == "" || $_POST['status'] == ""){
		echo "Nothing was entered";
		session_destroy();
	} else {
		$unitt = 1 . "-" . rand(100, 190) . "-" . rand(10, 19);
		$unitID = (string)$unitt;
		
		$hours = date("h:i:s") . " EST";
		
		DB::query("INSERT INTO messages VALUES ('', :body, :uid, :loc, :posty, :severity, :timey)", array(':body'=>$_POST['body'], ':uid'=>$unitID, ':loc'=>$_POST['location'], ':posty'=>$_POST['postal'], ':severity'=>$_POST['status'], ':timey'=>$hours));
		session_destroy(); 
	} 
}

?>
<h1>Dispatch Hub:</h1>
<h1 id="timer"></h1>
<form id="f1" method="post">
		<h3>Location</h3>
		<textarea name="location" rows="1" cols="30"></textarea>
		<h3>Postal</h3>
		<textarea name="postal" rows="1" cols="30"></textarea>
		<h3>Severity</h3>
		<select name="status">
			<option value="select">Select Severity</option>
			<option value="Green">Green (Code 1)</option>
			<option value="Blue">Blue (Code 2)</option>
			<option value="Red">Red (Code 3)</option>
		</select>
		<h3>Description</h3>
        <textarea name="body" rows="8" cols="60"></textarea>
		<input type="hidden" name="nocsrf" value="<?php echo $_SESSION['token']; ?>">
		<input type="submit" name="send" value="Send Message">
</form>

<h1>Dispatch Center:</h1>

<div id="datacontainer">

</div>

<style>

.formbox {
	padding:20px;
	background: #333;
	color: white;
	width: 50%;
	position: relative;
}

.knob {
	position: absolute;
	float: right;
}

</style>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script type="text/javascript">
$(document).ready(function() {
 	 $("#datacontainer").load("retrieve-datadis.php");
   var refreshId = setInterval(function() {
      $("#datacontainer").load('retrieve-datadis.php');
   }, 3000);
   $.ajaxSetup({ cache: false });
   
   setInterval(function() {
    var currentTime = new Date ( );    
    var currentHours = currentTime.getHours ( );   
    var currentMinutes = currentTime.getMinutes ( );   
    var currentSeconds = currentTime.getSeconds ( );
    currentMinutes = ( currentMinutes < 10 ? "0" : "" ) + currentMinutes;   
    currentSeconds = ( currentSeconds < 10 ? "0" : "" ) + currentSeconds;    
    var timeOfDay = ( currentHours < 12 ) ? "AM" : "PM";    
    currentHours = ( currentHours > 12 ) ? currentHours - 12 : currentHours;    
    currentHours = ( currentHours == 0 ) ? 12 : currentHours;    
    var currentTimeString = currentHours + ":" + currentMinutes + ":" + currentSeconds + " " + timeOfDay;
    document.getElementById("timer").innerHTML = currentTimeString;
}, 1000);
   
});


function callDell(str) {
	var xmlhttp = new XMLHttpRequest();
	xmlhttp.onreadystatechange = function() {
		if (this.readyState == 4 && this.status == 200) {
			//document.getElementById("txtHint").innerHTML = this.responseText;
		}
	};
	xmlhttp.open("GET", "deletecall.php?id=" + str, true);
	xmlhttp.send();
}


</script>