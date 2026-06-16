<?php
if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION['nomeUsuario'])) {
    header("Location: paginaLogin.php");
    exit();
}

if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: suasFichas.php");
    exit();
}

include("conexao.php");

$id_ficha = $_GET['id'];

try {
    // Buscar o ID do usuário logado para segurança
    $sql_user = "SELECT idusuarios FROM usuarios WHERE nomeusuario = :user";
    $stmt_user = $conexao->prepare($sql_user);
    $stmt_user->bindValue(':user', $_SESSION['nomeUsuario']);
    $stmt_user->execute();
    $user_data = $stmt_user->fetch(PDO::FETCH_ASSOC);
    $usuario_id = $user_data['idusuarios'];

    // Buscar as referências das sub-tabelas vinculadas a essa ficha
    $sql_busca = "SELECT informacoesbase_id, atributos_id, habilidades_id FROM fichas WHERE idfichas = :id_ficha AND usuario_id = :usuario_id";
    $stmt_busca = $conexao->prepare($sql_busca);
    $stmt_busca->bindValue(':id_ficha', $id_ficha);
    $stmt_busca->bindValue(':usuario_id', $usuario_id);
    $stmt_busca->execute();
    $ficha_refs = $stmt_busca->fetch(PDO::FETCH_ASSOC);

    // Se a ficha existir e pertencer ao usuário logado, prossegue com a deleção
    if ($ficha_refs) {
        
        // Inicia uma transação para garantir que ou apaga TUDO ou não apaga NADA
        $conexao->beginTransaction();

        //  remover o registro principal na tabela 'fichas' para quebrar o vínculo
        $sql_del_ficha = "DELETE FROM fichas WHERE idfichas = :id_ficha";
        $stmt_del_ficha = $conexao->prepare($sql_del_ficha);
        $stmt_del_ficha->bindValue(':id_ficha', $id_ficha);
        $stmt_del_ficha->execute();

        // Agora apagar os dados das tabelas dependentes
        if (!empty($ficha_refs['informacoesbase_id'])) {
            $conexao->prepare("DELETE FROM informacoesbase WHERE idinformacoesbase = ?")->execute([$ficha_refs['informacoesbase_id']]);
        }
        
        if (!empty($ficha_refs['atributos_id'])) {
            $conexao->prepare("DELETE FROM atributos WHERE idatributos = ?")->execute([$ficha_refs['atributos_id']]);
        }

        if (!empty($ficha_refs['habilidades_id'])) {
            $conexao->prepare("DELETE FROM habilidades WHERE idhabilidades = ?")->execute([$ficha_refs['habilidades_id']]);
        }

        // Confirmar as alterações no banco de dados
        $conexao->commit();
    }

    // Redirecionar de volta atualizando a página sem a ficha deletada
    header("Location: suasFichas.php");
    exit();

} catch (PDOException $e) {
    // Se algo der errado, desfaz as deleções no banco para não corromper os dados
    if ($conexao->inTransaction()) {
        $conexao->rollBack();
    }
    die("Erro ao tentar excluir a ficha: " . $e->getMessage());
}