Feature: Gerenciamento de Produtos

     Scenario: Cadastrar um produto com sucesso
          Given os seguintes dados válidos para criação do produto:
               | nome          | descricao                     | preco | categoria |
               | Produto Teste | Descrição do produto de teste | 10.99 | teste     |
          When eu cadastro um produto com esses dados válidos
          Then o produto é cadastrado e recebo mensagem de sucesso ao cadastrar

     Scenario: Cadastrar um produto com nome já existente
          Given os seguintes dados de um produto já existente:
               | nome          | descricao                     | preco | categoria |
               | Produto Teste | Descrição do produto de teste | 10.99 | teste     |
          When eu cadastro um produto esses dados de um produto já existente
          Then o produto não é cadastrado e recebo uma mensagem informando que o nome já está em uso

     Scenario: Cadastrar um produto com nome vazio
          Given os seguintes dados para criação de um produto com nome vazio:
               | nome | descricao                     | preco | categoria |
               |      | Descrição do produto de teste | 10.99 | teste     |
          When eu cadastro um produto com nome vazio
          Then recebo uma mensagem informando que o nome do produto é obrigatório

     Scenario: Cadastrar um produto com descrição vazia
          Given os seguintes dados para criação de um produto com descrição vazia:
               | nome          | descricao | preco | categoria |
               | Produto Teste |           | 10.99 | teste     |
          When eu cadastro um produto com descrição vazia
          Then recebo uma mensagem informando que a descrição do produto é obrigatória

     Scenario: Cadastrar um produto com preço vazio
          Given os seguintes dados para criação de um produto com preço vazio:
               | nome          | descricao                     | preco | categoria |
               | Produto Teste | Descrição do produto de teste |       | teste     |
          When eu cadastro um produto com preço vazio
          Then recebo uma mensagem informando que o preço do produto é obrigatório

     Scenario: Cadastrar um produto com categoria vazia
          Given os seguintes dados para criação de um produto com categoria vazia:
               | nome          | descricao                     | preco | categoria |
               | Produto Teste | Descrição do produto de teste | 10.99 |           |
          When eu cadastro um produto com categoria vazia
          Then recebo uma mensagem informando que a categoria do produto é obrigatória

     Scenario: Atualizar um produto com sucesso
          Given os seguintes dados válidos para atualização do produto:
               | nome                 | descricao                 | preco | categoria |
               | Novo nome do produto | Nova descrição do produto | 25.49 | teste     |
          When eu atualizo um produto com dados válidos
          Then o produto é atualizado com sucesso e recebo mensagem de sucesso ao atualizar produto

     Scenario: Excluir um produto com sucesso
          Given um id de um produto existente
          When eu excluo o produto existente com id igual ao id informado
          Then o produto é excluído e recebo uma mensagem de sucesso

     Scenario: Excluir um produto que não existe
          Given um id de um produto que não existe
          When eu tento excluir um produto que não existe
          Then recebo uma mensagem de erro informando que o produto não existe
