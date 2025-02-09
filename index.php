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

function limparLogs(){
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