<!DOCTYPE HTML>
<html lang="br" class="no-js">
     <head>
          <meta charset="UTF-8">
          <meta name="viewport" content="width=device-width, initial-scale=1.0">
          <title>Sistema de Cadastro</title>
          <link href="style.css" rel="stylesheet" />
     </head>

     <body>

     <?php
     session_start();
     include ("conexao.php");

     if (isset($_SESSION['msg'])) {
     echo '<p class="mensagem">' . $_SESSION['msg'] . '</p>';
     unset($_SESSION['msg']); 
     }

     if (isset($_GET['msg_exclusao']) && $_GET['msg_exclusao'] === 'true') {
          $_SESSION['msg'] = "Registro excluído com sucesso.";
     }

     if (isset($_SESSION['msg_edicao'])) {
          $_SESSION['msg_edicao'] = "Registro editado com sucesso.";
     }
     ?>

     <section class ="container">
          <form id="form" action="enviar_formulario.php" method="post" onsubmit="return validateForm()">
          <h1>Cadastro de Corretor</h1>
               <div>
                    <div class ="form-group">
                         <input type="hidden" id="id" name="id" value="">
                         <input type="text" id="cpf" name="cpf" placeholder="Digite seu CPF" maxlength="14" required>
                         <input type="text" id="creci" name="creci" placeholder="Digite seu Creci" minlength="2" required>
                    </div>
                    <div class ="form-name">
                         <input type="text" id="nome" name="nome" placeholder="Digite seu Nome" minlength="2" pattern="[A-Za-z\s]+" title="Nome deve conter apenas letras e espaços" required>
                    </div>
                    <div>
                         <button class = salvar-btn type="submit" id="submitBtn">Enviar</button>
                    </div> 
               </div>                     
          </form>
     </section>
          
     <?php

          if ($conexao->connect_error) {
               die("Erro na conexão: " . $conexao->connect_error);
          }

          $query = "SELECT * FROM Corretor";
          $result = $conexao->query($query);

          if ($result->num_rows >= 0) {
          echo '<table border="1"';
          echo '<tr><th>ID</th><th>Nome</th><th>CPF</th><th>Creci</th></tr>';

          while ($row = $result->fetch_assoc()) {
               echo '<tr>';
               echo '<td>' . $row['id'] . '</td>';
               echo '<td>' . $row['nome'] . '</td>';
               echo '<td>' . $row['cpf'] . '</td>';
               echo '<td>' . $row['creci'] . '</td>';
               echo '<td><button class="editar-btn" onclick="editarRegistro(' . $row['id'] . ', \'' . addslashes($row['nome']) . '\', \'' . addslashes($row['cpf']) . '\', \'' . addslashes($row['creci']) . '\')">Editar</button></td>';
               echo '<td><button class="excluir-btn" onclick="excluirRegistro(' . $row['id'] . ')">Excluir</button></td>';
               echo '</tr>';
          }    
               echo '</table>';
          } else {
               echo '<p>Nenhum registro encontrado.</p>';
          }
     ?>

     <script>
          function formatarCPF(cpf) {
               cpf = cpf.replace(/\D/g, ''); // Remove todos os caracteres não numéricos

               if (cpf.length === 11) {
               return cpf.replace(/(\d{3})(\d{3})(\d{3})(\d{2})/, "$1.$2.$3-$4"); // Formato com ponto e traço
               }
               
               return cpf; // CPF sem formatação
          }

          function validateForm() {
               var cpfInput = document.getElementById("cpf");
               var cpf = cpfInput.value;

               cpf = formatarCPF(cpf); // Formatar o CPF

               cpfInput.value = cpf; // Atualiza o valor no campo

               // Validação para CPF com 11 dígitos numéricos
               if (!/^\d{11}$/.test(cpf.replace(/\D/g, ''))) {
                    alert("CPF deve ter 11 dígitos numéricos ou estar no formato 000.000.000-00.");
                    return false;
               }    
               
               var creci = document.getElementById("creci").value;
               var nome = document.getElementById("nome").value;

               // Validação para Creci e Nome com pelo menos 2 caracteres
               if (creci.length < 2 || nome.length < 2) {
                    alert("Creci e Nome devem ter pelo menos 2 caracteres.");
                    return false;
               }
                    return true;
          }

          function editarRegistro(id, nome, cpf,creci) {
               document.getElementById("id").value = id;
               document.getElementById("nome").value = nome;
               document.getElementById("cpf").value = cpf;
               document.getElementById("creci").value = creci;

               document.getElementById("submitBtn").innerHTML = "Salvar";

               var editIdInput = document.createElement("input");
               editIdInput.type = "hidden";
               editIdInput.name = "edit_id";
               editIdInput.value = id;
               document.getElementById("form").appendChild(editIdInput);
          }

          function excluirRegistro(id) {
               var confirmDelete = confirm("Tem certeza que deseja excluir este registro?");

               if (confirmDelete) {
               var xhr = new XMLHttpRequest();
               xhr.onreadystatechange = function () {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                         var rowToRemove = document.getElementById("row_" + id);
                         
                         if (rowToRemove) {
                              rowToRemove.parentNode.removeChild(rowToRemove);
                              }
                              location.reload();
                         }
                    }
               xhr.open("POST", "enviar_formulario.php", true);
               xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
               xhr.send("delete_id=" + id);
               }

          }

     </script>
     </body>
</html>