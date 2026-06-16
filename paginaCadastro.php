<?php 
// 1. Inclui o arquivo de conexão com o PostgreSQL
include("conexao.php");

$mensagem = ""; // Variável para exibir feedback de sucesso
$erro = "";     // Variável para exibir mensagens de erro

if (isset($_POST["nomeUsuario"]) && isset($_POST["senha"])) {
    $usuario_digitado = trim($_POST['nomeUsuario']);
    // Criptografia idêntica à usada na checagem do login
    $senha_criptografada = password_hash($_POST['senha'], PASSWORD_DEFAULT); 

    try {
        // Passo 1: Verificar se o usuário já existe no pergaminho de registros
        $sql_busca = "SELECT idUsuarios FROM usuarios WHERE nomeUsuario = :usuario";
        $stmt_busca = $conexao->prepare($sql_busca);
        $stmt_busca->bindValue(':usuario', $usuario_digitado);
        $stmt_busca->execute();

        if ($stmt_busca->rowCount() > 0) {
            $erro = "Este nome de usuário já foi reivindicado por outro aventureiro.";
        } else {
            // Passo 2: Se não existe, podemos inserir o novo usuário
            $sql_inserir = "INSERT INTO usuarios (nomeUsuario, senha) VALUES (:usuario, :senha)";
            $stmt_inserir = $conexao->prepare($sql_inserir);
            $stmt_inserir->bindValue(':usuario', $usuario_digitado);
            $stmt_inserir->bindValue(':senha', $senha_criptografada);
            
            if ($stmt_inserir->execute()) {
                $mensagem = "Cadastro realizado com sucesso! Você já pode entrar.";
                // Opcional: Se quiser redirecionar direto para o login após 3 segundos:
                // header("Refresh: 3; url=login.php");
            } else {
                $erro = "Falha ao salvar.";
            }
        }
    } catch (PDOException $e) {
        $erro = "Erro no sistema de crônicas: " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html class="light" lang="pt-BR">
<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <title>Áster - Cadastro</title>
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
<body class="stone-bg min-h-screen flex flex-col font-body-md text-on-surface selection:bg-ethereal-gold/30">

<header class="bg-arcane-purple text-ethereal-gold shadow-lg border-b border-ethereal-gold/20 flex justify-between items-center w-full px-margin-mobile md:px-margin-desktop py-4 sticky top-0 z-50">
    <div class="flex items-center gap-4">
        <img alt="Áster Chronicles Logo" class="h-12 w-12 object-contain" src="https://lh3.googleusercontent.com/aida-public/AB6AXuCIjtRcKxY093CLigOc-c2TuTUplcLOUYI3RtQ0cRszwyn0FrVXBKvrnp_hjpVf3FPkx3SxsS7gVCY1exIMrCUS9QKYBIdZT_AHywCe0v171xl6a5h3SgyiXAkkTVe-F9Ne5-KXawLPdEi2sjf87Nn50kiZFclWUGAkfMc4Z6QpoWNrnnMopjVfpQyp2OaFrlcdj1F4nNfCkbVyJoMDZjH7pde4Z6cTD-jFpcXaE_-LxL23hF70TgUOhmt85VFKW6JPoXdjo3P7Okc">
        <h1 class="font-manuscript-title text-4xl leading-none"><a href="index.html">Áster</a></h1>
    </div>
    <nav class="flex items-center gap-6">
        <a href="index.html" class="bg-primary-container text-secondary-container border border-secondary-container px-6 py-2 font-label-caps text-label-caps hover:bg-secondary-container hover:text-primary-container transition-all active:scale-95">
            Home
        </a>
        <button class="material-symbols-outlined text-parchment-base opacity-80 hover:text-ethereal-gold md:hidden">menu</button>
    </nav>
</header>

<main class="flex-grow flex items-center justify-center py-12 px-margin-mobile">
    <div class="container-max w-full max-w-[500px]">
        <div class="parchment-texture p-8 md:p-12 relative overflow-hidden border border-ink-deep/10">
            <div class="absolute top-0 left-0 w-12 h-12 border-t-2 border-l-2 border-primary/20 m-4"></div>
            <div class="absolute bottom-0 right-0 w-12 h-12 border-b-2 border-r-2 border-primary/20 m-4"></div>
            
            <div class="relative z-10">
                <div class="text-center mb-10">
                    <h2 class="font-manuscript-title text-5xl text-primary mb-2">Cadastro</h2>
                    <div class="ink-divider w-1/2 mx-auto"></div>
                </div>

                <?php if(!empty($erro)): ?>
                    <div class="mb-6 p-3 bg-red-100 border border-red-400 text-red-700 rounded text-sm text-center">
                        <?php echo $erro; ?>
                    </div>
                <?php endif; ?>

                <?php if(!empty($mensagem)): ?>
                    <div class="mb-6 p-3 bg-green-100 border border-green-400 text-green-700 rounded text-sm text-center">
                        <?php echo $mensagem; ?>
                    </div>
                <?php endif; ?>

                <form class="space-y-6" action="" method="POST">
                    <div class="space-y-2">
                        <label class="block font-body-md text-ink-deep/80 text-sm italic" for="nomeUsuario">Crie um nome de usuário</label>
                        <input class="w-full bg-surface-container-low/50 border-b-2 border-primary/20 border-t-0 border-l-0 border-r-0 py-3 px-2 font-body-md text-ink-deep placeholder:text-ink-deep/30 focus:border-ethereal-gold bg-transparent transition-all" id="nomeUsuario" name="nomeUsuario" placeholder="Digite seu nome" required type="text" value="<?php echo isset($_POST['nomeUsuario']) ? htmlspecialchars($_POST['nomeUsuario']) : ''; ?>">
                    </div>
                    
                    <div class="space-y-2">
                        <label class="block font-body-md text-ink-deep/80 text-sm italic" for="password">Crie uma Senha</label>
                        <input class="w-full bg-surface-container-low/50 border-b-2 border-primary/20 border-t-0 border-l-0 border-r-0 py-3 px-2 font-body-md text-ink-deep placeholder:text-ink-deep/30 focus:border-ethereal-gold bg-transparent transition-all" id="password" name="senha" placeholder="••••••••" required type="password">
                    </div>

                    <button class="w-full bg-primary-container text-ethereal-gold font-label-caps py-4 rounded-sm border border-ethereal-gold/30 hover-glow active:scale-95 transition-all duration-300 uppercase tracking-widest flex justify-center items-center gap-2" type="submit">
                        Cadastrar
                        <span class="material-symbols-outlined text-[18px]">auto_stories</span>
                    </button>
                </form>
                
                <div class="mt-10 pt-6 border-t border-primary/10 text-center">
                    <p class="font-body-md text-on-surface-variant text-sm">
                        Já possui cadastro? 
                        <a class="text-primary font-bold hover:text-secondary transition-all underline decoration-ethereal-gold/50 underline-offset-4 ml-1" href="Paginalogin.php">
                            Faça Login
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</main>

<footer class="bg-primary-container border-t border-on-primary-fixed-variant/20">
    <div class="flex flex-col md:flex-row justify-between items-center w-full px-margin-mobile md:px-margin-desktop py-4 max-w-[1280px] mx-auto text-secondary-container">
        <div class="mb-4 md:mb-0">
            <h4 class="font-manuscript-title text-4xl leading-none">
                <img alt="Logo" class="inline-block h-8 w-auto mr-2 align-middle" src="https://lh3.googleusercontent.com/aida-public/AB6AXuDUdPDRqfuHwZmxBu14HYH3TFhDWayvqszsJzU70FpW23aBH3WYK5YYcgMig-WNSNccEIDJj5uhmti5VCgdj3K4DK6hjQzLWtPFDI05hVvaahDn8fEUkqrP2vM7kn607cGGbXAw1p53lVlDEwMkJQDMBlRsziOCYGqPLn0p8_NZUieQjbJunR-qo908WvEqG8hVJkGyLBlJsAhI5T90vsVcQeNrMrwwbXOGGbSvrqzV0xcYjWgYepv_0sUndcL8hVXjqZm6KVSlyeQ"/><a href="index.html">Áster</a>
            </h4>
        </div>
        <p class="font-body-md text-[14px] text-center md:text-right text-on-primary-container">
            © 2026 Áster RPG. O uso comercial não é authorized.
        </p>
        <div class="mt-4 md:mt-0">
            <a class="text-on-primary-container font-body-md hover:text-secondary-fixed-dim transition-colors duration-200 underline underline-offset-4" href="#">
                Voltar ao topo
            </a>
        </div>
    </div>
</footer>

<script>
    const inputs = document.querySelectorAll('input');
    inputs.forEach(input => {
        input.addEventListener('focus', () => {
            input.parentElement.querySelector('label').style.color = '#755b00';
        });
        input.addEventListener('blur', () => {
            input.parentElement.querySelector('label').style.color = 'rgba(35, 26, 17, 0.8)';
        });
    });
</script>
</body>
</html>