<?php
session_start();

$mensagemErro = '';

// Verifica se a sessão com o número da conta está ativa
if (!isset($_SESSION['usernumber'])) {
    header("Location: login.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $senhaDigitada = $_POST["senha"] ?? '';

    $dadosJson = file_get_contents("../usuarios.json");
    $usuarios = json_decode($dadosJson, true);

    $conta = $_SESSION['usernumber'];
    $usuario = null;

    foreach ($usuarios as $u) {
        if ($u['usernumber'] === $conta) {
            $usuario = $u;
            break;
        }
    }

    if ($usuario && $usuario["senha"] === $senhaDigitada) {
        header("Location: menu.php");
        exit;
    } else {
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