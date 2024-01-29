<?php

require "./src/External/MySqlConnection.php";
require "./src/Controllers/ProdutoController.php";

use Behat\Gherkin\Node\TableNode;
use Produto\External\MySqlConnection;
use PHPUnit\Framework\TestCase;
use Behat\Behat\Context\Context;
use Produto\Controllers\ProdutoController;


class FeatureContext extends TestCase implements Context
{
    private $idProduto;
    private $resultado;
    private $produtoController;
    private $exceptionMessage;
    private $exceptionCode;
    private $dbConnection;
    private $dadosProduto;

    public function __construct()
    {
        $this->dbConnection = new MySqlConnection();
        $this->produtoController = new ProdutoController();
    }

    /**
     * @Given os seguintes dados válidos para criação do produto:
     */
    public function osSeguintesDadosValidosParaCriacaoDoProduto(TableNode $table)
    {
        $this->dadosProduto = $table->getHash();
    }

    /**
     * @When eu cadastro um produto com esses dados válidos
     */
    public function euCadastroUmProdutoComEssesDadosValidos()
    {
        $produtoExcluido = $this->produtoController->excluirPorCategoria($this->dbConnection, 'teste');
        $this->assertTrue($produtoExcluido);
        $this->resultado = $this->produtoController->cadastrar($this->dbConnection, $this->dadosProduto[0]);
    }

    /**
     * @Then o produto é cadastrado e recebo mensagem de sucesso ao cadastrar
     */
    public function oProdutoECadastradoEReceboMensagemDeSucessoAoCadastrar()
    {
        $this->assertIsInt((int)$this->resultado);
        $produtoExcluido = $this->produtoController->excluirPorCategoria($this->dbConnection, 'teste');
        $this->assertTrue($produtoExcluido);
    }

    /**
     * @Given os seguintes dados de um produto já existente:
     */
    public function osSeguintesDadosDeUmProdutoJaExistente(TableNode $table)
    {
        $this->dadosProduto = $table->getHash();
    }

    /**
     * @When eu cadastro um produto esses dados de um produto já existente
     */
    public function euCadastroUmProdutoEssesDadosDeUmProdutoJaExistente()
    {
        $this->produtoController->cadastrar($this->dbConnection, $this->dadosProduto[0]);
        try {
            $this->resultado = $this->produtoController->cadastrar($this->dbConnection, $this->dadosProduto[0]);
        } catch (Exception $e) {
            $this->exceptionMessage = $e->getMessage();
            $this->exceptionCode = $e->getCode();
            $produtoExcluido = $this->produtoController->excluirPorCategoria($this->dbConnection, 'teste');
            $this->assertTrue($produtoExcluido);
        }
    }

    /**
     * @Then o produto não é cadastrado e recebo uma mensagem informando que o nome já está em uso
     */
    public function oProdutoNaoECadastradoEReceboUmaMensagemInformandoQueONomeJaEstaEmUso()
    {
        $this->assertEquals("Já existe um produto cadastrado com esse nome.", $this->exceptionMessage);
        $this->assertEquals(409, $this->exceptionCode);
    }

    /**
     * @Given os seguintes dados para criação de um produto com nome vazio:
     */
    public function osSeguintesDadosParaCriacaoDeUmProdutoComNomeVazio(TableNode $table)
    {
        $this->dadosProduto = $table->getHash();
    }

    /**
     * @When eu cadastro um produto com nome vazio
     */
    public function euCadastroUmProdutoComNomeVazio()
    {
        try {
            $this->resultado = $this->produtoController->cadastrar($this->dbConnection, $this->dadosProduto[0]);
        } catch (Exception $e) {
            $this->exceptionMessage = $e->getMessage();
            $this->exceptionCode = $e->getCode();
        }
    }

    /**
     * @Then recebo uma mensagem informando que o nome do produto é obrigatório
     */
    public function receboUmaMensagemInformandoQueONomeDoProdutoEObrigatorio()
    {
        $this->assertEquals("O campo nome é obrigatório.", $this->exceptionMessage);
        $this->assertEquals(400, $this->exceptionCode);
    }

    /**
     * @Given os seguintes dados para criação de um produto com descrição vazia:
     */
    public function osSeguintesDadosParaCriacaoDeUmProdutoComDescricaoVazia(TableNode $table)
    {
        $this->dadosProduto = $table->getHash();
    }

    /**
     * @When eu cadastro um produto com descrição vazia
     */
    public function euCadastroUmProdutoComDescricaoVazia()
    {
        try {
            $this->resultado = $this->produtoController->cadastrar($this->dbConnection, $this->dadosProduto[0]);
        } catch (Exception $e) {
            $this->exceptionMessage = $e->getMessage();
            $this->exceptionCode = $e->getCode();
        }
    }

    /**
     * @Then recebo uma mensagem informando que a descrição do produto é obrigatória
     */
    public function receboUmaMensagemInformandoQueADescricaoDoProdutoEObrigatoria()
    {
        $this->assertEquals("O campo descrição é obrigatório.", $this->exceptionMessage);
        $this->assertEquals(400, $this->exceptionCode);
    }

