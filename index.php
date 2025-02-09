<?php
    $usuarios = [
        [
            "usuario" => "admin",
            "senha" => "12345"
        ]
    ];


    function logar($usuario, $senha){
        global $usuarios;
        foreach($usuarios as $user){
            if($user["usuario"] == $usuario && $user["senha"] == $senha){
                return true;
            }
        }

        return false;
    }

    echo chr(27).chr(codepoint: 91).'H'.chr(27).chr(91).'J'; // ^[H^[J


    while(true){

        echo "[1] Login\n[2] Sair\n";
        $escolha = readline("-");
        if($escolha == 1){
            echo chr(27).chr(codepoint: 91).'H'.chr(27).chr(91).'J'; // ^[H^[J
            $usuario = readline("Usuário: ");
            $senha = readline("Senha: ");
            if(logar($usuario, $senha)){
                while(true){
                    //Menu do usuario logado
                }
            } else{
                echo chr(27).chr(codepoint: 91).'H'.chr(27).chr(91).'J'; // ^[H^[J
                echo "Senha ou usuarios incorretos!\n";
            }

        } else if( $escolha == 2){
            break;
        }


    }



?>