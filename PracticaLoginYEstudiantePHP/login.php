<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">

	<title>Login</title>
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<link rel="stylesheet" href="css/bootstrap.min.css">
	<link rel="stylesheet" href="fonts/style.css">
	<link rel="stylesheet" href="css/estilosDev.css">
	<script type="text/javascript" language="javascript" src="general_repository/my_js/login.js"></script>
</head>
<body>
	
	<section class="jumbotron">
			<div class="container">
				<!--cuerpo del encabezado-->
				<div class="row col-xs-12">
					<br>
				</div>
				<div class="row col-xs-12 t1 text-center">
					<img src="imagenes/header/loginlogo.png" class="" alt="fotoheader" id="fotoheader">
				</div>
			<!--con (show-tamaño se muestra contenido y con (hidden-tamaño se oculta el contenido))-->
			
				
				<div class="clearfix"></div>
					<!--Fin del cuerpo-->
				</div>
			</div>
		</section>

	<!--texto abajo-->
	<div class="container col-xs-6 espacio">
		<div class="panel panel-success">
 	 <div class="panel-heading">
    		<h3 class="panel-title">Login...</h3>
  	</div>
  		<div class="panel-body">
			    <form role="">
			  		<div class="form-group">
			    		<label for="ejemplo_email_1">Usuario</label>
			    <input type="text" class="form-control" id="usuario" name="usuario"
			           placeholder="Introduce tu usuario">
			  </div>
			  <div class="form-group">
			    <label for="ejemplo_password_1">Contraseña</label>
			    <input type="password" class="form-control" id="contrasena" 
			           placeholder="Contraseña" name="contrasena">
			  </div>
			 
			  <button class="btn btn-primary btn-lg" onclick='fncValidarUsuario(); return false;'>Ingresar</button>
			</form>
  		</div>
</div>
	</div>

	<div class="clearfix">
		
	</div>
	<!--Espacios-->
	<br>
	<br>
</body>
	<script src="js/jquery.js"></script>
	<script src="js/bootstrap.min.js"></script>
	<script src="js/arriba.js"></script>
	<footer class="footer text-center">
			<div class="container col-xs-12">
				<p class="text-center">Copyright 2015 Grupo # 9</p>
				<p class="text-center">| All Rights Reserved | Powered by <cite>Grupo # 9.</cite> </p>
			</div>
			<div class="clearfix"></div>

</footer>
</html>