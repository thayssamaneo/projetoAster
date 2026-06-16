<?php

if (!isset($_SESSION)) {
    session_start();
}
if (!isset($_SESSION['nomeUsuario'])) {
    header("Location: login.php");
    exit();
}

include("conexao.php");

if (!isset($_GET['id'])) {
    header("Location: suasFichas.php");
    exit();
}

$idFicha = (int)$_GET['id'];
$mensagem = "";
$erro = "";

// 1. Carregamento Prévio dos Dados para popular o formulário
try {
    $sql_load = "SELECT f.*, ib.*, a.*, h.* FROM fichas f
                 JOIN informacoesBase ib ON f.informacoesBase_id = ib.idInformacoesBase
                 JOIN atributos a ON f.atributos_id = a.idAtributos
                 JOIN habilidades h ON f.habilidades_id = h.idHabilidades
                 WHERE f.idFichas = :idFicha";
    $stmt_load = $conexao->prepare($sql_load);
    $stmt_load->bindValue(':idFicha', $idFicha);
    $stmt_load->execute();
    $dados = $stmt_load->fetch(PDO::FETCH_ASSOC);

    if (!$dados) {
        die("Esta crônica/ficha não foi localizada nos grimórios.");
    }
} catch (PDOException $e) {
    die("Erro ao carregar dados: " . $e->getMessage());
}

