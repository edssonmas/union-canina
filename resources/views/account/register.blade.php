<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://fonts.googleapis.com/css?family=Roboto|Ubuntu" rel="stylesheet">
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Union Canina: Crear cuenta</title>
    <style>
        @media only screen and (max-width: 600px) {
            
        }

        @media only screen and (min-width: 600px) {
        
        }
        @media only screen and (min-width: 768px) {
        
        }
        .login{
            height: 380;
            width: 300;
            background: white;
            margin: 0 auto;
            border: 1px solid #d9d9d9;
            border-radius: 7px;
            margin-top: 100px;
            text-align: center;
            padding-top: 20px;
        }
        .formulario{
            margin-top: 870px;
            margin: 0 auto;
        }
        .formulario p{
             font-family: 'Ubuntu',
            sans-serif;
            font-weight: bold;
            font-size: 13px;
            margin-bottom: 0;
        }
        .textbox{
            width: 90%;  
            height: 30px;
            margin-left:15px;
            margin-bottom: 10px;
        }
        .btn{
            width: 90%;
            
        }
        .registrar{
            height: 50;
            width: 300;
            background: white;
            margin: 0 auto;
            border: 1px solid #d9d9d9;
            border-radius: 7px;
            margin-top: 40px;
            text-align: center;
            padding-top: 10px;
            font-size: 14px;
              font-family: 'Ubuntu',
            sans-serif;
        }
        .logo{
            position: relative;
            height: 50;
            width: 300;
            margin: 0 auto;
            top: 40px;
            text-align: center;
            padding-top: 10px;
        }
        .titulo{
            position: relative;
            top: 80px;
              font-family: 'Ubuntu',
            sans-serif;
            color: #4A4A4A;
            
        }
    </style>
    <script src='https://www.google.com/recaptcha/api.js'></script>
</head>
<body style="background-color:#F2FEFF;">
    <div class="container">
        <div class="logo">
            <a href="{{ url('/') }}">
                <img src="../img/logo-black.png" alt="" height="50" width="50">
            </a>
        </div>
        <h4 class="titulo text-center">Crear cuenta en Union Canina</h4>
        <div class="login">
            <div class="formulario">
                <form action="{{ url('registrar') }}" method="post">
                    {{ csrf_field() }}
                    <p>Nombre</p>
                    <input type="text" name="nombre" class="form-control textbox">
                     <p>Apellido paterno</p>
                    <input type="text" name="apPaterno" class="form-control textbox">
                     <p>Apellido materno</p>
                    <input type="text" name="apMaterno" class="form-control textbox">
                     <p>Correo electrónico</p>
                    <input type="text" name="correo" class="form-control textbox">
                     <p>Contraseña</p>
                    <input type="text" name="password" class="form-control textbox">

                    <button class="btn btn-primary">Crear cuenta</button>
                </form>
            </div>
            <div class="registrar">
                <p>¿Ya tienes cuenta? <a href="{{ url('/public/login') }}">Inicia sesion</a></p>
            </div>
        </div>
    </div>


    <script src="../js/popper.min.js"></script>
    <script src="../js/jquery-3.3.1.slim.min.js"></script>
    <script src="../js/bootstrap.js"></script>
</body>
</html>