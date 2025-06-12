<?php
session_start(); // Inicia a sessão para usar variáveis de sessão

$erro_senha = false; // Variável para controlar erro na senha

// Verifica se o formulário foi enviado via POST
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $senha_digitada = $_POST['senha'] ?? ''; // Pega a senha digitada, ou string vazia se não existir

    // Carrega os dados dos usuários do arquivo JSON
    $usuarios = json_decode(file_get_contents('../usuarios.json'), true);
    $usernumber = $_SESSION['usernumber'] ?? null; // Obtém o identificador do usuário na sessão

    // Se não existir usernumber na sessão, redireciona para a página inicial
    if (!$usernumber) {
        header('Location: ../index.html');
        exit();
    }

    // Busca o índice do usuário no array pelo usernumber
    $usuario_encontrado = null;
    foreach ($usuarios as $key => $usuario) {
        if ($usuario['usernumber'] === $usernumber) {
            $usuario_encontrado = $key;
            break;
        }
    }

    // Se usuário não encontrado, redireciona para página inicial
    if ($usuario_encontrado === null) {
        header('Location: ../index.html');
        exit();
    }

    $usuario = $usuarios[$usuario_encontrado]; // Dados do usuário atual

    // Verifica se a senha digitada é igual à senha cadastrada
    if ($senha_digitada === $usuario['senha']) {
        $valor = $_SESSION['valor'] ?? 0; // Valor da operação guardado na sessão
        $tipo = $_SESSION['operacao'] ?? ''; // Tipo da operação guardado na sessão

        // Validação: valor máximo para depósito é R$ 5.000
        if ($valor > 5000) {
            $_SESSION['erro_operacao'] = 'O valor máximo para depósito é R$ 5.000.';
            header('Location: confirmar_operacao.php');
            exit();
        }
        // Verifica se a operação e valor estão definidos na sessão
        if (!isset($_SESSION['operacao']) || !isset($_SESSION['valor'])) {
            $_SESSION['erro_operacao'] = 'Operação inválida. Tente novamente.';
            header('Location: menu.php'); // Redireciona para menu ou página inicial
            exit();
        }

        // Executa a operação dependendo do tipo
        switch ($tipo) {
            case 'emprestimo_credito':
                // Adiciona o valor ao saldo corrente e adiciona dívida de crédito
                $usuarios[$usuario_encontrado]['saldo_corrente'] += $valor;
                $usuarios[$usuario_encontrado]['divida_credito'] += $valor;
                break;
            case 'deposito_cc':
                // Adiciona valor ao saldo da conta corrente
                $usuarios[$usuario_encontrado]['saldo_corrente'] += $valor;
                break;

            case 'deposito_poupanca':
                // Adiciona valor ao saldo da poupança
                $usuarios[$usuario_encontrado]['saldo_poup'] += $valor;
                break;

            case 'saque_cc':
                // Verifica se saldo corrente é suficiente para saque
                if ($usuarios[$usuario_encontrado]['saldo_corrente'] >= $valor) {
                    $usuarios[$usuario_encontrado]['saldo_corrente'] -= $valor;
                } else {
                    // Saldo insuficiente para saque
                    $_SESSION['erro_operacao'] = 'Saldo insuficiente.';
                    header('Location: confirmar_operacao.php');
                    exit();
                }
                break;
            case 'saque_poup':
                // Verifica se saldo poupança é suficiente para saque
                if ($usuarios[$usuario_encontrado]['saldo_poup'] >= $valor) {
                    $usuarios[$usuario_encontrado]['saldo_poup'] -= $valor;
                } else {
                    // Saldo insuficiente na poupança
                    $_SESSION['erro_operacao'] = 'Saldo insuficiente na poupança.';
                    header('Location: confirmar_operacao.php');
                    exit();
                }
                break;
            case 'transferencia':
                // Busca conta destino na sessão
                $conta_destino = $_SESSION['conta_destino'] ?? null;
                if (!$conta_destino) {
                    $_SESSION['erro_operacao'] = 'Conta de destino não informada.';
                    header('Location: confirmar_operacao.php');
                    exit();
                }
                // Não permite transferir para a própria conta
                if ($conta_destino === $usernumber) {
                    $_SESSION['erro_operacao'] = 'Não é possível transferir para a própria conta.';
                    header('Location: confirmar_operacao.php');
                    exit();
                }
                $origem = $_SESSION['origem'] ?? 'corrente'; // Conta de origem da transferência
                $campo_origem = $origem === 'poupanca' ? 'saldo_poup' : 'saldo_corrente';

                // Verifica se saldo da conta origem é suficiente
                if ($usuarios[$usuario_encontrado][$campo_origem] >= $valor) {
                    // Busca o destinatário na lista de usuários
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

                    // Realiza a transferência: debita origem e credita destinatário na conta corrente
                    $usuarios[$usuario_encontrado][$campo_origem] -= $valor;
                    $usuarios[$destinatario_key]['saldo_corrente'] += $valor;

                } else {
                    // Saldo insuficiente para transferência
                    $_SESSION['erro_operacao'] = 'Saldo insuficiente para transferência.';
                    header('Location: confirmar_operacao.php');
                    exit();
                }
                break;
            case 'pagar_divida':
                $divida = $usuarios[$usuario_encontrado]['divida_credito'];

                // Verifica se o valor é positivo
                if ($valor <= 0) {
                    $_SESSION['erro_operacao'] = 'Valor inválido para pagamento.';
                    header('Location: confirmar_operacao.php');
                    exit();
                }

                // Verifica se o usuário tem dívida para pagar
                if ($divida <= 0) {
                    $_SESSION['erro_operacao'] = 'Você não possui dívida de crédito para pagar.';
                    header('Location: confirmar_operacao.php');
                    exit();
                }

                // Valor não pode ser maior que a dívida
                if ($valor > $divida) {
                    $_SESSION['erro_operacao'] = 'O valor informado é maior que sua dívida.';
                    header('Location: confirmar_operacao.php');
                    exit();
                }

                // Verifica se o saldo corrente é suficiente para pagar dívida
                if ($usuarios[$usuario_encontrado]['saldo_corrente'] >= $valor) {
                    // Subtrai valor do saldo e da dívida
                    $usuarios[$usuario_encontrado]['saldo_corrente'] -= $valor;
                    $usuarios[$usuario_encontrado]['divida_credito'] -= $valor;
                } else {
                    // Saldo insuficiente para pagar dívida
                    $_SESSION['erro_operacao'] = 'Saldo insuficiente para pagar a dívida.';
                    header('Location: confirmar_operacao.php');
                    exit();
                }
                break;
        }

        // Salva os dados atualizados no arquivo JSON
        file_put_contents('../usuarios.json', json_encode($usuarios, JSON_PRETTY_PRINT));

        // Limpa as variáveis da sessão relacionadas à operação
        unset($_SESSION['valor']);
        unset($_SESSION['operacao']);
        unset($_SESSION['conta_destino']);
        unset($_SESSION['origem']);

        // Redireciona para página de operação concluída
        header('Location: operacao_concluida.html');
        exit();
    } else {
        // Se a senha estiver incorreta, seta variável para exibir erro
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
            <!-- Botões de navegação que tocam som e redirecionam -->
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

            <!-- Formulário para confirmar operação digitando a senha -->
            <form method="post">
                <div class="tudo_op">
                    <div class="meio_op">
                        <span class="inserir_valor_texto">Confirme a operação colocando sua senha:</span>
                        <input type="password" name="senha" id="senha" autofocus style="caret-color: transparent;"
                            maxlength="6" pattern="\d*" inputmode="numeric"
                            oninput="this.value = this.value.replace(/\D/g, '')">
                        
                        <!-- Exibe mensagem de erro da operação, se houver -->
                        <?php if (isset($_SESSION['erro_operacao'])): ?>
                            <p class="mensagem-erro" id="mensagem-erro"><?= $_SESSION['erro_operacao'] ?></p>
                            <?php unset($_SESSION['erro_operacao']); ?>
                        <?php endif; ?>

                        <!-- Exibe erro se a senha digitada estiver incorreta -->
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

        // Função para tocar som e depois redirecionar
        function tocarComAtraso(url) {
            beep.currentTime = 0;
            beep.play();
            setTimeout(() => {
                window.location.href = url;
            }, 550);
        }

        // Função para tocar som e enviar o formulário após delay
        function enviarFormulario() {
            saqueDeposito.currentTime = 0;
            saqueDeposito.play();
            setTimeout(() => {
                document.querySelector('form').submit();
            }, 1250);
        }

        // Remove a mensagem de erro após 2 segundos com efeito de fade
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
