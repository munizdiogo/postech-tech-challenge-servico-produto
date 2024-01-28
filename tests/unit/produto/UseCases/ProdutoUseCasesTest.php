<?php

use Produto\External\MySqlConnection;
use PHPUnit\Framework\TestCase;
use Produto\Entities\Produto;
use Produto\Gateways\ProdutoGateway;
use Produto\UseCases\ProdutoUseCases;

class ProdutoUseCasesTest extends TestCase
{
    private $dbConnection;
    private $produtoGateway;
    private $produtoUseCases;

    public function setUp(): void
    {
        parent::setUp();
        $this->dbConnection = new MySqlConnection;
        $this->produtoGateway = new ProdutoGateway($this->dbConnection);
        $this->produtoUseCases = new ProdutoUseCases();
    }
    public function testCadastrarProdutoComSucesso()
    {
        $produto = new Produto("Nome do Produto", "Descrição do Produto 1", 25.51, "teste");
        $idProduto = $this->produtoUseCases->cadastrar($this->produtoGateway, $produto);
        $this->assertIsInt($idProduto);
        $produtoExcluido = $this->produtoUseCases->excluirPorCategoria($this->produtoGateway, 'teste');
        $this->assertTrue($produtoExcluido);
    }
    public function testExcluirPorCategoriaComCategoriaNaoInformada()
    {
        try {
            $this->produtoUseCases->excluirPorCategoria($this->produtoGateway, '');
        } catch (\Exception $e) {
            $this->assertEquals("O campo categoria é obrigatório.", $e->getMessage());
            $this->assertEquals(400, $e->getCode());
        }
    }

    public function testCadastrarProdutoComNomeFaltando()
    {
        $produto = new Produto("", "Descrição do Produto 1", 25.52, "teste");
        try {
            $this->produtoUseCases->cadastrar($this->produtoGateway, $produto);
        } catch (\Exception $e) {
            $this->assertEquals("O campo nome é obrigatório.", $e->getMessage());
            $this->assertEquals(400, $e->getCode());
        }
    }
    public function testCadastrarProdutoComDescricaoFaltando()
    {
        $produto = new Produto("Nome do produto", "", 25.53, "teste");
        try {
            $this->produtoUseCases->cadastrar($this->produtoGateway, $produto);
        } catch (\Exception $e) {
            $this->assertEquals("O campo descrição é obrigatório.", $e->getMessage());
            $this->assertEquals(400, $e->getCode());
        }
    }
    public function testCadastrarProdutoComPrecoFaltando()
    {
        $produto = new Produto("Nome do produto", "Descrição do Produto 1", "", "teste");
        try {
            $this->produtoUseCases->cadastrar($this->produtoGateway, $produto);
        } catch (\Exception $e) {
            $this->assertEquals("O campo preço é obrigatório.", $e->getMessage());
            $this->assertEquals(400, $e->getCode());
        }
    }
    public function testCadastrarProdutoComCategoriaFaltando()
    {
        $produto = new Produto("Nome do produto", "Descrição do Produto 1", 25.54, "");
        try {
            $this->produtoUseCases->cadastrar($this->produtoGateway, $produto);
        } catch (\Exception $e) {
            $this->assertEquals("O campo categoria é obrigatório.", $e->getMessage());
            $this->assertEquals(400, $e->getCode());
        }
    }

    public function testCadastrarProdutoJaExistente()
    {
        $produto = new Produto("Nome do Produto", "Descrição do Produto 1", 25.55, "teste");

        $idProduto = $this->produtoUseCases->cadastrar($this->produtoGateway, $produto);
        $this->assertIsInt($idProduto);

        try {
            $this->produtoUseCases->cadastrar($this->produtoGateway, $produto);
        } catch (\Exception $e) {
            $this->assertEquals("Já existe um produto cadastrado com esse nome.", $e->getMessage());
            $this->assertEquals(409, $e->getCode());
            $produtoExcluido = $this->produtoUseCases->excluirPorCategoria($this->produtoGateway,  'teste');
            $this->assertTrue($produtoExcluido);
        }
    }

    public function testAtualizarProdutoComSucesso()
    {
        $produto = new Produto("Nome do Produto", "Descrição do Produto 1", 25.00, "teste");

        $idProduto = $this->produtoUseCases->cadastrar($this->produtoGateway, $produto);
        $this->assertIsInt($idProduto);

        $produtosCategoriaTeste = $this->produtoUseCases->obterPorCategoria($this->produtoGateway, 'teste');

        $novosDadosProduto = new Produto("Novo Nome do Produto", "Nova Descrição do Produto 1", 30.59, "teste2");

        $resultado = $this->produtoUseCases->atualizar($this->produtoGateway, $produtosCategoriaTeste[0]["id"], $novosDadosProduto);

        $this->assertTrue($resultado);

        $produtoExcluido = $this->produtoUseCases->excluir($this->produtoGateway, $produtosCategoriaTeste[0]["id"]);
        $this->assertTrue($produtoExcluido);
    }

