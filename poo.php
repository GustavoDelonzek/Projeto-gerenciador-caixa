<?php
date_default_timezone_set("America/Sao_Paulo");

class User
{
    public $id;
    public $usuario;
    private $senha;
    public $vendas;


    public function __construct($id, $usuario, $senha)
    {
        $this->id = $id;
        $this->usuario = $usuario;
        $this->senha = $senha;
        $this->vendas = 0;
    }

    public function adicionaVenda($valor)
    {
        $this->vendas += $valor;
    }

    public function relatorioVenda()
    {
        echo "--------------------------\nUsuário Atual: $this->usuario\nTotal de vendas realizadas: R$" . number_format($this->vendas, 2) . "\n--------------------------\n";
        readline("Pressione qualquer tecla para voltar...");


        registrarLog("O $this->usuario visualizou seu próprio perfil");
    }

    public function getSenha(){
        return $this->senha;
    }

    public function getUsuario(){
        return $this->usuario;
    }

}


class Produto
{
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


    public function removerEstoque($quantidade)
    {
        if($quantidade <= $this->estoque){
            
            $this->estoque -= $quantidade;
        } else{
            echo "Valor ultrapassa quantidade no estoque";
        }
    }

    public function adicionarEstoque($quantidade)
    {
        $this->estoque += $quantidade;
    }

    public function verificarEstoque($quantidade)
    {
        if ($this->estoque < $quantidade) {
            return false;
        } else {
            return true;
        }
    }

    public function getEstoque()
    {
        return $this->estoque;
    }


    public function getNome()
    {
        return $this->nome;
    }

    public function setNome($nome)
    {
        $this->nome = $nome;
    }


    public function getPreco()
    {
        return $this->preco;
    }


    public function setPreco($preco)
    {
        $this->preco = $preco;
    }

}


class Caixa
{
    private $dinheiro;

    public function __construct($dinheiro)
    {
        $this->dinheiro = $dinheiro;
    }

    public function verificarTroco($troco)
    {
        if ($troco > $this->dinheiro) {
            return false;
        } else {
            return true;
        }
    }

    public function realizarVenda($valorVenda, )
    {
        $this->dinheiro += $valorVenda;
    }

    public function getDinheiro()
    {
        return $this->dinheiro;
    }

}


function logar($usuario, $senha)
{
    global $usuarios;
    global $usuarioAtual;
    foreach ($usuarios as $user) {
        if ($user->getUsuario() == $usuario && $user->getSenha() == $senha) {
            $usuarioAtual = $user;
            return true;
        }
    }
    return false;
}

function deslogar()
{
    global $usuarioAtual;
    registrarLog("Usuário " . $usuarioAtual->getUsuario() . " deslogou");
    $usuarioAtual = null;

}

function existeUsuario($nome)
{
    global $usuarios;
    foreach ($usuarios as $user) {
        if ($user->getUsuario() == $nome) {
            return true;
        }
    }
    return false;
}


