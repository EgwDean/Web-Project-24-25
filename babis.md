<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redirect to Instagram</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body, html {
            height: 100%;
            font-family: 'Arial', sans-serif;
            background: url('https://imgs.search.brave.com/ZAoOdcvjLPiMN979kF1S5mmXrLCap1T5majMHgurY3k/rs:fit:860:0:0:0/g:ce/aHR0cHM6Ly9pMS5z/bmRjZG4uY29tL2Fy/dHdvcmtzLWN1aVFa/QXprN3Ftb1VMb3ot/UnJBVTN3LXQ1MDB4/NTAwLmpwZw') no-repeat center center fixed;
            background-size: cover;
        }

        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100%;
        }

        button {
            font-size: 24px;
            padding: 20px 40px;
            background-color: #ff4500;
            color: white;
            border: none;
            border-radius: 10px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #ff6347;
        }
    </style>
</head>
<body>
    <div class="container">
        <button id="redirectButton">Βλεπει κινεζικα μικι μαο</button>
    </div>

    <script>
        document.getElementById('redirectButton').addEventListener('click', function() {
            window.location.href = 'https://www.instagram.com/mpampis_anastasiou/';
        });
    </script>
</body>
</html>
