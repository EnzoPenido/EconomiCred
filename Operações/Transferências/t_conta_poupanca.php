<?php
session_start();

// Lê o JSON com os dados dos usuários
$usuarios = json_decode(file_get_contents('../../usuarios.json'), true);

// Garante que o valor já foi inserido na etapa anterior
if (!isset($_SESSION['valor_transferencia'])) {
    header("Location: t_valor_poupanca.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $conta_destino = $_POST['conta_destino'] ?? '';

    $destinatario = null;
    foreach ($usuarios as $usuario) {
        if ($usuario['usernumber'] === $conta_destino) {
            $destinatario = $usuario;
            break;
        }
    }

    if (!$destinatario) {
        $erro = "Conta de destino não encontrada.";
    } else {
        $_SESSION['conta_destino'] = $conta_destino;
        $_SESSION['destinatario_nome'] = $destinatario['username'];
        $_SESSION['origem'] = 'poupanca';
        header("Location: ../../menu/confirmar_operacao.php");
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
    <title>EconomiCred - conta transferência</title>
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
            <input type="button" class="botao" onclick="tocarComAtraso('t_valor_poupanca.php')">
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
            <form method="post" id="formConta">
                <div class="tudo_op">
                    <div class="meio_op">
                        <?php if (isset($erro)): ?>
                            <div style="color: red; font-weight: bold; text-align: center; position: relative; top: 90%;">
                                <?= $erro ?>
                            </div>
                        <?php endif; ?>

                        <div class="inserir_valor_texto">
                            <span>Número da conta do destinatário:</span>
                        </div>

                        <input type="text" name="conta_destino" id="conta_destino" maxlength="6" autofocus
                            style="caret-color: transparent;" inputmode="numeric"
                            oninput="this.value = this.value.replace(/\D/g, '')">


                    </div>

                    <!-- Aqui temos um divisor entre o imput e os botões falsos -->

                    <div class="footer_op">

                        <div class="botao_voltar_valor">
                            <span>Voltar</span>
                        </div>

                        <div class="botao_confirmar_valor">
                            <span>Confirmar</span>
                        </div>

                    </div>
            </form>
        </div>

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

    <!-- Script responsável pelos sons e encaminhamento de paginas -->

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
                document.getElementById('formConta').submit();
            }, 550);
        }
    </script>
</body>

</html>

</html>