function show_date()
	{//modificado el rango de fechas
	validate_date(document.forms['example']);
	}

function show_cliente(cod,num)
	{//mostrando tabla
	document.getElementById("div_tabla").innerHTML="";
	document.getElementById('div_msj').innerHTML='';
	if(cod==-1)
		{
		cod=document.getElementById('cod_cliente').value;
		}
	div= document.getElementById("div_cliente");
	div.innerHTML="";
	str_param = "cod="+cod+"&o_cliente=1";
	ajax_dynamic_div("POST",'store_alerts_tabla.php',str_param,div,false);
	show_ver(num);
	}

function show_ver(op)
	{
	cust=document.getElementById('idcustomer').value;
	if(cust!=-1)
		{
		document.getElementById('div_imprimir').style.visibility='hidden';
		document.getElementById('div_imprimir').style.display='none';
		document.getElementById('div_opciones').style.visibility='visible';
		document.getElementById('div_opciones').style.display='inline';
		document.getElementById('divver_0').style.background = "#E0F8E0";
		document.getElementById('divver_1').style.background = "#E0F8E0";
		document.getElementById('divver_2').style.background = "#E0F8E0";
		document.getElementById('divver_3').style.background = "#E0F8E0";
		if (op==0)
			{
			document.getElementById('divver_0').style.background = "#81F79F";
			document.getElementById('op').value=0;
			}
		else if (op==1)
			{
			if(cust==0)
				{
				msj="<font color=#FF0000><br/><b><big><big><big>Ingrese el Codigo del Cliente para Editar</big></big></big></b><br/><br/></font>";
				document.getElementById('div_msj').innerHTML=msj;
				document.getElementById('divver_0').style.background = "#81F79F";
				document.getElementById('op').value=0;
				}
			else
				{
				document.getElementById('divver_1').style.background = "#81F79F";
				document.getElementById('op').value=1;
				}
			}
		else if (op==2)
			{
			if(cust==0)
				{
				msj="<font color=#FF0000><br/><b><big><big><big>Ingrese el Codigo del cliente para agregar una nueva Alerta</big></big></big></b><br/><br/></font>";
				document.getElementById('div_msj').innerHTML=msj;
				document.getElementById('divver_0').style.background = "#81F79F";
				document.getElementById('op').value=0;
				}
			else
				{
				document.getElementById('divver_2').style.background = "#81F79F";
				document.getElementById('op').value=2;
				}
			}
		else if (op==3)
			{
			document.getElementById('divver_3').style.background = "#81F79F";
			document.getElementById('op').value=3;
			document.getElementById('div_imprimir').style.visibility='visible';
			document.getElementById('div_imprimir').style.display='inline';
			}
		show_table();
		}
	else
		{
		document.getElementById("div_tabla").innerHTML="";
		document.getElementById('div_sector').innerHTML="";
		document.getElementById('div_opciones').style.visibility='hidden';
		document.getElementById('div_opciones').style.display='none';
		}
	}
function show_table()
	{
	div=document.getElementById("div_tabla");
	$(div).html("<img src='images/loading.gif' align='center'>");
	op=document.getElementById('op').value;
	n_ale=document.getElementById('no_alert').value;
	code=document.getElementById('cod_cliente').value;
	addr=document.getElementById('idaddress').value;
	cust=document.getElementById('idcustomer').value;
	no_1=document.getElementById('no_1').value;
	no_2=document.getElementById('no_2').value;
	day1=document.getElementById('date1').value;
	day1 = day1.substring(6,10)+day1.substring(3,5)+day1.substring(0,2);
	day2=document.getElementById('date2').value;
	day2 = day2.substring(6,10)+day2.substring(3,5)+day2.substring(0,2);
	str_param="addr="+addr+"&no_1="+no_1+"&no_2="+no_2+"&day1="+day1+"&day2="+day2+"&n_ale="+n_ale+"&cust="+cust+"&code="+code+"&op="+op+"&ver_tabla=1";
	ajax_dynamic_div("POST",'store_alerts_tabla.php',str_param,div,false);
	}
function grabar()
	{
	document.getElementById('div_msj').innerHTML="";
	div=document.getElementById("div_update");
	$(div).html("<img src='images/loading.gif' align='center'>");
	day1=document.getElementById('date3').value;
	day1 = day1.substring(6,10)+day1.substring(3,5)+day1.substring(0,2);
	tipo=document.getElementById('tipo').value;
	carr=document.getElementById('idcarrier').value;
	modo=document.getElementById('modo').value;
	obse=document.getElementById('obser').value;
	addr=document.getElementById('idaddress').value;
	str_param="addr="+addr+"&day1="+day1+"&carr="+carr+"&tipo="+tipo+"&modo="+modo+"&obse="+obse+"&in_alerta=1";
	ajax_dynamic_div("POST",'store_alerts_update.php',str_param,div,false);
	show_ver(0);
	}
	
function show_editar(id, code)
	{
	document.getElementById('no_alert').value=id;
	document.getElementById('cod_cliente').value=code;
	show_cliente(-1,1);
	}

function show_new(code)
	{
	document.getElementById('cod_cliente').value=code;
	show_cliente(-1,2);
	}

function editar()
	{
	document.getElementById('div_msj').innerHTML="";
	div=document.getElementById("div_update");
	$(div).html("<img src='images/loading.gif' align='center'>");
	day1=document.getElementById('date3').value;
	day1 = day1.substring(6,10)+day1.substring(3,5)+day1.substring(0,2);
	tipo=document.getElementById('tipo').value;
	carr=document.getElementById('idcarrier').value;
	modo=document.getElementById('modo').value;
	obse=document.getElementById('obser').value;
	addr=document.getElementById('idaddress').value;
	aler=document.getElementById('idalert').value;
	str_param="addr="+addr+"&aler="+aler+"&day1="+day1+"&carr="+carr+"&tipo="+tipo+"&modo="+modo+"&obse="+obse+"&up_alerta=1";
	ajax_dynamic_div("POST",'store_alerts_update.php',str_param,div,false);
	show_ver(0);
	}
function cancelar()
	{
	document.getElementById('cod_cliente').value="";
	show_cliente(-1,0);
	}
function show_delete(id, code, sect)
	{
	if (confirm('Usted esta a punto de ELIMINAR la Alerta No.'+id+' de '+code+' '+sect+'. ¿Desea Continuar?'))
		{ 
		div=document.getElementById('div_msj');
		$(div).html("<img src='images/loading.gif' align='center'> Eliminando...");
		parametros="aler="+id+"&anular=1";
		ajax_dynamic_div("POST","store_alerts_update.php",parametros,div,false);
		}
	show_ver(0);
	}

function show_imprimir(id)
	{
	document.getElementById('no_1').value=id;
	document.getElementById('no_2').value=id;
	show_ver(3);
	}
function borrar_imprimir()
	{
	document.getElementById('no_1').value="";
	document.getElementById('no_2').value="";
	show_ver(3);
	}
function limite_linea(Control, limite)
	{
	var iCont;
	var iLineas = 1;
	var iCaracter;

	if (event.keyCode ==13)
		{
		 for (iCont=0; iCont<Control.value.length; iCont++)
			{
			iCaracter=Control.value.charCodeAt(iCont);
			if (iCaracter==10)
				{
				iLineas++;
				if (iLineas==limite)
					{
					return false;
					}
				}
			}
		}	
	else{
	 return true;
	}
}

	