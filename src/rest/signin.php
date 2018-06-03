<?php

include_once ('class/class.Log.php');
include_once ('class/class.ErrorLog.php');
include_once ('class/class.AccessLog.php');

// if cross browser request options ignore
if($_SERVER['REQUEST_METHOD'] == "OPTIONS")
{
	exit();
}

//
// get date time for this transaction
//
$datetime = date("Y-m-d H:i:s");

// get post values & set values for query
$parms = json_decode(file_get_contents("php://input"), true);

// print_r($parms);

$email = $parms["email"];
$password = $parms["password"];
$rc = 1;
$msgtext = "";

//
// messaging
//
$returnArrayLog = new AccessLog("logs/");
// $returnArrayLog->writeLog("Client List request started" );

//------------------------------------------------------
// check if already exists
//------------------------------------------------------
// open connection to host
$DBhost = "localhost";
$DBschema = "udemy";
$DBuser = "tarryc";
$DBpassword = "tarryc";

//
// connect to db
//
$dbConn = @mysql_connect($DBhost, $DBuser, $DBpassword);
if (!$dbConn)
{
	$log = new ErrorLog("logs/");
	$dberr = mysql_error();
	$log->writeLog("DB error: $dberr - Error mysql connect. Unable to signin for recipe email $email.");

	$rv = "";
	exit($rv);
}

if (!mysql_select_db($DBschema, $dbConn))
{
	$log = new ErrorLog("logs/");
	$dberr = mysql_error();
	$log->writeLog("DB error: $dberr - Error selecting db Unable to signin for recipe email $email.");

	$rv = "";
	exit($rv);
}

//---------------------------------------------------------------
// Get email password for compare.
//---------------------------------------------------------------
$sql = "SELECT email, password FROM usertbl WHERE email = '$email'";
// print $sql;

$rc = 1;
$sql_result = @mysql_query($sql, $dbConn);
if (!$sql_result)
{
	$log = new ErrorLog("logs/");
	$sqlerr = mysql_error();
	$log->writeLog("SQL error: $sqlerr - Error doing select to db Unable to signin for recipe email $email.");
	$log->writeLog("SQL: $sql");

	$rc = -100;
	$msgtext = "System Error: $sqlerr";
}

//
// check if we got any rows
//
if ($rc == 1)
{
	$count = mysql_num_rows($sql_result);
	if ($count == 1)
	{
		$row = mysql_fetch_assoc($sql_result);
		$tblemail = $row['email'];
		$tblpassword = $row['password'];

		//
		// passwords must match
		//
		if ($tblpassword != $password)
		{
			$rc = -1;
			$msgtext = "Password does not match password on file.";
		}
		else
		{
			$msgtext = "You are now logged in!";
		}
    }
    else {
        {
            $msgtext = "email not registered!";
        }
    }
}

//
// close db connection
//
mysql_close($dbConn);

//
// pass back info
//
$msg["rc"] = $rc;
$msg["text"] = $msgtext;

exit(json_encode($msg));
?>
