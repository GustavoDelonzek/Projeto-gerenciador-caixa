<?php
date_default_timezone_set("America/Sao_Paulo");

    class User{
        public $id;
        public $usuario;
        public $senha;
        public $vendas;


        public function __construct($id, $usuario, $senha)
        {   
            $this->id = $id;
            $this->usuario = $usuario;
            $this->senha = $senha;
            $this->vendas = 0;
        }

        public function adicionaVenda($valor){
            $this->vendas += $valor;
        }

        public function relatorioVenda(){
            echo "Bla bla bla\nNumero de vendas: R$$this->vendas";
        }

    }


    class Produto{
        public $id;
        public $nome;
        public $preco;
        public $estoque;

        public function __construct($id, $nome, $preco, $estoque)
        {
            $this->id = $id;
            $this->nome = $nome;
            $this->preco = $preco;
            $this->estoque = $estoque;
        }


        public function removerEstoque($quantidade){
            //metodo de verificação antes, para sabe se é válido
            $this->estoque -= $quantidade;
        }

        public function adicionarEstoque($quantidade){
            $this->estoque += $quantidade;
        }

        public function verificarEstoque($quantidade){
            if($this->estoque <= $quantidade){
                return false;
            } else {
                return true;
            }
        }

        public function getEstoque(){
            return $this->estoque;
        }


        public function getNome(){
            return $this->nome;
        }

        public function setNome($nome){
            $this->nome = $nome;
        }


        public function getPreco(){
            return $this->preco;
        }


        public function setPreco($preco){
            $this->preco = $preco;
        }

    }


    class Caixa{
        private $dinheiro;
        
        public function __construct($dinheiro)
        {
            $this->dinheiro = $dinheiro;
        }


        public function verificarTroco($troco){
            if($troco > $this->dinheiro){
                return false;
            } else{
                return true;
            }
        }


        public function realizarVenda($valorVenda, ){
            $this->dinheiro += $valorVenda;
        }       

        public function getDinheiro(){
            return $this->dinheiro;
        }

    }


    $usuarios = [];
    $admin =  new User(1, 'admin', 12345);
    $usuarios[] =$admin;



    function logar($usuario, $senha)
    {
        global $usuarios;
        foreach ($usuarios as $user) {
            if ($user->usuario == $usuario && $user->senha == $senha) {
                return true;
            }
        }

        return false;
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

                    echo "--------------------------\nDinheiro em caixa: R$".$caixa->getDinheiro()."\n--------------------------\n[1]Realizar venda\n[2]Verificar logs\n[3]Cadastrar novo usuário\n[4]Cadastrar novo produto\n[5]Editar produto\n[6]Exibir produtos\n[7]Exibir usuário\n[8]Deslogar\n--------------------------\n";
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
                    $valorCaixa = (double) readline("Quanto de dinheiro há no caixa? R$");
                    while (true) {
                        if ($valorCaixa <= 0 || !is_double($valorCaixa)) {
                            limparTela();
                            echo "Valor inserido incorreto!\n";
                            $valorCaixa = (double) readline("Digite um valor válido para o caixa: R$");
                
                        } else {
                            $caixa = new Caixa($valorCaixa);
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