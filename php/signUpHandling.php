<?php
$servername = "localhost";
$username = "fisherharris_HackAdmin";
$password = "Hackathon2018";
$dbname = "fisherharris_Hackathon2018";

$clientId = "354034323316-crkljsls1bslbnsmvq530dq9ok19nvp1.apps.googleusercontent.com";

$userId = null;

$conn = new mysqli($servername, $username, $password, $dbname); //connect to the databse
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
} 

function authenticate() //Ensure this user is valid and authenticated
{
  $userToken = $_POST["userToken"];
  $http = "https://www.googleapis.com/oauth2/v3/tokeninfo?id_token=$userToken";
  $googleAPIConnection = curl_init($http);
  curl_setopt($googleAPIConnection, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($googleAPIConnection, CURLOPT_URL,$http);
  $auth = curl_exec($googleAPIConnection);
  $aud = json_decode($auth)->{'aud'};
  $sub = json_decode($auth)->{'sub'};
  $fname = json_decode($auth)->{'given_name'};
  $lname = json_decode($auth)->{'family_name'};
  $email = json_decode($auth)->{'email'};
  
  $sql = "SELECT ID FROM User WHERE (Token = '$sub')";
  $result = $GLOBALS['conn']->query($sql);
  $rowCount = $result->num_rows;
  
  if($aud === $GLOBALS['clientId']) //The token is for a valid user
  {
    if($rowCount == 0) //This user is authentic, but not in the userbase
    {
      $sql = "INSERT INTO User(Token, FName, LName, Email) VALUES ('$sub', '$fname' ,'$lname', '$email')";
      $result = $GLOBALS['conn']->query($sql);
    }
    $GLOBALS['userId'] = $sub;
  }

  else
    die("User Authentication Failed");

}
authenticate();
mysql_close(); //close the database connection
?>