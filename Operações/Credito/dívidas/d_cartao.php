<?php
session_start();

// Se o usuário não estiver logado, redireciona
if (!isset($_SESSION['usernumber'])) {
    echo "Usuário não logado.";
    exit;
}

$usuario_id = $_SESSION['usernumber'];
$caminho_json = '../../../usuarios.json';

// Verifica se o arquivo de usuários existe
if (!file_exists($caminho_json)) {
    echo "Arquivo de usuários não encontrado.";
    exit;
}

$usuarios = json_decode(file_get_contents($caminho_json), true);

// Pega dados do usuário atual
$saldo = 0;
$divida_credito = 0;

foreach ($usuarios as $usuario) {
    if ($usuario['usernumber'] == $usuario_id) {
        $saldo = $usuario['saldo_corrente'] ?? 0;
        $divida_credito = $usuario['divida_credito'] ?? 0;
        break;
    }
}

// Define os dados da operação para confirmação posterior
$_SESSION['valor'] = $divida_credito;
$_SESSION['operacao'] = 'pagar_divida';
$_SESSION['origem'] = 'd_cartao.php';
?>







<!DOCTYPE html>
<html lang="pt-br">

<head>
    <link rel="stylesheet" href="../../../menu/menu.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EconomiCred - Página Inicial</title>
    <link rel="shortcut icon" href="../../../Imagens/iconmaior.ico" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@450&display=swap" rel="stylesheet">
</head>

<body>

    <audio src="../../../Sons/beep.mp3" id="som"></audio>

    <!-- Aqui estão os botões da esquerda -->

    <div class="tudo">
        <div class="botoes">
            <div class="spacerbtn"></div>
            <input type="button" class="botao" onclick="tocarComAtraso('#')">
            <input type="button" class="botao" onclick="tocarComAtraso('#')">
            <input type="button" class="botao" onclick="tocarComAtraso('dividas.html')">
            <div class="spacerbtn"></div>
        </div>

        <div class="border">

        </div>

        <!-- Aqui está a "tela" do caixa -->

        <div class="tela">

            <!-- Header com imagem e propaganda -->

            <div class="header">
                <div class="logo_header">
                    <img src="../../../Imagens/Logocomnome.png">
                </div>
                <div class="banco24h">
                    <img src="../../../Imagens/Banco24h.png">
                </div>
            </div>

            <div class="space">
                <!-- Espaçador -->
            </div>

            <!-- Tela principal -->

            <div class="d_conta_corrente_tudo">
                <div class="meio_op">

                    <span class="inserir_valor_texto">Divida:</span>
                    <div class="mostrar_valores">R$<?= number_format($divida_credito) ?></div>

                </div>
                <div class="footer_op">

                    <div class="botao_voltar_valor">
                        <span>Voltar</span>
                    </div>

                    <div class="botao_confirmar_valor">
                        <span>Pagar dívida</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Aqui estão os botões da direita -->

        <div class="botoes">
            <div class="spacerbtn"></div>
            <input type="button" class="botao" onclick="tocarComAtraso('#')">
            <input type="button" class="botao" onclick="tocarComAtraso('#')">
            <input type="button" class="botao" onclick="tocarComAtraso('../../../menu/confirmar_operacao.php')">
            <div class="spacerbtn"></div>
        </div>
    </div>

    <!-- Script responsável pelos sons e encaminhamento de paginas -->

    <script>

        const beep = new Audio('../Sons/beep.mp3');

        function tocarComAtraso(url) {
            beep.currentTime = 0; // Reinicia o som se já tiver sido tocado
            beep.play();
            setTimeout(() => {
                window.location.href = url;
            }, 550);
        }
    </script>

</body>

</html>