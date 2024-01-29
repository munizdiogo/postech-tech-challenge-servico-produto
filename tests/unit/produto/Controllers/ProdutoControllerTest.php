<?php

namespace Produto\Controllers;

require "./src/Controllers/ProdutoController.php";
require "./src/External/MySqlConnection.php";

use Produto\Controllers\ProdutoController;
use Produto\External\MySqlConnection;
use PHPUnit\Framework\TestCase;

class ProdutoControllerTest extends TestCase
{
    protected $produtoController;
    protected $dbConnection;

    public function setUp(): void
    {
        parent::setUp();
        $this->produtoController = new ProdutoController();
        $this->dbConnection = new MySqlConnection();
    }

    public function testCadastrarProdutoComSucesso()
    {
        $dadosProduto = [
            'nome' => 'Produto Teste',
            'descricao' => 'Descrição do produto de teste 2',
            'preco' => 10.99,
            'categoria' => 'teste'
        ];

        $idProduto = $this->produtoController->cadastrar($this->dbConnection, $dadosProduto);
        $this->assertIsInt($idProduto);

        $produtoExcluido = $this->produtoController->excluirPorCategoria($this->dbConnection, 'teste');
        $this->assertTrue($produtoExcluido);
    }

    public function testCadastrarProdutoComNomeProdutoJaExistente()
    {
        $dadosProduto = [
            'nome' => 'Produto Teste',
            'descricao' => 'Descrição do produto de teste 1',
            'preco' => 10.99,
            'categoria' => 'teste'
        ];

        $idProduto = $this->produtoController->cadastrar($this->dbConnection, $dadosProduto);
        $this->assertIsInt($idProduto);

        try {
            $this->produtoController->cadastrar($this->dbConnection, $dadosProduto);
        } catch (\Exception $e) {
            $this->assertEquals("Já existe um produto cadastrado com esse nome.", $e->getMessage());
            $this->assertEquals(409, $e->getCode());
            $produtoExcluido = $this->produtoController->excluirPorCategoria($this->dbConnection, 'teste');
            $this->assertTrue($produtoExcluido);
        }
    }

    public function testCadastrarProdutoComCamposFaltando()
    {
        $dadosProduto = [
            'descricao' => 'Descrição do produto de teste 3',
            'preco' => 10.99,
            'categoria' => 'teste'
        ];

        try {
            $this->produtoController->cadastrar($this->dbConnection, $dadosProduto);
        } catch (\Exception $e) {
            $this->assertEquals("O campo nome é obrigatório.", $e->getMessage());
            $this->assertEquals(400, $e->getCode());
        }
    }

    public function testAtualizarProdutoComSucesso()
    {
        $dadosProduto = [
            'nome' => 'Produto Teste',
            'descricao' => 'Descrição do produto de teste 4',
            'preco' => 10.99,
            'categoria' => 'teste'
        ];

        $idProduto = $this->produtoController->cadastrar($this->dbConnection, $dadosProduto);
        $this->assertIsInt($idProduto);

        $produtosCategoriaTeste = $this->produtoController->obterPorCategoria($this->dbConnection, 'teste');

        $novosDadosProduto = [
            'id' => $produtosCategoriaTeste[0]["id"],
            'nome' => 'Novo nome do produto',
            'descricao' => 'Nova descrição',
            'preco' => 15,
            'categoria' => 'teste'
        ];

        $resultado = $this->produtoController->atualizar($this->dbConnection, $novosDadosProduto);
        $this->assertTrue($resultado);

        $produtoExcluido = $this->produtoController->excluirPorCategoria($this->dbConnection, "teste");
        $this->assertTrue($produtoExcluido);
    }

    public function testAtualizarProdutoNaoExistente()
    {
        $novosDadosProduto = [
            'id' => 999999999999999999,
            'nome' => 'Novo nome do produto',
            'descricao' => 'Nova descrição',
            'preco' => 15,
            'categoria' => 'teste'
        ];

        try {
            $this->produtoController->atualizar($this->dbConnection, $novosDadosProduto);
        } catch (\Exception $e) {
            $this->assertEquals("Não foi encontrado um produto com o ID informado.", $e->getMessage());
            $this->assertEquals(400, $e->getCode());
        }
    }

    public function testExcluirProdutoComSucesso()
    {
        $dadosProduto = [
            'nome' => 'Produto Teste',
            'descricao' => 'Descrição do produto de teste 5',
            'preco' => 10.99,
            'categoria' => 'teste'
        ];

        $idProduto = $this->produtoController->cadastrar($this->dbConnection, $dadosProduto);
        $this->assertIsInt($idProduto);

        $produtoExcluido = $this->produtoController->excluirPorCategoria($this->dbConnection, 'teste');
        $this->assertTrue($produtoExcluido);
    }
    public function testExcluirProdutoNaoExistente()
    {
        try {
            $this->produtoController->excluir($this->dbConnection, 999999999999999999);
        } catch (\Exception $e) {
            $this->assertEquals("Não foi encontrado um produto com o ID informado.", $e->getMessage());
            $this->assertEquals(400, $e->getCode());
        }
    }
}
