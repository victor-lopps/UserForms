<?php
session_start();
require 'connectionDB.php';

if (isset($_POST['cadastrar'])) {
    $nome = $_POST['nome'];
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $csenha = $_POST['confirmasenha'];

    // Verifica se a senha digitada se confirma com a senha de confirmação
    if($senha == $csenha)
    {
        //Se a senha de confirmação for igual a senha, executa o cadastro de usuário
        $query = "INSERT INTO user (email, senha, nome) VALUES (':email', ':senha',':nome')";
        $stmt = $PDO->prepare($query);
        $stmt->bindValue(':email', $email);
        $stmt->bindValue(':senha', $senha);
        $stmt->bindValue(':nome', $nome);
        $result = $stmt->execute();
    
        if ($result) {
            echo 'usuario cadastrado';
            header("Location: singup.php");
            exit(0);
        } else {
            echo 'erro ao criar o usuario';
            header("Location: singup.php");
            exit(0);
        }
    }
    else{
        //Se não, exibe na tela:
        echo 'as senhas não se conferem!';
        return;
    }
}

if (isset($_POST['mudarsenha'])) {
    $email = $_POST['email'];
    $senha = $_POST['senha'];
    $novasenha = $_POST['novasenha'];

    // Consulta o banco de dados para verificar se o email e a senha coincidem
    $queryEmail = "SELECT email FROM user WHERE email = :email AND senha = :senha";
    $stmt = $PDO->prepare($queryEmail);
    $stmt->bindValue(':email', $email);
    $stmt->bindValue(':senha', $senha);
    $stmt->execute();

    // Armazena o resultado encontrado na variável $rows
    $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (count($rows) > 0) {
        // Email e senha coincidem, então atualiza a senha no banco de dados
        $querySenha = "UPDATE user SET senha = :novaSenha WHERE email = :email";
        $stmt = $PDO->prepare($querySenha);
        $stmt->bindValue(':novaSenha', $novasenha);
        $stmt->bindValue(':email', $email);
        $result = $stmt->execute();

        if (!$result) {
            echo 'Ocorreu um erro ao atualizar a senha';
        } else {
            echo 'Senha alterada!';
        }
    } else {
        echo 'Email ou senha incorretos';
    }
}

if(isset($_POST['login'])) {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    $query = "SELECT * FROM user WHERE email = :email AND senha = :senha";
    $stmt = $PDO->prepare($query);
    $stmt->bindValue(':email', $email);
    $stmt->bindValue(':senha', $senha);
    $stmt->execute();

    // Verifica se o registro foi encontrado
    if ($stmt->rowCount() > 0) {
        echo 'Sucesso ao cadastrar!';
    } else {
        // Credenciais inválidas, exibe uma mensagem de erro
        echo 'Email ou senha incorretos';
    }
}

if (isset($_POST['excluir'])) {
    $email = $_POST['email'];
    $senha = $_POST['senha'];

    // Verifica se o email e a senha fornecidos estão corretos
    $query = "SELECT * FROM user WHERE email = :email AND senha = :senha";
    $stmt = $PDO->prepare($query);
    $stmt->bindValue(':email', $email);
    $stmt->bindValue(':senha', $senha);
    $stmt->execute();

    // Verifica se o registro foi encontrado
    if ($stmt->rowCount() > 0) {
        //Caso email e senha se coincidam, executa a linha de código de exclusão
        $queryDelete = "DELETE FROM user WHERE email = :email";
        $stmtDelete = $PDO->prepare($queryDelete);
        $stmtDelete->bindValue(':email', $email);

        if ($stmtDelete->execute()) {
            echo 'Usuário excluído com sucesso!';
        } else {
            echo 'Erro ao excluir o usuário.';
        }
    } else {
        echo 'Email ou senha incorretos!';
    }
}


?>