<?php

   require_once 'connectvars.php';   
   $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) or die("Erro ao conectar na base de dados.");
   $email = mysqli_real_escape_string($dbc, trim($_POST['email_cad']));
   $senha = mysqli_real_escape_string($dbc, trim($_POST['senha_cad'])); 
   $nome = mysqli_real_escape_string($dbc, trim($_POST['nome_cad']));
   $sobrenome = mysqli_real_escape_string($dbc, trim($_POST['sobrenome_cad']));
   $nascimento = $_POST['ano'].'/'.$_POST['mes'].'/'.$_POST['dia'];
   $codigo = time();     
   $query = "INSERT INTO usuarios (email, senha, nome, sobrenome, data, codigo) 
             VALUES ('$email', SHA('$senha'), '$nome', '$sobrenome', '$nascimento', $codigo)";
   if(mysqli_query($dbc, $query)) {
      echo "<h2>Usuário cadastrado com sucesso!</h2>";
      echo "<h2>Acesse seu e-mail para pegar seu código de confirmação único.</h2>";
      echo '<h2><a href="confirmacao.php">Inserir Código de Confirmação</a></h2>';
   }
   else { 
      echo ('Erro ao tentar salvar perfil!<br /><a href="cadastro.php">Voltar para a tela de cadastro</a>');
   }

?>

<html>
<head>
   <title>Cadastro</title>
   <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
   <script type="text/javascript" src="javascript/cadastro.js"></script>
   <link type="text/css" rel="stylesheet" href="css/cadastro.css" />
</head>
<body></body>
</html>