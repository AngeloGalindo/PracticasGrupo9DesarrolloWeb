function show_date()
	{//modificado el rango de fechas
	validate_date(document.forms['example']);
	}

function check_items(obj_check,num)
	{
	ultimo_x=document.getElementById('ultimo_x').value;
	cant=0;
	t_pend=0;
	status=obj_check.checked;
	for(i=1;i<ultimo_x;i++)
		{
		if(document.getElementById('check_'+i).checked)
			{
			document.getElementById('check_'+i).checked=false;
			//t_pend=Math.round(t_pend)+Math.round(document.getElementById('disponible_'+i).value);
			}
		}
	if(status=='true')
		{
		obj_check.checked=true;
		t_pend=Math.round(document.getElementById('disponible_'+num).value);
		}
					
	cant=Math.round(document.getElementById('cantidad').value);
	if((t_pend>cant)&&(cant>0))
		document.getElementById("txtHint").innerHTML="<INPUT TYPE=BUTTON onClick='cancelar()' VALUE=\"Cancelar\"> &nbsp;&nbsp;&nbsp;&nbsp; <INPUT TYPE=BUTTON onClick='operar()' VALUE=\"Ingresar Devoluci&oacute;n\">";
	else
		document.getElementById("txtHint").innerHTML="";
		
	document.getElementById("txtHint2").innerHTML=document.getElementById("txtHint").innerHTML;
	}

function show_tabla()
	{
	div=document.getElementById("div_tabla");
	$(div).html("");
	$(div).html("<img src='images/loading.gif' align='center'>");
	document.getElementById("txtHint").innerHTML="";
	document.getElementById("txtHint2").innerHTML="";
	day1=document.getElementById('date1').value;
	day1 = day1.substring(6,10)+day1.substring(3,5)+day1.substring(0,2);
	day2=document.getElementById('date2').value;
	day2 = day2.substring(6,10)+day2.substring(3,5)+day2.substring(0,2);
	code=document.getElementById('cod_cliente').value;
	str_param="day1="+day1+"&day2="+day2+"&code="+code+"&ver_tabla=1";
	ajax_dynamic_div("POST",'integrate_mass_refund_tabla.php',str_param,div,false);
	}

function operar()
	{
	div=document.getElementById("div_update");
	ultimo_x=document.getElementById('ultimo_x').value;
	cant=document.getElementById('cantidad').value;
	day3=document.getElementById('date3').value;
	day3 = day3.substring(6,10)+day3.substring(3,5)+day3.substring(0,2);
	bole=document.getElementById('boleta').value;
	
	if (bole=='')
		{
		alert('Ingrese numero de boleta');	
		}
	else{
		//codigo para una devolucion a una sola factura
		for(i=1;i<ultimo_x;i++)
			{
			seri=document.getElementById('serie_'+i).value;
			numb=document.getElementById('number_'+i).value;
			cust=document.getElementById('idcustomer_'+i).value;
			item=document.getElementById('total_items_'+i).value;
			pric=document.getElementById('total_price_'+i).value;
			addr=document.getElementById('address_'+i).value;
			str_param="addr="+addr+"&pric="+pric+"&item="+item+"&day3="+day3+"&cant="+cant+"&bole="+bole+"&seri="+seri+"&cust="+cust+"&numb="+numb+"&o_insertar=1";
			ajax_dynamic_div("POST",'integrate_mass_refund_update.php',str_param,div,false);
			}
		//codigo para balancear la devolucion entre varias facturas
		// items=0;
		// for(i=1;i<ultimo_x;i++)
			// {
			// if(document.getElementById('check_'+i).checked)
				// items++;
			// }
		// cociente=Math.round(cant/items);
		// residuo=Math.round(cant%items);
		// do{
			// for(i=1;i<ultimo_x;i++)
				// {
				// disponible=Math.round(document.getElementById('disponible_'+i).value);
				// if((document.getElementById('check_'+i).checked)&&(disponible>0))
					// {
					// cant=Math.round(cociente)+Math.round(residuo);
					// residuo=0;
					// if(cant>disponible)
						// {
						// residuo=Math.round(cant)-Math.round(disponible);
						// cant=disponible;
						// }
					// seri=document.getElementById('serie_'+i).value;
					// numb=document.getElementById('number_'+i).value;
					// cust=document.getElementById('idcustomer_'+i).value;
					// item=document.getElementById('total_items_'+i).value;
					// pric=document.getElementById('total_price_'+i).value;
					// addr=document.getElementById('address_'+i).value;
					// str_param="addr="+addr+"&pric="+pric+"&item="+item+"&day3="+day3+"&cant="+cant+"&bole="+bole+"&seri="+seri+"&cust="+cust+"&numb="+numb+"&o_insertar=1";
					// ajax_dynamic_div("POST",'integrate_mass_refund_update.php',str_param,div,false);
					// document.getElementById('disponible_'+i).value=Math.round(document.getElementById('disponible_'+i).value)-Math.round(cant);
					// }
				// }
			// cociente=0;
			// }while(residuo>0);
		show_tabla();
		}
	}
function cancelar(op)
	{
	show_tabla();
	}
