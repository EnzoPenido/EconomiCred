<?php
session_start();

$usuarios = json_decode(file_get_contents('../../usuarios.json'), true); // ajuste o caminho
$usuario_logado = null;

foreach ($usuarios as $u) {
    if ($u['usernumber'] == $_SESSION['usernumber']) {
        $usuario_logado = $u;
        break;
    }
}





$saldo_poup = $usuario_logado['saldo_poup'] ?? 0; // saldo padrão se não achar
?>








<!DOCTYPE html>
<html lang="pt-br">

<head>
    <link rel="stylesheet" href="../../menu/menu.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EconomiCred - Página Inicial</title>
    <link rel="shortcut icon" href="/Imagens/iconmaior.ico" type="image/x-icon">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@450&display=swap" rel="stylesheet">
</head>

<body>

    <audio src="../../Sons/beep.mp3" id="som"></audio>

    <!-- Aqui estão os botões da esquerda -->

    <div class="tudo">
        <div class="botoes">
            <div class="spacerbtn"></div>
            <input type="button" class="botao" onclick="tocarComAtraso('#')">
            <input type="button" class="botao" onclick="tocarComAtraso('#')">
            <input type="button" class="botao" onclick="tocarComAtraso('saldo.html')">
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

                    <span class="saldo_texto">Saldo:</span>
                    <div class="mostrar_valores">R$<?php echo $saldo_poup; ?></div>
                    <div class="tampar_valores" id="saldo"></div>

                </div>

                <!-- Aqui temos um divisor entre o input e os botões falsos -->

                <div class="footer_op">

                    <div class="botao_voltar_valor">
                        <span>Voltar</span>
                    </div>

                    <div class="botao_confirmar_valor">
                        <span>Olhar valor</span>
                    </div>
                </div>
            </div>

        </div>

        <!-- Aqui estão os botões da direita -->

        <div class="botoes">
            <div class="spacerbtn"></div>
            <input type="button" class="botao" onclick="tocarComAtraso('#')">
            <input type="button" class="botao" onclick="tocarComAtraso('#')">
            <input type="button" class="botao" onmousedown="segurarSaldo()" onmouseup="soltarSaldo()">
            <div class="spacerbtn"></div>
        </div>
    </div>

    <!-- Script responsável pelos sons e encaminhamento de paginas -->

    <script>
        function segurarSaldo() {
            const audio = document.getElementById('som');
            const saldo = document.getElementById('saldo');
            audio.play();
            saldo.style.display = 'none';
        }
        function soltarSaldo() { // Função para quando soltar o botão
            const saldo = document.getElementById('saldo');
            saldo.style.display = 'block';
        }
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