// 2. Processar a Atualização dos Dados (Salvar as alterações)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $conexao->beginTransaction();

        // Verificar se houve upload de uma NOVA imagem de aparência
        $caminho_imagem = $dados['aparencia']; // Mantém a atual por padrão
        if (isset($_FILES['aparencia']) && $_FILES['aparencia']['error'] === UPLOAD_ERR_OK) {
            $extensao = pathinfo($_FILES['aparencia']['name'], PATHINFO_EXTENSION);
            $nome_unico = md5(uniqid(rand(), true)) . "." . $extensao;
            $caminho_imagem = "img/" . $nome_unico;
            move_uploaded_file($_FILES['aparencia']['tmp_name'], $caminho_imagem);
        }

        // Update Informações Base
        $sql_up_info = "UPDATE informacoesBase SET nomePersonagem = :nome, idade = :idade, especie = :especie, aparencia = :aparencia, conexaoMagica = :conexao, hobbies = :hobbies, inventario = :inventario, observacoes = :observacoes, magiasConhecidas = :magias WHERE idInformacoesBase = :id";
        $stmt_up_info = $conexao->prepare($sql_up_info);
        $stmt_up_info->bindValue(':nome', $_POST['nomePersonagem'] ?? '');
        $stmt_up_info->bindValue(':idade', (int)($_POST['idade'] ?? 0));
        $stmt_up_info->bindValue(':especie', $_POST['especie'] ?? '');
        $stmt_up_info->bindValue(':aparencia', $caminho_imagem);
        $stmt_up_info->bindValue(':conexao', (int)($_POST['conexaoMagica'] ?? 0));
        $stmt_up_info->bindValue(':hobbies', $_POST['hobbies'] ?? '');
        $stmt_up_info->bindValue(':inventario', $_POST['inventario'] ?? '');
        $stmt_up_info->bindValue(':observacoes', $_POST['observacoes'] ?? '');
        $stmt_up_info->bindValue(':magias', $_POST['magiasConhecidas'] ?? '');
        $stmt_up_info->bindValue(':id', $dados['informacoesbase_id']);
        $stmt_up_info->execute();

        // Update Atributos
        $sql_up_atri = "UPDATE atributos SET forca = :forca, intelecto = :intelecto, agilidade = :agilidade, carisma = :carisma, vida = :vida, afinidadeMagica = :afinidade, defesa = :defesa, defesaMagica = :defesaMagica, bloqueio = :bloqueio WHERE idAtributos = :id";
        $stmt_up_atri = $conexao->prepare($sql_up_atri);
        $stmt_up_atri->bindValue(':forca', (int)($_POST['forca'] ?? 0));
        $stmt_up_atri->bindValue(':intelecto', (int)($_POST['intelecto'] ?? 0));
        $stmt_up_atri->bindValue(':agilidade', (int)($_POST['agilidade'] ?? 0));
        $stmt_up_atri->bindValue(':carisma', (int)($_POST['carisma'] ?? 0));
        $stmt_up_atri->bindValue(':vida', (int)($_POST['vida'] ?? 100));
        $stmt_up_atri->bindValue(':afinidade', (int)($_POST['afinidadeMagica'] ?? 0));
        $stmt_up_atri->bindValue(':defesa', (int)($_POST['defesa'] ?? 0));
        $stmt_up_atri->bindValue(':defesaMagica', (int)($_POST['defesaMagica'] ?? 0));
        $stmt_up_atri->bindValue(':bloqueio', (int)($_POST['bloqueio'] ?? 0));
        $stmt_up_atri->bindValue(':id', $dados['atributos_id']);
        $stmt_up_atri->execute();

        // Update Habilidades
        $sql_up_hab = "UPDATE habilidades SET crime = :crime, furtividade = :furtividade, iniciativa = :iniciativa, tiroAoAlvo = :tiro, luta = :luta, atletismo = :atletismo, intuicao = :intuicao, investigacao = :investigacao, medicina = :medicina, sobrevivencia = :sobrevivencia, tatica = :tatica, labia = :labia, orientacaoGeografica = :orientacao, percepcao = :percepcao, adestramento = :adestramento, alquimia = :alquimia, navegacao = :navegacao WHERE idHabilidades = :id";
        $stmt_up_hab = $conexao->prepare($sql_up_hab);
        $stmt_up_hab->bindValue(':crime', (int)($_POST['crime'] ?? 0));
        $stmt_up_hab->bindValue(':furtividade', (int)($_POST['furtividade'] ?? 0));
        $stmt_up_hab->bindValue(':iniciativa', (int)($_POST['iniciativa'] ?? 0));
        $stmt_up_hab->bindValue(':tiro', (int)($_POST['tiroAoAlvo'] ?? 0));
        $stmt_up_hab->bindValue(':luta', (int)($_POST['luta'] ?? 0));
        $stmt_up_hab->bindValue(':atletismo', (int)($_POST['atletismo'] ?? 0));
        $stmt_up_hab->bindValue(':intuicao', (int)($_POST['intuicao'] ?? 0));
        $stmt_up_hab->bindValue(':investigacao', (int)($_POST['investigacao'] ?? 0));
        $stmt_up_hab->bindValue(':medicina', (int)($_POST['medicina'] ?? 0));
        $stmt_up_hab->bindValue(':sobrevivencia', (int)($_POST['sobrevivencia'] ?? 0));
        $stmt_up_hab->bindValue(':tatica', (int)($_POST['tatica'] ?? 0));
        $stmt_up_hab->bindValue(':labia', (int)($_POST['labia'] ?? 0));
        $stmt_up_hab->bindValue(':orientacao', (int)($_POST['orientacaoGeografica'] ?? 0));
        $stmt_up_hab->bindValue(':percepcao', (int)($_POST['percepcao'] ?? 0));
        $stmt_up_hab->bindValue(':adestramento', (int)($_POST['adestramento'] ?? 0));
        $stmt_up_hab->bindValue(':alquimia', (int)($_POST['alquimia'] ?? 0));
        $stmt_up_hab->bindValue(':navegacao', (int)($_POST['navegacao'] ?? 0));
        $stmt_up_hab->bindValue(':id', $dados['habilidades_id']);
        $stmt_up_hab->execute();

        $conexao->commit();
        
        header("Location: editarFicha.php?id=" . $idFicha . "&sucesso=1");
        exit();

    } catch (Exception $e) {
        $conexao->rollBack();
        $erro = "Falha ao gravar alterações: " . $e->getMessage();
    }
}
?>

