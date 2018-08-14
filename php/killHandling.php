<?php
	//user details (obtained locally)
	$localScannerID = $_POST["localScannerID"];
	$localTargetID = $_POST["localTargetID"];
	$localLocationLat = $_POST["localLocationLat"];
	$localLocationLng = $_POST["localLocationLng"];

	//database details
	$servername = "localhostt";
	$username = "HackAdmin";
	$password = "Hackathon2018";
	$dbname = "fisherharris_Hackathon2018";

	//connect and check
	$connect = new mysqli($servername, $username, $password, $dbname);
	if($connect == false)
		die("Connection failed: " . mysqli_connect_error());

	//update scannerID's location
	$sqlSend = "UPDATE playerTable SET locationLat = $localLocationLat, locationLng = $localLocationLng WHERE scannerID = $localScannerID";
	if($connect -> query($sqlSend) == false)
					die("Failed to update database: " . $connect -> error);

	//get scanner and target ID pair from database for verification
	$sqlGet = "SELECT scannerID, targetID FROM playerTable";
	$result = $connect -> query($sqlGet);

	//check id's from database against local id
	if($result -> num_rows >0)
	{
		$flag = true;
		while($row = $result -> fetch_assoc() && flag)
		{
			//correct ID pairing; update kill
			if($row[scannerID] == $localScannerID && $row[targetID] == $localTargetID)
			{
				$flag = false;
				$sqlSend = "UPDATE playerTable SET playerDead='true' 
					WHERE scannerID=$localTargetID";
				//send newly dead player's status
				if($connect -> query($sqlSend) == false)
					die("Failed to update database: " . $connect -> error);

				//TODO indicate to user there was a kill; assign new target
			}
		}

		//incorrect ID pairing; no kill
		if($flag)
		{
			//TODO indicate to user there was no kill
		}
	}

	//close database
	mysqli_close($connect);
?>