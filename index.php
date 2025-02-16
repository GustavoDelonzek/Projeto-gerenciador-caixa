<?php
date_default_timezone_set("America/Sao_Paulo");

$usuarios = [
    [
        "usuario" => "admin",
        "senha" => "12345",
        "vendas" => 0
    ]
];

$produtos = [
    [
        "id" => 1,
        "nome" => "café",
        "preco" => 23.99,
        "estoque" => 10
    ]
];

$caixa = null;


function logar($usuario, $senha)
{
    global $usuarios;
    foreach ($usuarios as $user) {
        if ($user["usuario"] == $usuario && $user["senha"] == $senha) {
            return true;
        }
    }

    return false;
}

function existeUsuario($nome)
{
    global $usuarios;
    foreach ($usuarios as $user) {
        if ($user["usuario"] === $nome) {
            return true;
        }
    }
    return false;
}

function exibirVendasUsuario($nome)
{
    global $usuarios;
    foreach ($usuarios as $usuario) {
        if ($nome == $usuario["usuario"]) {
            return $usuario["vendas"];
        }
    }

    return 0;
}

function cadastrarUsuario()
{
    limparTela();
    global $usuarios;
    $novoUsuario = readline("Novo usuário: ");
    while(true){
        if(strlen(trim($novoUsuario)) < 3){
            limparTela();
               
            echo "Nome de usuário deve conter pelo menos 3 caracteres!Tente Novamente.\n";
            $novoUsuario = readline("Novo usuário: ");
        }elseif (existeUsuario($novoUsuario)) {
            limparTela();
            registrarLog("Falha no cadastro! usuário $novoUsuario já existe ");
            
            echo "Usuário já existe! TENTE NOVAMENTE.\n";
            $novoUsuario = readline("Novo usuário: ");
        } else{
            break;
        }
    }
    $senha = readline("Senha: ");
    while(true){
        if(strlen(trim($senha)) < 5){
            limparTela();
               
            echo "Senha deve conter pelo menos 5 caracteres! TENTE NOVAMENTE.\n";
            $senha = readline("Senha: ");
        } else{
            break;
        }
    }
 
    $usuarios[] = [
        "usuario" => $novoUsuario,
        "senha" => $senha,
        "vendas" => 0
    ];
    limparTela();
    registrarLog("Novo usuário $novoUsuario cadastrado com sucesso");

    echo "Usuário cadastrado com sucesso!\n";

}


function registrarLog($texto)
{
    $data = date('d/m/Y H:i:s');
    $log = "$texto - $data\n";
    file_put_contents("logs.txt", $log, FILE_APPEND);
}

function atualizarVendaUsuario($usuario, $valor)
{
    global $usuarios;
    foreach ($usuarios as &$user) {
        if ($user["usuario"] == $usuario) {
            $user["vendas"] += $valor;
            registrarLog("Vendas do usuário '$usuario' atualizada!");
        }
    }
}

function exibirLogs()
{
    $logs = file_get_contents("logs.txt");
    limparTela();
    echo $logs;
    readline("Qualquer tecla para continuar...");
}

function cadastrarProduto()
{
    global $produtos;
    limparTela();
    $id = count($produtos) + 1;
    echo "---CADASTRO DE PRODUTO---\nId: $id\n";

    $nome = readline("Nome: ");
    while(true){
        if(strlen(trim($nome)) < 1){
            limparTela();
            echo "---CADASTRO DE PRODUTO---\nNome deve conter pelo menos 1 caracter válido!\n--------------------------\nId: $id\n";

            $nome = readline("Nome: ");
        } else{
            limparTela();
            echo "---CADASTRO DE PRODUTO---\nId: $id\nNome: $nome\n";

            break;
        }
    }
    $preco = (double) readline("Preço: ");
    while (true) {
        if ($preco <= 0 || !is_double($preco)) {
            limparTela();
            echo "---CADASTRO DE PRODUTO---\nValor inserido inválido para preço!\n--------------------------\nId: $id\nNome: $nome\n";
           
            $preco = (double) readline("Preço: ");

        } else {
            limparTela();
            echo "---CADASTRO DE PRODUTO---\nId: $id\nNome: $nome\nPreço: R$$preco\n";
            break;
        }
    }

    $estoque = (int) readline("Estoque: ");
    while (true) {
        if ($estoque <= 0 || !is_int($estoque)) {
            limparTela();
            echo "---CADASTRO DE PRODUTO---\nValor inserido inválido para o campo estoque!\n--------------------------\nId: $id\nNome: $nome\nPreço: R$$preco\n";

            $estoque = (int) readline("Estoque: ");

        } else {
            break;
        }
    }

    $produtos[] = [
        "id" => $id,
        "nome" => $nome,
        "preco" => $preco,
        "estoque" => $estoque
    ];
    limparTela();
    registrarLog("Novo produto '$nome' cadastrado com sucesso");

    echo "Produto cadastrado com sucesso!\n";

}

