<?php
session_start();
$erro = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $valor = $_POST['valor'] ?? '';
    $valor = floatval(str_replace(',', '.', $valor));

    if ($valor <= 0) {
        $erro = true;
    } else {
        $_SESSION['valor'] = $valor;
        $_SESSION['operacao'] = 'deposito_poupanca';
        header('Location: ../../menu/confirmar_operacao.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <link rel="stylesheet" href="../../menu/menu.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EconomiCred - Página Inicial</title>
    <link rel="shortcut icon" href="../../Imagens/iconmaior.ico" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@450&display=swap" rel="stylesheet">
</head>

<body>

    <audio src="/Sons/beep.mp3" id="som"></audio>
    <audio src="/Sons/saque_deposito.mp3" id="somdinheiro"></audio>

    <div class="tudo">
        <!-- Botões da esquerda -->
        <div class="botoes">
            <div class="spacerbtn"></div>
            <input type="button" class="botao" onclick="tocarComAtraso('#')">
            <input type="button" class="botao" onclick="tocarComAtraso('#')">
            <input type="button" class="botao" onclick="tocarComAtraso('deposito.html')">
            <div class="spacerbtn"></div>
        </div>

        <div class="border"></div>

        <!-- Tela do caixa -->
        <div class="tela">
            <!-- Header -->
            <div class="header">
                <div class="logo_header">
                    <img src="../../Imagens/Logocomnome.png">
                </div>
                <div class="banco24h">
                    <img src="../../Imagens/Banco24h.png">
                </div>
            </div>

            <div class="space"></div>

            <!-- Conteúdo principal -->
            <form method="post">
                <div class="tudo_op">
                    <div class="meio_op">
                        <span class="inserir_valor_texto">Valor a ser depositado na conta poupança:</span>
                        <input type="number" name="valor" id="valor" autofocus style="caret-color: transparent;"
                            pattern="\d*" inputmode="numeric" oninput="this.value = this.value.replace(/\D/g, '')">

                        <?php if ($erro): ?>
                            <p class="mensagem-erro" id="mensagem-erro">Valor inválido. Insira um valor maior que zero.</p>
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
        const beep = new Audio('../../Sons/beep.mp3');

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
                document.querySelector('form').submit();
            }, 550);
        }

        // Esconde a mensagem de erro após 2 segundos
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