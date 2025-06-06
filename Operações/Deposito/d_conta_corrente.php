<?php
session_start();

// Saldo inicial, só uma vez
if (!isset($_SESSION['saldo'])) {
    $_SESSION['saldo'] = 3451;
}

$mensagem = ""; // <- Adiciona esta linha para evitar erro na linha 41

// Se o formulário foi enviado
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $valor_saque = intval($_POST['valor_saque']);  // Usa o metodo post e coloca que a variavel é inteira 

    if ($valor_saque > 0 && $valor_saque <= $_SESSION['saldo']) {     
        $_SESSION['saldo'] += $valor_saque;                         
        $mensagem = " Depósito  de R$$valor_saque realizado com sucesso!!";
    } else {
        $mensagem = " Valor inválido ou insuficiente";
    }
}

                            
$saldo = $_SESSION['saldo'];


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

    <!-- Aqui estão os botões da esquerda -->

    <div class="tudo">
        <div class="botoes">
            <div class="spacerbtn"></div>
            <input type="button" class="botao" onclick="tocarComAtraso('#')">
            <input type="button" class="botao" onclick="tocarComAtraso('#')">
            <input type="button" class="botao" onclick="tocarComAtraso('deposito.html')">
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

            <div class="tudo_op">
                <div class="meio_op">

                    <i class="inserir_valor_texto">Valor a ser depositado na conta corrente:</i>
                    <input type="number" name="valor" id="valor" autofocus style="caret-color: transparent;" pattern="\d*" inputmode="numeric"
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

            </div>

        </div>

        <!-- Aqui estão os botões da direita -->

        <div class="botoes">
            <div class="spacerbtn"></div>
            <input type="button" class="botao" onclick="tocarComAtraso('#')">
            <input type="button" class="botao" onclick="tocarComAtraso('#')">
            <input type="submit" class="botao" onclick="tocarComAtraso('../../menu/confirmar_operacao.html')" value="">
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
    </script>

</body>

</html>