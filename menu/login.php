<?php
session_start();
$erro = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $usernumber_digitado = $_POST['usernumber'] ?? '';
    $dados_json = file_get_contents('../usuarios.json');
    $usuarios = json_decode($dados_json, true);

    foreach ($usuarios as $usuario) {
        if ($usuario['usernumber'] === $usernumber_digitado) {
            $_SESSION['usernumber'] = $usernumber_digitado;
            header('Location: senha.php');
            exit();
        }
    }

    $erro = true;
}
?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
    <link rel="stylesheet" href="menu.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EconomiCred - Login</title>
    <link rel="shortcut icon" href="../Imagens/iconmaior.ico" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@450&display=swap" rel="stylesheet">
</head>

<body>

    <audio src="../Sons/beep.mp3" id="som"></audio>

    <div class="tudo">
        <!-- Botões da esquerda -->
        <div class="botoes">
            <div class="spacerbtn"></div>
            <input type="button" class="botao" onclick="tocarComAtraso('#')">
            <input type="button" class="botao" onclick="tocarComAtraso('#')">
            <input type="button" class="botao" onclick="tocarComAtraso('../index.html')">
            <div class="spacerbtn"></div>
        </div>

        <div class="border"></div>

        <!-- Tela -->
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

            <!-- Formulário -->
            <form id="formConta" method="post">
                <div class="tudo_op">
                    <div class="meio_op">
                        <div class="numero_da_conta">
                            <span>Número da sua conta:</span>
                        </div>
                        <input type="text" name="usernumber" id="valor" maxlength="6" autofocus
                            style="caret-color: transparent;" inputmode="numeric"
                            oninput="this.value = this.value.replace(/\D/g, '')">

                        <?php if ($erro): ?>
                            <p class="mensagem-erro" id="mensagem-erro">Conta não encontrada.</p>
                        <?php endif; ?>
                    </div>

                    <div class="footer_op">
                        <div class="botao_voltar_valor">
                            <span>Voltar</span>
                        </div>
                        <div class="botao_confirmar_valor">
                            <span>Confirmar</span>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <!-- Botões da direita -->
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
                document.getElementById('formConta').submit();
            }, 550);
        }

        // Desaparece automaticamente após 3 segundos
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