<!DOCTYPE html><html lang="pt-BR"><head><link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet">
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<title>Áster RPG - Criador de Fichas</title>
<script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
<link href="https://fonts.googleapis.com/css2?family=Amita:wght@400;700&amp;family=Courier+Prime:wght@400;700&amp;family=Playfair+Display:wght@400;600;700&amp;family=Work+Sans:wght@400;600&amp;family=Fleur+De+Leah&amp;display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet">
<script id="tailwind-config">
        tailwind.config = {
            darkMode: "class",
            theme: {
                extend: {
                    "colors": {
                        "surface-variant": "#f2dfd0",
                        "background": "#fff8f5",
                        "arcane-purple": "#49326B",
                        "surface-container-high": "#f8e5d5",
                        "outline": "#7b7580",
                        "stone-background": "#C0B499",
                        "on-tertiary-fixed-variant": "#6d297a",
                        "surface-container-lowest": "#ffffff",
                        "surface-tint": "#6b538e",
                        "on-secondary": "#ffffff",
                        "ethereal-gold": "#EEC554",
                        "error": "#ba1a1a",
                        "on-error-container": "#93000a",
                        "inverse-on-surface": "#ffeee1",
                        "surface-dim": "#ead7c8",
                        "on-tertiary-fixed": "#350040",
                        "inverse-surface": "#392e24",
                        "secondary": "#755b00",
                        "surface-bright": "#fff8f5",
                        "secondary-fixed-dim": "#e6c366",
                        "ink-deep": "#231A11",
                        "on-tertiary": "#ffffff",
                        "tertiary-fixed": "#ffd6ff",
                        "on-secondary-fixed": "#241a00",
                        "on-primary": "#ffffff",
                        "surface-container-highest": "#f2dfd0",
                        "primary-fixed-dim": "#d6bafd",
                        "primary": "#321b53",
                        "outline-variant": "#ccc4d0",
                        "on-error": "#ffffff",
                        "surface-container-low": "#fff1e7",
                        "surface-container": "#feeadb",
                        "on-secondary-fixed-variant": "#584400",
                        "error-container": "#ffdad6",
                        "on-primary-fixed": "#250d46",
                        "tertiary-fixed-dim": "#f7adff",
                        "surface": "#fff8f5",
                        "on-background": "#231a11",
                        "on-primary-container": "#b89dde",
                        "tertiary": "#490058",
                        "on-primary-fixed-variant": "#523b75",
                        "primary-fixed": "#eddcff",
                        "on-tertiary-container": "#da8de4",
                        "secondary-container": "#fed979",
                        "on-surface-variant": "#4a454f",
                        "secondary-fixed": "#ffdf90",
                        "on-surface": "#231a11",
                        "inverse-primary": "#d6bafd",
                        "tertiary-container": "#621f70",
                        "on-secondary-container": "#785d03",
                        "primary-container": "#49326b",
                        "parchment-base": "#E9D6C7"
                    },
                    "borderRadius": {
                        "DEFAULT": "0.125rem",
                        "lg": "0.25rem",
                        "xl": "0.5rem",
                        "full": "0.75rem"
                    },
                    "spacing": {
                        "margin-mobile": "16px",
                        "gutter": "24px",
                        "page-padding": "40px",
                        "margin-desktop": "40px",
                        "unit": "8px",
                        "container-max": "1140px"
                    },
                    "fontFamily": {
                        "body-md": ["Courier Prime"],
                        "headline-lg-mobile": ["Playfair Display"],
                        "display-manuscript": ["Amita"],
                        "label-caps": ["Work Sans"],
                        "headline-lg": ["Playfair Display"],
                        "headline-md": ["Playfair Display"],
                        "fleur": ["Fleur De Leah"]
                    },
                    "fontSize": {
                        "body-md": ["16px", {"lineHeight": "1.6", "fontWeight": "400"}],
                        "headline-lg-mobile": ["32px", {"lineHeight": "1.2", "fontWeight": "700"}],
                        "display-manuscript": ["48px", {"lineHeight": "1.2", "letterSpacing": "-0.02em", "fontWeight": "700"}],
                        "label-caps": ["12px", {"lineHeight": "1.0", "letterSpacing": "0.1em", "fontWeight": "600"}],
                        "headline-lg": ["48px", {"lineHeight": "1.2", "fontWeight": "700"}],
                        "headline-md": ["32px", {"lineHeight": "1.3", "fontWeight": "600"}],
                        "section-title": ["42px", {"lineHeight": "1.1", "fontWeight": "400"}]
                    }
                }
            }
        }
    </script>
