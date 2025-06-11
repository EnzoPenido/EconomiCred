<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $valor = isset($_POST['valor']) ? floatval($_POST['valor']) : 0;

    if ($valor <= 0) {
        $erro = "Insira um valor válido para transferir.";
    } elseif ($valor > 5000) {
        $erro = "O valor máximo para transferência é R$ 5.000.";
    } else {
        $_SESSION['valor_transferencia'] = $valor;
        $_SESSION['valor'] = $valor; // ESSENCIAL para confirmar_operacao.php
        $_SESSION['operacao'] = 'transferencia'; // ESSENCIAL também
        $_SESSION['origem'] = 'poupanca'; // ESSENCIAL para saber que é da poupança
        header("Location: t_conta_poupanca.php"); // Página seguinte
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
    <!-- Aqui estão os botões da esquerda -->

    <div class="tudo">
        <div class="botoes">
            <div class="spacerbtn"></div>
            <input type="button" class="botao" onclick="tocarComAtraso('#')">
            <input type="button" class="botao" onclick="tocarComAtraso('#')">
            <input type="button" class="botao" onclick="tocarComAtraso('transferencias.html')">
            <div class="spacerbtn"></div>
        </div>

        <div class="border">

        </div>

        <!-- Aqui está a "tela" do caixa -->

        <div class="tela">

            <!-- Header com imagem e propaganda -->

            <div class="header">
                <div class="logo_header">
                    <img src="../../Imagens/Logocomnome.png">
                </div>
                <div class="banco24h">
                    <img src="../../Imagens/Banco24h.png">
                </div>
            </div>

            <div class="space">
                <!-- Espaçador -->
            </div>

            <!-- Tela principal -->
            <form method="post" id="formValor">
                <div class="tudo_op">
                    <div class="meio_op">
                        <span class="inserir_valor_texto">Insira o valor para transferir</span>
                        <input type="number" step="0.01" name="valor" id="valor" required max="5000" maxlength="6"
                            autofocus inputmode="decimal" />
                        <?php if (isset($erro)): ?>
                            <div class="mensagem-erro">
                                <?= $erro ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="footer_op">
                        <div class="botao_voltar_valor">
                            <span>Voltar</span>
                        </div>
                        <button type="submit" class="botao_confirmar_valor">
                            <span>Confirmar</span>
                        </button>
                    </div>
                </div>
            </form>


        </div>

        <!-- Aqui estão os botões da direita -->

        <div class="botoes">
            <div class="spacerbtn"></div>
            <input type="button" class="botao" onclick="tocarComAtraso('#')">
            <input type="button" class="botao" onclick="tocarComAtraso('#')">
            <input type="button" class="botao" onclick="enviarFormulario()" value="">
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
                document.getElementById('formValor').submit();
            }, 550);
        }
    </script>
</body>

</html>