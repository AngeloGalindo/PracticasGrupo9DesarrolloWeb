function show_date()
	{//modificado el rango de fechas
	}
function test_box(i,x)
	{
	if(isNaN(document.getElementById('box_'+i+'_'+x+'').value) || document.getElementById('box_'+i+'_'+x+'').value <0 || document.getElementById('box_'+i+'_'+x+'').value == "")
		{
		document.getElementById('box_'+i+'_'+x+'').value =document.getElementById('obox_'+i+'_'+x+'').value;
		}
	test_order(x);
	}
function cuadradora()
	{
	ultimo2=document.getElementById('ultimo').value;
	suma_total=0;
	for(bb=1;bb<6;bb++)
		{
		suma=0;
		for(aa=1;aa<ultimo2;aa++)
			{
			suma+=Math.round(document.getElementById('box_'+bb+'_'+aa+'').value);
			}
		suma_total+=Math.round(suma);
		document.getElementById('suma_'+bb).value=suma;
		}
	document.getElementById('suma_t').value=suma_total;
	}
function test_order(x)
	{
	suma=0;band_div=0;
	for(j=1;j<6;j++)
		{
		if(isNaN(document.getElementById('box_'+j+'_'+x+'').value) || document.getElementById('box_'+j+'_'+x+'').value <0 || document.getElementById('box_'+j+'_'+x+'').value == "")
			{
			document.getElementById('box_'+j+'_'+x+'').value =document.getElementById('obox_'+j+'_'+x+'').value;
			}
		valor=document.getElementById('box_'+j+'_'+x+'').value;
		suma=Math.round(suma)+Math.round(valor);
		if (document.getElementById('box_'+j+'_'+x+'').value != document.getElementById('obox_'+j+'_'+x+'').value)
			band_div++;
		}
	document.getElementById('total_'+x+'').value=Math.round(suma);
	if (band_div>0)
		{
		document.getElementById("div_"+x).innerHTML="<img src=\"images/pencil.png\" alt=\"Cambios No Grabados\" title=\"Cambios No Grabados\" width=\"16\" height=\"16\" border=\"0\">";
		document.getElementById('div_boton1').style.visibility = 'visible';
		document.getElementById('div_boton2').style.visibility = 'visible';
		}
	else
		{
		document.getElementById("div_"+x).innerHTML="<img src=\"images/accept.png\" alt=\"Actualizado\" title=\"Actualizado\" width=\"16\" height=\"16\" border=\"0\">";
		}
	cuadradora();
	}

function show_tabla()
	{//mostrando tabla
	cont=(parseInt(document.getElementById('contador').value)+1);
	document.getElementById('contador').value=cont;
	if(cont<20)
		{
		cust=document.getElementById('idcustomer').value;
		addr=document.getElementById('idaddress').value;
		idpublication=document.getElementById('idpublication').value;
		div= document.getElementById("div_tabla");
		div.innerHTML="";
		document.getElementById('div_boton1').style.visibility = 'hidden';
		document.getElementById('div_boton2').style.visibility = 'hidden';
		day1=document.getElementById("date1").value;
		day1=day1.substring(6,10)+day1.substring(3,5)+day1.substring(0,2);
		day2=document.getElementById('date2').value;
		day2=day2.substring(6,10)+day2.substring(3,5)+day2.substring(0,2);
		str_param = "cust="+cust+"&addr="+addr+"&day1="+day1+"&day2="+day2+"&idpublication="+idpublication+"&o_tabla=1";
		ajax_dynamic_div("POST",'genorders_new_tabla.php',str_param,div,false);
		}
	else
		{
		day1=document.getElementById("date1").value;
		day2=document.getElementById('date2').value;
		cod=document.getElementById('cod_cliente').value;
		location.href="genorders.php?cod="+cod+"&day1="+day1+"&day2="+day2;
		}
	}
function show_cliente(cod)
	{//mostrando tabla
	if(cod==-1)
		{
		cod=document.getElementById('cod_cliente').value;
		}
	div= document.getElementById("div_cliente");
	div.innerHTML="";
	document.getElementById("div_tabla").innerHTML="";
	if(cod!="")
		{
		str_param = "cod="+cod+"&o_cliente=1";
		ajax_dynamic_div("POST",'genorders_new_tabla.php',str_param,div,false);
		}
	generar_ordenes();
	}