<style>
        body {
            background-color: #C0B499;
            background-image: radial-gradient(circle at center, rgba(255, 248, 245, 0.2) 0%, rgba(35, 26, 17, 0.1) 100%);
            scrollbar-width: thin;
            scrollbar-color: #49326B #E9D6C7;
        }
        ::-webkit-scrollbar { width: 12px; }
        ::-webkit-scrollbar-track { background: #E9D6C7; }
        ::-webkit-scrollbar-thumb { background: #49326B; border: 3px solid #E9D6C7; border-radius: 6px; }

        .parchment-sheet {
            background-color: #E9D6C7;
            background-image: url("https://www.transparenttextures.com/patterns/natural-paper.png");
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3), inset 0 0 100px rgba(123, 117, 128, 0.1);
            position: relative;
            overflow: hidden;
        }
        .parchment-sheet::before {
            content: "";
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            border: 20px solid transparent;
            border-image: url('data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" width="100" height="100" viewBox="0 0 100 100"><rect width="100" height="100" fill="none" stroke="%2349326B" stroke-width="2" stroke-dasharray="10 5"/></svg>') 30 stretch;
            pointer-events: none;
            opacity: 0.1;
        }

        .input-underline {
            background: transparent;
            border: none;
            border-bottom: 1px solid #49326B;
            border-radius: 0;
            padding: 2px 4px;
        }
        .input-underline:focus {
            outline: none;
            border-bottom: 2px solid #EEC554;
            box-shadow: none;
        }

        .attribute-orb {
            background: rgba(192, 180, 153, 0.3);
            border: 1px solid rgba(73, 50, 107, 0.2);
            box-shadow: inset 0 2px 4px rgba(0,0,0,0.1);
        }

        .section-divider {
            height: 2px;
            background: linear-gradient(to right, transparent, #49326B, transparent);
            margin: 2rem 0;
            opacity: 0.3;
        }

        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 300, 'GRAD' 0, 'opsz' 24;
            vertical-align: middle;
        }
    </style>
</head>
<body class="min-h-screen text-on-background selection:bg-ethereal-gold selection:text-ink-deep">
    <header class="bg-primary-container border-b border-on-primary-fixed-variant/20 sticky top-0 z-50">
<div class="flex justify-between items-center w-full px-margin-mobile md:px-margin-desktop h-20 max-w-[1280px] mx-auto">
<h1 class="font-brand text-[44px] text-secondary-container drop-shadow-sm cursor-pointer hover:text-secondary-fixed-dim transition-all duration-300 leading-none font-fleur text-[44px] text-arcane-purple leading-none">
<img alt="Logo" class="inline-block h-12 w-auto mr-3 align-middle " src="https://lh3.googleusercontent.com/aida-public/AB6AXuDUdPDRqfuHwZmxBu14HYH3TFhDWayvqszsJzU70FpW23aBH3WYK5YYcgMig-WNSNccEIDJj5uhmti5VCgdj3K4DK6hjQzLWtPFDI05hVvaahDn8fEUkqrP2vM7kn607cGGbXAw1p53lVlDEwMkJQDMBlRsziOCYGqPLn0p8_NZUieQjbJunR-qo908WvEqG8hVJkGyLBlJsAhI5T90vsVcQeNrMrwwbXOGGbSvrqzV0xcYjWgYepv_0sUndcL8hVXjqZm6KVSlyeQ"/><a href="suasFichas.php">Áster</a>
</h1>
<div class="hidden md:flex items-center gap-8 font-label-caps uppercase tracking-widest text-parchment-base opacity-80">
<a class="hover:text-ethereal-gold hover:shadow-[0_0_15px_rgba(238,197,84,0.3)] transition-all pb-1 border-b border-transparent hover:border-ethereal-gold" href="suasFichas.php">Minhas Fichas</a>
</div>

</div>
</header>
<main class="max-w-container-max mx-auto my-page-padding px-margin-mobile md:px-margin-desktop">
<div class="parchment-sheet p-8 md:p-16 rounded-sm">
<div class="text-center mb-16 space-y-2">
<h1 class="font-fleur text-[64px] text-arcane-purple leading-none">Editando ficha</h1>
<div class="section-divider !mt-8"></div>
</div>
<form class="space-y-16" id="characterSheet" method="POST" enctype="multipart/form-data">
<section>
<div class="flex items-center gap-4 mb-8">
<span class="material-symbols-outlined text-arcane-purple">edit_note</span>
<h2 class="font-fleur text-section-title text-arcane-purple">Informações básicas</h2>
</div>
<div class="grid grid-cols-1 md:grid-cols-3 gap-12">
<div class="col-span-1 md:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-8">
<div class="flex flex-col gap-1">
<label class="font-label-caps text-label-caps uppercase opacity-70">Nome do Personagem</label>
<input class="w-full bg-surface-container-low/50 border-b-2 border-primary/20 py-3 px-2 font-body-md" id="nomePersonagem" name="nomePersonagem" type="text" value="<?php echo htmlspecialchars($dados['nomepersonagem'] ?? ''); ?>" required>
</div>
<div class="flex flex-col gap-1">
<label class="font-label-caps text-label-caps uppercase opacity-70">Idade</label>
<input class="input-underline font-body-md text-body-md text-arcane-purple w-24" id="idade" name="idade" placeholder="--" type="number" value="<?php echo htmlspecialchars($dados['idade'] ?? ''); ?>">
</div>
<div class="flex flex-col gap-1">
<label class="font-label-caps text-label-caps uppercase opacity-70">Espécie / Origem</label>
<input class="input-underline font-body-md text-body-md text-arcane-purple" id="especie" name="especie" placeholder="Ex: Fantasma" type="text" value="<?php echo htmlspecialchars($dados['especie'] ?? ''); ?>">
</div>
<div class="flex flex-col gap-1">
<label class="font-label-caps text-label-caps uppercase opacity-70">Conexão Mágica (0-10)</label>
<input class="input-underline font-body-md text-body-md text-arcane-purple w-24" max="10" min="0" id="conexaoMagica" name="conexaoMagica" placeholder="0" type="number" value="<?php echo htmlspecialchars($dados['conexaomagica'] ?? 0); ?>">
</div>
</div>
<div class="space-y-4">
    <label class="block font-body-md text-ink-deep/80 text-sm italic">Aparência do Personagem</label>
    
    <div class="flex items-center gap-4 p-3 bg-surface-container-low/30 border border-primary/10 rounded-sm">
        <img src="<?php echo htmlspecialchars($dados['aparencia'] ?? 'img/default_avatar.png'); ?>" alt="Aparência Actual" class="w-24 h-24 object-cover border-2 border-primary/20 rounded-sm shadow-md">
        <div>
            <p class="text-xs font-bold text-primary">Retrato Actual</p>
            <p class="text-xs text-ink-deep/60 italic">Para alterar o visual deste personagem, selecione um novo arquivo abaixo.</p>
        </div>
    </div>
    <input class="w-full bg-surface-container-low/50 border-b-2 border-primary/20 py-3 px-2 font-body-md text-ink-deep focus:border-ethereal-gold bg-transparent transition-all" 
           id="aparencia" 
           name="aparencia" 
           type="file" 
           accept="image/*">
</div>
</div>
</section>
<div class="section-divider"></div>
<section>
<div class="flex items-center gap-4 mb-10">
<span class="material-symbols-outlined text-arcane-purple">shield_with_heart</span>
<h2 class="font-fleur text-section-title text-arcane-purple">Atributos</h2>
</div>
<div class="grid grid-cols-2 md:grid-cols-4 gap-8 mb-12">
<div class="attribute-orb p-6 rounded-full aspect-square flex flex-col items-center justify-center gap-2 border-arcane-purple/30 border-2">
<label class="font-label-caps text-[10px] uppercase text-arcane-purple">Força</label>
<input class="bg-transparent text-center font-display-manuscript text-3xl w-16 text-arcane-purple border-none focus:ring-0" id="forca" name="forca" type="number" value="<?php echo htmlspecialchars($dados['forca'] ?? 0); ?>">
</div>
<div class="attribute-orb p-6 rounded-full aspect-square flex flex-col items-center justify-center gap-2 border-arcane-purple/30 border-2">
<label class="font-label-caps text-[10px] uppercase text-arcane-purple">Intelecto</label>
<input class="bg-transparent text-center font-display-manuscript text-3xl w-16 text-arcane-purple border-none focus:ring-0" id="intelecto" name="intelecto" type="number" value="<?php echo htmlspecialchars($dados['intelecto'] ?? 0); ?>">
</div>
<div class="attribute-orb p-6 rounded-full aspect-square flex flex-col items-center justify-center gap-2 border-arcane-purple/30 border-2">
<label class="font-label-caps text-[10px] uppercase text-arcane-purple">Agilidade</label>
<input class="bg-transparent text-center font-display-manuscript text-3xl w-16 text-arcane-purple border-none focus:ring-0" id="agilidade" name="agilidade" type="number" value="<?php echo htmlspecialchars($dados['agilidade'] ?? 0); ?>">
</div>
<div class="attribute-orb p-6 rounded-full aspect-square flex flex-col items-center justify-center gap-2 border-arcane-purple/30 border-2">
<label class="font-label-caps text-[10px] uppercase text-arcane-purple">Carisma</label>
<input class="bg-transparent text-center font-display-manuscript text-3xl w-16 text-arcane-purple border-none focus:ring-0" id="carisma" name="carisma" type="number" value="<?php echo htmlspecialchars($dados['carisma'] ?? 0); ?>">
</div>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 gap-x-16 gap-y-8 bg-ink-deep/5 p-8 rounded-lg">
<div class="space-y-6">
<div class="grid grid-cols-2 gap-4">
<div class="flex flex-col gap-1">
<label class="font-label-caps text-label-caps uppercase opacity-70">Defesa</label>
<input class="input-underline font-body-md text-body-md text-arcane-purple" id="defesa" name="defesa" type="number" value="<?php echo htmlspecialchars($dados['defesa'] ?? 0); ?>">
</div>
<div class="flex flex-col gap-1">
<label class="font-label-caps text-label-caps uppercase opacity-70">Def. Mágica</label>
<input class="input-underline font-body-md text-body-md text-arcane-purple" id="defesaMagica" name="defesaMagica" type="number" value="<?php echo htmlspecialchars($dados['defesamagica'] ?? 0); ?>">
</div>
<div class="flex flex-col gap-1">
<label class="font-label-caps text-label-caps uppercase opacity-70">Bloqueio</label>
<input class="input-underline font-body-md text-body-md text-arcane-purple" id="bloqueio" name="bloqueio" type="number" value="<?php echo htmlspecialchars($dados['bloqueio'] ?? 0); ?>">
</div>
<div class="flex flex-col gap-1">
<label class="font-label-caps text-label-caps uppercase opacity-70">Esquiva</label>
<input class="input-underline font-body-md text-body-md text-arcane-purple" id="esquiva" name="esquiva" type="number" value="<?php echo htmlspecialchars($dados['esquiva'] ?? 0); ?>">
</div>
</div>
</div>
<div class="space-y-8">
<div class="space-y-2">
<div class="flex justify-between items-center">
<label class="font-label-caps text-label-caps uppercase text-arcane-purple font-bold">Vida</label>
<span class="font-body-md text-arcane-purple" id="lifeVal"><?php echo htmlspecialchars($dados['vida'] ?? 100); ?>/100</span>
</div>
<input class="w-full h-2 bg-parchment-base rounded-lg appearance-none cursor-pointer accent-arcane-purple" id="vida" name="vida" oninput="document.getElementById('lifeVal').innerText = this.value + '/100'" type="range" min="0" max="100" value="<?php echo htmlspecialchars($dados['vida'] ?? 100); ?>">
</div>
<div class="space-y-2">
<div class="flex justify-between items-center">
<label class="font-label-caps text-label-caps uppercase text-secondary font-bold">Afinidade Mágica</label>
<span class="font-body-md text-secondary" id="magicVal"><?php echo htmlspecialchars($dados['afinidademagica'] ?? 50); ?>%</span>
</div>
<input class="w-full h-2 bg-parchment-base rounded-lg appearance-none cursor-pointer accent-ethereal-gold" id="afinidadeMagica" name="afinidadeMagica" oninput="document.getElementById('magicVal').innerText = this.value + '%'" type="range" min="0" max="100" value="<?php echo htmlspecialchars($dados['afinidademagica'] ?? 50); ?>">
</div>
</div>
</div>
</section>
<div class="section-divider"></div>
<section>
<div class="flex items-center gap-4 mb-8">
<span class="material-symbols-outlined text-arcane-purple">swords</span>
<h2 class="font-fleur text-section-title text-arcane-purple">Habilidades</h2>
</div>
<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-x-8 gap-y-4">
<?php
// Mapeamento das perícias para bater com as colunas em minúsculas do Postgres
$skills_map = [
    "Crime" => "crime", "Furtividade" => "furtividade", "Iniciativa" => "iniciativa", "Tiro ao Alvo" => "tiroaoalvo", 
    "Luta" => "luta", "Atletismo" => "atletismo", "Intuição" => "intuicao", "Investigação" => "investigacao", 
    "Medicina" => "medicina", "Sobrevivência" => "sobrevivencia", "Tática" => "tatica", "Lábia" => "labia", 
    "Percepção" => "percepcao", "Lidar com Animais" => "adestramento", "Orientação Geográfica" => "orientacaogeografica", 
    "Alquimia" => "alquimia", "Navegação" => "navegacao"
];

foreach ($skills_map as $label => $column_name) {
    $valor_habilidade = htmlspecialchars($dados[$column_name] ?? 0);
    echo '
    <div class="flex items-center justify-between border-b border-arcane-purple/10 py-1 hover:bg-arcane-purple/5 transition-colors px-2">
        <span class="font-body-md text-[13px] text-on-surface opacity-80">' . $label . '</span>
        <input type="number" name="' . $column_name . '" value="' . $valor_habilidade . '" class="w-10 bg-transparent text-right font-bold text-arcane-purple border-none focus:ring-0 p-0">
    </div>';
}
?>                        
</div>
</section>
<div class="section-divider"></div>
<section>
<div class="flex items-center gap-4 mb-8">
<span class="material-symbols-outlined text-arcane-purple">inventory_2</span>
<h2 class="font-fleur text-section-title text-arcane-purple">Crônicas & Pertences</h2>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 gap-8">
<div class="space-y-2">
<label class="font-label-caps text-label-caps uppercase opacity-70 flex items-center gap-2">
<span class="material-symbols-outlined text-sm">auto_fix_high</span> Magias Conhecidas
                            </label>
<textarea class="w-full bg-ink-deep/5 border-arcane-purple/20 font-body-md text-body-md p-4 rounded focus:ring-ethereal-gold focus:border-ethereal-gold" id="magiasConhecidas" name="magiasConhecidas" placeholder="Descreva os rituais e encantamentos..." rows="6"><?php echo htmlspecialchars($dados['magiasconhecidas'] ?? ''); ?></textarea>
</div>
<div class="space-y-2">
<label class="font-label-caps text-label-caps uppercase opacity-70 flex items-center gap-2">
<span class="material-symbols-outlined text-sm">backpack</span> Inventário
                            </label>
<textarea class="w-full bg-ink-deep/5 border-arcane-purple/20 font-body-md text-body-md p-4 rounded focus:ring-ethereal-gold focus:border-ethereal-gold" id="inventario" name="inventario" placeholder="Itens consumíveis, armas, tesouros..." rows="6"><?php echo htmlspecialchars($dados['inventario'] ?? ''); ?></textarea>
</div>
<div class="space-y-2">
<label class="font-label-caps text-label-caps uppercase opacity-70 flex items-center gap-2">
<span class="material-symbols-outlined text-sm">history_edu</span> Observações
                            </label>
<textarea class="w-full bg-ink-deep/5 border-arcane-purple/20 font-body-md text-body-md p-4 rounded focus:ring-ethereal-gold focus:border-ethereal-gold" id="observacoes" name="observacoes" placeholder="Anotações sobre a jornada..." rows="6"><?php echo htmlspecialchars($dados['observacoes'] ?? ''); ?></textarea>
</div>
<div class="space-y-2">
<label class="font-label-caps text-label-caps uppercase opacity-70 flex items-center gap-2">
<span class="material-symbols-outlined text-sm">palette</span> Hobbies & Talentos
                            </label>
<textarea class="w-full bg-ink-deep/5 border-arcane-purple/20 font-body-md text-body-md p-4 rounded focus:ring-ethereal-gold focus:border-ethereal-gold" id="hobbies" name="hobbies" placeholder="O que seu personagem faz no tempo livre?" rows="6"><?php echo htmlspecialchars($dados['hobbies'] ?? ''); ?></textarea>
</div>
</div>
</section>
<div class="flex justify-center pt-12 pb-8">
<button class="bg-primary-container text-secondary-container px-12 py-4 font-label-caps text-label-caps tracking-[0.2em] uppercase rounded shadow-lg border border-ethereal-gold/30 hover:scale-105 hover:shadow-[0_0_20px_rgba(238,197,84,0.4)] transition-all flex items-center gap-4 active:scale-95" type="submit">
<span class="material-symbols-outlined" style="font-variation-settings: 'FILL' 1;">save</span>
                        Selar Manuscrito
                    </button>
</div>
</form>
<div class="absolute top-10 right-10 opacity-10 pointer-events-none rotate-12">
<span class="material-symbols-outlined text-8xl text-arcane-purple">explore</span>
</div>
<div class="absolute bottom-10 left-10 opacity-10 pointer-events-none -rotate-12">
<span class="material-symbols-outlined text-9xl text-arcane-purple">anchor</span>
</div>
</div>
</main>
<footer class="bg-primary-container border-t border-on-primary-fixed-variant/20">
<div class="flex flex-col md:flex-row justify-between items-center w-full px-margin-mobile md:px-margin-desktop py-unit-gutter max-w-[1280px] mx-auto text-secondary-container">
<div class="mb-4 md:mb-0">
<h4 class="font-brand text-[36px] text-secondary-container leading-none font-fleur text-[64px] text-arcane-purple leading-none">
<br>
<img alt="Logo" class="inline-block h-8 w-auto mr-2 align-middle"src="https://lh3.googleusercontent.com/aida-public/AB6AXuDUdPDRqfuHwZmxBu14HYH3TFhDWayvqszsJzU70FpW23aBH3WYK5YYcgMig-WNSNccEIDJj5uhmti5VCgdj3K4DK6hjQzLWtPFDI05hVvaahDn8fEUkqrP2vM7kn607cGGbXAw1p53lVlDEwMkJQDMBlRsziOCYGqPLn0p8_NZUieQjbJunR-qo908WvEqG8hVJkGyLBlJsAhI5T90vsVcQeNrMrwwbXOGGbSvrqzV0xcYjWgYepv_0sUndcL8hVXjqZm6KVSlyeQ"/><a href="suasFichas.php">Áster</a>
</h4>
<br>
</div>
<p class="font-body-md text-[14px] text-center md:text-right text-on-primary-container">
                © 2026 Áster RPG. O uso comercial não é autorizado.
            </p>
<div class="mt-4 md:mt-0">
<a class="text-on-primary-container font-body-md hover:text-secondary-fixed-dim transition-colors duration-200 underline underline-offset-4" href="#">
                    Voltar ao topo
                </a>
</div>
</div>
</footer>
<script>
        const inputs = document.querySelectorAll('input, textarea');
        inputs.forEach(input => {
            input.addEventListener('focus', () => {
                input.parentElement.classList.add('opacity-100');
            });
        });
    </script>
</body></html>