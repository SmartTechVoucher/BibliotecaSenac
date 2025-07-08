<div class="card_dev">
    <img src="<?= $aluno['imagem'] ?>" alt="Imagem de <?= $aluno['nome'] ?>">

    <section class="texto">
        <h1><?= $aluno['nome'] ?></h1>
        <h3><?= $aluno['funcao'] ?></h3>
    </section>

    <section class="redes">
        <a href="<?= $aluno['linkedin'] ?>" target="_blank">
            <img src="https://img.icons8.com/?size=100&id=8808&format=png&color=40C057" alt="LinkedIn">
        </a>
        <a href="<?= $aluno['github'] ?>" target="_blank">
            <img src="https://img.icons8.com/?size=100&id=62856&format=png&color=40C057" alt="GitHub">
        </a>
</section>

</div>
