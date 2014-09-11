painel_administrativo
=====================

Painel Administrativo com Codeigniter

1. Controle de Usuário;
2. Controle de Grupo de Usuário;
3. Log de Ação;
4. Permissão por grupo de usuário;

Login e Senha para primeiro acesso como Administrador: admin

Instalação

1. Criar banco de dados no MySql;
2. Executar o arquivo database.sql no banco de dados;
3. Deletar arquivo database.sql;
4. Configurar conexão com banco MySql em application/config/database.php;
5. Configurar nome do Cliente ou Projeto, na constante NOME_CLIENTE em application/config/constants.php linha 40;

Permissão

Permissões poderão ser adicionadas na tabela usu_metodo seguindo os campos abaixo:
1. modulo => Módulo localizado no diretório application/modules;
2. classe => Nome do Controller localizado dentro do módulo;
3. metodo => Método do Controller;
4. area => Nome amigável da class para visualização pela aplicação;
5. apelido => Nome amigável do metodo para visualização pela aplicação;
6. privado => Irá checar se o usuário logado possui permissão de acesso. Caso não tenha permissão, será redirecionado para página de acesso negado;
7. default => Para áreas comuns a todos usuários autenticados, como ex: home do painel. Deve ser combinado com privado = 1;

Por padrão apenas o módulo Painel é autenticado. Para aplicar permissão em outros módulo, editar o arquivo application/hooks/auth_hook.php.
