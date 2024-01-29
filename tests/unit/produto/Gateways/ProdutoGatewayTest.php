<?php

namespace Produto\Tests\Gateways;

use PHPUnit\Framework\TestCase;
use Produto\Gateways\ProdutoGateway;
use Produto\Entities\Produto;
use Produto\External\MySqlConnection;

class ProdutoGatewayTest extends TestCase
{
    private $dbConnection;
    private $produtoGateway;

    protected function setUp(): void
    {
        $this->dbConnection = new MySqlConnection();
        $this->produtoGateway = new ProdutoGateway($this->dbConnection);
    }

    public function testCadastrarProdutoComSucesso()
    {
        $dadosProduto = new Produto('Produto Teste', 'Descrição do produto de teste 11', 10.99, 'teste');
        $idProduto = $this->produtoGateway->cadastrar($dadosProduto);
        $this->assertIsInt($idProduto);

        $produtosCategoriaTeste = $this->produtoGateway->obterPorCategoria('teste');

        $produtoExcluido = $this->produtoGateway->excluir($produtosCategoriaTeste[0]["id"]);
        $this->assertTrue($produtoExcluido);
    }

    public function testCadastrarProdutoComNomeProdutoJaExistente()
    {
        $dadosProduto = new Produto('Produto Teste', 'Descrição do produto de teste 12', 10.99, 'teste');
        $idProduto = $this->produtoGateway->cadastrar($dadosProduto);
        $this->assertIsInt($idProduto);

        try {
            $this->produtoGateway->cadastrar($dadosProduto);
        } catch (\Exception $e) {
            $this->assertEquals("Já existe um produto cadastrado com esse nome.", $e->getMessage());
            $this->assertEquals(409, $e->getCode());
            $produtosCategoriaTeste = $this->produtoGateway->obterPorCategoria('teste');
            $produtoExcluido = $this->produtoGateway->excluir($produtosCategoriaTeste[0]["id"]);
            $this->assertTrue($produtoExcluido);
        }
    }

    public function testAtualizarProdutoComSucesso()
    {
        $dadosProduto = new Produto('Produto Teste', 'Descrição do produto de teste 14', 10.99, 'teste');
        $idProduto = $this->produtoGateway->cadastrar($dadosProduto);
        $this->assertIsInt($idProduto);

        $produtosCategoriaTeste = $this->produtoGateway->obterPorCategoria('teste');

        $novosDadosProduto = new Produto('Novo nome do produto', 'Nova descrição', 15, 'teste2');

        $resultado = $this->produtoGateway->atualizar($produtosCategoriaTeste[0]["id"], $novosDadosProduto);
        $this->assertTrue($resultado);

        $produtoExcluido = $this->produtoGateway->excluir($produtosCategoriaTeste[0]["id"]);
        $this->assertTrue($produtoExcluido);
    }

    public function testExcluirProdutoComSucesso()
    {
        $dadosProduto = new Produto('Produto Teste', 'Descrição do produto de teste 15', 10.99, 'teste');

        $idProduto = $this->produtoGateway->cadastrar($dadosProduto);
        $this->assertIsInt($idProduto);

        $produtosCategoriaTeste = $this->produtoGateway->obterPorCategoria('teste');

        $produtoExcluido = $this->produtoGateway->excluir($produtosCategoriaTeste[0]["id"]);
        $this->assertTrue($produtoExcluido);
    }
}
