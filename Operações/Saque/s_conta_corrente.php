<?php
session_start(); // Inicia a sessão para usar variáveis de sessão
$erro = ""; // Inicializa variável para armazenar mensagens de erro

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Recebe o valor enviado pelo formulário e converte para inteiro
    $valor = intval($_POST["valor"]);

    // Define as cédulas válidas para saque
    $cedulas_validas = [5, 10, 20, 50, 100];

    // Verifica se o valor está dentro do limite permitido e é positivo
    if ($valor <= 0 || $valor > 10000) {
        $erro = "Valor inválido. Máximo: R$10.000, mínimo: R$5.";
    } 
    // Verifica se o valor é múltiplo de 5 (menor valor de cédula)
    elseif ($valor % 5 !== 0) {
        $erro = "Não há notas disponíveis para esse valor.";
    } else {
        // Se passar nas validações, pega os dados da operação na sessão
        $_SESSION["operacao"] = "saque_cc";
        $_SESSION["valor"] = $valor;
        // Redireciona para a página de confirmação da operação
        header("Location: ../../menu/confirmar_operacao.php");
        exit;
    }
}
?>


<!DOCTYPE html>
<html lang="pt-br">

<head>
    <link rel="stylesheet" href="../../menu/menu.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Saque - Conta Corrente</title>
    <link rel="shortcut icon" href="../../Imagens/iconmaior.ico" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@450&display=swap" rel="stylesheet">
</head>
<!--Botões da esquerda-->
<body>
    <div class="tudo">
        <div class="botoes">
            <div class="spacerbtn"></div>
            <input type="button" class="botao" onclick="tocarComAtraso('#')">
            <input type="button" class="botao" onclick="tocarComAtraso('#')">
            <input type="button" class="botao" onclick="tocarComAtraso('saque.html')">
            <div class="spacerbtn"></div>
        </div>

        <div class="border"></div>

        <div class="tela">
            <div class="header">
                <div class="logo_header">
                    <img src="../../Imagens/LogoComNome.png">
                </div>
                <div class="banco24h">
                    <img src="../../Imagens/Banco24h.png">
                </div>
            </div>

            <div class="space"></div>

            <div class="tudo_op">
                <form method="POST">
                    <div class="meio_op">
                        <div class="saque_conta_corrente">
                            <span>Valor a ser sacado da conta corrente:</span>
                        </div>
                        <input type="number" name="valor" id="valor" required autofocus
                            oninput="this.value = this.value.replace(/\D/g, '')" inputmode="numeric" pattern="\d*"
                            style="caret-color: transparent;">

                        <?php if (!empty($erro)): ?>
                            <div class="mensagem-erro"><?= htmlspecialchars($erro) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="footer_op">
                        <button type="button" class="botao_voltar_valor">
                            Voltar
                        </button>
                        <button type="button" class="botao_confirmar_valor">
                            Confirmar
                        </button>
                    </div>
                </form>
            </div>
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
    </script>
</body>

</html>