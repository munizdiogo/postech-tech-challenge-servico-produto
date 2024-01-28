<?php

namespace Produto\External;

const DB_HOST = "localhost";
const DB_NAME = "dbpostech";
const DB_USERNAME = "root";
const DB_PASSWORD = "";
const DB_PORT = 3306;

require "./src/Interfaces/DbConnection/DbConnectionInterface.php";

use Produto\Interfaces\DbConnection\DbConnectionInterface;
use \PDO;
use \PDOException;

class MySqlConnection implements DbConnectionInterface
{
    public function conectar()
    {
        $conn = null;

        try {
            $conn = new PDO("mysql:host=" . DB_HOST . ";port=" . DB_PORT . ";dbname=" . DB_NAME, DB_USERNAME, DB_PASSWORD);
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo "Erro na conexão com o banco de dados: " . $e->getMessage();
        }

        return $conn;
    }

    public function inserir(string $nomeTabela, array $parametros)
    {
        $db = $this->conectar();
        $nomesCampos = implode(", ", array_keys($parametros));
        $nomesValores = ":" . implode(", :", array_keys($parametros));
        $query = "INSERT INTO $nomeTabela ($nomesCampos) VALUES ($nomesValores)";
        $stmt = $db->prepare($query);

        foreach ($parametros as $chave => $valor) {
            $stmt->bindValue(":$chave", $valor);
        }

        try {
            return  $stmt->execute() ? (int)$db->lastInsertId() : false;
        } catch (PDOException $e) {
            return false;
        }
    }

    public function excluir(string $nomeTabela, int $id): bool
    {
        $db = $this->conectar();
        $query = "DELETE FROM $nomeTabela WHERE id = :id";
        $stmt = $db->prepare($query);
        $stmt->bindValue(":id", $id);
        try {
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }
    public function excluirPorCategoria(string $nomeTabela, string $categoria): bool
    {
        $db = $this->conectar();
        $query = "DELETE FROM $nomeTabela WHERE categoria = :categoria";
        $stmt = $db->prepare($query);
        $stmt->bindValue(":categoria", $categoria);
        try {
            return $stmt->execute();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function atualizar(string $nomeTabela, int $id, array $parametros): bool
    {
        $db = $this->conectar();
        $nomesCampos = "";

        foreach ($parametros as $chave => $valor) {
            $nomesCampos .= $chave . " = :" . $chave . ", ";
        }

        $nomesCampos = substr($nomesCampos, 0, -2);
        $query = "UPDATE $nomeTabela SET $nomesCampos WHERE id = :id";
        $stmt = $db->prepare($query);

        foreach ($parametros as $chave => $valor) {
            $stmt->bindValue(":$chave", $valor);
        }

        $stmt->bindValue(":id", $id);

        try {
            $stmt->execute();
            return $stmt->rowCount();
        } catch (PDOException $e) {
            return false;
        }
    }

    public function buscarPorParametros(string $nomeTabela, array $campos, array $parametros): array
    {
        $camposBusca = $this->ajustarCamposExpressao($campos);
        $parametrosBusca = $this->prepararParametrosBusca($parametros);

        $db = $this->conectar();

        if (!empty($parametrosBusca["restricao"])) {
            $query = "SELECT $camposBusca FROM $nomeTabela " . $parametrosBusca["restricao"];
            $stmt = $db->prepare($query);

            foreach ($parametros as $item) {
                $stmt->bindValue(":{$item['campo']}", $item['valor']);
            }
        } else {
            $query = "SELECT $camposBusca FROM $nomeTabela";
            $stmt = $db->prepare($query);
        }

        $stmt->execute();
        $dados = $stmt->fetchAll(PDO::FETCH_ASSOC);
        return $dados ?? [];
    }

    private function ajustarCamposExpressao(array $campos): string
    {
        if (empty($campos)) {
            return " * ";
        } else {
            return implode(", ", $campos);
        }
    }

    private function prepararParametrosBusca(array $params): array
    {
        if (empty($params)) {
            return [
                "restricao" => "",
                "valores" => [],
            ];
        }

        $camposRestricaoArray = [];
        $valores = [];

        foreach ($params as $item) {
            $camposRestricaoArray[] = $item["campo"] . " = :" . $item["campo"];
            $valores[] = $item["valor"];
        }

        $camposRestricao = implode(" AND ", $camposRestricaoArray);

        return [
            "restricao" => "WHERE $camposRestricao",
            "valores" => $valores
        ];
    }
}
