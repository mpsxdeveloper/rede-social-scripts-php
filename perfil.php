<?php
 
   require_once('appvars.php');
   require_once('connectvars.php');  
   session_start();
   if(!isset($_SESSION['id']) && !isset($_SESSION['nome'])) {
      echo "Você precisa logar para acessar essa página!";
      echo "<br /><a href='index.php'>Login</a>";
      exit();
   }
   else {
      $id = $_SESSION['id'];
      $nome = $_SESSION['nome'];
      $dbc = mysqli_connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME) or die("Erro ao conectar na base de dados.");
   }
   $query = "SELECT screenshot, usuario_id FROM fotos WHERE usuario_id = $id";
   $data = mysqli_query($dbc, $query);
   while($row = mysqli_fetch_array($data)) {
      $foto_nome = $row['screenshot'];
   }   
   if(isset($_POST['submit'])) {
      $foto_nome = $_FILES['foto']['name'];
      $foto_tipo = $_FILES['foto']['type'];
      $foto_tamanho = $_FILES['foto']['size'];
      if(!empty($foto_nome)) {
         if((($foto_tipo == 'image/jpeg') || ($foto_tipo == 'image/png')) && ($foto_tamanho > 0) && ($foto_tamanho <= REDESOCIAL_MAXIMO_TAMANHO)) {
            if($_FILES['foto']['error'] == 0) {
               $foto_nome = 'foto' . $id;    
               $target = REDESOCIAL_CAMINHO . $foto_nome;
               if(move_uploaded_file($_FILES['foto']['tmp_name'], $target)) {
                  $query = "SELECT usuario_id FROM fotos WHERE usuario_id = $id";
                  $result = mysqli_query($dbc, $query);
                  if(mysqli_num_rows($result) == 1) {
                     $query = "UPDATE fotos SET screenshot = '$foto_nome' WHERE usuario_id = $id";   
                     mysqli_query($dbc, $query);          
                  }
                  else {
                     $query = "INSERT INTO fotos (screenshot, usuario_id) VALUES ('$foto_nome', $id)";
                     mysqli_query($dbc, $query);
                  }
               }
               else {
                  echo '<p class="error">Desculpe, ocorreu um erro ao enviar sua foto.</p>';
               }
            }
            else {
               echo '<p class="error">A foto deve estar no formato JPEG ou PNG e o tamanho não pode exceder ' . (REDESOCIAL_MAXIMO_TAMANHO / 1024) . ' KB.</p>';
            }
            @unlink($_FILES['foto']['tmp_name']);
         }
         else {
            echo '<p class="error">Desculpe, ocorreu um erro ao enviar sua foto.</p>';
         }
      }
      mysqli_close($dbc);
   }
   if(isset($_POST['pessoal'])) {
      $relacionamento = mysqli_real_escape_string($dbc, trim($_POST['relacionamento']));
      $interesse = mysqli_real_escape_string($dbc, trim($_POST['interesse']));
      $religiao = mysqli_real_escape_string($dbc, trim($_POST['religiao']));
      $cidade = mysqli_real_escape_string($dbc, trim($_POST['cidade']));
      $morando = mysqli_real_escape_string($dbc, trim($_POST['morando']));
      $query = "SELECT relacionamento, interesse, religiao, cidade, morando, usuario_id FROM perfis_pessoais WHERE usuario_id = $id";
      $result = mysqli_query($dbc, $query);
      if(mysqli_num_rows($result) == 1) {
         $row = mysqli_fetch_array($result); 
         if(empty($_POST['relacionamento'])) $relacionamento = $row['relacionamento'];
         if(empty($_POST['interesse'])) $interesse = $row['interesse'];
         if(empty($_POST['religiao'])) $religiao = $row['religiao'];
         if(empty($_POST['cidade'])) $cidade = $row['cidade'];
         if(empty($_POST['morando'])) $morando = $row['morando'];
         $query = "UPDATE perfis_pessoais SET relacionamento = '$relacionamento', interesse = '$interesse', religiao = '$religiao', cidade = '$cidade', morando = '$morando' WHERE usuario_id = $id";   
         mysqli_query($dbc, $query);         
      }
      else {
         $query = "INSERT INTO perfis_pessoais (relacionamento, interesse, religiao, cidade, morando, usuario_id) VALUES ('$relacionamento', '$interesse', '$religiao', '$cidade', '$morando', $id)";
         mysqli_query($dbc, $query);
      }
   }
   mysqli_close($dbc);
 
?>

<html>
<head>
   <title>Perfil</title>
   <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
   <link type="text/css" rel="stylesheet" href="css/perfil.css" />