    /**
     * @Given os seguintes dados para criação de um produto com preço vazio:
     */
    public function osSeguintesDadosParaCriacaoDeUmProdutoComPrecoVazio(TableNode $table)
    {
        $this->dadosProduto = $table->getHash();
    }

    /**
     * @When eu cadastro um produto com preço vazio
     */
    public function euCadastroUmProdutoComPrecoVazio()
    {
        try {
            $this->resultado = $this->produtoController->cadastrar($this->dbConnection, $this->dadosProduto[0]);
        } catch (Exception $e) {
            $this->exceptionMessage = $e->getMessage();
            $this->exceptionCode = $e->getCode();
        }
    }

    /**
     * @Then recebo uma mensagem informando que o preço do produto é obrigatório
     */
    public function receboUmaMensagemInformandoQueOPrecoDoProdutoEObrigatorio()
    {
        $this->assertEquals("O campo preço é obrigatório.", $this->exceptionMessage);
        $this->assertEquals(400, $this->exceptionCode);
    }

    /**
     * @Given os seguintes dados para criação de um produto com categoria vazia:
     */
    public function osSeguintesDadosParaCriacaoDeUmProdutoComCategoriaVazia(TableNode $table)
    {
        $this->dadosProduto = $table->getHash();
    }

    /**
     * @When eu cadastro um produto com categoria vazia
     */
    public function euCadastroUmProdutoComCategoriaVazia()
    {
        try {
            $this->resultado = $this->produtoController->cadastrar($this->dbConnection, $this->dadosProduto[0]);
        } catch (Exception $e) {
            $this->exceptionMessage = $e->getMessage();
            $this->exceptionCode = $e->getCode();
        }
    }

    /**
     * @Then recebo uma mensagem informando que a categoria do produto é obrigatória
     */
    public function receboUmaMensagemInformandoQueACategoriaDoProdutoEObrigatoria()
    {
        $this->assertEquals("O campo categoria é obrigatório.", $this->exceptionMessage);
        $this->assertEquals(400, $this->exceptionCode);
    }

    /**
     * @When eu atualizo um produto com dados válidos
     */
    public function euAtualizoUmProdutoComDadosValidos()
    {
        $this->resultado = $this->produtoController->cadastrar($this->dbConnection, $this->dadosProduto[0]);
        $this->idProduto = $this->resultado;
        $this->dadosProduto[0]["id"] = $this->idProduto;
        $novosDadosProduto = $this->dadosProduto[0];
        $this->resultado = $this->produtoController->atualizar($this->dbConnection, $novosDadosProduto);
    }

    /**
     * @Then o produto é atualizado com sucesso e recebo mensagem de sucesso ao atualizar produto
     */
    public function oProdutoEAtualizadoComSucessoEReceboMensagemDeSucessoAoAtualizarProduto()
    {
        $this->assertTrue($this->resultado);
        $produtoExcluido = $this->produtoController->excluirPorCategoria($this->dbConnection, 'teste');
        $this->assertTrue($produtoExcluido);
    }


    /**
     * @Given os seguintes dados válidos para atualização do produto:
     */
    public function osSeguintesDadosValidosParaAtualizacaoDoProduto(TableNode $table)
    {
        $this->dadosProduto = $table->getHash();
    }

    /**
     * @Given um id de um produto existente
     */
    public function umIdDeUmProdutoExistente()
    {
        $dadosProduto = [
            'nome' => 'Produto Teste',
            'descricao' => 'Descrição do produto de teste 2',
            'preco' => 10.99,
            'categoria' => 'teste'
        ];

        $this->idProduto = $this->produtoController->cadastrar($this->dbConnection, $dadosProduto);
    }

    /**
     * @When eu excluo o produto existente com id igual ao id informado
     */
    public function euExcluoOProdutoExistenteComIdIgualAoIdInformado()
    {
        $this->resultado = $this->produtoController->excluir($this->dbConnection,  $this->idProduto);
    }

    /**
     * @Then o produto é excluído e recebo uma mensagem de sucesso
     */
    public function oProdutoEExcluidoEReceboUmaMensagemDeSucesso()
    {
        $this->assertTrue($this->resultado);
    }

    /**
     * @Given um id de um produto que não existe
     */
    public function umIdDeUmProdutoQueNaoExiste()
    {
        $this->idProduto = 9999999999999999;
    }

    /**
     * @When eu tento excluir um produto que não existe
     */
    public function euTentoExcluirUmProdutoQueNaoExiste()
    {
        try {
            $this->resultado = $this->produtoController->excluir($this->dbConnection,  $this->idProduto);
        } catch (Exception $e) {
            $this->exceptionMessage = $e->getMessage();
            $this->exceptionCode = $e->getCode();
        }
    }

    /**
     * @Then recebo uma mensagem de erro informando que o produto não existe
     */
    public function receboUmaMensagemDeErroInformandoQueOProdutoNaoExiste()
    {
        $this->assertEquals("Não foi encontrado um produto com o ID informado.", $this->exceptionMessage);
        $this->assertEquals(400, $this->exceptionCode);
    }
}
