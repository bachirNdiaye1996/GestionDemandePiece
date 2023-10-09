
<?php
    include 'connexion.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <!-- App favicon -->
    <link rel="shortcut icon" href="./image/iconOnglet.png" />
    <style>
        body{
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100hv;
            background-color: #23242a;
        }

        /* Pour la page index.php*/

        *
        {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Poppins',sans-serif;
        }

        .box{
            position: relative;
            width: 380px;
            height: 420px;
            border-radius: 8px;
            margin-top: 170px;
            background: #1c1c1c;
            overflow: hidden;
        }

        .box::before{
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 380px;
            height: 420px;
            background: linear-gradient(0deg,transparent,#45f3ff,#45f3ff);
            transform-origin: bottom right;
            animation: animat 6s linear infinite;
        }

        .box::after{
            content: '';
            position: absolute;
            top: -50%;
            left: -50%;
            width: 380px;
            height: 420px;
            background: linear-gradient(0deg,transparent,#45f3ff,#45f3ff);
            transform-origin: bottom right;
            animation: animat 6s linear infinite;
            animation-delay: -3s;
        }

        @keyframes animat{
            0%
            {
                transform: rotate(0deg);
            }
            100%
            {
                transform: rotate(360deg);
            }
        }

        .form{
            position: absolute;
            z-index: 10;
            inset: 2px;
            background: #1c1c1c ;
            border-radius: 8px;
            padding: 50px 40px;
            display: flex;
            flex-direction: column;
        }

        .form h2{
            color: #45f3ff;
            font-weight: 520px;
            text-align: center;
            letter-spacing: 0.1em;
        }

        .inputBox{
            position: relative;
            width: 300px;
            margin-top: 35px;
        }

        .inputBox input{
            position: relative;
            width: 100%;
            padding: 20px 10px 10px;
            background: transparent;
            border: none;
            outline: none;
            color: #23242a;
            font-size: 1em;
            letter-spacing: 0.05em;
            z-index: 10;
        }

        .inputBox span{
            position: absolute;
            left: 0;
            padding: 20px 0px 10px;
            font-size: 1em;
            color: #8f8f8f;
            pointer-events: none;
            letter-spacing: 0.05em;
            transition:0.5s;
        }

        .inputBox input:valid ~ span,
        .inputBox input:focus ~ span{
            color: #45f3ff;
            transform: translateX(-10px) translateY(-34px);
            font-size: 0.75em;
        }

        .inputBox i{
            position: absolute;
            left: 0;
            bottom: 0;
            width: 100%;
            height: 1px;
            background: #45f3ff;
            border-radius: 4px;
            transition: 0.5s;
            pointer-events: none;
            z-index: 9;
        }

        .inputBox input:valid ~ i,
        .inputBox input:focus ~ i{
            height: 44px;
        }

        .link{
            display: flex;
            justify-content: space-between;
        }

        .link a{
            margin: 15px;
            margin-left:70%;
            font-size: 0.85em;
            color: #8f8f8f;
            text-decoration: none;
        }

        .link a:hover,
        .link a:nth-child(2)
        {
            color: #45f3ff;
        }

        input[type='submit']{
            border: none;
            outline: none;
            background: #45f3ff;
            padding: 11px 25px;
            width: 150px;
            margin-top: 10px;
            border-radius: 4px;
            font-weight: 600;
            cursor: pointer;
        }

        input[type='submit']:active{
            opacity: 0.8;
        }
        /* Fin */
    </style>
    <title>METAL AFRIQUE</title>
</head>
<body>
    <div class="box">
        <div class="form">
            <h2>METAL AFRIQUE</h2>
            <form action="" method="POST">
                <div class="inputBox">
                    <input type="text" name="username">
                    <span>Nom d'utilisateur</span>
                    <i></i>
                </div>
                <div class="inputBox">
                    <input type="password" name="password">
                    <span>Mot de passe</span>
                    <i></i>
                </div>
                <div class="buttom">
                    <input type="submit" value="SE CONNECTER" name="valide">
                </div>
            </form>
        </div>
    </div>
</body>
</html>