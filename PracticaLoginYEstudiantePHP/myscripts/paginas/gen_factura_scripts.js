function cancelar()
	{//cancelar
	document.getElementById("div_anular1").style.visibility="visible";
	document.getElementById("div_anular2").style.visibility="hidden";
	document.getElementById("div_anular3").style.visibility="hidden";
	}
function show_date()
	{//modificado el rango de fechas
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
	seri=document.getElementById('serie').value;
	esta=document.getElementById('estado').value;
	document.getElementById("div_nc").style.visibility="hidden";
	if(esta=="Impresa")
		{
		document.getElementById("div_nc").style.visibility="visible";
		}
	str_param = "day2="+day2+"&day1="+day1+"&seri="+seri+"&esta="+esta+"&o_tabla=1";
	ajax_dynamic_div("POST",'gen_factura_get.php',str_param,div,false);
	}
function nuevo()
	{//mostramos el modulo de anular fact en masa
	document.getElementById("div_anular1").style.visibility="hidden";
	document.getElementById("div_anular2").style.visibility="visible";
	document.getElementById("div_anular3").style.visibility="visible";
	}
function eliminar_factura(num)
	{
	serie=document.getElementById('serie_'+num).value;
	numero=document.getElementById('number_'+num).value;
	invoice=document.getElementById('idinvoice_'+num).value;
	if (confirm('Usted esta a punto de ELIMINAR la factura '+serie+' '+numero+'. ¿Desea Continuar?'))
		{ 
		div=document.getElementById('div_msj');
		$(div).html("<img src='images/loading.gif' align='center'> Anulando...");
		parametros="seri="+serie+"&numb="+numero+"&num="+num+"&inv="+invoice+"&anular=1";
		ajax_dynamic_div("POST","gen_factura_update.php",parametros,div,false);
		show_table();
		}
	}
	
function asignar_nc(num)
	{
	serie=document.getElementById('serie_'+num).value;
	numero=document.getElementById('number_'+num).value;
	invoice=document.getElementById('idinvoice_'+num).value;
	nc_serie=document.getElementById('nc_serie').value;
	nc_number=document.getElementById('nc_number').value;
	day4=document.getElementById('date4').value;
	day4 = day4.substring(6,10)+day4.substring(3,5)+day4.substring(0,2);
	if (confirm('Usted esta a punto de Asignarle a la factura '+serie+' '+numero+' la nota de credito '+nc_serie+' '+nc_number+' con fecha '+document.getElementById('date4').value+'. ¿Desea Continuar?'))
		{ 
		div=document.getElementById('div_msj');
		$(div).html("<img src='images/loading.gif' align='center'> Asignando Nota de Credito...");
		parametros="nc_serie="+nc_serie+"&nc_number="+nc_number+"&day4="+day4+"&inv="+invoice+"&nc=1";
		ajax_dynamic_div("POST","gen_factura_update.php",parametros,div,false);
		show_table();
		}
	}
function anular_factura(num)
	{
	serie=document.getElementById('serie_'+num).value;
	numero=document.getElementById('number_'+num).value;
	invoice=document.getElementById('idinvoice_'+num).value;
	if (confirm('Usted esta a punto de ANULAR la factura '+serie+' '+numero+'. ¿Desea Continuar?'))
		{ 
		div=document.getElementById('div_msj');
		$(div).html("<img src='images/loading.gif' align='center'> Anulando...");
		parametros="seri="+serie+"&numb="+numero+"&num="+num+"&inv="+invoice+"&anulado=1";
		ajax_dynamic_div("POST","gen_factura_update.php",parametros,div,false);
		show_table();
		}
	}

function confirmar_factura(num)
	{
	serie=document.getElementById('serie_'+num).value;
	numero=document.getElementById('number_'+num).value;
	invoice=document.getElementById('idinvoice_'+num).value;
	if (confirm('Usted esta a punto de CONFIRMAR la factura '+serie+' '+numero+'. ¿Desea Continuar?'))
		{ 
		div=document.getElementById('div_msj');
		$(div).html("<img src='images/loading.gif' align='center'> Confirmando...");
		parametros="seri="+serie+"&numb="+numero+"&num="+num+"&inv="+invoice+"&confirmar=1";
		ajax_dynamic_div("POST","gen_factura_update.php",parametros,div,false);
		show_table();
		}
	}
	
function confirmar_todo()
	{
	if (confirm('Usted esta a punto de INICIAR el proceso de confirmacion de todas las facturas mostradas en estado Emitido ¿Desea Continuar?'))
		{
		band=0;s_inv="0";
		div=document.getElementById('div_msj');
		$(div).html("<img src='images/loading.gif' align='center'> Grabando...");
		ultimo_x=document.getElementById('ultimo_x').value;
		for(i=1;i<=ultimo_x;i++)
			{
			if(document.getElementById('check_'+i).checked)
				{
				if(document.getElementById('check_'+i).value>0)
					{
					inv=document.getElementById('idinvoice_'+i).value;
					s_inv=s_inv+","+document.getElementById('idinvoice_'+i).value;
					band++;
					}
				}
			if(band>=100)
				{
				parametros="inv="+s_inv+"&confirmar=1";
				ajax_dynamic_div("POST","gen_factura_update.php",parametros,div,false);
				s_inv="0";
				band=0;
				}
			}
		parametros="inv="+s_inv+"&confirmar=1";
		ajax_dynamic_div("POST","gen_factura_update.php",parametros,div,false);
		show_table();
		}
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