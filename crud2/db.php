<?php


$hostname="localhost";
$username="root";
$password="";
$dbname="crud2";



$conn=mysqli_connect($hostname,$username,$password,$dbname);


if(!$conn){
    die("Connection Failed:".mysqli_connect_error());
}



?>