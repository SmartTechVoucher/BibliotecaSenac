<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Card D&D - Guia do Mestre</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #f0f0f0;
            padding: 50px;
            font-family: Arial, sans-serif;
        }

        .card-livro {
            width: 300px;
            height: 450px;
            position: relative;
            border-radius: 15px;
            overflow: hidden;
            background: white;
            margin: 0 auto;
        }

        .capa-livro {
            width: 100%;
            height: 60%;
            object-fit: cover;
            position: relative;
        }

        .curva-container {
            position: absolute;
            bottom: 40%;
            left: 0;
            width: 100%;
            height: 60px;
            overflow: hidden;
            z-index: 10;
        }

        .curva {
            position: absolute;
            bottom: -2px;
            left: 0;
            width: 100%;
            height: 60px;
        }

        .container-info {
            position: absolute;
            bottom: 0;
            left: 0;
            right: 0;
            height: 40%;
            background: white;
            padding: 25px 20px 20px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .titulo-livro {
            font-size: 18px;
            font-weight: bold;
            color: #333;
            margin-bottom: 8px;
        }

        .autor-livro {
            font-size: 14px;
            color: #666;
            margin-bottom: 2px;
        }

        .autor-nome {
            color: #999;
            font-size: 13px;
        }

        .status-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 15px;
        }

        .status-livro {
            font-size: 14px;
            color: #e74c3c;
            font-weight: 500;
        }

        .btn-ver-livro {
            background: #2c3e50;
            color: white;
            border: none;
            padding: 8px 16px;
            border-radius: 6px;
            font-size: 13px;
            cursor: pointer;
            font-weight: 500;
            transition: background 0.3s ease;
        }

        .btn-ver-livro:hover {
            background: #34495e;
        }
    </style>
</head>
<body>
    <div class="card-livro">
        <img 
            src="https://images-na.ssl-images-amazon.com/images/S/compressed.photo.goodreads.com/books/1714763387i/212703311.jpg"
            alt="Capa do livro"
            class="capa-livro">
            
        <div class="curva-container">
            <svg class="curva" viewBox="0 0 300 60" xmlns="http://www.w3.org/2000/svg">
                <defs>
                    <linearGradient id="grad" x1="0" y1="0" x2="0" y2="1">
                        <stop offset="0%" stop-color="#2c3e50"/>
                        <stop offset="100%" stop-color="#34495e"/>
                    </linearGradient>
                </defs>
                <path d="M0,60 C75,10 225,10 300,60 L300,60 L0,60 Z" fill="url(#grad)"/>
            </svg>
        </div>

        <div class="container-info">
            <div>
                <h3 class="titulo-livro">Guia do Mestre - D&D</h3>
                <p class="autor-livro">Autor:</p>
                <p class="autor-nome">(Nome do autor)</p>
            </div>
            
            <div class="status-container">
                <p class="status-livro">Disponível</p>
                <button class="btn-ver-livro">Ver livro</button>
            </div>
        </div>
    </div>
</body>
</html>