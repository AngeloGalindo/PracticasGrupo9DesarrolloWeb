function show_date()
	{//modificado el rango de fechas
	validate_date(document.forms['example']);
	}
	
function show_table()
	{
	document.getElementById("boton").style.visibility="hidden";
	div=document.getElementById("div_tabla");
	$(div).html("<img src='images/loading.gif' align='center'>");
	document.getElementById("txtHint").innerHTML="";
	document.getElementById("txtHint2").innerHTML="";
	op=document.getElementById('op').value;
	if (op==0)
		{
		document.getElementById("opciones_div").style.visibility="hidden";
		document.getElementById("opciones_div").style.display="none";
		}
	else
		{
		document.getElementById("opciones_div").style.visibility="visible";
		document.getElementById("opciones_div").style.display="inline";
		}
	if (document.forms['example'].op_mos[0].checked)
		prom = 0;
	else 
		prom = 1;
		
	day1=document.getElementById('date1').value;
	day1 = day1.substring(6,10)+day1.substring(3,5)+day1.substring(0,2);
	day2=document.getElementById('date2').value;
	day2 = day2.substring(6,10)+day2.substring(3,5)+day2.substring(0,2);
	cana=document.getElementById('canal').value;
	cana_name=document.getElementById('canal').options[document.getElementById('canal').selectedIndex].text;
	str_param="day1="+day1+"&day2="+day2+"&cana="+cana+"&msj="+cana_name+"&prom="+prom+"&op="+op+"&ver_tabla=1";
	ajax_dynamic_div("POST",'integrate_store_tabla.php',str_param,div);
	}

function pasar_focus(nombre)
	{	
	document.getElementById(nombre).focus();
	document.getElementById(nombre).select();
	}
function enter(e)
	{
	tecla=(document.all) ? e.keyCode : e.which;
	if(tecla>36&&tecla<41)
		{
		obj= document.activeElement.name.split('_');
		obj_name=obj[0];
		obj_numb=obj[1];
		if(tecla==39)
			{
			if(obj_name=="refund")
				{
				document.getElementById("factura_"+obj_numb).focus();
				document.getElementById("factura_"+obj_numb).select();
				}
			else
				{
				document.getElementById("refund_"+(parseInt(obj_numb)+1)).focus();
				document.getElementById("refund_"+(parseInt(obj_numb)+1)).select();
				}
			}
		else if(tecla==37)
			{
			if(obj_name=="refund")
				{
				document.getElementById("factura_"+(parseInt(obj_numb)-1)).focus();
				document.getElementById("factura_"+(parseInt(obj_numb)-1)).select();	
				}	
			else
				{
				document.getElementById("refund_"+obj_numb).focus();
				document.getElementById("refund_"+obj_numb).select();
				}
			}
		else if(tecla==38)
			{
			document.getElementById(obj_name+"_"+(parseInt(obj_numb)-1)).focus();
			document.getElementById(obj_name+"_"+(parseInt(obj_numb)-1)).select();
			}
		else if(tecla==40)
			{
			document.getElementById(obj_name+"_"+(parseInt(obj_numb)+1)).focus();
			document.getElementById(obj_name+"_"+(parseInt(obj_numb)+1)).select();
			}
		}
	}

function change_test(num)
	{
	band=0;
	factura=document.getElementById('factura_'+num);
	refund=document.getElementById('refund_'+num);
	if(isNaN(factura.value) || factura.value <=0 || factura.value == "" ||parseInt(factura.value)>parseInt(document.getElementById('fact_limit').options[document.getElementById('fact_limit').selectedIndex].text))
		{
		factura.value=document.getElementById('p_factura_'+num).value;
		}
	else
		{
		if(factura.value!=document.getElementById('p_factura_'+num).value)
			band++;
		}
	if(isNaN(refund.value) || refund.value <0 || refund.value == ""||parseInt(refund.value)>parseInt(document.getElementById('neto_'+num).value+document.getElementById('p_neto_'+num).value))
		{
		alert("La devolucion no puede superar el pedido del Cliente");
		refund.value=Math.round(parseInt(document.getElementById('p_refund_'+num).value));
		document.getElementById('neto_'+num).value=Math.round(parseInt(document.getElementById('p_neto_'+num).value));
		}
	else
		{
		if(refund.value!=document.getElementById('p_refund_'+num).value)
			band++;
		}
	if(band>0)
		document.getElementById("div_"+num).innerHTML="<A id='a_"+num+"' HREF=\"#a_"+num+"\" onClick='grabar("+num+");'><img src=\"images/pencil.png\" alt=\"Cambios No Grabados\" title=\"Cambios No Grabados\" width=\"16\" height=\"16\" border=\"0\"></a>";
	else
		document.getElementById("div_"+num).innerHTML="<img src=\"images/accept.png\" alt=\"Actualizado\" title=\"Actualizado\" width=\"16\" height=\"16\" border=\"0\">";
		
	neto=Math.round(parseInt(document.getElementById('p_neto_'+num).value)-parseInt(refund.value)+parseInt(document.getElementById('p_refund_'+num).value));
	monto=Math.round((parseInt(neto)*parseFloat(document.getElementById('precio_'+num).value))*100)/100;
	document.getElementById('neto_'+num).value=neto;
	document.getElementById('monto_'+num).value=monto;
	}
	
