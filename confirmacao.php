<?php

   require_once 'connectvars.php';   
   $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) or die("Erro ao conectar na base de dados.");   
   $email = mysqli_real_escape_string($dbc, trim($_POST['email_conf']));
   $codigo = mysqli_real_escape_string($dbc, trim($_POST['codigo_conf']));   
   $query = "SELECT email, codigo FROM usuarios WHERE email = '$email' AND codigo = $codigo";
   $result = mysqli_query($dbc, $query);
   if(mysqli_num_rows($result) == 1) {
      echo "<h1>Código confirmado com sucesso!</h1>";
      echo 'Faça seu login: <a href="index.php">Login</a>';
      $query = "UPDATE usuarios SET aprovado = 1";
      $result = mysqli_query($dbc, $query);
   }
   else {
      echo "<h1>Código não foi confirmado com sucesso!</h1>";
   }