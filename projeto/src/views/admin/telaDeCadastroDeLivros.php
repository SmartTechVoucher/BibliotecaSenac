<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Livros</title>
    <?php
    require_once "../../../config/constantes.php";
    ?>
    <link rel="stylesheet" href="<?php echo $URLBASE ?>/public/css/components/usuario/footer.css">
    <link rel="stylesheet" href="<?php echo $URLBASE ?>/public/css/components/usuario/modal.css">
    <link rel="stylesheet" href="../../../public/css/admin/telaCadastroLivros.css">
    <?php include "../../../public/components/admin/input/input-admin.php"; ?>
    <?php include "../../../public/components/admin/button/button-admin.php"; ?>
    <?php require_once __DIR__ . '/../../../public/components/admin/select/input-select.php'; ?>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,50;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,50;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,50;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,50;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,50;0,300;0,400;0,500;0,700;0,900;1,50;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montaga&family=Poppins:ital,wght@0,50;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,50;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Roboto:ital,wght@0,50;0,300;0,400;0,500;0,700;0,900;1,50;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">

</head>

<body>

    <!--Cabeçalho--> <!--Cabeçalho--> <!--Cabeçalho-->
    <?php
    include "../../../public/components/admin/header/header-admin.php";
    ?>
    <div class="container-main">
        <form id="cadastro-form" action="#" method="post">

            <fieldset class="form-section">
                <legend>Cadastro de livro</legend>
                <div class="form-coluna">
                    <div class="form-grupo">
                        <label for="titulo-livro">Titulo</label>
                        <?php
                        InputAdmin(largura: 100, name: "titulo-livro", id: "titulo-livro", required: true)
                        ?>
                    </div>
                    
                    <div class="form-grupo">
                        <?php
                        $autoresMock = [
                            ['id' => 1, 'nome' => 'Machado de Assis'],
                            ['id' => 2, 'nome' => 'Clarice Lispector'],
                            ['id' => 3, 'nome' => 'Graciliano Ramos'],
                            ['id' => 4, 'nome' => 'Carlos Drummond de Andrade'],
                            ['id' => 5, 'nome' => 'José Saramago'],
                            ['id' => 6, 'nome' => 'Fernando Pessoa'],
                            ['id' => 7, 'nome' => 'J.R.R. Tolkien'],
                            ['id' => 8, 'nome' => 'George Orwell'],
                            ['id' => 9, 'nome' => 'Gabriel García Márquez'],
                            ['id' => 10, 'nome' => 'Stephen King'],
                            ['id' => 11, 'nome' => 'Agatha Christie'],
                            ['id' => 12, 'nome' => 'Isaac Asimov'],
                            ['id' => 13, 'nome' => 'Virginia Woolf'],
                            ['id' => 14, 'nome' => 'H.P. Lovecraft'],
                            ['id' => 15, 'nome' => 'Albert Camus'],
                            ['id' => 16, 'nome' => 'J.K. Rowling'],
                            ['id' => 17, 'nome' => 'Jane Austen'],
                            ['id' => 18, 'nome' => 'C.S. Lewis'],
                        ];

                        renderSelectModal('autor', 'Autor', $autoresMock);
                        ?>

                        </select>
                    </div>
                    <div class="form-grupo">

                        <?php
                        
                        $editorasMock = [
                            ['id' => 1, 'nome' => 'Companhia das Letras'],
                            ['id' => 2, 'nome' => 'Editora Rocco'],
                            ['id' => 3, 'nome' => 'Editora Record'],
                            ['id' => 4, 'nome' => 'Penguin Random House'],
                            ['id' => 5, 'nome' => 'Grupo Editorial Pensamento'],
                            ['id' => 6, 'nome' => 'Intrínseca'],
                            ['id' => 7, 'nome' => 'Globo Livros'],
                            ['id' => 8, 'nome' => 'Editora Martins Fontes'],
                            ['id' => 9, 'nome' => 'HarperCollins Brasil'],
                            ['id' => 10, 'nome' => 'Saraiva'],
                            ['id' => 11, 'nome' => 'Editora 34'],
                            ['id' => 12, 'nome' => 'Zahar'],
                            ['id' => 13, 'nome' => 'Editora Aleph'],
                            ['id' => 14, 'nome' => 'Cengage Learning'],
                            ['id' => 15, 'nome' => 'Manole']
                        ];

                        renderSelectModal('editora', 'Editora', $editorasMock);
                        ?>
                    </div>




                </div>
                <div class="form-coluna">
                    <div class="form-grupo">
                        <label for="isbn-livro">ISBN</label>
                        <?php
                        InputAdmin(largura: 100, name: "isbn-livro", id: "isbn-livro", required: true)
                        ?>
                    </div>
                    <div class="form-grupo">
                        <?php
                        

                        $idiomasMock = [
                            ['id' => 1, 'nome' => 'Português'],
                            ['id' => 2, 'nome' => 'Inglês'],
                            ['id' => 3, 'nome' => 'Espanhol'],
                            ['id' => 4, 'nome' => 'Francês'],
                            ['id' => 5, 'nome' => 'Alemão'],
                            ['id' => 6, 'nome' => 'Italiano'],
                            ['id' => 7, 'nome' => 'Japonês'],
                            ['id' => 8, 'nome' => 'Chinês'],
                            ['id' => 9, 'nome' => 'Russo'],
                            ['id' => 10, 'nome' => 'Árabe'],
                        ];

                        renderSelectModal('idioma', 'Idioma', $idiomasMock);
                        ?>
                    </div>
                    <div class="form-grupo">
                        <?php
                        

                        $categoriasMock = [
                            ['id' => 1, 'nome' => 'Ficção'],
                            ['id' => 2, 'nome' => 'Não-ficção'],
                            ['id' => 3, 'nome' => 'Romance'],
                            ['id' => 4, 'nome' => 'Suspense'],
                            ['id' => 5, 'nome' => 'Fantasia'],
                            ['id' => 6, 'nome' => 'Ficção Científica'],
                            ['id' => 7, 'nome' => 'Biografia'],
                            ['id' => 8, 'nome' => 'Autoajuda'],
                            ['id' => 9, 'nome' => 'História'],
                            ['id' => 10, 'nome' => 'Culinária'],
                            ['id' => 11, 'nome' => 'Infantil'],
                            ['id' => 12, 'nome' => 'Poesia'],
                            ['id' => 13, 'nome' => 'Aventura'],
                            ['id' => 14, 'nome' => 'Humor'],
                        ];

                        renderSelectModal('categoria', 'Categoria', $categoriasMock);
                        ?>

                    </div>
                </div>
                <div class="form-coluna">
                    <div class="form-grupo">
                        <?php
                           

                            $areasMock = [
                                ['id' => 1, 'nome' => 'Ciências Exatas'],
                                ['id' => 2, 'nome' => 'Ciências Biológicas'],
                                ['id' => 3, 'nome' => 'Ciências Humanas'],
                                ['id' => 4, 'nome' => 'Ciências Sociais Aplicadas'],
                                ['id' => 5, 'nome' => 'Engenharias'],
                                ['id' => 6, 'nome' => 'Saúde'],
                                ['id' => 7, 'nome' => 'Linguística, Letras e Artes'],
                                ['id' => 8, 'nome' => 'Agricultura e Meio Ambiente'],
                                ['id' => 9, 'nome' => 'Arquitetura e Urbanismo'],
                                ['id' => 10, 'nome' => 'Computação e Informática'],
                            ];

                            renderSelectModal('area', 'Área', $areasMock);
                            ?>

                            </div>
                            <div class="form-grupo">
                                <label for="publicacao-livro">Ano</label>
                                <?php
                                InputAdmin(largura: 100, name: "publicacao-livro", id: "publicacao-livro", tipo: "date")
                                ?>
                            </div>
                            <div class="form-grupo">
                                <label for="capa-livro">Capa</label>
                                <?php
                                InputAdmin(largura: 100, name: "capa-livro", id: "capa-livro", tipo: "file")
                                ?>
                            </div>
                    </div>
                    <div class="form-coluna">
                        <div class="form-grupo">
                            <label for="resumo-livro">Resumo</label>
                            <textarea name="resumo-livro" id="resumo-livro" cols="30" rows="10"></textarea>
                        </div>
                        <div class="form-grupo">
                            <label for="notas-livro">Notas</label>
                            <textarea name="notas-livro" id="notas-livro" cols="30" rows="10"></textarea>
                        </div>
                        <div class="form-grupo">
                            <label for="tipo-documento">Tipo de documento</label>
                            <select id="tipo-documento" name="tipo-documento" class="input-admin" required>
                                <option value="">Selecione</option>
                                <option value="tipo-documento-livro">Livro</option>
                                <option value="tipo-documento-ebook">eBook</option>
                                <option value="tipo-documento-revista">Revista</option>
                            </select>
                        </div>
                    </div>





            </fieldset>
            <div class="botao-container">
                <?php
                botao(texto: "Registrar", tipo: "submit");
                botao(texto: "Cancelar", tipo: "reset", cor: "#d00");
                ?>
            </div>


        </form>
    </div>


 </div>  
    
   <?php
    include "../../../public/components/admin/footer/footer-admin.php";
   ?>
    <script src="../../../public/js/admin/telaDeCadastroDeLivros.js"></script>

</body>

</html>