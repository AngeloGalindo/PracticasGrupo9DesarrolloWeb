function show_date()
	{//modificado el rango de fechas
	}

function change_serie(op)
	{//buscar canales de distribucion y numero de correlativo
	parametros="serie="+document.forms['example'].serie.value+"&op="+op+"&o_serie=1";
	ajax_dynamic_div("POST","integrate_invoice_mass_tabla.php",parametros,document.getElementById('div_mas_info'),false);
	show_tabla();
	}
	
function show_tabla()
	{
	document.getElementById("div_boton1").innerHTML="<br/><br/>";
	document.getElementById("div_boton2").innerHTML="<br/><br/>";
	div=document.getElementById("div_tabla");
	$(div).html("<img src='images/loading.gif' align='center'>");
	day1=document.getElementById('date1').value;
	day1 = day1.substring(6,10)+day1.substring(3,5)+day1.substring(0,2);
	day2=document.getElementById('date2').value;
	day2 = day2.substring(6,10)+day2.substring(3,5)+day2.substring(0,2);
	if (document.forms['example'].op_ver[0].checked)
		ver = 1;
	else if (document.forms['example'].op_ver[1].checked)
		ver = 2;
	else if (document.forms['example'].op_ver[2].checked)
		ver = 3;
	chan=document.getElementById('channel').value;//
	seri=document.getElementById('serie').value;
	str_param="chan="+chan+"&seri="+seri+"&day1="+day1+"&day2="+day2+"&ver="+ver+"&o_tabla=1";
	ajax_dynamic_div("POST",'integrate_invoice_mass_tabla.php',str_param,div,false);
	}

function cancelar()
	{
	show_tabla();
	}
function operar_factura()
	{
	document.getElementById("div_boton1").innerHTML="<img src='loadingcircle.gif' align='center'> Verificando Facturas";
	document.getElementById("div_boton2").innerHTML=document.getElementById("div_boton1").innerHTML;
	serie_estimado=document.getElementById('serie').value;
	numero_estimado=document.getElementById('numero').value;
	ultimo_x=document.getElementById('ultimo_x').value;
	div=document.getElementById("div_msj");
	var i;x=1;band=0;sum_num="";
	for(i=1;i<ultimo_x;i++)
		{
		if (document.getElementById('check_'+i+'').checked)
			{
			if(x==1)
				sum_num=numero_estimado;
			else				
				sum_num=sum_num+","+numero_estimado;
			numero_estimado++;
			x++;
			}
		}
	str_param="serie="+serie_estimado+"&nums="+sum_num+"&o_verif=1";
	ajax_dynamic_div("POST",'sus_invoice_update.php',str_param,div,false);
	verif=document.forms['example'].verif.value;
	if(verif==0)
		Asignar_facturas();
	document.getElementById("div_boton1").innerHTML="<br/><br/>";
	document.getElementById("div_boton2").innerHTML="<br/><br/>";
	}
	
function Asignar_facturas()
	{
	document.getElementById("div_boton1").innerHTML="<img src='loadingcircle.gif' align='center'> Asignando Facturas";
	document.getElementById("div_boton2").innerHTML="<img src='loadingcircle.gif' align='center'> Asignando Facturas";
	ultimo_x=document.getElementById('ultimo_x').value;
	div=document.getElementById("div_update");
	serie=document.getElementById('serie').value;
	
	var i; str_id ='';
	for(i=1;i<ultimo_x;i++)
		{
		if (document.getElementById('check_'+i+'').checked)
			{
			susc=document.getElementById('idsusc_'+i).value;
			fday=document.getElementById('fecha_'+i).value;
			str_param="seri="+serie+"&fday="+fday+"&susc="+susc+"&o_facturar=1";
			ajax_dynamic_div("POST",'sus_invoice_update.php',str_param,div,false);
			document.getElementById('check_'+i+'').checked=false;
			}
		}
	// ajaxpost(document.forms['example'],<?php echo CurrentUserID();?>);	
	// change_serie(0);
	document.getElementById("div_boton1").innerHTML="<br/><br/>";
	document.getElementById("div_boton2").innerHTML="<br/><br/>";
	change_serie(serie);
	}
	
function check_items()
	{
	ultimo_x=document.getElementById('ultimo_x').value;
	band=0;
	for(i=1;i<ultimo_x;i++)
		{
		if(document.getElementById('check_'+i).checked)
			{
			band++;
			}
		}
	if(band>0)
		document.getElementById("div_boton1").innerHTML="<INPUT TYPE=BUTTON onClick='operar_factura()' VALUE=\"Asignar Facturas\"> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <INPUT TYPE=BUTTON onClick='cancelar()' VALUE=\"Cancelar\">";
	else
		document.getElementById("div_boton1").innerHTML="<br/><br/>";	
	document.getElementById("div_boton2").innerHTML=document.getElementById("div_boton1").innerHTML;
	}

function activar_all(obj_check)
	{
	ultimo_x=document.getElementById('ultimo_x').value;
	for(i=1;i<ultimo_x;i++)
		{
		if(document.getElementById('check_'+i+'').style.visibility!='hidden')
			{
			if (obj_check.checked)
				document.getElementById('check_'+i+'').checked = true;
			else
				document.getElementById('check_'+i+'').checked = false;
			}
		}
	check_items();
	}

function anular(idsusc, serie,numero)// cambio de fecha
	{
	if (!confirm('Usted esta a punto de ANULAR la factura '+serie+' '+numero+'. ¿Desea Continuar?'))
		{ 
		}
	else
		{
		div=document.getElementById("div_update");
		str_param="idanular="+idsusc+"&o_anular=1";
		ajax_dynamic_div("POST",'sus_invoice_update.php',str_param,div,false);
		change_serie(serie);
		}
	}