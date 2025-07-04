<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Desenvolvedores</title>
    <link rel="stylesheet" href="../../../public/css/usuario/devs.css">

</head>

<body>
    
    <main id="main-devs">
        <section>
            <link href="https://fonts.googleapis.com/css2?family=Bai+Jamjuree:wght@400;600;700&display=swap" rel="stylesheet">
            <div class="logo_dev">
                <p>DESENVOLVEDORES</p>
                <h1>DE SISTEMA</h1>
            </div>
        </section>

        <section class="cards-grid">
            <?php for ($i = 0; $i < 10; $i++): ?>
                <?php include "../../../public/components/card_devs/card_devs.php"; ?>
            <?php endfor; ?>
        </section>
    </main>

</body>

</html>