    public function testAtualizarProdutoComNomeFaltando()
    {
        $produto = new Produto("Nome do produto", "Descrição do Produto 1", 25.56, "teste");
        $idProduto = $this->produtoUseCases->cadastrar($this->produtoGateway, $produto);
        $this->assertIsInt($idProduto);

        $produtosCategoriaTeste = $this->produtoUseCases->obterPorCategoria($this->produtoGateway, 'teste');

        $novosDadosProduto = new Produto("", "Nova Descrição do Produto 1", 28.50, "teste2");

        try {
            $this->produtoUseCases->atualizar($this->produtoGateway, $produtosCategoriaTeste[0]["id"], $novosDadosProduto);
        } catch (\Exception $e) {
            $this->assertEquals("O campo nome é obrigatório.", $e->getMessage());
            $this->assertEquals(400, $e->getCode());
            $produtoExcluido = $this->produtoUseCases->excluir($this->produtoGateway,  $produtosCategoriaTeste[0]["id"]);
            $this->assertTrue($produtoExcluido);
        }
    }

    public function testAtualizarProdutoComIdFaltando()
    {
        $produto = new Produto("Nome do produto", "Descrição do Produto 1", 25.57, "teste");
        $idProduto = $this->produtoUseCases->cadastrar($this->produtoGateway, $produto);
        $this->assertIsInt($idProduto);

        $produtosCategoriaTeste = $this->produtoUseCases->obterPorCategoria($this->produtoGateway, 'teste');

        $novosDadosProduto = new Produto("Nome nome do produto", "Nova Descrição do Produto 1", 28.50, "teste2");

        try {
            $this->produtoUseCases->atualizar($this->produtoGateway, 0, $novosDadosProduto);
        } catch (\Exception $e) {
            $this->assertEquals("O campo id é obrigatório.", $e->getMessage());
            $this->assertEquals(400, $e->getCode());
            $produtoExcluido = $this->produtoUseCases->excluir($this->produtoGateway,  $produtosCategoriaTeste[0]["id"]);
            $this->assertTrue($produtoExcluido);
        }
    }

    public function testAtualizarProdutoComDescricaoFaltando()
    {
        $produto = new Produto("Nome do produto", "Descrição do Produto 1", 25.58, "teste");
        $idProduto = $this->produtoUseCases->cadastrar($this->produtoGateway, $produto);
        $this->assertIsInt($idProduto);

        $produtosCategoriaTeste = $this->produtoUseCases->obterPorCategoria($this->produtoGateway, 'teste');

        $novosDadosProduto = new Produto("Nome nome do produto", "", 28.50, "teste2");

        try {
            $this->produtoUseCases->atualizar($this->produtoGateway, $produtosCategoriaTeste[0]["id"], $novosDadosProduto);
        } catch (\Exception $e) {
            $this->assertEquals("O campo descrição é obrigatório.", $e->getMessage());
            $this->assertEquals(400, $e->getCode());
            $produtoExcluido = $this->produtoUseCases->excluir($this->produtoGateway,  $produtosCategoriaTeste[0]["id"]);
            $this->assertTrue($produtoExcluido);
        }
    }

