<?php

namespace Produto\Controllers;

require "./src/Interfaces/Controllers/ProdutoControllerInterface.php";
require "./src/Entities/Produto.php";
require "./src/Gateways/ProdutoGateway.php";
require "./src/UseCases/ProdutoUseCases.php";

use Produto\Gateways\ProdutoGateway;
use Produto\Entities\Produto;
use Produto\Interfaces\Controllers\ProdutoControllerInterface;
use Produto\UseCases\ProdutoUseCases;

class ProdutoController implements ProdutoControllerInterface
{
    public function cadastrar($dbConnection, array $dados)
    {
        $nome = $dados["nome"] ?? "";
        $descricao = $dados["descricao"] ?? "";
        $preco = $dados["preco"] ?? "";
        $categoria = $dados["categoria"] ?? "";
        $produto = new Produto($nome, $descricao, $preco, $categoria);
        $produtoGateway = new ProdutoGateway($dbConnection);
        $produtoUseCases = new ProdutoUseCases();
        $salvarDados = $produtoUseCases->cadastrar($produtoGateway, $produto);
        return $salvarDados;
    }

    public function atualizar($dbConnection, array $dados)
    {
        $nome = $dados["nome"] ?? "";
        $descricao = $dados["descricao"] ?? "";
        $preco = $dados["preco"] ?? "";
        $categoria = $dados["categoria"] ?? "";
        $id = $dados["id"] ?? 0;
        $produto = new Produto($nome, $descricao, $preco, $categoria);
        $produtoGateway = new ProdutoGateway($dbConnection);
        $produtoUseCases = new ProdutoUseCases();
        $atualizarDados = $produtoUseCases->atualizar($produtoGateway, $id, $produto);
        return $atualizarDados;
    }

    public function excluir($dbConnection, int $id)
    {
        $produtoGateway = new ProdutoGateway($dbConnection);
        $produtoUseCases = new ProdutoUseCases();
        $excluirProduto = $produtoUseCases->excluir($produtoGateway, $id);
        return $excluirProduto;
    }
    public function excluirPorCategoria($dbConnection, string $categoria)
    {
        $produtoGateway = new ProdutoGateway($dbConnection);
        $produtoUseCases = new ProdutoUseCases();
        $excluirProduto = $produtoUseCases->excluirPorCategoria($produtoGateway, $categoria);
        return $excluirProduto;
    }

    public function obterPorCategoria($dbConnection, string $nome)
    {
        $produtoGateway = new ProdutoGateway($dbConnection);
        $produtoUseCases = new ProdutoUseCases();
        $produtos = $produtoUseCases->obterPorCategoria($produtoGateway, $nome);
        return $produtos;
    }
}
