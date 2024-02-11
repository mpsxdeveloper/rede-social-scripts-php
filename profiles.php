<?php

   require_once('appvars.php');
   require_once 'connectvars.php';
   $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) or die("Erro ao conectar na base de dados.");
   $perfil = $_GET['perfil'];
   $query = "SELECT usuarios.id, usuarios.nome, usuarios.sobrenome, usuarios.email, perfis_pessoais.religiao, perfis_pessoais.relacionamento, perfis_pessoais.interesse, perfis_pessoais.cidade, perfis_pessoais.morando FROM usuarios, perfis_pessoais WHERE usuarios.email = '$perfil' AND perfis_pessoais.usuario_id = usuarios.id";
   $data = mysqli_query($dbc, $query);
   $row = mysqli_fetch_array($data);
   $amigo_id = $row['id'];
   $amigo_nome = $row['nome'];
   $amigo_sobrenome = $row['sobrenome'];
   $amigo_email = $row['email'];
   $amigo_religiao = $row['religiao'];
   $amigo_relacionamento = $row['relacionamento'];
   $amigo_interesse = $row['interesse'];
   $amigo_cidade = $row['cidade'];
   $amigo_morando = $row['morando'];
   echo 'Nome: ' . $amigo_nome . ' ' . $amigo_sobrenome . '<br />';
   echo 'E-mail: ' . $amigo_email . '<br />';
   echo 'Religião: ' . $amigo_religiao . '<br />';
   echo 'Relacionamento: ' . $amigo_relacionamento . '<br />'; 
   echo 'Interesse: ' . $amigo_interesse . '<br />';
   echo 'Nasceu em: ' .$amigo_cidade . '<br />';
   echo 'Morando em: ' . $amigo_morando . '<br />';
   $query = "SELECT screenshot FROM fotos WHERE usuario_id = $amigo_id";
   $data = mysqli_query($dbc, $query);
   $row = mysqli_fetch_array($data);
   $foto_nome = $row['screenshot'];
   echo '<span><img src="' . REDESOCIAL_CAMINHO . $foto_nome . '" alt="Foto do perfil" /></span>';

?>