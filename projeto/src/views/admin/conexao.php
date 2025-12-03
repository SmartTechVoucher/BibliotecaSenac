<?php
$servername = "localhost";
$username = "root"; 
$password = "";     
$database = "bibliotecasenac"; 


$conn = new mysqli($servername, $username, $password, $database);

if ($conn->connect_error) {
    die("Conexão falhou: " . $conn->connect_error);
}

/*
que file é essa, qual o propósito disso aqui?
*/

