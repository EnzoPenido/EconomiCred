<?php
session_start(); // Inicia a sessão para usar variáveis de sessão
$erro = ""; // Inicializa variável para mensagens de erro

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Recebe o valor do formulário e converte para inteiro
    $valor = intval($_POST["valor"]);

    // Define as cédulas válidas para saque
    $cedulas_validas = [5, 10, 20, 50, 100];

    // Verifica se o valor é positivo e está no limite máximo permitido
    if ($valor <= 0 || $valor > 10000) {
        $erro = "Valor inválido. Máximo: R$10.000, mínimo: R$5.";
    } 
    // Verifica se o valor é múltiplo de 5 
    elseif ($valor % 5 !== 0) {
        $erro = "Não há notas disponíveis para esse valor.";
    } else {
        // Se válido, define a operação como saque da poupança
        $_SESSION["operacao"] = "saque_poup";
        $_SESSION["valor"] = $valor;
        // Redireciona para página de confirmação da operação
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
    <title>EconomiCred - Página Inicial</title>
    <link rel="shortcut icon" href="../../Imagens/iconmaior.ico" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@450&display=swap" rel="stylesheet">
</head>

<body>

    <!-- Aqui estão os botões da esquerda -->

    <div class="tudo">
        <div class="botoes">
            <div class="spacerbtn"></div>
            <input type="button" class="botao" onclick="tocarComAtraso('#')">
            <input type="button" class="botao" onclick="tocarComAtraso('#')">
            <input type="button" class="botao" onclick="tocarComAtraso('saque.html')">
            <div class="spacerbtn"></div>
        </div>

        <div class="border">

        </div>

        <!-- Aqui está a "tela" do caixa -->

        <div class="tela">

            <!-- Header com imagem e propaganda -->

            <div class="header">
                <div class="logo_header">
                    <img src="../../Imagens/LogoComNome.png">
                </div>
                <div class="banco24h">
                    <img src="../../Imagens/Banco24h.png">
                </div>
            </div>

            <div class="space">
                <!-- Espaçador -->
            </div>

            <!-- Tela principal -->

            <div class="tudo_op">
                <form method="POST" id="form-saque">
                    <div class="meio_op">
                        <div class="saque_conta_corrente">
                            <span>Valor a ser sacado da conta poupança:</span>
                        </div>
                        <input type="number" name="valor" id="valor" required autofocus
                            oninput="this.value = this.value.replace(/\D/g, '')" inputmode="numeric" pattern="\d*"
                            style="caret-color: transparent;">

                        <?php if (!empty($erro)): ?>
                            <div class="mensagem-erro"><?= htmlspecialchars($erro) ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="footer_op">
                        <button type="button" class="botao_voltar_valor" onclick="tocarComAtraso('menu.php')">
                            Voltar
                        </button>
                        <button type="submit" class="botao_confirmar_valor">
                            Confirmar
                        </button>
                    </div>
                </form>
            </div>

        </div>

        <!-- Aqui estão os botões da direita -->

        <div class="botoes">
            <div class="spacerbtn"></div>
            <input type="button" class="botao" onclick="tocarComAtraso('#')">
            <input type="button" class="botao" onclick="tocarComAtraso('#')">
            <input type="button" class="botao" onclick="enviarFormulario()">
            <div class="spacerbtn"></div>
        </div>
    </div>

    <!-- Script responsável pelos sons e encaminhamento de paginas -->

    <script>
        const beep = new Audio('../../Sons/beep.mp3');

        function tocarComAtraso(url) {
            beep.currentTime = 0; // Reinicia o som se já tiver sido tocado
            beep.play();
            setTimeout(() => {
                window.location.href = url;
            }, 550);
        }
        function enviarFormulario() {
            beep.currentTime = 0;
            beep.play();
            setTimeout(() => {
                document.getElementById('form-saque').submit();
            }, 550);
        }
    </script>

</body>

</html>