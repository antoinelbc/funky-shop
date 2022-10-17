<?php

//---- SESSION ----//

session_start();


//---- ROUTES ----//

define("ROOT_SITE", "/funky-shop/");
//echo ROOT_SITE;

//----  VARIABLES ----//

$content = "";


//----  REQUIRED FILES ----//

require_once("functions.php");
require_once("database.php");