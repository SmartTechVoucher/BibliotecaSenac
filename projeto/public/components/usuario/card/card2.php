<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <link rel="stylesheet" href="../../../css/components/usuario/card2.css">
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@200;400;600&display=swap" rel="stylesheet">
  <link
    rel="stylesheet"
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" />
</head>



<div class="card-livro">
  <button class="btn-favorito">
    <i class="fa-regular fa-heart"></i>
  </button>
  <div class="capa-wrapper">
    <img
      src="https://covers.odilo.io/publicms/Tecnologia_e_Comunica__o__Mario_Sergio_Cortella_e_Breno_Cortella__Papo_de_Fam_lia_/VD310_318x451.jpg"
      class="capa-livro"
      alt="Capa do Livro" />
    <div class="overlay">
      <p class="descricao-livro">
      Tecnologia e Comunicação: Mario Sergio Cortella e Breno Cortella "Papo de Família".
      </p>
    </div>
  </div>
  <div class="conteudo-card">
    <h2 class="titulo-livro">Guia do Mestre - D&D</h2>
    <p class="autor-livro">Autor: Fulano de Tal</p>
    <p class="status-livro disponivel">Disponível</p>
    <button class="btn-reservar">Reservar</button>
  </div>
</div>

<script>
  const favoritoBtn = document.querySelector('.btn-favorito');

  favoritoBtn.addEventListener('click', function() {
    // alterna a classe .clicked
    this.classList.toggle('clicked');

    // alterna o ícone de preenchido/vazio
    const icon = this.querySelector('i');
    if (icon.classList.contains('fa-regular')) {
      icon.classList.remove('fa-regular');
      icon.classList.add('fa-solid');
    } else {
      icon.classList.remove('fa-solid');
      icon.classList.add('fa-regular');
    }

    // remove o efeito mágico após um tempo (opcional)
    setTimeout(() => {
      this.classList.remove('clicked');
    }, 300); // 300ms
  });


  const card = document.querySelector('.card-livro');

  card.addEventListener('mousemove', (e) => {
    const rect = card.getBoundingClientRect();
    const x = e.clientX - rect.left;
    const y = e.clientY - rect.top;
    const centerX = rect.width / 2;
    const centerY = rect.height / 2;
    const rotateX = ((y - centerY) / centerY) * 5; // 5 graus no eixo X
    const rotateY = ((x - centerX) / centerX) * 5; // 5 graus no eixo Y
    card.style.transform = `rotateX(${-rotateX}deg) rotateY(${rotateY}deg)`;
  });

  card.addEventListener('mouseleave', () => {
    card.style.transform = "rotateX(0) rotateY(0)";
  });


  // const card = document.querySelector('.card-livro');
  // card.addEventListener('mousemove', (e) => {
  //   const rect = card.getBoundingClientRect();
  //   const x = e.clientX - rect.left;
  //   const y = e.clientY - rect.top;
  //   const centerX = rect.width / 2;
  //   const centerY = rect.height / 2;
  //   const rotateX = ((y - centerY) / centerY) * 5; // até 5 graus
  //   const rotateY = ((x - centerX) / centerX) * 5;
  //   card.style.transform = `rotateX(${-rotateX}deg) rotateY(${rotateY}deg)`;
  // });
  // card.addEventListener('mouseleave', () => {
  //   card.style.transform = "rotateX(0) rotateY(0)";
  // }); 
</script>



</html>