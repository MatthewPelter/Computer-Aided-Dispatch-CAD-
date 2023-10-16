<?php
include('./classes/connect.php');

$sql = "SELECT id, body, uid, loc, posty, severity, timey FROM messages";
$result = $conn->query($sql);

if ($result->num_rows > 0) {

    while($row = $result->fetch_assoc()) {
		echo "<div class='formbox'><strong>".$row['uid']." | ".$row['loc']." | ".$row['posty']." | ".$row['severity']." | ".$row['body']." | ".$row['timey']."</strong><input class='bttn' id=".$row['id']." name='delete' onClick='callDell(this.id);' type='submit' value='Delete'></div>".'<hr />';
	}
} else {
    echo "No Active Calls";
}
$conn->close();
?>