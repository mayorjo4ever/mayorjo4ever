<?php error_reporting(E_ALL^E_NOTICE); 
	require_once "config/config.php";
	require_once "config/DBController.php";
	require_once "config/dbTool.php";
	require_once "config/pdo_dal.php"; 
	
	/************/
	$dbm = new DbTool(); 
	$mydbm = new DBController(); 
	$mydal = new DAL(); 
	 
?>