function editarProduto()
{
    global $produtos;
    limparTela();

    echo "---EDIÇÃO DE PRODUTO---\n";
    $idEditar = readline("Qual id do produto: ");

    foreach ($produtos as &$produto) {
        if ($produto["id"] == $idEditar) {
            echo "O que deseja editar?\n[1]Nome\n[2]Preço\n[3]Estoque\n";
            $escolha = readline("- ");
            if ($escolha == 1) {
                $escolha = "nome";
                $mudanca = readline("Novo nome: ");
            } else if ($escolha == 2) {
                $escolha = "preco";
                $mudanca = (double) readline("Novo preço: ");
                while (true) {
                    if ($mudanca <= 0 || !is_double($mudanca)) {
                        limparTela();
                        echo "Valor inserido incorreto!\n";
                        $mudanca = (double) readline("Novo preço: ");
            
                    } else {
                        break;
                    }
                }
            } else if ($escolha == 3) {
                $escolha = "estoque";
                $mudanca = (int) readline("Novo estoque: ");
                while (true) {
                    if ($mudanca <= 0 || !is_int($mudanca)) {
                        limparTela();
                        echo "Valor inserido incorreto!\n";
                        $mudanca = (int) readline("Novo estoque: ");
            
                    } else {
                        break;
                    }
                }
            } else {
                return;
            }

            $produto[$escolha] = $mudanca;
            limparTela();

            registrarLog("Produto com  id: $idEditar teve seu $escolha editado com sucesso");
            echo "Produto editado com sucesso!\n";
            return;
        }
    }

    limparTela();

    registrarLog("Produto com  id: $idEditar não encontrado!");
    echo "Produto não encontrado!\n";

}

function existeProduto($id)
{
    global $produtos;
    foreach ($produtos as $produto) {
        if ($produto["id"] == $id) {
            return true;
        }
    }

    return false;
}

function verificaEstoque($id, $quantidade)
{
    global $produtos;
    foreach ($produtos as $produto) {
        if ($produto["id"] == $id && $produto["estoque"] >= $quantidade) {
            return true;
        }
    }
    return false;

}

function exibirProdutos()
{
    limparTela();
    global $produtos;
    echo "----ESTOQUE DE PRODUTOS----\nProdutos cadastrados: " . count($produtos) . "\n---------------------------\n";
    foreach ($produtos as $produto) {
        echo "Id: " . $produto["id"] . "\nNome: " . $produto["nome"] . "\nPreço: R$" . $produto["preco"] . "\nEstoque: " . $produto["estoque"] . "\n---------------------------\n";
    }

    readline("Pressione qualquer tecla para voltar...");
    limparTela();

}

function precificarVenda($id, $quantidade)
{
    global $produtos;
    foreach ($produtos as $produto) {
        if ($produto["id"] == $id) {
            return $produto["preco"] * $quantidade;
        }
    }
}

function atualizarEstoque($quantidade, $id)
{
    global $produtos;
    foreach ($produtos as &$produto) {
        if ($produto["id"] == $id) {
            $produto["estoque"] -= $quantidade;
            registrarLog("Produto com id $id teve seu estoque atualizado, estoque atual " . $produto["estoque"]);
        }
    }
}


