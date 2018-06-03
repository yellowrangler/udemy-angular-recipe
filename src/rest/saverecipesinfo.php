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


//
// delete all recipes and ingredients
//
$sql = "DELETE FROM recipestbl";

// print $sql;

$sql_result = @mysql_query($sql, $dbConn);
if (!$sql_result)
{
	$log = new ErrorLog("logs/");
	$sqlerr = mysql_error();
	$log->writeLog("SQL error: $sqlerr - Error doing select to db Unable to delete recipes information.");
	$log->writeLog("SQL: $sql");

	$status = -100;
	$msgtext = "System Error: $sqlerr";
}

$sql = "DELETE FROM ingredientstbl";

// print $sql;

$sql_result = @mysql_query($sql, $dbConn);
if (!$sql_result)
{
	$log = new ErrorLog("logs/");
	$sqlerr = mysql_error();
	$log->writeLog("SQL error: $sqlerr - Error doing select to db Unable to delete ingredient information.");
	$log->writeLog("SQL: $sql");

	$status = -100;
	$msgtext = "System Error: $sqlerr";
}


//---------------------------------------------------------------
// loop and look for recipe name. Then insert ingredients
//---------------------------------------------------------------

$inserts = 0;
$updates = 0;
foreach ($recipes as $rkey => $recipe) {
	$name = $recipe['name'];
	$description = $recipe['description'];
	$imagePath = $recipe['imagePath'];
	$ingredients = $recipe['ingredients'];

	// Do Insert
	$sql = "INSERT INTO  recipestbl ( name,  description,  imagepath )
		VALUES ('$name', '$description', '$imagePath')";

	$inserts++;

	$sql_result = @mysql_query($sql, $dbConn);
	if (!$sql_result)
	{
	    $log = new ErrorLog("logs/");
	    $sqlerr = mysql_error();
	    $log->writeLog("SQL error: $sqlerr - Error doing insert to db Unable to store recipes information.");
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
