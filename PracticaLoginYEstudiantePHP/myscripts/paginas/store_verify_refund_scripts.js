function show_table()
	{
	div=document.getElementById("div_tabla");
	$(div).html("<img src='images/loading.gif' align='center'>");
	cana=document.getElementById('canal').value;
	cana_name=document.getElementById('canal').options[document.getElementById('canal').selectedIndex].text;
	str_param="cana="+cana+"&msj="+cana_name+"&ver_tabla=1";
	ajax_dynamic_div("POST",'store_verify_refund_tabla.php',str_param,div);
	}

function verificar(idrefund, num, op)
	{//procedimiento que pasa a estado verificado las devoluciones
	if (document.getElementById("pendiente_"+num).value==0)	
		{
		strparm = 'ref='+idrefund+"&op=0";
		ajax_dynamic_div("GET","update_order_anulado.php",strparm,'#ajaxresult',false);
		if (op==1)
			show_table();
		}
	else if (document.getElementById("pendiente_"+num).value==1)
		{
		alert(" Integrar el  ejemplar pendiente antes de Verificar");
		}
	else
		{
		alert(" Integrar los "+document.getElementById("pendiente_"+num).value+" ejemplares pendientes antes de Verificar");
		}
	}
			
function show_date()
	{//modificado el rango de fechas
	}
function enter(e)
	{
	}
function verificar_todas()
	{//procedimiento que pasa a estado verificado todas las devoluciones posibles
	if (confirm('Usted esta a punto de cambiar el estado a TODAS LAS DEVOLUCIONES a estado "Verificado". ¿Desea Continuar?'))
		{
		ultimo=document.getElementById('ultimo_x').value;
		for(i=1; i<=ultimo; i++)
			{
			idrefund= document.getElementById('idrefund_'+i+'').value;
			grabar(i);
			verificar(idrefund,i,0);
			}
		}
	}
function editando_cantidad(fila,colu)
	{
	total_fila=0;
	contador=document.getElementById('contador_'+fila).value;
	obj=document.getElementById('quantity_refund_'+fila+'_'+colu+'');
	if(isNaN(obj.value) || obj.value <0)
		obj.value = document.getElementById('oquantity_refund_'+fila+'_'+colu+'').value;
	if(obj.value =="")
		obj.value =0;
		
	for(ii=1; ii<=contador; ii++)
		{
		total_fila+=Math.round(document.getElementById('quantity_refund_'+fila+'_'+ii+'').value);
		}
	devolucion=document.getElementById('quantity_'+fila+'').value;
	if (total_fila>devolucion)
		{
		obj.value = document.getElementById('oquantity_refund_'+fila+'_'+colu+'').value;
		alert("La cantidad de ejemplares ingresado al detalle no puede superar el total de la devolucion");
		}
	total_items=Math.round(document.getElementById('total_items_'+fila+'_'+colu+'').value)+Math.round(document.getElementById('oquantity_refund_'+fila+'_'+colu+'').value);
	if (obj.value>total_items)
		{
		obj.value = document.getElementById('oquantity_refund_'+fila+'_'+colu+'').value;
		alert("La cantidad de ejemplares ingresado al detalle no puede superar el envio en la orden. (ejemplares disponibles:"+total_items+")");
		}
	verificando_fila(fila);
	}
function verificando_fila(fila)
	{
	total_fila=0;band=0;
	contador=document.getElementById('contador_'+fila).value;
	for(ii=1; ii<=contador; ii++)
		{
		cantidad =Math.round(document.getElementById('quantity_refund_'+fila+'_'+ii+'').value);
		ocantidad=Math.round(document.getElementById('oquantity_refund_'+fila+'_'+ii+'').value);
		total_fila+=cantidad;
		if (cantidad!=ocantidad)
			{
			band=1;
			}
		}
	quantity =Math.round(document.getElementById('quantity_'+fila+'').value);
	document.getElementById("pendiente_"+fila).value=quantity-total_fila;
	if(band==1 && (quantity-total_fila)==0)
		document.getElementById("div_"+fila).innerHTML="<A id='a_"+fila+"' HREF=\"#a_"+fila+"\" onClick='grabar("+fila+");'><img src=\"images/pencil.png\" alt=\"Click para Grabar\" title=\"Click para Grabar\" width=\"16\" height=\"16\" border=\"0\"></a>";
	else if(band==1 && (quantity-total_fila)==1)
		document.getElementById("div_"+fila).innerHTML="<img src=\"images/error2.png\" alt=\"Pendiente "+(quantity-total_fila)+"\" title=\"Pendiente "+(quantity-total_fila)+"\" width=\"16\" height=\"16\" border=\"0\" onclick='alert(\"Integre el ejemplar Pendiente para continuar...\");'>";
	else if(band==1)
		document.getElementById("div_"+fila).innerHTML="<img src=\"images/error2.png\" alt=\"Pendientes "+(quantity-total_fila)+"\" title=\"Pendientes "+(quantity-total_fila)+"\" width=\"16\" height=\"16\" border=\"0\" onclick='alert(\"Integre los "+(quantity-total_fila)+" ejemplares Pendientes para continuar...\");'>";
	else
		document.getElementById("div_"+fila).innerHTML="<img src=\"images/accept.png\" alt=\"Actualizado\" title=\"Actualizado\" width=\"16\" height=\"16\" border=\"0\">";
	}
function grabar(fila)
	{
	div=document.getElementById("div_update");
	$(div).html("");
	contador=document.getElementById('contador_'+fila).value;
	for(j=1; j<=contador; j++)
		{
		cantidad =Math.round(document.getElementById('quantity_refund_'+fila+'_'+j+'').value);
		ocantidad=Math.round(document.getElementById('oquantity_refund_'+fila+'_'+j+'').value);
		if (cantidad!=ocantidad)
			{
			deta=document.getElementById('iddetail_'+fila+'_'+j+'').value;
			total_items=Math.round(document.getElementById('total_items_'+fila+'_'+j+'').value);
			str_param="deta="+deta+"&cant="+cantidad+"&fila="+fila+"&colu="+j+"&grabar_detalle=1";
			ajax_dynamic_div("POST",'store_verify_refund_update.php',str_param,div,false);
			document.getElementById('total_items_'+fila+'_'+j+'').value=Math.round(total_items-(cantidad-ocantidad));
			document.getElementById('oquantity_refund_'+fila+'_'+j+'').value=Math.round(cantidad);
			}
		}
	document.getElementById("div_"+fila).innerHTML="<img src=\"images/accept.png\" alt=\"Actualizado\" title=\"Actualizado\" width=\"16\" height=\"16\" border=\"0\">";
	}