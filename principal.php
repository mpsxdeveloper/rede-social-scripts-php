<?php

   require_once 'connectvars.php';
   
   session_start();
   if(isset($_SESSION['id']) && isset($_SESSION['nome'])) {
      $id = $_SESSION['id'];
      $nome = $_SESSION['nome'];
   }
   
   if(!isset($_POST['email_login']) || !isset($_POST['senha_login'])) {
      if(!isset($_SESSION['id']) && !isset($_SESSION['nome'])) {
         echo "Você precisa logar para acessar essa página!";
         echo "<br /><a href='index.php'>Login</a>";
         exit();
      }
   }
   else {
      $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) or die("Erro ao conectar na base de dados.");
      $email = mysqli_real_escape_string($dbc, trim($_POST['email_login']));
      $senha = mysqli_real_escape_string($dbc, trim($_POST['senha_login']));   
      $query = "SELECT id, email, senha, nome, aprovado FROM usuarios WHERE email = '$email' AND senha = SHA('$senha')";
      $result = mysqli_query($dbc, $query);
      if(mysqli_num_rows($result) == 1) { 
         $row = mysqli_fetch_array($result);
         if($row['aprovado'] == 0) {
            echo "<h1>Você ainda não inseriu seu código de confirmação.</h1>";
            echo '<h2>Verifique seu e-mail e depois acesse: <a href="confirmacao.php">Confirmar Código</a></h2>';
            mysqli_close($dbc);
            exit();
         }
         else {
            $id = $_SESSION['id'] = $row['id'];
            $nome = $_SESSION['nome'] = $row['nome'];
            mysqli_close($dbc);
         }
      }
      else {
         echo "<h1>Usuário e/ou senha incorretos!</h1>";
         echo '<h2>Verifique seu e-mail e sua senha: <a href="index.php">Página Inicial</a></h2>';
         mysqli_close($dbc);
         exit();
      }
   }
?>

<html>
   <head>
      <title>Principal</title>
      <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
      <script type="text/javascript" src="javascript/principal.js"></script>
      <link type="text/css" rel="stylesheet" href="css/principal.css" />
   </head>

   <body>
   
      <div id="tudo"> 
         <div id="topo">
            <img src="imagens/logotipo_pequeno.png" alt="logotipo pequeno" />
         </div>   
         <ul id="navegacao">
            <li><a href="principal.php">Página Inicial</a></li>
            <li><a href="perfil.php">Meu Perfil</a></li>
            <li><a href="amigos.php">Meus Amigos</a></li>
            <li><a href="mensagens.php">Mensagens</a></li>
            <li><a href="pesquisa.php">Pesquisa</a></li>
            <li><a href="configuracoes.php">Configurações</a></li>
            <li><a href="logout.php">Sair</a></li>
            <li><br /><hr /></li>
            <li><span style="padding-left: 2px;">Você está logado como:</span></li>
            <li><span style="padding-left: 2px;"><?php echo $nome ?></span></li>
            <li><hr /></li>
            <li><span style="padding-left: 2px;">Mensagem do dia:</span></li>
            <li><span style="padding-left: 2px;"><?php echo "Mensagem do dia" ?></span></li>
         </ul>      
         <div id="conteudos">
            <div id="principal">
         </div>
         <div id="auxiliar"></div>
      </div>
      <div id="rodape">Rede Social - 2013</div>   
   </div>
    
</body>
   
</html>