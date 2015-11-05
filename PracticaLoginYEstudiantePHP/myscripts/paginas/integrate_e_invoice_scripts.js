function show_tabla()
	{//mostrando tabla de ordenes
	document.getElementById("txtHint").innerHTML="";
	document.getElementById("txtHint2").innerHTML="";
	div=document.getElementById("div_tabla");
	$(div).html("<img src='images/loading.gif' align='center'>");
	day1=document.getElementById('date1').value;
	day1 = day1.substring(6,10)+day1.substring(3,5)+day1.substring(0,2);
	day2=document.getElementById('date2').value;
	day2 = day2.substring(6,10)+day2.substring(3,5)+day2.substring(0,2);
	chan=document.getElementById('canal').value;
	tipo=document.getElementById('tipo_codigo').value;
	if (document.forms['example'].op_ver[0].checked)
		ver = 1;
	else if (document.forms['example'].op_ver[1].checked)
		ver = 2;
	else if (document.forms['example'].op_ver[2].checked)
		ver = 3;
	else if (document.forms['example'].op_ver[3].checked)
		ver = 4;
	else if (document.forms['example'].op_ver[4].checked)
		ver = 5;
	else if (document.forms['example'].op_ver[5].checked)
		ver = 6;
	str_param="day1="+day1+"&day2="+day2+"&tipo="+tipo+"&chan="+chan+"&ver="+ver+"&ver_tabla=1";
	ajax_dynamic_div("POST",'integrate_e_invoice_tabla.php',str_param,div,false);
	}
	
function show_date()
	{// cambio de fecha
	validate_date(document.forms['example']);
	//show_tabla();
	}

function cancelar()
	{
	show_tabla();
	}

function change_check(obj_check, num)
	{
	ultimo_x=document.getElementById('ultimo_x').value;
	cant=0;
	for(i=1;i<ultimo_x;i++)
		{
		if(document.getElementById('check_'+i).checked)
			cant++;
		}
	if(cant>0)
		document.getElementById("txtHint").innerHTML="<INPUT TYPE=BUTTON onClick='cancelar()' VALUE=\"Cancelar\"> &nbsp;&nbsp;&nbsp;&nbsp; <INPUT TYPE=BUTTON onClick='operar_factura()' VALUE=\"Asignar Factura\">";
	else
		document.getElementById("txtHint").innerHTML="";
		
	document.getElementById("txtHint2").innerHTML=document.getElementById("txtHint").innerHTML;
	}

function operar_factura()
	{
	if (!confirm('Usted esta a punto de INICIAR el proceso de facturacion electronica. ¿Desea Continuar?'))
		{ 
		}
	else
		{
		band=0;s_iord="0";s_invo="0";
		div=document.getElementById('div_msj');
		$(div).html("<img src='images/loading.gif' align='center'> Grabando...");
		ultimo_x=document.getElementById('ultimo_x').value;
		for(i=1;i<ultimo_x;i++)
			{
			if(document.getElementById('check_'+i).checked)
				{
				if(document.getElementById('state_'+i).value=='Pendiente')
					{
					iord=document.getElementById('idorder_'+i).value;
					invo=document.getElementById('idinvoice_'+i).value;
					if (iord>0)
						s_iord=s_iord+","+document.getElementById('idorder_'+i).value;
					else if (invo>0)
						s_invo=s_invo+","+document.getElementById('idinvoice_'+i).value;
					band++;
					}
				}
			if(band>=100)
				{
				iord=document.getElementById('idorder_'+i).value;
				parametros="s_iord="+s_iord+"&s_invo="+s_invo+"&s_grabar=1";
				ajax_dynamic_div("POST","integrate_e_invoice_update.php",parametros,div,false);
				s_iord="0";
				s_invo="0";
				band=0;
				}
			}
		parametros="s_iord="+s_iord+"&s_invo="+s_invo+"&s_grabar=1";
		ajax_dynamic_div("POST","integrate_e_invoice_update.php",parametros,div,false);
		show_tabla();
		}
	}
	
function anular_e(idorden, code, state, i)
	{
	if (!confirm('Usted esta a punto de ANULAR la factura Electronica en estado '+state+' del Cliente '+code+'. ¿Desea Continuar?'))
		{ 
		}
	else
		{
		div=document.getElementById('div_msj');
		$(div).html("<img src='images/loading.gif' align='center'> Anulando...");
		iord=document.getElementById('idorder_'+i).value;
		invo=document.getElementById('idinvoice_'+i).value;
		if (invo==0)
			parametros="iord="+idorden+"&s_anula=1";
		else
			parametros="invo="+invo+"&s_anula=1";
		ajax_dynamic_div("POST","integrate_e_invoice_update.php",parametros,div,false);
		show_tabla();
		}
	}
function anular_e_funcion_error(idorden, code, state)
	{	
	ultimo_x=document.getElementById('ultimo_x').value;
	for(i=1;i<ultimo_x;i++)
		{
		if((document.getElementById('p_factura_'+i).value!=document.getElementById('factura_'+i).value)||(document.getElementById('refund_'+i).value != document.getElementById('p_refund_'+i).value))
			{
			grabar(i);
			}
		}
	}
	
function activar_all(obj)
	{	
	ultimo_x=document.getElementById('ultimo_x').value;
	for(i=1;i<ultimo_x;i++)
		{
		if (obj.checked)
			{
			if (document.getElementById('check_'+i+'').value == '0')
				{
				}
			else
				{
				document.getElementById('check_'+i+'').checked = true;
				
				}
			document.getElementById("txtHint").innerHTML="<INPUT TYPE=BUTTON onClick='cancelar()' VALUE=\"Cancelar\"> &nbsp;&nbsp;&nbsp;&nbsp; <INPUT TYPE=BUTTON onClick='operar_factura()' VALUE=\"Cambiar Estado\">";
			}
		else
			{
			document.getElementById('check_'+i+'').checked = "";
			document.getElementById("txtHint").innerHTML="";
			}
		}
	}//