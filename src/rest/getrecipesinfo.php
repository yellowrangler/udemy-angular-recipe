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
	$log->writeLog("DB error: $dberr - Error mysql connect. Unable to get recipes & ingredients information.");

	$rv = "";
	exit($rv);
}

if (!mysql_select_db($DBschema, $dbConn))
{
	$log = new ErrorLog("logs/");
	$dberr = mysql_error();
	$log->writeLog("DB error: $dberr - Error selecting db Unable to get recipes & ingredients information.");

	$rv = "";
	exit($rv);
}

//---------------------------------------------------------------
// get all recipes
//---------------------------------------------------------------
$sql = "SELECT *  FROM recipestbl";

// print $sql;

$sql_result_recipe = @mysql_query($sql, $dbConn);
if (!$sql_result_recipe)
{
    $log = new ErrorLog("logs/");
    $sqlerr = mysql_error();
    $log->writeLog("SQL error: $sqlerr - Error doing select to db Unable to get recipes information.");
    $log->writeLog("SQL: $sql");

    $status = -100;
    $msgtext = "System Error: $sqlerr";
}

$count = 0;
$recipes = array();
$count = mysql_num_rows($sql_result_recipe);
if ($count > 0)
{
	//
	// fill the array
	//
	while($r = mysql_fetch_assoc($sql_result_recipe)) {
		$recipename = $r['name'];

		//---------------------------------------------------------------
		// get all recipe ingredients
		//---------------------------------------------------------------
		$sql = "SELECT name, amount  FROM ingredientstbl where recipename = '$recipename'";

		// print $sql;

		$sql_result_ingredients = @mysql_query($sql, $dbConn);
		if (!$sql_result_ingredients)
		{
		    $log = new ErrorLog("logs/");
		    $sqlerr = mysql_error();
		    $log->writeLog("SQL error: $sqlerr - Error doing select to db Unable to get ingredients information.");
		    $log->writeLog("SQL: $sql");

		    $status = -100;
		    $msgtext = "System Error: $sqlerr";
		}

		$ingredients = array();
		while($i = mysql_fetch_assoc($sql_result_ingredients)) {
		    $ingredients[] = $i;
		}
		$r['ingredients'] = $ingredients;
	    $recipes[] = $r;
	}
}

//
// close db connection
//
mysql_close($dbConn);

//
// pass back info
//

exit(json_encode($recipes));

?>
