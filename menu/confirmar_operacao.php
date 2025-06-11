<?php
session_start();

$erro_senha = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $senha_digitada = $_POST['senha'] ?? '';

    // Carrega os dados do JSON
    $usuarios = json_decode(file_get_contents('../usuarios.json'), true);
    $usernumber = $_SESSION['usernumber'] ?? null;

    if (!$usernumber) {
        header('Location: ../index.html');
        exit();
    }

    // Busca o usuário pelo usernumber
    $usuario_encontrado = null;
    foreach ($usuarios as $key => $usuario) {
        if ($usuario['usernumber'] === $usernumber) {
            $usuario_encontrado = $key;
            break;
        }
    }

    if ($usuario_encontrado === null) {
        header('Location: ../index.html');
        exit();
    }

    $usuario = $usuarios[$usuario_encontrado];

    if ($senha_digitada === $usuario['senha']) {
        $valor = $_SESSION['valor'] ?? 0;
        $tipo = $_SESSION['operacao'] ?? '';

        if ($valor > 5000) {
            $_SESSION['erro_operacao'] = 'O valor máximo para depósito é R$ 5.000.';
            header('Location: confirmar_operacao.php');
            exit();
        }
        // Verifica se os dados da operação estão definidos
        if (!isset($_SESSION['operacao']) || !isset($_SESSION['valor'])) {
            $_SESSION['erro_operacao'] = 'Operação inválida. Tente novamente.';
            header('Location: menu.php'); // ou qualquer página inicial adequada
            exit();
        }

        switch ($tipo) {
            case 'emprestimo_credito':
                $usuarios[$usuario_encontrado]['saldo_corrente'] += $valor; // ou saldo_poup se preferir
                $usuarios[$usuario_encontrado]['divida_credito'] += $valor;
                break;
            case 'deposito_cc':
                $usuarios[$usuario_encontrado]['saldo_corrente'] += $valor;
                break;

            case 'deposito_poupanca':
                $usuarios[$usuario_encontrado]['saldo_poup'] += $valor;
                break;

            case 'saque_cc':
                if ($usuarios[$usuario_encontrado]['saldo_corrente'] >= $valor) {
                    $usuarios[$usuario_encontrado]['saldo_corrente'] -= $valor;
                } else {
                    $_SESSION['erro_operacao'] = 'Saldo insuficiente.';
                    header('Location: confirmar_operacao.php');
                    exit();
                }
                break;
            case 'saque_poup':
                if ($usuarios[$usuario_encontrado]['saldo_poup'] >= $valor) {
                    $usuarios[$usuario_encontrado]['saldo_poup'] -= $valor;
                } else {
                    $_SESSION['erro_operacao'] = 'Saldo insuficiente na poupança.';
                    header('Location: confirmar_operacao.php');
                    exit();
                }
                break;
            case 'transferencia':
                $conta_destino = $_SESSION['conta_destino'] ?? null;
                if (!$conta_destino) {
                    $_SESSION['erro_operacao'] = 'Conta de destino não informada.';
                    header('Location: confirmar_operacao.php');
                    exit();
                }
                if ($conta_destino === $usernumber) {
                    $_SESSION['erro_operacao'] = 'Não é possível transferir para a própria conta.';
                    header('Location: confirmar_operacao.php');
                    exit();
                }
                $origem = $_SESSION['origem'] ?? 'corrente';
                $campo_origem = $origem === 'poupanca' ? 'saldo_poup' : 'saldo_corrente';

                if ($usuarios[$usuario_encontrado][$campo_origem] >= $valor) {
                    // Encontrar destinatário
                    $destinatario_key = null;
                    foreach ($usuarios as $key => $u) {
                        if ($u['usernumber'] === $conta_destino) {
                            $destinatario_key = $key;
                            break;
                        }
                    }

                    if ($destinatario_key === null) {
                        $_SESSION['erro_operacao'] = 'Conta de destino não encontrada.';
                        header('Location: confirmar_operacao.php');
                        exit();
                    }

                    // Executar transferência
                    $usuarios[$usuario_encontrado][$campo_origem] -= $valor;
                    $usuarios[$destinatario_key]['saldo_corrente'] += $valor;

                } else {
                    $_SESSION['erro_operacao'] = 'Saldo insuficiente para transferência.';
                    header('Location: confirmar_operacao.php');
                    exit();
                }
                break;
        }

        // Salva os dados atualizados
        file_put_contents('../usuarios.json', json_encode($usuarios, JSON_PRETTY_PRINT));

        // Limpa sessão da operação
        unset($_SESSION['valor']);
        unset($_SESSION['operacao']);
        unset($_SESSION['conta_destino']);
        unset($_SESSION['origem']);
        header('Location: operacao_concluida.html');
        exit();
    } else {
        $erro_senha = true;
    }
}
?>

<!DOCTYPE html>
<html lang="pt-br">

<head>
    <link rel="stylesheet" href="menu.css">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EconomiCred - Confirmar</title>
    <link rel="shortcut icon" href="../Imagens/iconmaior.ico" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Lexend:wght@450&display=swap" rel="stylesheet">
</head>

<body>

    <div class="tudo">
        <div class="botoes">
            <div class="spacerbtn"></div>
            <input type="button" class="botao" onclick="tocarComAtraso('#')">
            <input type="button" class="botao" onclick="tocarComAtraso('#')">
            <input type="button" class="botao" onclick="tocarComAtraso('menu.php')">
            <div class="spacerbtn"></div>
        </div>

        <div class="border"></div>

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

            <form method="post">
                <div class="tudo_op">
                    <div class="meio_op">
                        <span class="inserir_valor_texto">Confirme a operação colocando sua senha:</span>
                        <input type="password" name="senha" id="senha" autofocus style="caret-color: transparent;"
                            maxlength="6" pattern="\d*" inputmode="numeric"
                            oninput="this.value = this.value.replace(/\D/g, '')">
                        <?php if (isset($_SESSION['erro_operacao'])): ?>
                            <p class="mensagem-erro" id="mensagem-erro"><?= $_SESSION['erro_operacao'] ?></p>
                            <?php unset($_SESSION['erro_operacao']); ?>
                        <?php endif; ?>

                        <?php if ($erro_senha): ?>
                            <p class="mensagem-erro" id="mensagem-erro">Senha incorreta. Tente novamente.</p>
                        <?php endif; ?>
                    </div>

                    <div class="footer_op">
                        <div class="botao_voltar_valor">
                            <span>Cancelar</span>
                        </div>
                        <div class="botao_confirmar_valor">
                            <span>Confirmar</span>
                        </div>
                    </div>
                </div>
            </form>
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
        const beep = new Audio('../Sons/beep.mp3');
        const saqueDeposito = new Audio('../Sons/saque_deposito.mp3');

        function tocarComAtraso(url) {
            beep.currentTime = 0;
            beep.play();
            setTimeout(() => {
                window.location.href = url;
            }, 550);
        }

        function enviarFormulario() {
            saqueDeposito.currentTime = 0;
            saqueDeposito.play();
            setTimeout(() => {
                document.querySelector('form').submit();
            }, 1250);
        }

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