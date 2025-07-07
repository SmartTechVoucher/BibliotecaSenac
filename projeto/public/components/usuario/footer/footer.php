<?php
require(__DIR__ . '/../../../../config/constantes.php');


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="<?php echo $URLBASE?>/public/css/components/footer.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400&display=swap" rel="stylesheet">
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&display=swap"
        rel="stylesheet">
</head>

<body>
    <div>
        <footer>
            <div class="logo-senac">
                <img src="<?php echo $URLBASE?>/public/assets/img/logosenac.svg" fill="white" alt="Logo Senac" id="logo-senac">
            </div>
            
            <div class="livro-brilhante">
                <img src="<?php echo $URLBASE ?>/public/assets/img/livro-brilhante.svg" alt="Livro icon">
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
                        <br>
                    </p>
                    <a href="<?php echo $URLBASE?>/src/views/usuario/desenvolvedores.php" class="link-desenvolvedores">Desenvolvedores</a>
                </div>
                <div class="redes">
                    <p>
                        Siga-nos
                    </p>
                    <div class="social-icons">
                        <ul>
                            <li><a href="https://www.facebook.com/senacmsoficial#"><img src="<?php echo $URLBASE ?>/public/assets/icons/Facebook.svg" alt="Facebook"></a></li>
                            <li><a href="https://www.instagram.com/senac_ms/"><img src="<?php echo $URLBASE ?>/public/assets/icons/Instagram.svg" alt="Instagram"></a></li>
                            <li><a href="https://www.linkedin.com/company/senacms"><img src="<?php echo $URLBASE ?>/public/assets/icons/LinkedIn.svg" alt="LinkedIn"></a></li>
                            <li> <a href="https://api.whatsapp.com/send?phone=5567999492638"><img src="<?php echo $URLBASE ?>/public/assets/icons/WhatsApp.svg" class="img-whatsaap" alt="WhatsApp"></a></li>
                            <li><a href="https://www.youtube.com/user/senacms"><img src="<?php echo $URLBASE ?>/public/assets/icons/YouTube.svg" alt="YouTube"></a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </footer>
    </div>

</body>

</html>