function existeProduto($id)
{
    global $produtos;
    foreach ($produtos as &$produto) {
        if ($produto->id == $id) {
            return $produto;
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
        echo "Id: " . $produto->id . "\nNome: " . $produto->nome . "\nPreço: R$" . number_format($produto->preco, 2) . "\nEstoque: " . $produto->estoque . "\n---------------------------\n";
    }

    readline("Pressione qualquer tecla para voltar...");
    limparTela();

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


$usuarios = [];
$produtos = [];

$produtos[] = new Produto(1, "Café", 27.99, 20);
$usuarios[] = new User(1, 'admin', 12345);
$usuarioAtual = null;


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
                    echo "--------------------------\nDinheiro em caixa: R$" . $caixa->getDinheiro() . "\n--------------------------\n[1]Realizar venda\n[2]Verificar logs\n[3]Cadastrar novo usuário\n[4]Cadastrar novo produto\n[5]Editar produto\n[6]Exibir produtos\n[7]Exibir usuário\n[8]Deslogar\n--------------------------\n";
                    $escolha = readline("-");
                    if ($escolha == 1) {
                        limparTela();
                        $idProduto = readline("Qual id do produto para a venda: ");
                        if (existeProduto($idProduto)) {
                            $produto = existeProduto($idProduto);
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
                            if ($produto->verificarEstoque($quantidade)) {
                                limparTela();
                                $valorVenda = $produto->getPreco() * $quantidade;
                                echo "--------------------------\nO valor da venda ficou R$" . number_format($valorVenda, 2) . "\n";
                                $recebido = readline("Valor recebido pelo cliente: R$");

                                $troco = $recebido - $valorVenda;

                                if ($recebido < $valorVenda) {
                                    limparTela();
                                    registrarLog("Venda cancelada: Cliente com valor insuficiente!");
                                    echo "Valor de pagamento suficiente! Venda cancelada!\n";
                                } else {

                                    if ($caixa->verificarTroco($troco)) {
                                        $usuarioAtual->adicionaVenda($valorVenda);
                                        $produto->removerEstoque($quantidade);
                                        $caixa->realizarVenda($recebido - $troco);
                                        registrarLog("Usuário '$usuarioAtual->usuario' realizou venda de $quantidade unidades do produto ". $produto->getNome() . " no valor de R$$valorVenda");
                                        limparTela();
                                        echo "Venda realizada com sucesso!" . $troco > 0 ? "Troco: R$" . number_format($troco, 2) . "\n" : "";
                                        echo "Venda: +R$$valorVenda\n";
                                    } else {
                                        limparTela();
                                        registrarLog("Venda cancelada: Sem troco em caixa !");
                                        echo "Caixa sem troco suficiente! Venda cancelada!\n";
                                    }
                                }
                            } else {
                                limparTela();
                                registrarLog("Venda cancelada: estoque insuficiente do produto $produto->nome !");
                                echo "Produto com estoque insuficiente!\n";
                            }
                        } else {
                            limparTela();
                            registrarLog("Venda cancelada: produto com id $idProduto não encontrado!");
                            echo "Produto não encontrado!\n";
                        }
                    } else if ($escolha == 2) {
                        exibirLogs();
                        limparTela();
                    } else if ($escolha == 3) {
                        limparTela();
                        $novoUsuario = readline("Novo usuário: ");
                        while (true) {
                            if (strlen(trim($novoUsuario)) < 3) {
                                limparTela();

                                echo "Nome de usuário deve conter pelo menos 3 caracteres!Tente Novamente.\n";
                                $novoUsuario = readline("Novo usuário: ");
                            } elseif (existeUsuario($novoUsuario)) {
                                limparTela();

                                registrarLog("Falha no cadastro! usuário $novoUsuario já existe ");
                                echo "Usuário já existe! TENTE NOVAMENTE.\n";
                                $novoUsuario = readline("Novo usuário: ");
                            } else {
                                break;
                            }
                        }
                        $senha = readline("Senha: ");
                        while (true) {
                            if (strlen(trim($senha)) < 5) {
                                limparTela();
                                echo "Senha deve conter pelo menos 5 caracteres! TENTE NOVAMENTE.\n";
                                $senha = readline("Senha: ");
                            } else {
                                break;
                            }
                        }

                        $usuarios[] = new User(count($usuarios) + 1, $novoUsuario, $senha);
                        limparTela();
                        registrarLog("Novo usuário $novoUsuario cadastrado com sucesso");
                        echo "Usuário cadastrado com sucesso!\n";
                    } else if ($escolha == 4) {
                        limparTela();

                        $novoProdutoId = count($produtos) + 1;
                        echo "---CADASTRO DE PRODUTO---\nId: $novoProdutoId\n";

                        $nomeNovoProduto = readline("Nome: ");
                        while (true) {
                            if (strlen(trim($nomeNovoProduto)) < 1) {
                                limparTela();
                                echo "---CADASTRO DE PRODUTO---\nNome deve conter pelo menos 1 caracter válido!\n--------------------------\nId: $novoProdutoId\n";

                                $nomeNovoProduto = readline("Nome: ");
                            } else {
                                limparTela();
                                echo "---CADASTRO DE PRODUTO---\nId: $novoProdutoId\nNome: $nomeNovoProduto\n";

                                break;
                            }
                        }

                        $precoNovoProduto = (double) readline("Preço: ");
                        while (true) {
                            if ($precoNovoProduto <= 0 || !is_double($precoNovoProduto)) {
                                limparTela();
                                echo "---CADASTRO DE PRODUTO---\nValor inserido inválido para preço!\n--------------------------\nId: $novoProdutoId\nNome: $nomeNovoProduto\n";

                                $precoNovoProduto = (double) readline("Preço: ");

                            } else {
                                limparTela();
                                echo "---CADASTRO DE PRODUTO---\nId: $novoProdutoId\nNome: $nomeNovoProduto\nPreço: R$$precoNovoProduto\n";
                                break;
                            }
                        }
                        
                        $estoqueNovoProduto = (int) readline("Estoque: ");
                        while (true) {
                            if ($estoqueNovoProduto <= 0 || !is_int($estoqueNovoProduto)) {
                                limparTela();
                                echo "---CADASTRO DE PRODUTO---\nValor inserido inválido para o campo estoque!\n--------------------------\nId: $novoProdutoId\nNome: $nomeNovoProduto\nPreço: R$$precoNovoProduto\n";

                                $estoqueNovoProduto = (int) readline("Estoque: ");

                            } else {
                                break;
                            }
                        }

                        $produtos[] = new Produto($novoProdutoId, $nomeNovoProduto, $precoNovoProduto, $estoqueNovoProduto);
                        limparTela();
                        registrarLog("Novo produto '$nomeNovoProduto' cadastrado com sucesso");

                        echo "Produto cadastrado com sucesso!\n";

                    } else if ($escolha == 5) {
                        limparTela();

                        echo "---EDIÇÃO DE PRODUTO---\n";
                        $idEditar = readline("Qual id do produto: ");
                        while (true) {
                            if (existeProduto($idEditar)) {
                                $produtoEditar = existeProduto($idEditar);
                                echo "O que deseja editar?\n[1]Nome\n[2]Preço\n[3]Estoque\n";
                                $escolha = readline("- ");
                                if ($escolha == 1) {
                                    limparTela();
                                    $mudancaNome = readline("Novo nome: ");
                                    $produtoEditar->setNome($mudancaNome);
                                } else if ($escolha == 2) {
                                    limparTela();
                                    $mudancaPreco = (double) readline("Novo preço: ");
                                    while (true) {
                                        if ($mudancaPreco <= 0 || !is_double($mudancaPreco)) {
                                            limparTela();
                                            echo "Valor inserido incorreto!\n";
                                            $mudancaPreco = (double) readline("Novo preço: ");

                                        } else {
                                            $produtoEditar->setPreco($mudancaPreco);
                                            break;
                                        }
                                    }
                                } else if ($escolha == 3) {
                                    limparTela();
                                    echo "[1] Adicionar ao estoque\n[2] Remover do estoque\n";
                                    $mudanca = (int) readline("-");
                                    while (true) {
                                        if ($mudanca <= 0 || !is_int($mudanca) || $mudanca > 2) {
                                            limparTela();
                                            echo "Valor inserido incorreto!\n";
                                            echo "[1] Adicionar ao estoque\n[2] Remover do estoque\n";
                                            $mudanca = (int) readline("-");

                                        } else {

                                            break;
                                        }
                                    }
                                    $quantidadeMudanca = (int) readline("Quantidade: ");
                                    while (true) {

                                        if ($quantidadeMudanca <= 0 || !is_int($quantidadeMudanca)) {
                                            limparTela();
                                            echo "Valor inserido incorreto!\n";
                                            $quantidadeMudanca = (int) readline("Quantidade: ");

                                        }else if($mudanca == 2 && $quantidadeMudanca > $produtoEditar->getEstoque()){
                                            limparTela();
                                            echo "Valor inserido incorreto! Excedeu a quantidade do estoque\n";
                                            $quantidadeMudanca = (int) readline("Quantidade: ");

                                        } else {

                                            break;
                                        }
                                    }

                                    if ($mudanca == 1) {
                                        $produtoEditar->adicionarEstoque($quantidadeMudanca);
                                    } else {
                                        $produtoEditar->removerEstoque($quantidadeMudanca);
                                    }
                                } else {
                                    return;
                                }

                                limparTela();
                                registrarLog("Produto $produtoEditar->nome foi editado com sucesso");
                                echo "Produto editado com sucesso!\n";
                                break;
                            } else {
                                limparTela();

                                registrarLog("Produto com  id: $idEditar não encontrado!");
                                echo "Produto não encontrado!\n";
                                break;
                            }
                        }

                    } else if ($escolha == 6) {
                        exibirProdutos();
                        registrarLog("O $usuario visualizou os produtos cadastrados");
                    } else if ($escolha == 7) {
                        $usuarioAtual->relatorioVenda();
                        limparTela();
                    } else if ($escolha == 8) {
                        deslogar();
                        limparTela();
                        break;
                    } else {
                        limparTela();
                    }

                } else {
                    limparTela();
                    $valorCaixa = (double) readline("Quanto de dinheiro há no caixa? R$");
                    while (true) {
                        if ($valorCaixa < 0 || !is_double($valorCaixa)) {
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