<?php
$alunos = [
    [
        'nome' => 'Gabriel Arruda',
        'funcao' => 'Back-End Developer',
        'imagem' => '../../../public/assets/img/Gabriel.jpg',
        'linkedin' => 'https://www.linkedin.com/feed/?trk=joogle',
        'github' => 'https://github.com/Gantt-sucessor',
    ],
    [
        'nome' => 'Marlon Oliveira',
        'funcao' => 'Full-Stack Developer',
        'imagem' => 'https://www.example.com/maria.jpg',
        'linkedin' => 'https://www.linkedin.com/in/mariasilva',
        'github' => 'https://github.com/mariasilva',
    ],
    [
        'nome' => 'Vitória Caetano',
        'funcao' => 'Full-Stack Developer',
        'imagem' => '../../../public/assets/img/Vitoria.jpg',
        'linkedin' => 'https://www.linkedin.com/in/vit%C3%B3ria-caetano-099572335?lipi=urn%3Ali%3Apage%3Ad_flagship3_profile_view_base_contact_details%3ByHs4q%2FqmTvGFMHtOpq7IEA%3D%3D',
        'github' => 'https://github.com/vik-cae',
    ],
    [
        'nome' => 'Bianca Loreslaine',
        'funcao' => 'Full-Stack Developer',
        'imagem' => '../../../public/assets/img/Bianca.jpg',
        'linkedin' => 'https://www.linkedin.com/in/bianca-loreslaine-634b36328/',
        'github' => 'https://github.com/bia-sx',
    ],
    [
        'nome' => 'Vitor Araujo',
        'funcao' => 'Back-End Developer',
        'imagem' => 'https://www.example.com/maria.jpg',
        'linkedin' => 'https://www.linkedin.com/in/mariasilva',
        'github' => 'https://github.com/mariasilva',
    ],
    [
        'nome' => 'Matheus Serpa',
        'funcao' => 'UX Designer',
        'imagem' => 'https://www.example.com/maria.jpg',
        'linkedin' => 'https://www.linkedin.com/in/mariasilva',
        'github' => 'https://github.com/mariasilva',
    ],
    [
        'nome' => 'Gabriel Augusto',
        'funcao' => 'Back-End Developer',
        'imagem' => 'https://as2.ftcdn.net/v2/jpg/05/89/93/27/1000_F_589932782_vQAEAZhHnq1QCGu5ikwrYaQD0Mmurm0N.jpg',
        'linkedin' => 'https://www.linkedin.com/in/mariasilva',
        'github' => 'https://github.com/mariasilva',
    ],
    [
        'nome' => 'Gustavo Víctor',
        'funcao' => '"Front-End Developer"',
        'imagem' => 'https://as2.ftcdn.net/v2/jpg/05/89/93/27/1000_F_589932782_vQAEAZhHnq1QCGu5ikwrYaQD0Mmurm0N.jpg',
        'linkedin' => 'https://www.linkedin.com/in/mariasilva',
        'github' => 'https://github.com/mariasilva',
    ],
    
];
?>

<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <title>Desenvolvedores</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../../../public/css/usuario/devs.css">
    <link href="https://fonts.googleapis.com/css2?family=Bai+Jamjuree:wght@400;600;700&display=swap" rel="stylesheet">
</head>
<body>

<main id="main-devs">
    <section>
        <div class="logo_dev">
            <p>DESENVOLVEDORES</p>
            <h1>DE SISTEMA</h1>
        </div>
    </section>

    <section class="cards-grid">
        <?php foreach ($alunos as $aluno): ?>
            <?php include "../../../public/components/card_devs/card_devs.php"; ?>
        <?php endforeach; ?>
    </section>
</main>

</body>
</html>