    public function testAtualizarProdutoComPrecoFaltando()
    {
        $produto = new Produto("Nome do produto", "Descrição do Produto 1", 25.59, "teste");
        $idProduto = $this->produtoUseCases->cadastrar($this->produtoGateway, $produto);
        $this->assertIsInt($idProduto);

        $produtosCategoriaTeste = $this->produtoUseCases->obterPorCategoria($this->produtoGateway, 'teste');

        $novosDadosProduto = new Produto("Nome nome do produto", "Nova descrição do produto", "", "teste2");

        try {
            $this->produtoUseCases->atualizar($this->produtoGateway, $produtosCategoriaTeste[0]["id"], $novosDadosProduto);
        } catch (\Exception $e) {
            $this->assertEquals("O campo preço é obrigatório.", $e->getMessage());
            $this->assertEquals(400, $e->getCode());
            $produtoExcluido = $this->produtoUseCases->excluir($this->produtoGateway,  $produtosCategoriaTeste[0]["id"]);
            $this->assertTrue($produtoExcluido);
        }
    }
    public function testAtualizarProdutoComCategoriaFaltando()
    {
        $produto = new Produto("Nome do produto", "Descrição do Produto 1", 25.60, "teste");
        $idProduto = $this->produtoUseCases->cadastrar($this->produtoGateway, $produto);
        $this->assertIsInt($idProduto);

        $produtosCategoriaTeste = $this->produtoUseCases->obterPorCategoria($this->produtoGateway, 'teste');

        $novosDadosProduto = new Produto("Nome nome do produto", "Nova descrição do produto", 15.55, "");

        try {
            $this->produtoUseCases->atualizar($this->produtoGateway, $produtosCategoriaTeste[0]["id"], $novosDadosProduto);
        } catch (\Exception $e) {
            $this->assertEquals("O campo categoria é obrigatório.", $e->getMessage());
            $this->assertEquals(400, $e->getCode());
            $produtoExcluido = $this->produtoUseCases->excluir($this->produtoGateway,  $produtosCategoriaTeste[0]["id"]);
            $this->assertTrue($produtoExcluido);
        }
    }


    public function testAtualizarProdutoComProdutoNaoEncontrado()
    {
        $novosDadosProduto = new Produto("Nome nome do produto", "Nova descrição do produto", 15.55, "teste");

        try {
            $this->produtoUseCases->atualizar($this->produtoGateway, 999999999999999999, $novosDadosProduto);
        } catch (\Exception $e) {
            $this->assertEquals("Não foi encontrado um produto com o ID informado.", $e->getMessage());
            $this->assertEquals(400, $e->getCode());
        }
    }

    public function testExcluirProdutoComSucesso()
    {
        $produto = new Produto("Nome do produto", "Descrição do Produto 1", 25.61, "teste");
        $idProduto = $this->produtoUseCases->cadastrar($this->produtoGateway, $produto);
        $this->assertIsInt($idProduto);

        $produtosCategoriaTeste = $this->produtoUseCases->obterPorCategoria($this->produtoGateway, 'teste');

        $produtoExcluido = $this->produtoUseCases->excluir($this->produtoGateway,  $produtosCategoriaTeste[0]["id"]);
        $this->assertTrue($produtoExcluido);
    }

    public function testExcluirProdutoComIdFaltando()
    {
        try {
            $this->produtoUseCases->excluir($this->produtoGateway, 0);
        } catch (\Exception $e) {
            $this->assertEquals("O campo ID é obrigatório.", $e->getMessage());
            $this->assertEquals(400, $e->getCode());
        }
    }
    public function testExcluirProdutoNaoEncontrado()
    {
        try {
            $this->produtoUseCases->excluir($this->produtoGateway, 99999999999999999);
        } catch (\Exception $e) {
            $this->assertEquals("Não foi encontrado um produto com o ID informado.", $e->getMessage());
            $this->assertEquals(400, $e->getCode());
        }
    }

    public function testObterPorCategoriaComProdutosEncontrados()
    {
        $produto = new Produto("Nome do produto", "Descrição do Produto 1", 25.50, "teste");
        $idProduto = $this->produtoUseCases->cadastrar($this->produtoGateway, $produto);
        $this->assertIsInt($idProduto);

        $produtosCategoriaTeste = $this->produtoUseCases->obterPorCategoria($this->produtoGateway, 'teste');
        $this->assertArrayHasKey("nome", $produtosCategoriaTeste[0]);
        $this->assertArrayHasKey("descricao", $produtosCategoriaTeste[0]);
        $this->assertArrayHasKey("preco", $produtosCategoriaTeste[0]);
        $this->assertArrayHasKey("categoria", $produtosCategoriaTeste[0]);

        $produtoExcluido = $this->produtoUseCases->excluir($this->produtoGateway,  $produtosCategoriaTeste[0]["id"]);
        $this->assertTrue($produtoExcluido);
    }
    public function testObterPorCategoriaSemProdutosEncontrados()
    {
        $categoria = "categoria_de_teste_sem_produtos";
        $resultado = $this->produtoUseCases->obterPorCategoria($this->produtoGateway, $categoria);
        $this->assertEquals([], $resultado);
    }

    public function testObterPorCategoriaComCategoriaFaltando()
    {
        try {
            $categoria = "";
            $this->produtoUseCases->obterPorCategoria($this->produtoGateway, $categoria);
        } catch (\Exception $e) {
            $this->assertEquals("O campo categoria é obrigatório.", $e->getMessage());
            $this->assertEquals(400, $e->getCode());
        }
    }
}
