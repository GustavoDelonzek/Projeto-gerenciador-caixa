<?php

$usuarios = [
    [
        "usuario" => "admin",
        "senha" => "12345"
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


function cadastrarUsuario()
{
    limparTela();
    global $usuarios;
    $novoUsuario = readline("Novo usuário: ");
    foreach ($usuarios as $user) {
        if ($user["usuario"] === $novoUsuario) {
            limparTela();
            registrarLog("Falha no cadastro! usuário $novoUsuario já existe ");

            echo "Usuário já existe!\n";
            return;
        }
    }
    $senha = readline("Senha: ");

    $usuarios[] = [
        "usuario" => $novoUsuario,
        "senha" => $senha
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
    $preco = readline("Preço: ");
    $estoque = readline("Estoque: ");

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
    echo "---EDIÇÃO DE PRODUTO---\n";
    $idEditar = readline("Qual id do produto: ");
   

   
    foreach ($produtos as $produto) {
        if ($produto["id"] == $idEditar) {
            echo "O que deseja editar?\n[1]Nome\n[2]Preço\n[3]Estoque\n";
            $escolha = readline("- ");
            if ($escolha == 1) {
                $escolha = "nome";
                $mudanca = readline("Novo nome: ");
            } else if ($escolha == 2) {
                $escolha = "preco";
                $mudanca = readline("Novo preço: ");
            } else if ($escolha == 3) {
                $escolha = "estoque";
                $mudanca = readline("Novo estoque: ");
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
    echo "[1] Login\n[2] Sair\n";
    $escolha = readline("-");

    if ($escolha == 1) {
        limparTela();
        $usuario = readline("Usuário: ");
        $senha = readline("Senha: ");

        if (logar($usuario, $senha)) {
            registrarLog("Usuário $usuario fez login");
            limparTela();
            while (true) {
                //Menu do usuario logado'
                if (isset($caixa)) {

                    echo "[1]Realizar venda\n[2]Verificar logs\n[3]Cadastrar novo usuário\n[4]Cadastrar novo produto\n[5]Editar produto\n[6]Deslogar\n";
                    $escolha = readline("-");
                    if ($escolha == 2) {
                        exibirLogs();
                        limparTela();
                    } else if ($escolha == 3) {
                        cadastrarUsuario();
                    } else if ($escolha == 4) {
                        cadastrarProduto();
                    } else if ($escolha == 5) {
                        editarProduto();
                    } else if ($escolha == 6) {
                        registrarLog("Usuário $usuario deslogou");

                        limparTela();
                        break;
                    } else {
                        limparTela();
                    }


                } else {
                    limparTela();
                    $caixa = readline("Quanto de dinheiro há no caixa? ");
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