function realizarVenda($user)
{
    global $caixa;
    global $produtos;
    limparTela();
    $idProduto = readline("Qual id do produto para a venda: ");
    if (existeProduto($idProduto)) {
        $quantidade = (int) readline("Quantas unidades do produto? ");
        
        while (true) {
            if ($quantidade <= 0 || !is_int($quantidade)) {
                limparTela();
                echo "Valor inserido incorreto!\n";
                $quantidade = (int) readline("Quantas unidades do produto? ");

            } else {
                break;
            }
        }
        if (verificaEstoque($idProduto, $quantidade)) { //feito com a classe
            limparTela();
            $valorVenda = precificarVenda($idProduto, $quantidade); //feito com o get preco * quantidade da classe produto
            echo "--------------------------\nO valor da venda ficou R$" . number_format($valorVenda, 2) . "\n";
            $recebido = readline("Valor recebido pelo cliente: R$");

            $troco = $recebido - $valorVenda; //verifica esse troco com a classe caixa

            if ($recebido < $valorVenda) { 
                limparTela();
                registrarLog("Venda cancelada: Cliente com valor insuficiente!");
                echo "Valor de pagamento suficiente! Venda cancelada!\n";
            } else {

                if ($troco <= $caixa) {
                    //Adiciona vendas ao usuario, atualiza o estoque, registar log de venda, atualizar valor do caixa
                    atualizarVendaUsuario($user, $valorVenda);
                    atualizarEstoque($quantidade, $idProduto);
                    $caixa += ($recebido - $troco);
                    registrarLog("Usuário '$user' realizou venda de $quantidade unidades do produto $idProduto no valor de R$$valorVenda");
                    limparTela();
                    echo "Venda realizada com sucesso! +R$$valorVenda\n";
                } else {
                    limparTela();
                    registrarLog("Venda cancelada: Sem troco em caixa !");
                    echo "Caixa sem troco suficiente! Venda cancelada!\n";
                }
            }
        } else {
            limparTela();
            registrarLog("Venda cancelada: estoque insuficiente do produto com id: $idProduto !");
            echo "Produto com estoque insuficiente!\n";
        }
    } else {
        limparTela();
        registrarLog("Venda cancelada: produto com id $idProduto não encontrado!");
        echo "Produto não encontrado!\n";
    }
}


function limparLogs()
{
    file_put_contents("logs.txt", "");
}

function limparTela()
{
    echo "\033[H\033[J";

}

limparTela();

while (true) {
    echo "--------------------------\n[1] Login\n[2] Sair\n--------------------------\n";
    $escolha = readline("-");

    if ($escolha == 1) {
        limparTela();
        $usuario = readline("Usuário: ");
        $senha = readline("Senha: ");
        $usuario = trim($usuario);
        if (logar($usuario, $senha)) {
            registrarLog("Usuário $usuario fez login");
            limparTela();
            while (true) {
                //Menu do usuario logado'
                if (isset($caixa)) {

                    echo "--------------------------\nDinheiro em caixa: R$$caixa\n--------------------------\n[1]Realizar venda\n[2]Verificar logs\n[3]Cadastrar novo usuário\n[4]Cadastrar novo produto\n[5]Editar produto\n[6]Exibir produtos\n[7]Exibir usuário\n[8]Deslogar\n--------------------------\n";
                    $escolha = readline("-");
                    if ($escolha == 1) {
                        realizarVenda($usuario);
                    } else if ($escolha == 2) {
                        exibirLogs();
                        limparTela();
                    } else if ($escolha == 3) {
                        cadastrarUsuario();
                    } else if ($escolha == 4) {
                        cadastrarProduto();
                    } else if ($escolha == 5) {
                        editarProduto();
                    } else if ($escolha == 6) {
                        exibirProdutos();
                        registrarLog("O $usuario visualizou os produtos cadastrados");
                    } else if ($escolha == 7) {
                        limparTela();
                        echo "--------------------------\nUsuário Atual: $usuario\nTotal de vendas realizadas: R$" . number_format(exibirVendasUsuario($usuario), 2) . "\n--------------------------\n";
                        readline("Pressione qualquer tecla para voltar...");
                        registrarLog("O $usuario visualizou seu próprio perfil");
                        limparTela();
                    } else if ($escolha == 8) {
                        registrarLog("Usuário $usuario deslogou");

                        limparTela();
                        break;
                    } else {
                        limparTela();
                    }


                } else {
                    limparTela();
                    $caixa = (double) readline("Quanto de dinheiro há no caixa? R$");
                    while (true) {
                        if ($caixa <= 0 || !is_double($caixa)) {
                            limparTela();
                            echo "Valor inserido incorreto!\n";
                            $caixa = (double) readline("Digite um valor válido para o caixa: R$");
                
                        } else {
                            break;
                        }
                    }
                    limparTela();
                }

            }
        } else {
            limparTela();
            registrarLog("Tentativa de login falhou para $usuario");

            echo "Senha ou Usuário incorretos!\n";
        }
    } else if ($escolha == 2) {
        limparLogs();
        break;
    }


}



?>