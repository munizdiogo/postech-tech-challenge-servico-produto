<?php

use Produto\External\MySqlConnection;
use PHPUnit\Framework\TestCase;
use Produto\Entities\Produto;
use Produto\Gateways\ProdutoGateway;
use Produto\UseCases\ProdutoUseCases;

class MySqlConnectionTest extends TestCase
{
    private $dbConnection;
    private $nomeTabela;

    public function setUp(): void
    {
        parent::setUp();
        $this->dbConnection = new MySqlConnection;
        $this->nomeTabela = 'produtos';
    }

    public function testConectarComSucesso()
    {
        $conn = $this->dbConnection->conectar();
        $this->assertInstanceOf(\PDO::class, $conn);
    }
    public function testConectarComErro()
    {
        $conn = $this->dbConnection->conectar();
        $this->assertInstanceOf(\PDO::class, $conn);
    }

    public function testInserir()
    {
        $parametros = [
            'nome' => 'teste',
            'categoria' => 'teste',
        ];

        $id = $this->dbConnection->inserir($this->nomeTabela, $parametros);
        $this->assertIsNumeric($id);
        $this->assertGreaterThan(0, $id);
        $excluirRegistro = $this->dbConnection->excluir($this->nomeTabela, $id);
        $this->assertTrue($excluirRegistro);
    }

    public function testExcluir()
    {
        $parametros = [
            'nome' => 'teste',
            'categoria' => 'teste',
        ];

        $id = $this->dbConnection->inserir($this->nomeTabela, $parametros);
        $this->assertIsNumeric($id);
        $this->assertGreaterThan(0, $id);
        $excluirRegistro = $this->dbConnection->excluir($this->nomeTabela, $id);
        $this->assertTrue($excluirRegistro);
    }

    public function testExcluirPorCategoria()
    {
        $parametros = [
            'nome' => 'teste',
            'categoria' => 'teste',
        ];

        $id = $this->dbConnection->inserir($this->nomeTabela, $parametros);
        $this->assertIsNumeric($id);
        $this->assertGreaterThan(0, $id);

        $categoria = 'teste';

        $result = $this->dbConnection->excluirPorCategoria($this->nomeTabela, $categoria);

        $this->assertTrue($result);
    }

    public function testAtualizar()
    {
        $parametros = [
            'nome' => 'teste',
            'categoria' => 'teste',
        ];

        $id = $this->dbConnection->inserir($this->nomeTabela, $parametros);
        $this->assertIsNumeric($id);
        $this->assertGreaterThan(0, $id);

        $parametros = [
            'nome' => 'novo nome do produto',
            'categoria' => 'teste',
        ];

        $result = $this->dbConnection->atualizar($this->nomeTabela, $id, $parametros);

        $this->assertTrue($result);

        $excluirRegistro = $this->dbConnection->excluir($this->nomeTabela, $id);

        $this->assertTrue($excluirRegistro);
    }

    public function testBuscarPorParametros()
    {
        $parametros = [
            'nome' => 'teste',
            'categoria' => 'teste',
        ];

        $id = $this->dbConnection->inserir($this->nomeTabela, $parametros);
        $this->assertIsNumeric($id);
        $this->assertGreaterThan(0, $id);

        $campos = ['categoria'];
        $parametros = [
            [
                'campo' => 'categoria',
                'valor' => 'teste',
            ],
        ];

        $result = $this->dbConnection->buscarPorParametros($this->nomeTabela, $campos, $parametros);
        $this->assertIsArray($result);

        $excluirRegistro = $this->dbConnection->excluir($this->nomeTabela, $id);
        $this->assertTrue($excluirRegistro);
    }
}
