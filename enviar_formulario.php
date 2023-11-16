<?php

session_start();
include ("conexao.php");

if ($conexao->connect_error) {
    die("Erro na conexão: " . $conexao->connect_error);
}

$cpf = $_POST['cpf'];
$creci = $_POST['creci'];
$nome = $_POST['nome'];

$sql = "INSERT INTO Corretor (nome, cpf, creci) VALUES ('$nome', '$cpf', '$creci')";

if ($conexao->query($sql) === TRUE) {
    $_SESSION['msg'] = "Registro inserido com sucesso!";
} else {
    $_SESSION['msg'] = "Erro ao inserir registro: " . $conexao->error;
}

if (isset($_POST['delete_id'])) {
    $delete_id = $_POST['delete_id'];

    $delete_query = "DELETE FROM Corretor WHERE id = $delete_id";
    
    if ($conexao->query($delete_query) === TRUE) {
        header("Location: index.php?msg_exclusao=true");
        exit();
    } else {
        $_SESSION['msg'] = "Erro ao excluir registro: " . $conexao->error;   
    }
}

if (isset($_POST['edit_id'])) {
    $edit_id = $_POST['edit_id'];
    
    $sql = "UPDATE Corretor SET nome='$nome', cpf='$cpf', creci='$creci' WHERE id=$edit_id";

    if ($conexao->query($sql) === TRUE) {
        $_SESSION['msg'] = "Registro editado com sucesso!";
    } else {
        $_SESSION['msg'] = "Erro ao editar registro: " . $conexao->error;
    }
} else {
    $sql = "INSERT INTO Corretor (nome, cpf, creci) VALUES ('$nome', '$cpf', '$creci')";
}

header("Location: index.php");
exit();
?>