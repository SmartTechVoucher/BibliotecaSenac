<?php
require(__DIR__ . '/../../../../config/constantes.php');


?>

<body>
    <div>
        <footer>
            <div class="logo-senac">
                <img src="<?php echo $URLBASE ?>/public/assets/img/logosenac.svg" fill="white" alt="Logo Senac" id="logo-senac">
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