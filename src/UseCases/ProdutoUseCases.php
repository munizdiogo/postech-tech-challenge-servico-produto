<?php

namespace Produto\UseCases;

require "./src/Interfaces/UseCases/ProdutoUseCasesInterface.php";

use Produto\Entities\Produto;
use Produto\Gateways\ProdutoGateway;
use Produto\Interfaces\UseCases\ProdutoUseCasesInterface;

class ProdutoUseCases implements ProdutoUseCasesInterface
{
    public function cadastrar(ProdutoGateway $produtoGateway, Produto $produto)
    {
        if (empty($produto->getNome())) {
            throw new \Exception("O campo nome é obrigatório.", 400);
        }

        if (empty($produto->getDescricao())) {
            throw new \Exception("O campo descrição é obrigatório.", 400);
        }

        if (empty($produto->getPreco())) {
            throw new \Exception("O campo preço é obrigatório.", 400);
        }

        if (empty($produto->getCategoria())) {
            throw new \Exception("O campo categoria é obrigatório.", 400);
        }

        $produtoJaCadastrado = $produtoGateway->obterPorNome($produto->getNome());

        if (!empty($produtoJaCadastrado)) {
            throw new \Exception("Já existe um produto cadastrado com esse nome.", 409);
        }

        $resultadoCadastro = $produtoGateway->cadastrar($produto);
        return $resultadoCadastro;
    }

    public function atualizar(ProdutoGateway $produtoGateway, int $id, Produto $produto)
    {
        if (empty($id)) {
            throw new \Exception("O campo id é obrigatório.", 400);
        }

        if (empty($produto->getNome())) {
            throw new \Exception("O campo nome é obrigatório.", 400);
        }

        if (empty($produto->getDescricao())) {
            throw new \Exception("O campo descrição é obrigatório.", 400);
        }

        if (empty($produto->getPreco())) {
            throw new \Exception("O campo preço é obrigatório.", 400);
        }

        if (empty($produto->getCategoria())) {
            throw new \Exception("O campo categoria é obrigatório.", 400);
        }

        $produtoEncontrado = $produtoGateway->obterPorId($id);

        if ($produtoEncontrado) {
            $produtoAtualizado = new Produto(
                $produto->getNome() ?? $produtoEncontrado["nome"],
                $produto->getDescricao() ?? $produtoEncontrado["descricao"],
                $produto->getPreco() ?? $produtoEncontrado["preco"],
                $produto->getCategoria() ?? $produtoEncontrado["categoria"]
            );
            $resultadoAtualizacao = $produtoGateway->atualizar($id, $produtoAtualizado);
            return $resultadoAtualizacao;
        } else {
            throw new \Exception("Não foi encontrado um produto com o ID informado.", 400);
        }
    }

    public function excluir(ProdutoGateway $produtoGateway, int $id)
    {
        if (empty($id)) {
            throw new \Exception("O campo ID é obrigatório.", 400);
        }

        $produtoEncontrado = $produtoGateway->obterPorId($id);

        if ($produtoEncontrado) {
            $resultadoAtualizacao = $produtoGateway->excluir($id);
            return $resultadoAtualizacao;
        } else {
            throw new \Exception("Não foi encontrado um produto com o ID informado.", 400);
        }
    }

    public function excluirPorCategoria(ProdutoGateway $produtoGateway, string $categoria)
    {
        if (empty($categoria)) {
            throw new \Exception("O campo categoria é obrigatório.", 400);
        }

        $resultado = $produtoGateway->excluirPorCategoria($categoria);
        return $resultado;
    }

    public function obterPorCategoria(ProdutoGateway $produtoGateway, string $categoria)
    {
        if (empty($categoria)) {
            throw new \Exception("O campo categoria é obrigatório.", 400);
        }

        $produtos = $produtoGateway->obterPorCategoria($categoria);
        return $produtos;
    }
}
