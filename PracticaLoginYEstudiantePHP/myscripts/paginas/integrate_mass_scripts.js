function show_date()
	{//modificado el rango de fechas
	}

function check_items(obj_check,num,op)
	{
	if(op==1)
		{
		ultimo_x=document.getElementById('ultimo_x').value;
		cant=0;
		monto=0;
		for(i=1;i<ultimo_x;i++)
			{
			if(document.getElementById('check_'+i).checked)
				{
				monto=monto+(Math.round(document.getElementById('monto_'+i).value*100)/100);
				}
			}
		document.getElementById('monto').value=monto;
		show_botom(1);
		}
	else if(op==2)
		{
		}
	}
function show_botom(op)
	{
	monto=0;
	if(op==1)
		{
		monto=document.getElementById('monto').value;
		if(monto>1)
			{
			document.getElementById("div_boton1").innerHTML="<INPUT TYPE=BUTTON onClick='cancelar(1)' VALUE=\"Cancelar\"> &nbsp;&nbsp;&nbsp;&nbsp; <INPUT TYPE=BUTTON onClick='operar_factura()' VALUE=\"Asignar Factura\">";
			}
		else if(monto==0)
			{
			document.getElementById("div_boton1").innerHTML="";
			}
		else 
			{
			document.getElementById("div_boton1").innerHTML="Se a presentado un Error en la suma del monto de las facturas, por favor indicar de este ERROR! a los desarrolladores";
			alert("Se a presentado un Error en la suma del monto de las facturas, por favor indicar de este ERROR! a los desarrolladores");
			}
		document.getElementById("div_boton2").innerHTML=document.getElementById("div_boton1").innerHTML;
		}
	else if(op==2)
		{
		}
	}	
function change_modo(op)
	{
	alert("aun no programado");
	}
function show_table()
	{
	div=document.getElementById("div_tabla");
	$(div).html("");
	if(document.getElementById('verif').value==0)
		{
		$(div).html("<img src='images/loading.gif' align='center'>");
		day1=document.getElementById('date1').value;
		day1 = day1.substring(6,10)+day1.substring(3,5)+day1.substring(0,2);
		day2=document.getElementById('date2').value;
		day2 = day2.substring(6,10)+day2.substring(3,5)+day2.substring(0,2);
		cus=document.getElementById('idcustomer').value;
		if (document.forms['example'].op_mos_modo[0].checked)
			modo = 1;
			else if (document.forms['example'].op_mos_modo[1].checked)
				modo = 2;
		str_param="day1="+day1+"&day2="+day2+"&cus="+cus+"&modo="+modo+"&ver_tabla=1";
		ajax_dynamic_div("POST",'integrate_mass_tabla.php',str_param,div,false);
		}
	}
function show_cliente(cod)
	{//mostramos datos del cliente
	if(cod==-1)
		{
		cod=document.getElementById('cod_cliente').value;
		}
	div=document.getElementById("div_det");
	div.innerHTML="";
	document.getElementById("div_date").style.visibility="hidden";
	document.getElementById("div_tabla").innerHTML="";
	if(cod!="")
		{
		if (document.forms['example'].op_mos_modo[0].checked){
		modo = 1;
		msj=" Factura";}
		else if (document.forms['example'].op_mos_modo[1].checked){
			modo = 2;
			msj=" Pago ";}
		str_param = "cod="+cod+"&modo="+modo+"&msj="+msj+"&only_cod=1";
		ajax_dynamic_div("POST",'integrate_mass_get.php',str_param,div,false);
		}
	}
function cancelar(op)
	{
	if(op==1)
		{
		show_table();
		document.getElementById('monto').value=0;
		show_botom(1);
		}
	else if(op==2)
		{
		}
	}
function operar_factura()
	{
	if (!confirm('Usted esta a punto de INICIAR el proceso de facturacion. ¿Desea Continuar?'))
		{ 
		}
	else
		{
		div=document.getElementById('div_msj');
		$(div).html("<img src='images/loading.gif' align='center'> Grabando...");
		ultimo_x=document.getElementById('ultimo_x').value;
		day3=document.getElementById('date3').value;
		day3 = day3.substring(6,10)+day3.substring(3,5)+day3.substring(0,2);
		seri=document.getElementById('serie').value;
		numb=document.getElementById('number_invoice').value;
		for(i=1;i<ultimo_x;i++)
			{
			if(document.getElementById('check_'+i).checked)
				{
				iord=document.getElementById('idorder_'+i).value;
				parametros="iord="+iord+"&seri="+seri+"&numb="+numb+"&day3="+day3+"&s_grabar=1";
				ajax_dynamic_div("POST","integrate_mass_update.php",parametros,div,false);
				}
			}
		show_table();
		}
	}
function verificar_f()
	{
	div=document.getElementById('div_verificar');
	$(div).html("<img src='images/loading.gif' align='center'> Verificando...");
	seri=document.getElementById('serie').value;
	numb=document.getElementById('number_invoice').value;
	parametros="seri="+seri+"&numb="+numb+"&s_verificar=1";
	ajax_dynamic_div("POST","integrate_mass_get.php",parametros,div,false);
	show_table();
	}