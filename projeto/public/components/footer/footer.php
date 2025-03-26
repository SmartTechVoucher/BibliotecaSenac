<?php
require "../../../config/constantes.php"


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="<?php echo $URLBASE?>/public/css/footer.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
</head>

<body>
    <div>
        <footer>
            <div class="logo-senac">
                <img src="<?php echo $URLBASE?>/public/assets/img/logo-senac.svg" alt="Logo Senac" id="logo-senac">
            </div>
            
            <div class="livro">
                <img src="/public/livro.svg" alt="Livro icon">
                <p>Senac MS Copyright © <br>
                    2024. Todos os Direitos Reservados</p>
            </div>

            <div class="textos">
                <div class="contato">
                    <p>
                        Fale conosco
                        <br>
                        Central de
                        <br>
                        Atendimento
                        <br>
                        (67) 3312-6260
                    </p>
                </div>
                <div class="email">
                    <p>
                        Email:
                        <br>
                        atendimento@ms.senac.br
                        <br>
                        Sugestões, dúvidas
                        elogios ou críticas
                        
                    </p>
                </div>
                <div class="redes">
                    <p>
                        Siga-nos
                    </p>
                    <div class="social-icons">
                        <a href="#"><img src="../../assets/icons/Facebook.svg" alt="Facebook"></a>
                        <a href="#"><img src="../../assets/icons/Instagram.svg" alt="Instagram"></a>
                        <a href="#"><img src="../../assets/icons/LinkedIn.svg" alt="LinkedIn"></a>
                        <a href="#"><img src="../../assets/icons/Twitter.svg" alt="Twitter"></a>
                        <a href="#"><img src="../../assets/icons/WhatsApp.svg" alt="WhatsApp"></a>
                        <a href="#"><img src="../../assets/icons/YouTube.svg" alt="YouTube"></a>
                      </div>
                </div>
            </div>
        </footer>
    </div>

</body>

</html>