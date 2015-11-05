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
<script src="myscripts/jquery-1.4.4.js" type="text/javascript"></script>
<SCRIPT LANGUAGE="JavaScript" SRC="myscripts/librerias.js"></SCRIPT>
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
    		<h3 class="panel-title">Formulario de Ingreso Estudiante</h3>
  	</div>
  		<div class="panel-body">
			  <form >
				
			  	
			 	<div class="row">
			 		<div class="form-group col-xs-6">
			    		<label for="ejemplo_password_1">Nombre</label>
			    		<input type="text" class="form-control" id="nombre" 
			         placeholder="Ingrese su Nombre" name="nombre" required>
			     	</div>
			     	<div class="form-group col-xs-6">
			    		<label for="ejemplo_password_1">Apellido</label>
			    		<input type="text" class="form-control" id="apellido" 
			           	placeholder="Ingrese su Apellido" name="apellido" required>
			  		</div>
			 	</div>
			  	<div class="row">
			  		<div class="form-group col-xs-6">
			    <label for="ejemplo_password_1">Sexo</label>
			    <input type="text" class="form-control" id="sexo" 
			           placeholder="Sexo" name="sexo" required>
			  </div>
			   <div class="form-group col-xs-6">
			    <label for="ejemplo_password_1">Date</label>
			    <input type="date" class="form-control" id="fecha" 
			            name="fecha" >
			  </div>
			  	</div>
			
			   

			 
			  <button class="btn btn-primary btn-lg" onclick='fncGuardarAlumno(); return false;'>Ingresar</button>
<div id="div_detalle" style="height:100%; overflow: scroll;" ></div>
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