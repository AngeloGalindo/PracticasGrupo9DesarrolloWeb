function fncValidarUsuario()
{
	usuario=document.getElementById('usuario').value;
	contrasena=document.getElementById('contrasena').value;
	if(usuario == '')
		alert('Favor Ingrese Usuario');
	else if(contrasena == '')
		alert('Favor Ingrese Contraseña');
	else
		fncConectarUsuario(usuario,contrasena);
}

function fncConectarUsuario(usuario,contrasena)
{
	if(usuario == 'root' && contrasena =='123')
		location.href ='principal.php';
	else
		alert('Usuario o Contraseña Invalida');
}

function fncGuardarAlumno()
{
	div=document.getElementById("div_detalle");
	$(div).html("<img src='images/loading.gif' align='center'>");
	
	nombre=document.getElementById('nombre').value;
	apellido=document.getElementById('apellido').value;
	sexo=document.getElementById('sexo').value;
	fecha=document.getElementById('fecha').value;
	str_param="nombre="+nombre+"&apellido="+apellido+"&sexo="+sexo+"&fecha="+fecha;
	ajax_dynamic_div("POST",'principal_update.php',str_param,div,false);
	$(div).html("<div><br/><br/>"+$(div).html()+'</div>');

	}