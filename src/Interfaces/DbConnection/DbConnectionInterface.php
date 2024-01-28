<?php

namespace Produto\Interfaces\DbConnection;

interface DbConnectionInterface
{
    public function conectar();
    public function inserir(string $nomeTabela, array $parametros);
    public function excluir(string $nomeTabela, int $id): bool;
    public function excluirPorCategoria(string $nomeTabela, string $categoria): bool;
    public function atualizar(string $nomeTabela, int $id, array $parametros): bool;
    public function buscarPorParametros(string $nomeTabela, array $campos, array $parametros): array;
}
