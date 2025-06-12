<?php
session_start(); // Inicia a sessão para usar variáveis de sessão

$mensagemErro = ''; // Inicializa variável para mensagem de erro

// Verifica se a sessão com o número da conta está ativa
if (!isset($_SESSION['usernumber'])) {
    // Se não estiver, redireciona para a página de login
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Pega a senha digitada no formulário, ou string vazia se não existir
    $senhaDigitada = $_POST["senha"] ?? '';

    // Carrega o conteúdo do arquivo JSON com dados dos usuários
    $dadosJson = file_get_contents("../usuarios.json");
    // Decodifica o JSON em array associativo
    $usuarios = json_decode($dadosJson, true);

    // Pega o número da conta da sessão atual
    $conta = $_SESSION['usernumber'];
    $usuario = null;

    // Procura o usuário na lista que tenha o número da conta da sessão
    foreach ($usuarios as $u) {
        if ($u['usernumber'] === $conta) {
            $usuario = $u;
            break; // Sai do loop ao encontrar
        }
    }

    // Verifica se usuário foi encontrado e a senha bate com a digitada
    if ($usuario && $usuario["senha"] === $senhaDigitada) {
        // Senha correta: redireciona para o menu
        header("Location: menu.php");
        exit;
    } else {
        // Senha incorreta: define mensagem de erro
        $mensagemErro = "Senha incorreta.";
    }
}
?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
    <link rel="stylesheet" href="menu.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EconomiCred - Senha</title>
    <link rel="shortcut icon" href="../Imagens/iconmaior.ico" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@450&display=swap" rel="stylesheet">
</head>

<body>
    <audio src="../Sons/beep.mp3" id="som"></audio>

    <div class="tudo">
        <div class="botoes">
            <div class="spacerbtn"></div>
            <input type="button" class="botao" onclick="tocarComAtraso('#')">
            <input type="button" class="botao" onclick="tocarComAtraso('#')">
            <input type="button" class="botao" onclick="tocarComAtraso('login.php')">
            <div class="spacerbtn"></div>
        </div>

        <div class="border"></div>

        <div class="tela">
            <div class="header">
                <div class="logo_header">
                    <img src="../Imagens/Logocomnome.png">
                </div>
                <div class="banco24h">
                    <img src="../Imagens/Banco24h.png">
                </div>
            </div>

            <div class="space"></div>

            <form id="formSenha" method="POST">
                <div class="tudo_op">
                    <div class="meio_op">
                        <div class="senha_da_conta">
                            <span>Insira sua senha:</span>
                        </div>

                        <input type="password" name="senha" id="valor" autofocus style="caret-color: transparent;"
                            maxlength="4" pattern="\d*" inputmode="numeric"
                            oninput="this.value = this.value.replace(/\D/g, '')">

                        <?php if (!empty($mensagemErro)): ?>
                            <div class="erro-senha" id="mensagem-erro">
                                <?= $mensagemErro ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="footer_op">
                        <div class="botao_voltar_valor">
                            <span>Voltar</span>
                        </div>
                        <div class="botao_confirmar_valor">
                            <span onclick="enviarFormulario()">Confirmar</span>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="botoes">
            <div class="spacerbtn"></div>
            <input type="button" class="botao" onclick="tocarComAtraso('#')">
            <input type="button" class="botao" onclick="tocarComAtraso('#')">
            <input type="button" class="botao" onclick="enviarFormulario()">
            <div class="spacerbtn"></div>
        </div>
    </div>

    <script>
        const beep = new Audio('../Sons/beep.mp3');

        function tocarComAtraso(url) {
            beep.currentTime = 0;
            beep.play();
            setTimeout(() => {
                window.location.href = url;
            }, 550);
        }

        function enviarFormulario() {
            beep.currentTime = 0;
            beep.play();
            setTimeout(() => {
                document.getElementById('formSenha').submit();
            }, 550);
        }

        setTimeout(() => {
            const msg = document.getElementById('mensagem-erro');
            if (msg) {
                msg.style.transition = "opacity 0.5s ease";
                msg.style.opacity = 0;
                setTimeout(() => msg.remove(), 500);
            }
        }, 2000);
    </script>
</body>

</html>