function grabar_factura(num)
	{
	document.getElementById("div_"+num).innerHTML="<img src=\"images/loading.gif\" alt=\"Grabando\" title=\"Grabando\" width=\"16\" height=\"16\" border=\"0\">";
	div=document.getElementById("div_update");
	$(div).html("");
	day1=document.getElementById('date1').value;
	day1=day1.substring(6,10)+day1.substring(3,5)+day1.substring(0,2);
	day2=document.getElementById('date2').value;
	day2=day2.substring(6,10)+day2.substring(3,5)+day2.substring(0,2);
	day3=document.getElementById('date3').value;
	day3=day3.substring(6,10)+day3.substring(3,5)+day3.substring(0,2);
	addr=document.getElementById('idaddress_'+num).value;
	seri=document.getElementById('serie').value;
	seri_name=document.getElementById('serie').options[document.getElementById('serie').selectedIndex].text;
	mont=document.getElementById('monto_'+num).value;
	item=document.getElementById('neto_'+num).value;
	fact=document.getElementById('factura_'+num).value;
	count=document.getElementById('ordenes_'+num).value;
	cust=document.getElementById('idcustomer_'+num).value;
	str_param="day1="+day1+"&day2="+day2+"&day3="+day3+"&count="+count+"&cust="+cust+"&item="+item+"&mont="+mont+"&addr="+addr+"&fact="+fact+"&seri_name="+seri_name+"&seri="+seri+"&num="+num+"&grabar_factura=1";
	ajax_dynamic_div("POST",'integrate_store_update.php',str_param,div,false);
	}//
	
function anular(num)
	{
	serie=document.getElementById('serie_'+num).value;
	numero=document.getElementById('factura_'+num).value;
	invoice=document.getElementById('idinvoice_'+num).value;
	if (confirm('Usted esta a punto de ELIMINAR la factura '+serie+' '+numero+'. ¿Desea Continuar?'))
		{ 
		div=document.getElementById('div_msj');
		$(div).html("<img src='images/loading.gif' align='center'> Anulando...");
		parametros="seri="+serie+"&numb="+numero+"&num="+num+"&inv="+invoice+"&anular=1";
		ajax_dynamic_div("POST","integrate_store_update.php",parametros,div,false);
		show_table();
		}
	}
function grabar_todo()
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
function grabar(num)
	{
	if(document.getElementById('refund_'+num).value != document.getElementById('p_refund_'+num).value)
		grabar_refund(num);
	if(document.getElementById('factura_'+num).value != document.getElementById('p_factura_'+num).value)
		grabar_factura(num);
	buscar_cod(0,0);
	}
function f_alert(fact, op)
	{
	if (op==0)
		{
		alert("ERROR: la factura "+fact+" ya existe.");
		}
	else if (op==1)
		{
		alert("ERROR: el numero de factura "+fact+" es menor a las ultimas facturas ingresada.");
		}
	}
function grabar_refund(num)
	{
	document.getElementById("div_"+num).innerHTML="<img src=\"images/loading.gif\" alt=\"Grabando\" title=\"Grabando\" width=\"16\" height=\"16\" border=\"0\">";
	div=document.getElementById("div_update");
	$(div).html("");
	day1=document.getElementById('date1').value;
	day1=day1.substring(6,10)+day1.substring(3,5)+day1.substring(0,2);
	day2=document.getElementById('date2').value;
	day2=day2.substring(6,10)+day2.substring(3,5)+day2.substring(0,2);
	addr=document.getElementById('idaddress_'+num).value;
	cant=document.getElementById('refund_'+num).value;
	cust=document.getElementById('idcustomer_'+num).value;
	pric=document.getElementById('precio_'+num).value;
	str_param="pric="+pric+"&num="+num+"&day1="+day1+"&day2="+day2+"&cust="+cust+"&cant="+cant+"&addr="+addr+"&grabar_refund=1";
	ajax_dynamic_div("POST",'integrate_store_update.php',str_param,div,false);
	}
function change_serie()
	{
	document.getElementById('p_fecha3').value=document.getElementById('serie').value;
	document.getElementById('fact_limit').value=document.getElementById('serie').value;
	document.getElementById('date3').value=document.getElementById('p_fecha3').options[document.getElementById('p_fecha3').selectedIndex].text;
	}

