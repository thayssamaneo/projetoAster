<?php
$host = "localhost";
$dbname = "fichasaster";
$user = "postgres";
$pass = "postgres";

try {
    // CORREÇÃO: Removidos os espaços em branco e quebras de linha da string de conexão
    $conexao = new PDO("pgsql:host=$host;dbname=$dbname", $user, $pass);
    
    // Configura o PDO para lançar exceções em caso de erros internos do banco
    $conexao->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
} catch(PDOException $e) {
    // Se o banco falhar, interrompe a execução para você ver o erro real na tela
    die("Erro na conexão com o banco de dados: " . $e->getMessage());
}