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
            while (true) {
                //Menu do usuario logado'
                limparTela();
                echo "[1]Realizar venda\n[2]Verificar logs\n[3]Cadastrar novo usuário\n[4]Deslogar\n";
                $escolha = readline("-");

            }
        } else {
            limparTela();
            echo "Senha ou usuarios incorretos!\n";
        }
    } else if ($escolha == 2) {
        break;
    }


}



?>