function generar_ordenes()
	{//mostrando tabla
	cust=document.getElementById('idcustomer').value;
	addr=document.getElementById('idaddress').value;
	idpublication=document.getElementById('idpublication').value;
	div= document.getElementById("div_msj");
	div.innerHTML="";
	document.getElementById('div_boton1').style.visibility = 'hidden';
	document.getElementById('div_boton2').style.visibility = 'hidden';
	day1=document.getElementById("date1").value;
	day1=day1.substring(6,10)+day1.substring(3,5)+day1.substring(0,2);
	day2=document.getElementById('date2').value;
	day2=day2.substring(6,10)+day2.substring(3,5)+day2.substring(0,2);
	str_param = "cust="+cust+"&addr="+addr+"&day1="+day1+"&day2="+day2+"&idpublication="+idpublication+"&o_nuevo=1";
	ajax_dynamic_div("POST",'genorders_new_updated.php',str_param,div,false);
	show_tabla();
	}
function grabar_ordenes()
	{
	div= document.getElementById("div_msj");
	ultimo=document.getElementById('ultimo').value;
	for(a=1;a<ultimo;a++)
		{
		test_order(a);
		for(b=1;b<6;b++)
			{
			if (document.getElementById('box_'+b+'_'+a+'').value != document.getElementById('obox_'+b+'_'+a+'').value)
				{
				ido=document.getElementById('idorder_'+a+'').value;
				idde=document.getElementById('iddetail_'+b+'_'+a+'').value;
				cant=document.getElementById('box_'+b+'_'+a+'').value;
				str_param = "idde="+idde+"&cant="+cant+"&ord="+ido+"&o_detail=1";
				ajax_dynamic_div("POST",'genorders_new_updated.php',str_param,div,false);
				}	
			}
		}
	show_tabla();
	document.getElementById('cod_cliente').focus();
	document.getElementById('cod_cliente').select();
	}
function allbox(num)
	{
	ultimo=document.getElementById('ultimo').value;
	for(a=1;a<ultimo;a++)
		{
		if (document.getElementById('band_'+num+'_'+a+'').value ==1)
			{
			if(document.getElementById('allbox_'+num+'').checked)
				{
				document.getElementById('box_'+num+'_'+a+'').value =document.getElementById('autobox_'+num+'').value;	
				}
			else
				{
				document.getElementById('box_'+num+'_'+a+'').value =document.getElementById('obox_'+num+'_'+a+'').value;	
				}
			test_order(a);
			}
		}
	}
function enter(e)
	{
	tecla=(document.all) ? e.keyCode : e.which;
	if(tecla==13)
		{
		try { 
			A=document.getElementById("idcustomer").value;
			grabar_ordenes();
			} 
		catch(e) 
			{ 
			} 
		}
	else if(tecla>36&&tecla<41)
		{
		//alert(tecla);
		obj= document.activeElement.name.split('_');
		obj_name=obj[0];
		obj_colu=parseInt(obj[1]);
		obj_fila=parseInt(obj[2]);
		if(tecla==39)
			{
			if(obj_colu<5)
				{
				document.getElementById(obj_name+"_"+(obj_colu+1)+"_"+obj_fila).focus();
				document.getElementById(obj_name+"_"+(obj_colu+1)+"_"+obj_fila).select();
				}
			else
				{
				document.getElementById(obj_name+"_1_"+(obj_fila+1)).focus();
				document.getElementById(obj_name+"_1_"+(obj_fila+1)).select();
				}
			}
		else if(tecla==37)
			{
			if(obj_colu>1)
				{
				document.getElementById(obj_name+"_"+(obj_colu-1)+"_"+obj_fila).focus();
				document.getElementById(obj_name+"_"+(obj_colu-1)+"_"+obj_fila).select();
				}
			else
				{
				document.getElementById(obj_name+"_5_"+(obj_fila-1)).focus();
				document.getElementById(obj_name+"_5_"+(obj_fila-1)).select();
				}
			}
		else if(tecla==38)
			{
			document.getElementById(obj_name+"_"+obj_colu+"_"+(obj_fila-1)).focus();
			document.getElementById(obj_name+"_"+obj_colu+"_"+(obj_fila-1)).select();
			}
		else if(tecla==40)
			{
			document.getElementById(obj_name+"_"+obj_colu+"_"+(obj_fila+1)).focus();
			document.getElementById(obj_name+"_"+obj_colu+"_"+(obj_fila+1)).select();
			}
		}
	}
function pasar_focus(nombre)
	{	
	document.getElementById(nombre).focus();
	document.getElementById(nombre).select();
	}
	
function anular(idorder, fecha)
	{
	if (confirm('Usted esta a punto de ANULAR la orden '+idorder+' del '+fecha+' Desea Continuar?'))
		{
		strparm = 'ord='+idorder+'&o_anular=1';
		ajax_dynamic_div("POST",'genorders_new_updated.php',strparm,'#div_msj',false);
		show_tabla();
		}
	}