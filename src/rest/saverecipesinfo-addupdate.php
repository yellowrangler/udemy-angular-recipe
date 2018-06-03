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
// get data
//

$recipes = json_decode(file_get_contents("php://input"), true);

//
// get date time for this transaction
//
$datetime = date("Y-m-d H:i:s");


// set variables
$enterdate = $datetime;

//
// messaging
//
$returnArrayLog = new AccessLog("logs/");

//------------------------------------------------------
// get admin user info
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
	$log->writeLog("DB error: $dberr - Error mysql connect. Unable to store recipes & Ingredient information.");

	$rv = "";
	exit($rv);
}

if (!mysql_select_db($DBschema, $dbConn))
{
	$log = new ErrorLog("logs/");
	$dberr = mysql_error();
	$log->writeLog("DB error: $dberr - Error selecting db Unable to store recipes & Ingredient information.");

	$rv = "";
	exit($rv);
}

//---------------------------------------------------------------
// loop and look for recipe name. if find update else insert
//---------------------------------------------------------------

$inserts = 0;
$updates = 0;
foreach ($recipes as $rkey => $recipe) {
	$name = $recipe['name'];
	$description = $recipe['description'];
	$imagePath = $recipe['imagePath'];
	$ingredients = $recipe['ingredients'];

	//
	// see if id exists
	//
	$sql = "SELECT *  FROM recipestbl WHERE name = '$name'";

	// print $sql;

	$sql_result = @mysql_query($sql, $dbConn);
	if (!$sql_result)
	{
	    $log = new ErrorLog("logs/");
	    $sqlerr = mysql_error();
	    $log->writeLog("SQL error: $sqlerr - Error doing select to db Unable to store recipes information.");
	    $log->writeLog("SQL: $sql");

	    $status = -100;
	    $msgtext = "System Error: $sqlerr";
	}

	$count = 0;
	$isUpdate = 0;
	$count = mysql_num_rows($sql_result);
	if ($count > 0)
	{
		// Do update
		$sql = "UPDATE recipestbl
			SET name='$name', description='$description', imagepath='$imagePath'
			WHERE name = '$name'";

		$updates++;
		$isUpdate = 1;
	}
	else
	{
		// Do Insert
		$sql = "INSERT INTO  recipestbl ( name,  description,  imagepath )
			VALUES ('$name', '$description', '$imagePath')";

		$inserts++;
		$isUpdate = 1;
	}

	$sql_result = @mysql_query($sql, $dbConn);
	if (!$sql_result)
	{
	    $log = new ErrorLog("logs/");
	    $sqlerr = mysql_error();
	    $log->writeLog("SQL error: $sqlerr - Error doing insert or update to db Unable to store recipes information.");
	    $log->writeLog("SQL: $sql");

	    $status = -100;
	    $msgtext = "System Error: $sqlerr";
	}

	// print $sql;

	//
	// store all ingrediet information for recipe
	// delete all ingredients if update.
	//
	$recipename = $name;

	if ($isUpdate == 1)
	{
		//
		// delete all ingredient data
		//
		$sql = "DELETE FROM ingredientstbl WHERE recipename = '$recipename'";

		// print $sql;

		$sql_result = @mysql_query($sql, $dbConn);
		if (!$sql_result)
		{
			$log = new ErrorLog("logs/");
			$sqlerr = mysql_error();
			$log->writeLog("SQL error: $sqlerr - Error doing select to db Unable to store delete Ingredient information.");
			$log->writeLog("SQL: $sql");

			$status = -100;
			$msgtext = "System Error: $sqlerr";
		}
	}


	foreach ($ingredients as $ikey => $ingredient) {
		$name = $ingredient['name'];
		$amount = $ingredient['amount'];

		// Do Insert
		$sql = "INSERT INTO  ingredientstbl ( recipename, name,  amount )
			VALUES ('$recipename', '$name', '$amount')";

		$sql_result = @mysql_query($sql, $dbConn);
		if (!$sql_result)
		{
		    $log = new ErrorLog("logs/");
		    $sqlerr = mysql_error();
		    $log->writeLog("SQL error: $sqlerr - Error doing insert or update to db Unable to store Ingredient information.");
		    $log->writeLog("SQL: $sql");

		    $status = -100;
		    $msgtext = "System Error: $sqlerr";
		}
	}
}

//
// Build message
//
$msg = "Updates:".$updates." Added:".$inserts;

// close db connection
//
mysql_close($dbConn);

//
// pass back info
//

exit($msg);

?>
