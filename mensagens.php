<?php

   require_once('connectvars.php');
   session_start();   
   if(!isset($_SESSION['id']) && !isset($_SESSION['nome'])) {
      echo "Você precisa logar para acessar essa página!";
      echo "<br /><a href='index.php'>Login</a>";
   }
   else {
      $id = $_SESSION['id'];
      $nome = $_SESSION['nome'];
      echo "Voce está logado como: " . $_SESSION['nome'];
      echo "<br />";
      echo "<hr />";  
      echo "<h2>Suas mensagens:</h2>";
      echo "<hr />";
      $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);
      $query = "SELECT mensagens.emissor_id, nome, mensagem, mensagens.data FROM usuarios, mensagens WHERE $id = mensagens.receptor_id
               AND usuarios.id = mensagens.emissor_id";
      $data = mysqli_query($dbc, $query);
      while($row = mysqli_fetch_array($data)) {
         echo $row['mensagem'] . '<br />De: ' . $row['nome'] . ' Data/Hora: ' . $row['data'] . '<hr />';
      }
   }

?>

<html>    
<head>
   <title>Mensagens</title>
   <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
   <script type="text/javascript" src="javascript/mensagens.js"></script>
   <link type="text/css" rel="stylesheet" href="css/mensagens.css" />
</head>
<body></body>
</html>