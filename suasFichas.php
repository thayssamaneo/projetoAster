<?php
if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION['nomeUsuario'])) {
    header("Location: paginaLogin.php"); 
    exit();
}

include("conexao.php");

try {
    // Buscar ID do usuário logado
    $sql_user = "SELECT idusuarios FROM usuarios WHERE nomeusuario = :user";
    $stmt_user = $conexao->prepare($sql_user);
    $stmt_user->bindValue(':user', $_SESSION['nomeUsuario']);
    $stmt_user->execute();
    $user_data = $stmt_user->fetch(PDO::FETCH_ASSOC);

    if ($user_data) {
        $usuario_id = $user_data['idusuarios'];
    } else {
        // Se por algum motivo o usuário da sessão não existir no banco, desloga
        header("Location: index.php");
        exit();
    }

    // Buscar todas as fichas do usuário ativo unindo com a tabela informacoesBase
    $sql_fichas = "SELECT f.idFichas, ib.nomePersonagem, ib.especie, ib.aparencia, f.criadaEm 
                   FROM fichas f
                   JOIN informacoesBase ib ON f.informacoesBase_id = ib.idInformacoesBase
                   WHERE f.usuario_id = :user_id 
                   ORDER BY f.idFichas DESC";
                   
    $stmt_fichas = $conexao->prepare($sql_fichas);
    $stmt_fichas->bindValue(':user_id', $usuario_id);
    $stmt_fichas->execute();
    $fichas = $stmt_fichas->fetchAll(PDO::FETCH_ASSOC);

} catch (PDOException $e) {
    die("Erro ao carregar suas fichas: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html class="light" lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Áster - Suas Fichas</title>
    <script src="https://cdn.tailwindcss.com?plugins=forms,container-queries"></script>
    <link href="https://fonts.googleapis.com" rel="preconnect">
    <link crossorigin="" href="https://fonts.gstatic.com" rel="preconnect">
    <link href="https://fonts.googleapis.com/css2?family=Amita:wght@400;700&family=Courier+Prime:ital,wght@0,400;0,700;1,400&family=Playfair+Display:ital,wght@0,400..900;1,400..900&family=Work+Sans:wght@300;400;600&family=Fleur+De+Leah&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:wght,FILL@100..700,0..1&display=swap" rel="stylesheet">
    <style>
        .material-symbols-outlined {
            font-variation-settings: 'FILL' 0, 'wght' 400, 'GRAD' 0, 'opsz' 24;
        }
        .parchment-texture {
            background-color: #E9D6C7;
            background-image: radial-gradient(circle at 50% 50%, rgba(255, 255, 255, 0.2) 0%, rgba(0, 0, 0, 0) 100%),
                              url("https://www.transparenttextures.com/patterns/handmade-paper.png");
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1), inset 0 0 60px rgba(73, 50, 107, 0.05);
        }
        .stone-bg {
            background-color: #C0B499;
        }
        .ink-divider {
            height: 2px;
            background: linear-gradient(90deg, transparent 0%, #231A11 50%, transparent 100%);
            opacity: 0.3;
        }
        .font-manuscript-title {
            font-family: 'Fleur De Leah', cursive;
        }
        input:focus {
            outline: none;
            box-shadow: 0 0 0 2px #EEC554;
        }
        .hover-glow:hover {
            box-shadow: 0 0 15px rgba(238, 197, 84, 0.3);
        }
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: #E9D6C7; }
        ::-webkit-scrollbar-thumb { background: #49326B; border-radius: 4px; }
    </style>
    <script id="tailwind-config">
        tailwind.config = {
          darkMode: "class",
          theme: {
            extend: {
              "colors": {
                      "surface": "#fff8f5",
                      "surface-container": "#feeadb",
                      "background": "#fff8f5",
                      "on-surface-variant": "#4a454f",
                      "parchment-base": "#E9D6C7",
                      "surface-tint": "#6b538e",
                      "on-error-container": "#93000a",
                      "on-background": "#231a11",
                      "inverse-primary": "#d6bafd",
                      "surface-container-lowest": "#ffffff",
                      "surface-variant": "#f2dfd0",
                      "surface-bright": "#fff8f5",
                      "tertiary-fixed": "#ffd6ff",
                      "secondary-fixed": "#ffdf90",
                      "tertiary-fixed-dim": "#f7adff",
                      "error-container": "#ffdad6",
                      "on-error": "#ffffff",
                      "on-primary-fixed-variant": "#523b75",
                      "primary-fixed": "#eddcff",
                      "secondary-container": "#fed979",
                      "surface-container-low": "#fff1e7",
                      "on-primary-container": "#b89dde",
                      "primary": "#321b53",
                      "on-tertiary": "#ffffff",
                      "on-surface": "#231a11",
                      "secondary-fixed-dim": "#e6c366",
                      "inverse-surface": "#392e24",
                      "primary-fixed-dim": "#d6bafd",
                      "surface-dim": "#ead7c8",
                      "on-secondary-fixed-variant": "#584400",
                      "on-secondary-container": "#785d03",
                      "on-primary-fixed": "#250d46",
                      "primary-container": "#49326b",
                      "arcane-purple": "#49326B",
                      "surface-container-highest": "#f2dfd0",
                      "on-primary": "#ffffff",
                      "on-tertiary-fixed": "#350040",
                      "secondary": "#755b00",
                      "error": "#ba1a1a",
                      "on-tertiary-container": "#da8de4",
                      "ink-deep": "#231A11",
                      "on-tertiary-fixed-variant": "#6d297a",
                      "stone-background": "#C0B499",
                      "outline-variant": "#ccc4d0",
                      "outline": "#7b7580",
                      "tertiary": "#490058",
                      "tertiary-container": "#621f70",
                      "on-secondary": "#ffffff",
                      "on-secondary-fixed": "#241a00",
                      "inverse-on-surface": "#ffeee1",
                      "ethereal-gold": "#EEC554",
                      "surface-container-high": "#f8e5d5"
              },
              "borderRadius": {
                      "DEFAULT": "0.125rem",
                      "lg": "0.25rem",
                      "xl": "0.5rem",
                      "full": "0.75rem"
              },
              "spacing": {
                      "margin-mobile": "16px",
                      "margin-desktop": "40px",
                      "gutter": "24px",
                      "container-max": "1140px",
                      "page-padding": "40px",
                      "unit": "8px"
              },
              "fontFamily": {
                      "headline-md": ["Playfair Display"],
                      "display-manuscript": ["Amita"],
                      "label-caps": ["Work Sans"],
                      "body-md": ["Courier Prime"]
              }
            }
          }
        }
    </script>
</head>
<body class="stone-bg min-h-screen flex flex-col font-body-md text-on-surface">

<header class="bg-arcane-purple text-ethereal-gold shadow-lg py-4 px-8 flex justify-between items-center">
    <div class="flex items-center gap-4">
        <h1 class="font-manuscript-title text-4xl"><a href="index.html">Áster</a></h1>
    </div>
    <nav class="flex items-center gap-4">
        <a href="criarFicha.php" class="bg-secondary-container text-primary-container px-4 py-2 font-bold hover:bg-ethereal-gold transition-all">Criar Nova Ficha</a>
        <a href="index.php" class="text-ethereal-gold underline">Sair</a>
    </nav>
</header>

<main class="flex-grow py-12 px-6">
    <div class="max-w-[1200px] mx-auto">
        <div class="text-center mb-12">
            <h2 class="font-manuscript-title text-6xl text-primary mb-2">Suas Fichas</h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php if (count($fichas) === 0): ?>
                <div class="col-span-full text-center p-12 parchment-texture">
                    <p class="italic text-ink-deep/80">Nenhuma ficha foi criada ainda.</p>
                </div>
            <?php else: ?>
                <?php foreach ($fichas as $ficha): ?>
                    <div class="parchment-texture p-6 relative flex flex-col justify-between border border-ink-deep/10 shadow-lg hover:scale-[1.02] transition-all duration-300">
                        <div class="absolute top-0 left-0 w-8 h-8 border-t-2 border-l-2 border-primary/20 m-2"></div>
                        <div class="absolute bottom-0 right-0 w-8 h-8 border-b-2 border-r-2 border-primary/20 m-2"></div>
                        
                        <div class="flex items-start gap-4 mb-6">
                            <img alt="Aparência do Personagem" class="w-24 h-24 object-cover border-2 border-primary/30 rounded-sm" src="<?php echo htmlspecialchars($ficha['aparencia']); ?>">
                            <div>
                                <h3 class="font-headline-md text-2xl text-primary font-bold leading-tight"><?php echo htmlspecialchars($ficha['nomepersonagem']); ?></h3>
                                <p class="text-sm font-body-md text-ink-deep/60 mt-1">Espécie: <?php echo htmlspecialchars($ficha['especie']); ?></p>
                                <p class="text-xs text-ink-deep/40 mt-2">Criado em: <?php echo date('d/m/Y', strtotime($ficha['criadaem'])); ?></p>
                            </div>
                        </div>
                        
                        <a href="editarFicha.php?id=<?php echo $ficha['idfichas']; ?>" class="w-full bg-primary-container text-ethereal-gold text-center py-3 rounded-sm font-label-caps uppercase tracking-wider hover:opacity-90 transition-all flex justify-center items-center gap-2">
                            Abrir Ficha
                            <span class="material-symbols-outlined text-sm">auto_stories</span>
                        </a>
                        <br>
                        <a href="deletarFicha.php?id=<?php echo $ficha['idfichas']; ?>" 
                            onclick="return confirm('Tem certeza de que deseja excluir esta ficha? Isso não pode ser desfeito.');" 
                            class="w-full bg-red-900/20 text-red-400 border border-red-500/30 text-center py-2 rounded-sm font-label-caps uppercase tracking-wider hover:bg-red-900/40 transition-all flex justify-center items-center gap-2 text-xs">
                            Deletar Ficha
                        <span class="material-symbols-outlined text-xs">delete</span>
                    </a>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</main>

<footer class="bg-primary-container border-t border-on-primary-fixed-variant/20">
    <div class="flex flex-col md:flex-row justify-between items-center w-full px-margin-mobile md:px-margin-desktop py-4 max-w-[1280px] mx-auto text-secondary-container">
        <div class="mb-4 md:mb-0">
            <h4 class="font-manuscript-title text-4xl leading-none">
                <img alt="Logo" class="inline-block h-8 w-auto mr-2 align-middle" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDUdPDRqfuHwZmxBu14HYH3TFhDWayvqszsJzU70FpW23aBH3WYK5YYcgMig-WNSNccEIDJj5uhmti5VCgdj3K4DK6hjQzLWtPFDI05hVvaahDn8fEUkqrP2vM7kn607cGGbXAw1p53lVlDEwMkJQDMBlRsziOCYGqPLn0p8_NZUieQjbJunR-qo908WvEqG8hVJkGyLBlJsAhI5T90vsVcQeNrMrwwbXOGGbSvrqzV0xcYjWgYepv_0sUndcL8hVXjqZm6KVSlyeQ"/><a href="index.php">Áster</a>
            </h4>
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
</body>
</html>