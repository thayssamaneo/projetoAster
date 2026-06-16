<?php

if (!isset($_SESSION)) {
    session_start();
}
// Se o usuário não estiver logado, redireciona para o login
if (!isset($_SESSION['nomeUsuario'])) {
    header("Location: Paginalogin.php");
    exit();
}

include("conexao.php");

// Recuperar o ID do usuário logado na sessão
// Certifique-se de salvar o 'idUsuarios' na sessão no momento em que ele faz login
$sql_user = "SELECT idusuarios FROM usuarios WHERE nomeusuario = :user";
$stmt_user = $conexao->prepare($sql_user);
$stmt_user->bindValue(':user', $_SESSION['nomeUsuario']);
$stmt_user->execute();
$user_data = $stmt_user->fetch(PDO::FETCH_ASSOC);
$usuario_id = $user_data['idusuarios'];

$mensagem = "";
$erro = "";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    try {
        $conexao->beginTransaction();

        // 1. Upload da imagem de aparência com Nome Único
        $caminho_imagem = "img/default_avatar.png"; // Padrão caso não envie
        if (isset($_FILES['aparencia']) && $_FILES['aparencia']['error'] === UPLOAD_ERR_OK) {
            $extensao = pathinfo($_FILES['aparencia']['name'], PATHINFO_EXTENSION);
            $nome_unico = md5(uniqid(rand(), true)) . "." . $extensao;
            $caminho_imagem = "img/" . $nome_unico;
            
            if (!is_dir('img')) {
                mkdir('img', 0777, true);
            }
            move_uploaded_file($_FILES['aparencia']['tmp_name'], $caminho_imagem);
        }

        // 2. Insert na tabela informacoesBase
        $sql_info = "INSERT INTO informacoesbase (nomepersonagem, idade, especie, aparencia, conexaomagica, hobbies, inventario, observacoes, magiasconhecidas) 
                     VALUES (:nome, :idade, :especie, :aparencia, :conexao, :hobbies, :inventario, :observacoes, :magias) RETURNING idInformacoesBase";
        $stmt_info = $conexao->prepare($sql_info);
        $stmt_info->bindValue(':nome', $_POST['nomePersonagem'] ?? '');
        $stmt_info->bindValue(':idade', (int)($_POST['idade'] ?? 0));
        $stmt_info->bindValue(':especie', $_POST['especie'] ?? '');
        $stmt_info->bindValue(':aparencia', $caminho_imagem);
        $stmt_info->bindValue(':conexao', (int)($_POST['conexaoMagica'] ?? 0));
        $stmt_info->bindValue(':hobbies', $_POST['hobbies'] ?? '');
        $stmt_info->bindValue(':inventario', $_POST['inventario'] ?? '');
        $stmt_info->bindValue(':observacoes', $_POST['observacoes'] ?? '');
        $stmt_info->bindValue(':magias', $_POST['magiasConhecidas'] ?? '');
        $stmt_info->execute();
        $info_id = $stmt_info->fetchColumn();

        // 3. Insert na tabela atributos
        $sql_atri = "INSERT INTO atributos (forca, intelecto, agilidade, carisma, vida, afinidademagica, defesa, defesamagica, bloqueio) 
                     VALUES (:forca, :intelecto, :agilidade, :carisma, :vida, :afinidade, :defesa, :defesaMagica, :bloqueio) RETURNING idAtributos";
        $stmt_atri = $conexao->prepare($sql_atri);
        $stmt_atri->bindValue(':forca', (int)($_POST['forca'] ?? 0));
        $stmt_atri->bindValue(':intelecto', (int)($_POST['intelecto'] ?? 0));
        $stmt_atri->bindValue(':agilidade', (int)($_POST['agilidade'] ?? 0));
        $stmt_atri->bindValue(':carisma', (int)($_POST['carisma'] ?? 0));
        $stmt_atri->bindValue(':vida', (int)($_POST['vida'] ?? 100));
        $stmt_atri->bindValue(':afinidade', (int)($_POST['afinidadeMagica'] ?? 0));
        $stmt_atri->bindValue(':defesa', (int)($_POST['defesa'] ?? 0));
        $stmt_atri->bindValue(':defesaMagica', (int)($_POST['defesaMagica'] ?? 0));
        $stmt_atri->bindValue(':bloqueio', (int)($_POST['bloqueio'] ?? 0));
        $stmt_atri->execute();
        $atri_id = $stmt_atri->fetchColumn();

        // 4. Insert na tabela habilidades
        $sql_hab = "INSERT INTO habilidades (crime, furtividade, iniciativa, tiroaoalvo, luta, atletismo, intuicao, investigacao, medicina, sobrevivencia, tatica, labia, orientacaogeografica, percepcao, adestramento, alquimia, navegacao) 
                    VALUES (:crime, :furtividade, :iniciativa, :tiro, :luta, :atletismo, :intuicao, :investigacao, :medicina, :sobrevivencia, :tatica, :labia, :orientacao, :percepcao, :adestramento, :alquimia, :navegacao) RETURNING idHabilidades";
        $stmt_hab = $conexao->prepare($sql_hab);
        $stmt_hab->bindValue(':crime', (int)($_POST['crime'] ?? 0));
        $stmt_hab->bindValue(':furtividade', (int)($_POST['furtividade'] ?? 0));
        $stmt_hab->bindValue(':iniciativa', (int)($_POST['iniciativa'] ?? 0));
        $stmt_hab->bindValue(':tiro', (int)($_POST['tiroAoAlvo'] ?? 0));
        $stmt_hab->bindValue(':luta', (int)($_POST['luta'] ?? 0));
        $stmt_hab->bindValue(':atletismo', (int)($_POST['atletismo'] ?? 0));
        $stmt_hab->bindValue(':intuicao', (int)($_POST['intuicao'] ?? 0));
        $stmt_hab->bindValue(':investigacao', (int)($_POST['investigacao'] ?? 0));
        $stmt_hab->bindValue(':medicina', (int)($_POST['medicina'] ?? 0));
        $stmt_hab->bindValue(':sobrevivencia', (int)($_POST['sobrevivencia'] ?? 0));
        $stmt_hab->bindValue(':tatica', (int)($_POST['tatica'] ?? 0));
        $stmt_hab->bindValue(':labia', (int)($_POST['labia'] ?? 0));
        $stmt_hab->bindValue(':orientacao', (int)($_POST['orientacaoGeografica'] ?? 0));
        $stmt_hab->bindValue(':percepcao', (int)($_POST['percepcao'] ?? 0));
        $stmt_hab->bindValue(':adestramento', (int)($_POST['adestramento'] ?? 0));
        $stmt_hab->bindValue(':alquimia', (int)($_POST['alquimia'] ?? 0));
        $stmt_hab->bindValue(':navegacao', (int)($_POST['navegacao'] ?? 0));
        $stmt_hab->execute();
        $hab_id = $stmt_hab->fetchColumn();

        // 5. Vincular tudo na tabela Fichas associada ao usuário ativo
        $sql_ficha = "INSERT INTO fichas (informacoesbase_id, atributos_id, habilidades_id, criadaem, usuario_id) 
                      VALUES (:info_id, :atri_id, :hab_id, CURRENT_DATE, :user_id)";
        $stmt_ficha = $conexao->prepare($sql_ficha);
        $stmt_ficha->bindValue(':info_id', $info_id);
        $stmt_ficha->bindValue(':atri_id', $atri_id);
        $stmt_ficha->bindValue(':hab_id', $hab_id);
        $stmt_ficha->bindValue(':user_id', $usuario_id);
        $stmt_ficha->execute();

        $conexao->commit();
        header("Location: suasFichas.php");
        exit();

    } catch (Exception $e) {
        $conexao->rollBack();
        $erro = "Falha ao gravar os dados da ficha: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>

<!DOCTYPE html><html lang="pt-BR"><head><link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&amp;display=swap" rel="stylesheet">
<meta charset="utf-8">
<meta content="width=device-width, initial-scale=1.0" name="viewport">
<title>Áster - Criador de Fichas</title>
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
    <!-- Header -->
<header class="bg-primary-container border-b border-on-primary-fixed-variant/20 sticky top-0 z-50">
<div class="flex justify-between items-center w-full px-margin-mobile md:px-margin-desktop h-20 max-w-[1280px] mx-auto">
<h1 class="font-brand text-[44px] text-secondary-container drop-shadow-sm cursor-pointer hover:text-secondary-fixed-dim transition-all duration-300 leading-none font-fleur text-[44px] text-arcane-purple leading-none">
<img alt="Logo" class="inline-block h-12 w-auto mr-3 align-middle " src="https://lh3.googleusercontent.com/aida-public/AB6AXuDUdPDRqfuHwZmxBu14HYH3TFhDWayvqszsJzU70FpW23aBH3WYK5YYcgMig-WNSNccEIDJj5uhmti5VCgdj3K4DK6hjQzLWtPFDI05hVvaahDn8fEUkqrP2vM7kn607cGGbXAw1p53lVlDEwMkJQDMBlRsziOCYGqPLn0p8_NZUieQjbJunR-qo908WvEqG8hVJkGyLBlJsAhI5T90vsVcQeNrMrwwbXOGGbSvrqzV0xcYjWgYepv_0sUndcL8hVXjqZm6KVSlyeQ"/><a href="index.html">Áster</a>
</h1>
<div class="hidden md:flex items-center gap-8 font-label-caps uppercase tracking-widest text-parchment-base opacity-80">
<a class="hover:text-ethereal-gold hover:shadow-[0_0_15px_rgba(238,197,84,0.3)] transition-all pb-1 border-b border-transparent hover:border-ethereal-gold" href="suasFichas.php">Minhas Fichas</a>
</div>

</div>
</header>
<main class="max-w-container-max mx-auto my-page-padding px-margin-mobile md:px-margin-desktop">
<div class="parchment-sheet p-8 md:p-16 rounded-sm">
<!-- Page Title -->
<div class="text-center mb-16 space-y-2">
<h1 class="font-fleur text-[64px] text-arcane-purple leading-none">Áster criador de fichas</h1>
<div class="section-divider !mt-8"></div>
</div>
<form class="space-y-16" id="characterSheet" method="post" enctype="multipart/form-data">
<!-- Section 1: Informações Básicas -->
<section>
<div class="flex items-center gap-4 mb-8">
<span class="material-symbols-outlined text-arcane-purple">edit_note</span>
<h2 class="font-fleur text-section-title text-arcane-purple">Informações básicas</h2>
</div>
<div class="grid grid-cols-1 md:grid-cols-3 gap-12">
<div class="col-span-1 md:col-span-2 grid grid-cols-1 sm:grid-cols-2 gap-8">
<div class="flex flex-col gap-1">
<label class="font-label-caps text-label-caps uppercase opacity-70">Nome do Personagem</label>
<input class="input-underline font-body-md text-body-md text-arcane-purple" placeholder="Ex: Elara Silverleaf" type="text" name="nomePersonagem">
</div>
<div class="flex flex-col gap-1">
<label class="font-label-caps text-label-caps uppercase opacity-70">Idade</label>
<input class="input-underline font-body-md text-body-md text-arcane-purple w-24" placeholder="--" type="number" name="idade">
</div>
<div class="flex flex-col gap-1">
<label class="font-label-caps text-label-caps uppercase opacity-70">Espécie / Origem</label>
<input class="input-underline font-body-md text-body-md text-arcane-purple" placeholder="Ex: Fantasma" type="text" name="especie">
</div>
<div class="flex flex-col gap-1">
<label class="font-label-caps text-label-caps uppercase opacity-70">Conexão Mágica (0-10)</label>
<input class="input-underline font-body-md text-body-md text-arcane-purple w-24" max="10" min="0" placeholder="0" type="number" name="conexaoMagica">
</div>
</div>
<div class="col-span-1">
<label class="font-label-caps text-label-caps uppercase opacity-70 mb-2 block">Retrato (Aparência)</label>
<div class="border-2 border-dashed border-arcane-purple/20 bg-stone-background/10 aspect-[4/5] flex flex-col items-center justify-center cursor-pointer hover:bg-stone-background/20 transition-all group relative overflow-hidden">
<span class="material-symbols-outlined text-4xl text-arcane-purple/40 group-hover:scale-110 transition-transform">add_a_photo</span>
<p class="font-label-caps text-[10px] mt-2 opacity-40">Enviar Imagem</p>
<input class="absolute inset-0 opacity-0 cursor-pointer" type="file" name="aparencia">
</div>
</div>
</div>
</section>
<div class="section-divider"></div>
<!-- Section 2: Atributos -->
<section>
<div class="flex items-center gap-4 mb-10">
<span class="material-symbols-outlined text-arcane-purple">shield_with_heart</span>
<h2 class="font-fleur text-section-title text-arcane-purple">Atributos</h2>
</div>
<div class="grid grid-cols-2 md:grid-cols-4 gap-8 mb-12">
<!-- Attributes -->
<div class="attribute-orb p-6 rounded-full aspect-square flex flex-col items-center justify-center gap-2 border-arcane-purple/30 border-2">
<label class="font-label-caps text-[10px] uppercase text-arcane-purple">Força</label>
<input class="bg-transparent text-center font-display-manuscript text-3xl w-16 text-arcane-purple border-none focus:ring-0" type="number" value="0" name="forca">
</div>
<div class="attribute-orb p-6 rounded-full aspect-square flex flex-col items-center justify-center gap-2 border-arcane-purple/30 border-2">
<label class="font-label-caps text-[10px] uppercase text-arcane-purple">Intelecto</label>
<input class="bg-transparent text-center font-display-manuscript text-3xl w-16 text-arcane-purple border-none focus:ring-0" type="number" value="0" name="intelecto">
</div>
<div class="attribute-orb p-6 rounded-full aspect-square flex flex-col items-center justify-center gap-2 border-arcane-purple/30 border-2">
<label class="font-label-caps text-[10px] uppercase text-arcane-purple">Agilidade</label>
<input class="bg-transparent text-center font-display-manuscript text-3xl w-16 text-arcane-purple border-none focus:ring-0" type="number" value="0" name="agilidade">
</div>
<div class="attribute-orb p-6 rounded-full aspect-square flex flex-col items-center justify-center gap-2 border-arcane-purple/30 border-2">
<label class="font-label-caps text-[10px] uppercase text-arcane-purple">Carisma</label>
<input class="bg-transparent text-center font-display-manuscript text-3xl w-16 text-arcane-purple border-none focus:ring-0" type="number" value="0" name="carisma">
</div>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 gap-x-16 gap-y-8 bg-ink-deep/5 p-8 rounded-lg">
<div class="space-y-6">
<div class="grid grid-cols-2 gap-4">
<div class="flex flex-col gap-1">
<label class="font-label-caps text-label-caps uppercase opacity-70">Defesa</label>
<input class="input-underline font-body-md text-body-md text-arcane-purple" type="number" name="defesa">
</div>
<div class="flex flex-col gap-1">
<label class="font-label-caps text-label-caps uppercase opacity-70">Def. Mágica</label>
<input class="input-underline font-body-md text-body-md text-arcane-purple" type="number" name="defesaMagica">
</div>
<div class="flex flex-col gap-1">
<label class="font-label-caps text-label-caps uppercase opacity-70">Bloqueio</label>
<input class="input-underline font-body-md text-body-md text-arcane-purple" type="number" name="bloqueio">
</div>
<div class="flex flex-col gap-1">
<label class="font-label-caps text-label-caps uppercase opacity-70">Esquiva</label>
<input class="input-underline font-body-md text-body-md text-arcane-purple" type="number" name="esquiva">
</div>
</div>
</div>
<div class="space-y-8">
<div class="space-y-2">
<div class="flex justify-between items-center">
<label class="font-label-caps text-label-caps uppercase text-arcane-purple font-bold">Vida</label>
<span class="font-body-md text-arcane-purple" id="lifeVal">50/100</span>
</div>
<input class="w-full h-2 bg-parchment-base rounded-lg appearance-none cursor-pointer accent-arcane-purple" oninput="document.getElementById('lifeVal').innerText = this.value + '/100'" type="range" name="vida">
</div>
<div class="space-y-2">
<div class="flex justify-between items-center">
<label class="font-label-caps text-label-caps uppercase text-secondary font-bold">Afinidade Mágica</label>
<span class="font-body-md text-secondary" id="magicVal">50%</span>
</div>
<input class="w-full h-2 bg-parchment-base rounded-lg appearance-none cursor-pointer accent-ethereal-gold" oninput="document.getElementById('magicVal').innerText = this.value + '%'" type="range" name="afinidadeMagica">
</div>
</div>
</div>
</section>
<div class="section-divider"></div>
<!-- Section 3: Habilidades -->
<section>
<div class="flex items-center gap-4 mb-8">
<span class="material-symbols-outlined text-arcane-purple">swords</span>
<h2 class="font-fleur text-section-title text-arcane-purple">Habilidades</h2>
</div>
<div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-x-8 gap-y-4">
<!-- Ability Inputs -->
<script>
    const skills = [
        "Crime", "Furtividade", "Iniciativa", "Tiro ao Alvo", 
        "Luta", "Atletismo", "Intuição", "Investigação", 
        "Medicina", "Sobrevivência", "Tática", "Lábia", 
        "Percepção", "Lidar com Animais", "Orientação Geográfica", 
        "Alquimia", "Navegação"
    ];
    const skillsDB = [
        "crime", "furtividade", "iniciativa", "tiroAoAlvo", 
        "luta", "atletismo", "intuicao", "investigacao", 
        "medicina", "sobrevivencia", "tatica", "labia", 
        "percepcao", "adestramento", "orientacaoGeografica", 
        "alquimia", "navegacao"
    ];
    
    skills.forEach((skill, i) => {
        document.write(`
            <div class="flex items-center justify-between border-b border-arcane-purple/10 py-1 hover:bg-arcane-purple/5 transition-colors px-2">
                <span class="font-body-md text-[13px] text-on-surface opacity-80">${skill}</span>
                <input type="number" value="0" min="0"
                name="${skillsDB[i]}"
                class="w-10 bg-transparent text-right font-bold text-arcane-purple border-none focus:ring-0 p-0">
            </div>
        `);
    });
</script>                                         
</div>
</section>
<div class="section-divider"></div>
<!-- Section 4: Adicionais -->
<section>
<div class="flex items-center gap-4 mb-8">
<span class="material-symbols-outlined text-arcane-purple">inventory_2</span>
<h2 class="font-fleur text-section-title text-arcane-purple">Anotações &amp; Pertences</h2>
</div>
<div class="grid grid-cols-1 md:grid-cols-2 gap-8">
<div class="space-y-2">
<label class="font-label-caps text-label-caps uppercase opacity-70 flex items-center gap-2">
<span class="material-symbols-outlined text-sm">auto_fix_high</span> Magias Conhecidas
                            </label>
<textarea class="w-full bg-ink-deep/5 border-arcane-purple/20 font-body-md text-body-md p-4 rounded focus:ring-ethereal-gold focus:border-ethereal-gold" placeholder="Descreva os rituais e encantamentos..." rows="6" name="magiasConhecidas"></textarea>
</div>
<div class="space-y-2">
<label class="font-label-caps text-label-caps uppercase opacity-70 flex items-center gap-2">
<span class="material-symbols-outlined text-sm">backpack</span> Inventário
                            </label>
<textarea class="w-full bg-ink-deep/5 border-arcane-purple/20 font-body-md text-body-md p-4 rounded focus:ring-ethereal-gold focus:border-ethereal-gold" placeholder="Itens consumíveis, armas, tesouros..." rows="6" name="inventario"></textarea>
</div>
<div class="space-y-2">
<label class="font-label-caps text-label-caps uppercase opacity-70 flex items-center gap-2">
<span class="material-symbols-outlined text-sm">history_edu</span> Observações
                            </label>
<textarea class="w-full bg-ink-deep/5 border-arcane-purple/20 font-body-md text-body-md p-4 rounded focus:ring-ethereal-gold focus:border-ethereal-gold" placeholder="Anotações sobre a jornada..." rows="6" name="observacoes"></textarea>
</div>
<div class="space-y-2">
<label class="font-label-caps text-label-caps uppercase opacity-70 flex items-center gap-2">
<span class="material-symbols-outlined text-sm">palette</span> Hobbies &amp; Talentos
                            </label>
<textarea class="w-full bg-ink-deep/5 border-arcane-purple/20 font-body-md text-body-md p-4 rounded focus:ring-ethereal-gold focus:border-ethereal-gold" placeholder="O que seu personagem faz no tempo livre?" rows="6" name="hobbies"></textarea>
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
<!-- Decorative Elements from Images -->
<div class="absolute top-10 right-10 opacity-10 pointer-events-none rotate-12">
<span class="material-symbols-outlined text-8xl text-arcane-purple">explore</span>
</div>
<div class="absolute bottom-10 left-10 opacity-10 pointer-events-none -rotate-12">
<span class="material-symbols-outlined text-9xl text-arcane-purple">anchor</span>
</div>
</div>
</main>
<!-- Footer -->

<footer class="bg-primary-container border-t border-on-primary-fixed-variant/20">
<div class="flex flex-col md:flex-row justify-between items-center w-full px-margin-mobile md:px-margin-desktop py-unit-gutter max-w-[1280px] mx-auto text-secondary-container">
<div class="mb-4 md:mb-0">
<h4 class="font-brand text-[36px] text-secondary-container leading-none font-fleur text-[64px] text-arcane-purple leading-none">
<br>
<img alt="Logo" class="inline-block h-8 w-auto mr-2 align-middle"src="https://lh3.googleusercontent.com/aida-public/AB6AXuDUdPDRqfuHwZmxBu14HYH3TFhDWayvqszsJzU70FpW23aBH3WYK5YYcgMig-WNSNccEIDJj5uhmti5VCgdj3K4DK6hjQzLWtPFDI05hVvaahDn8fEUkqrP2vM7kn607cGGbXAw1p53lVlDEwMkJQDMBlRsziOCYGqPLn0p8_NZUieQjbJunR-qo908WvEqG8hVJkGyLBlJsAhI5T90vsVcQeNrMrwwbXOGGbSvrqzV0xcYjWgYepv_0sUndcL8hVXjqZm6KVSlyeQ"/><a href="index.html">Áster</a>
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