</head>

<body>

   <form enctype="multipart/form-data" method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
      <fieldset>
         <legend>Escolha sua foto para o perfil</legend>
         <input type="hidden" name="MAX_FILE_SIZE" value="<?php echo REDESOCIAL_MAXIMO_TAMANHO; ?>" />
         <label for="foto">Foto:</label>
         <input type="file" id="foto" name="foto" />
         <input type="submit" value="Atualizar Foto" name="submit" />
         <?php echo '<span><img src="' . REDESOCIAL_CAMINHO . $foto_nome . '" alt="Foto do perfil" width="100" height="100" /></span>'; ?>
      </fieldset>
   </form>
    
   <form method="post" action="<?php echo $_SERVER['PHP_SELF']; ?>">
      <fieldset>
         <legend>Pessoal</legend>    
         <table>
            <tr>
               <th>Relacionamento:</th>
               <td>
                  <select id="relacionamento" name="relacionamento">
                     <option value=""></option>
                     <option value="Solteiro(a)">Solteiro(a)</option>
                     <option value="Namorando">Namorando</option>
                     <option value="Noivo(a)">Noivo(a)</option>
                     <option value="Casado(a)">Casado(a)</option>
                     <option value="Divorciado(a)">Divorciado(a)</option>
                     <option value="Viúvo(a)">Viúvo(a)</option>
                  </select>
               </td>
            </tr>
            <tr>
               <th>Interesse em:</th>
               <td>
                  <select id="interesse" name="interesse">
                     <option value=""></option> 
                     <option value="Amizades">Amizades</option>
                     <option value="Namoro">Namoro</option>
                     <option value="Contatos Profissionais">Contatos Profissionais</option>
                     <option value="Nada">Nada</option>
                     <option value="Outros Interesses">Outros Interesses</option>
                  </select>
               </td>
            </tr>
            <tr>
               <th>Religião:</th>
               <td>
                  <select id="religiao" name="religiao">
                     <option value=""></option>
                     <option value="Agnosticismo">Agnosticismo</option>
                     <option value="Adventismo">Adventismo</option> 
                     <option value="Ateísmo">Ateísmo</option>
                     <option value="Catolicismo">Catolicismo</option>
                     <option value="Espiritismo">Espiritismo</option>
                     <option value="Islamismo">Islamismo</option>
                     <option value="Judaísmo">Judaísmo</option>
                     <option value="Mormonismo">Mormonismo</option>
                     <option value="Protestantismo">Protestantismo</option>
                     <option value="Testemunha de Jeová">Testemunha de Jeová</option>
                     <option value="Outra Religião">Outra Religião</option>
                  </select>
               </td>
            </tr>
            <tr>
               <th>Cidade Natal:</th>
               <td><input type="text" id="cidade" name="cidade" maxlength="30" size="40" /></td>
            </tr>
            <tr>
               <th>Morando em:</th>
               <td><input type="text" id="morando" name="morando" maxlength="30" size="40" /></td>
            </tr>
            <tr>
               <td></td>
               <td><input type="submit" value="Atualizar Perfil" name="pessoal" /></td>
            </tr>
         </table>
      </fieldset>
      <fieldset>
         <legend>Educação</legend>    
         <table>
            <tr>
               <th>Escolaridade:</th>
               <td>
                  <select id="escolaridade" name="escolaridade">
                     <option value="Ensino Fundamental - Em andamento">Ensino Fundamental - Em andamento</option>
                     <option value="Ensino Fundamental - Concluído">Ensino Fundamental - Concluído</option>
                     <option value="Ensino Médio - Em andamento">Ensino Médio - Em andamento</option>
                     <option value="Ensino Médio - Concluído">Ensino Médio - Concluído</option>
                     <option value="Ensino Superior - Em andamento">Ensino Superior - Em andamento</option>
                     <option value="Ensino Superior - Concluído">Ensino Superior - Concluído</option>
                     <option value="Especialização - Em andamento">Especialização - Em andamento</option>
                     <option value="Especialização - Concluído">Especialização - Concluído</option>
                     <option value="Mestrado - Em andamento">Mestrado - Em andamento</option>
                     <option value="Mestrado - Concluído">Mestrado - Concluído</option>
                     <option value="Doutorado - Em andamento">Doutorado - Em andamento</option>
                     <option value="Doutorado - Concluído">Doutorado - Concluído</option>
                  </select>
               </td>
            </tr>
            <tr>
               <td></td>
               <td><input type="submit" value="Atualizar Perfil" name="educacional" /></td>
            </tr> 
         </table>
      </fieldset>
   </form>  
    
</body>

</html>