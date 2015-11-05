function cancelar()
	{//cancelar
	}
function show_date()
	{//modificado el rango de fechas
	validate_date(document.forms['example']);
	}
function renovar_sus(i)
	{
	eday=document.getElementById('end_date_son_'+i).value;
	eday = eday.substring(6,10)+eday.substring(3,5)+eday.substring(0,2);
	sday=document.getElementById('start_date_son_'+i).value;
	sday = sday.substring(6,10)+sday.substring(3,5)+sday.substring(0,2);
	tipo=document.getElementById('idtype_sus_son_'+i).value;
	olds=document.getElementById('old_sus_'+i).value;
	div=document.getElementById('div_updated');
	div2=document.getElementById('div_button_'+i);
	$(div2).html("<img src='images/loading.gif' align='center'>");
	parametros="eday="+eday+"&sday="+sday+"&tipo="+tipo+"&olds="+olds+"&i="+i+"&o_renovando=1";
	ajax_dynamic_div("POST","sus_renew_update.php",parametros,div,false);
	}
function grabar()
	{//grabando anuladas en masa
	day3=document.getElementById('date3').value;
	fday = day3.substring(6,10)+day3.substring(3,5)+day3.substring(0,2);
	fseri=document.getElementById('factserie').value;
	fact1=document.getElementById('fact1').value;
	fact2=document.getElementById('fact2').value;
	div=document.getElementById('div_msj');
	$(div).html("<img src='images/loading.gif' align='center'> Anulando en Masa...");
	parametros="fday="+fday+"&fact1="+fact1+"&fact2="+fact2+"&fseri="+fseri+"&anular_mass=1";
	ajax_dynamic_div("POST","gen_factura_update.php",parametros,div,false);
	cancelar();
	show_table();
	}
function show_table()
	{//mostramos datos del cliente
	day1=document.getElementById('date1').value;
	day1 = day1.substring(6,10)+day1.substring(3,5)+day1.substring(0,2);
	day2=document.getElementById('date2').value;
	day2 = day2.substring(6,10)+day2.substring(3,5)+day2.substring(0,2);
	document.getElementById("div_pres").innerHTML="";
	div=document.getElementById("div_det");
	div.innerHTML=" <img src='images/loading.gif' align='center'>  Generando...";
	canal=document.getElementById('canal').value;
	str_param = "day2="+day2+"&day1="+day1+"&canal="+canal+"&o_tabla=1";
	ajax_dynamic_div("POST",'sus_renew_get.php',str_param,div,false);
	}
function show_detalle_sus(id,i)
	{//mostramos datos del cliente
	document.getElementById("div_pres").innerHTML="";
	div=document.getElementById("masdivb"+i);
	div.innerHTML=" <img src='images/loading.gif' align='center'>  Generando...";
	str_param = "id="+id+"&o_detalle=1";
	ajax_dynamic_div("POST",'sus_renew_get.php',str_param,div,false);
	}

function activar_all(obj)
	{	
	ultimo_x=document.getElementById('ultimo_x').value;
	for(i=1;i<=ultimo_x;i++)
		{
		if (obj.checked)
			{
			if (document.getElementById('check_'+i+'').value > '0')
				{
				document.getElementById('check_'+i+'').checked = true;
				}
			}
		else
			{
			document.getElementById('check_'+i+'').checked = "";
			}
		}
	}