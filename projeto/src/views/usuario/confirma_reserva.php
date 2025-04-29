<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../../../public/css/confirma_reserva.css">
    
</head>
<body>
    <?php
        include "../../../public/components/header/header.php"
    ?>
  <h1>Confirmação do Pedido de Reserva</h1>

  
<table>
  <tr>
    <th>Pedido de reserva</th>
    <th>Informações do bibliográficas</th>
  </tr>
  <tr>
    <td>Nome do Solicitante : Marlon Oliveira</td>
    <td>Nome do livro : Simpósio Barreado</td>
  </tr>
  <tr>
    <td>Data de empréstimo : 07/07/2024</td>
    <td>Autor : Dante Mendonça</td>
  </tr>
  <tr>
    <td>Data de devolução : 12/07/2024</td>
    <td>Código : 7895668</td>
  </tr>
  <tr>
    <td>E-mail : devbrabo123@gmail.com</td>
    <td>Páginas : 243</td>
  </tr>
  <tr>
    <td></td>
    <td>Editora : Club Litterario</td>
  </tr>
  <tr>
    <td></td>
    <td>Categoria : Culinária</td>
  </tr>
</table>
 
<h2>ㅤ

</h2>
<h3>ㅤ

</h3>
<h4>ㅤ

</h4>
<h5>ㅤ

</h5>
<h6>ㅤ

</h6>
<h7>ㅤ

</h7>
<h8>ㅤ

</h8>
<h9>ㅤ

</h9>
<h10>ㅤ

</h10>








    
<div class="button-fixed">
    <button class="circle-btn" onclick="confirmarReserva()" title="Confirmar">✓</button>
    <button class="circle-btn" title="Cancelar">X</button>
  </div>

  <div id="mensagem" class="mensagem">Reserva confirmada</div>

  <script>
    function confirmarReserva() {
      const msg = document.getElementById('mensagem');
      msg.style.display = 'block';
      setTimeout(() => {
        msg.style.display = 'none';
      }, 3000);
    }
  </script>

    <?php
        include "../../../public/components/footer/footer.php"
    ?>
    
</body>
</html>