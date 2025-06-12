<?php
// Inicia a sessão para usar variáveis de sessão
session_start();

// Verifica se o formulário foi enviado via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Salva o valor enviado no formulário na sessão, ou 0 se não existir
    $_SESSION['valor'] = $_POST['valor'] ?? 0;
    // Salva o tipo da operação  na sessão
    $_SESSION['operacao'] = $_POST['tipo'] ?? '';
    // Salva a origem da operação    na sessão
    $_SESSION['origem'] = $_POST['origem'] ?? '';

    // Redireciona para a página de confirmação da operação
    header('Location: ../../menu/confirmar_operacao.php');
    exit();
}

// Lê o arquivo JSON com os dados dos usuários
$usuarios = json_decode(file_get_contents('../../usuarios.json'), true); // caminho ajustado

// Inicializa variável para armazenar dados do usuário logado
$usuario_logado = null;

// Percorre os usuários para encontrar o que está logado na sessão
foreach ($usuarios as $u) {
    if ($u['usernumber'] == $_SESSION['usernumber']) {
        $usuario_logado = $u;
        break; // sai do loop ao encontrar o usuário
    }
}

// Pega o saldo da conta corrente do usuário logado, ou 0 se não existir
$saldo = $usuario_logado['saldo_corrente'] ?? 0;

// Pega a dívida de crédito do usuário logado, ou 0 se não existir
$divida = $usuario_logado['divida_credito'] ?? 0;
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

    <audio src="../../Sons/beep.mp3" id="som"></audio>
    <audio src="../../Sons/saque_deposito.mp3" id="somdinheiro"></audio>

    <!-- Aqui estão os botões da esquerda -->

    <div class="tudo">
        <div class="botoes">
            <div class="spacerbtn"></div>
            <input type="button" class="botao" onclick="tocarComAtraso('#')">
            <input type="button" class="botao" onclick="tocarComAtraso('#')">
            <input type="button" class="botao" onclick="tocarComAtraso('credito.html')">
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

            <form action="" method="post" id="formValor">
                <div class="tudo_op">
                    <div class="meio_op">
                        <span class="inserir_valor_texto">Valor a sacar do cartão de crédito:</span>
                        <input type="number" name="valor" id="valor" autofocus style="caret-color: transparent;">
                        <input type="hidden" name="tipo" value="emprestimo_credito">
                        <input type="hidden" name="origem" value="cartão_credito.php">
                    </div>
                    <!-- Aqui temos um divisor entre o input e os botões falsos -->
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
            <input type="submit" class="botao" onclick="audioDinheiro('../../menu/confirmar_operacao.php')" value="">
            <div class="spacerbtn"></div>
        </div>
        </form>
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
        function audioDinheiro(url) {
            const audio = new Audio('../../Sons/saque_deposito.mp3');
            audio.play();
            setTimeout(() => {
                window.location.href = url;
            }, 1250);
        }
    </script>

</body>

</html>