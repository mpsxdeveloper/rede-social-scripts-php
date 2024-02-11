<?php
   require_once 'connectvars.php';      
?>

<html>
<head>
   <title>Pesquisa</title>
   <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
   <link type="text/css" rel="stylesheet" href="css/pesquisa.css" />
</head>

<body>

   <form name="submit" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>" > 
      <fieldset>
         <legend>Pesquisa</legend>
         <input type="text" name="pesquisa" />
         <input type="submit" name="submit" value="Pesquisar" />
      </fieldset>
   </form>
    
<?php
   
   if(!isset($_SESSION['id']) && !isset($_SESSION['nome'])) {
      echo "Você precisa logar para acessar essa página!";
      echo "<br /><a href='index.php'>Login</a>";
   }
   else {
      $id = $_SESSION['id'];
      $nome = $_SESSION['nome'];
   }
      
   echo "<hr />";
   if(isset($_POST['submit'])) {
      $pesquisa = $_POST['pesquisa'];
      if(!empty($nome)) {
         $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) or die("Erro ao conectar na base de dados.");
         $query = "SELECT usuarios.id as amigo, usuarios.nome, usuarios.sobrenome, usuarios.email FROM usuarios WHERE nome LIKE '$pesquisa%' AND usuarios.id <> $id";
         $data = mysqli_query($dbc, $query);
         echo '<table style="background-color: #90EE90;">';
         while($row = mysqli_fetch_array($data)) {
            echo '<tr>';
            echo '<td>';
            echo $row['nome'] . ' ' . $row['sobrenome'];
            echo '</td>'; 
            echo '<td>';
            echo '<a href="profile.php?perfil=' . $row['email'] . '">Ver Perfil</a>';
            echo '</td>';
            echo '</tr>';
         }
         echo '</table>';
      }
   }

?>
    
</body>

</html>