<?php
session_start();
include ('template\header.html');
if(isset($_SESSION["user_name"]))
{
	include('template\hello.html');
}	
else
{
	include('template\auth.html');
	include('template\reg.html');
}
?>