function buscar_cod(num1,num2)
	{
	cargar_datos(0,2); 
	if (num1 ==0)
		num1=1;
	if (num2==0)
		num2=document.getElementById('ultimo_x').value;
	cod=document.getElementById('codigo_b').value.toUpperCase();
	band=0;
	if(cod!="")
		{
		for(i=num1;i<num2;i++)
			{
			if(document.getElementById('codigo_'+i).value.toUpperCase()==cod)
				{
				cargar_datos(i,1);
				band++;
				//i=num2;
				}
			}
		if(band==0)
			{
			if(num1>0||num2>0)
				buscar_cod(0,0);
			else
				cargar_datos(0,3);
			}
		}
	}

function cargar_datos(num,op)
	{
	if(op==1)
		{
		document.getElementById('num_b').value=num;
		document.getElementById('sector_b').value=document.getElementById('sector_'+num).value;
		document.getElementById('facturado_a_b').value=document.getElementById('facturado_a_'+num).value;
  		document.getElementById('nit_b').value=document.getElementById('nit_'+num).value;;
		document.getElementById('ordenes_b').value=document.getElementById('ordenes_'+num).value;
		document.getElementById('totalitems_b').value=document.getElementById('totalitems_'+num).value;
		document.getElementById('bonificacion_b').value=document.getElementById('bonificacion_'+num).value;
		document.getElementById('refund_b').value=document.getElementById('refund_'+num).value;
		document.getElementById('neto_b').value=document.getElementById('neto_'+num).value;
		document.getElementById('editions_b').value=document.getElementById('editions_'+num).value;
		document.getElementById('serie_b').value=document.getElementById('serie_'+num).value;
		document.getElementById('factura_b').value=document.getElementById('factura_'+num).value;
		document.getElementById('fecha_b').value=document.getElementById('fecha_'+num).value;
		document.getElementById('monto_b').value=document.getElementById('monto_'+num).value;
		document.getElementById('neto_b').value=document.getElementById('neto_'+num).value;
		document.getElementById('refund_b').style.visibility="visible";
		document.getElementById('factura_b').style.visibility="visible";
		document.getElementById('refund_b').readOnly=document.getElementById('refund_'+num).readOnly;
		document.getElementById('factura_b').readOnly=document.getElementById('factura_'+num).readOnly;
		document.getElementById("subir").innerHTML="<A HREF=\"#\" onclick='buscar_cod(0,"+(num)+");'><img src=\"images/up.png\"  alt=\"subir\" title=\"subir\" width=\"16\" height=\"16\" border=\"0\" ></a>";
		document.getElementById("div_anular_b").innerHTML=document.getElementById("div_anular_"+num).innerHTML;
		document.getElementById("div_b").innerHTML=document.getElementById("div_"+num).innerHTML;
		document.getElementById("masdiv_b").innerHTML=document.getElementById("masdiv"+num).innerHTML;
		}	
	else if (op==2)
		{
		document.getElementById('num_b').value=0;
		document.getElementById('sector_b').value="";
		document.getElementById('sector_b').value="";
		document.getElementById('facturado_a_b').value="";
		document.getElementById('nit_b').value="";
		document.getElementById('ordenes_b').value="";
		document.getElementById('totalitems_b').value="";
		document.getElementById('bonificacion_b').value="";
		document.getElementById('refund_b').value="";
		document.getElementById('neto_b').value="";
		document.getElementById('editions_b').value="";
		document.getElementById('serie_b').value="";
		document.getElementById('factura_b').value="";
		document.getElementById('fecha_b').value="";
		document.getElementById('monto_b').value="";
		document.getElementById('neto_b').value="";
		document.getElementById('refund_b').style.visibility="hidden";
		document.getElementById('factura_b').style.visibility="hidden";
		document.getElementById("subir").innerHTML="";
		}
	else if (op==3)
		{
		document.getElementById('num_b').value=0;
		document.getElementById('sector_b').value=" No se encontro "+document.getElementById('codigo_b').value;
		document.getElementById('facturado_a_b').value="";
		document.getElementById('nit_b').value="";
		document.getElementById('ordenes_b').value="";
		document.getElementById('totalitems_b').value="";
		document.getElementById('bonificacion_b').value="";
		document.getElementById('refund_b').value="";
		document.getElementById('neto_b').value="";
		document.getElementById('editions_b').value="";
		document.getElementById('serie_b').value="";
		document.getElementById('factura_b').value="";
		document.getElementById('fecha_b').value="";
		document.getElementById('monto_b').value="";
		document.getElementById('neto_b').value="";
		document.getElementById('codigo_b').value="";
		document.getElementById('refund_b').style.visibility="hidden";
		document.getElementById('factura_b').style.visibility="hidden";
		document.getElementById("subir").innerHTML="";
		}
	}
function sincronizar(op)
	{
	num=document.getElementById('num_b').value;
	if(op == 1 && num>0)
		{
		document.getElementById('refund_'+num).value=document.getElementById('refund_b').value;
		change_test(num);
		cargar_datos(num,1);
		}
	else if(op == 2 && num>0)
		{
		document.getElementById('factura_'+num).value=document.getElementById('factura_b').value;
		change_test(num);
		cargar_datos(num,1);
		}
	}
			