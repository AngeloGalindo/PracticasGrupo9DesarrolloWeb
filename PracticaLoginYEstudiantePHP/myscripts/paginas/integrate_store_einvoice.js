function show_date()
{//modificado el rango de fechas
	validate_date(document.forms['example']);
}
	
function fncShowAnnular(idinvoice)
{
	window.location.href="#divMoreDetail2";
	div=document.getElementById("divMoreDetail2");
	$(div).html("<img src='images/loading.gif' align='center'>");
	str_param="idinvoice="+idinvoice+"&AnnularInvoice=1";
	ajax_dynamic_div("POST",'integrate_invoice_face_get.php',str_param,div,false);
	$(div).html("<div><br/><br/>"+$(div).html()+'</div>');
}

function fncShowInvoice(idinvoice)
{	
	window.location.href="#divMoreDetail2";
	div=document.getElementById("divMoreDetail2");
	$(div).html("<img src='images/loading.gif' align='center'>");
	str_param="idinvoice="+idinvoice+"&viewInvoice=1";
	ajax_dynamic_div("POST",'integrate_invoice_face_get.php',str_param,div,false);
	$(div).html("<div><br/><br/>"+$(div).html()+'</div>');
}
	
function fnc_invoice_onDemand(ids)
{
	if (document.forms['example'].op_modo[1].checked)
	{
		fecha = " fue elegida de modo personalizada, le asignaste "+document.getElementById('date3').value+"";
		invoice_date = document.getElementById('date3').value;
	}
	else
	{
		fecha = " fue elegida de modo hoy, por lo que sera "+document.getElementById('date_modo_2').value+"";
		invoice_date = document.getElementById('date_modo_2').value;
	}
	userName=document.getElementById('userName').value;
	Alertify.dialog.labels.ok     = "    Confirmar y Facturar   ";
	Alertify.dialog.labels.cancel = "Cancelar";
	Alertify.dialog.confirm(userName+" estas apunto de facturar bajo demanda, la fecha de emision para la factura "+fecha+", ¿deseas confirmar?  ", 
	function () 
	{ 
		parametros="ids="+ids+"&invoice_date="+invoice_date;
		ajax_dynamic_div("POST","integrate_invoice_face_xml.php",parametros,document.getElementById('txtResult2'), false);
		show_table();
	}, 
	function () {Alertify.log.info(" Aqui no pasa nada; mejor dicho, pasan tantas cosas juntas al mismo tiempo que es mejor decir que no pasa nada. -Jaime Sabines");});

}

function fnc_invoice_email(serie, number, enterprise, email, idinvoice)
{
	email = document.getElementById('email').value;
	parametros="number="+number+"&serie="+serie+"&enterprise="+enterprise+"&mails="+email+"&solicitud=ENVIO";
	ajax_dynamic_div("POST","integrate_invoice_face_mail.php",parametros,document.getElementById('divResponse'), false);
	fncShowInvoice(idinvoice);
	//window.location.href='#close';
	//show_tabla();
}

function fnc_save_email(id,serie, number, enterprise, email,idinvoice)
{
	email = document.getElementById('email').value;
	parametros="id="+id+"&email="+email+"&updateEmail=1";
	ajax_dynamic_div("POST","integrate_invoice_face_update.php",parametros,document.getElementById('divResponse2'), false);
	fnc_invoice_email(serie, number, enterprise, email, idinvoice);
}

function fnc_invoice_anular(serie, number, enterprise, nit, negocio, email, annulment_date)
{	
	annulment_cause = document.getElementById('annulment_cause').value;
	annulment_cause = annulment_cause.replace("'", "");
	annulment_cause = annulment_cause.replace('"', "");
	annulment_cause = annulment_cause.replace(';', "");
	if(annulment_cause==''){
		alert("Agregar Motivo de Anulacion");
	}
	else
	{
		//annulment_date = '20150228';
		parametros="number="+number+"&serie="+serie+"&nit="+nit+"&enterprise="+enterprise+"&negocio="+negocio+"&annulment_date="+annulment_date+"&emails="+email+"&annulment_cause="+annulment_cause;
		ajax_dynamic_div("POST","integrate_invoice_face_annulment.php",parametros,document.getElementById('txtResult2'), false);
		show_table();
		window.location.href='#close';
	}
}

function showRow()
{
	countInvoice = 0;
	for(i=1; i<document.getElementById('ultimo_x').value; i++)
	{
		if (document.getElementById('check_'+i+'').checked)
		{
			countInvoice++;
		}
	}
	if (document.forms['example'].op_modo[1].checked)
	{
		fecha = " fue elegida de modo personalizada, le asignaste "+document.getElementById('date3').value+"";
	}
	else
	{
		fecha = " fue elegida de modo hoy, por lo que sera "+document.getElementById('date_modo_2').value+"";
	}
	userName=document.getElementById('userName').value;
	Alertify.dialog.labels.ok     = "    Confirmar Lote   ";
	Alertify.dialog.labels.cancel = "Cancelar";
	Alertify.dialog.confirm(userName+" estas apunto de iniciar con la facturacion de un lote de "+countInvoice+" facturas, la fecha de emision para las facturas "+fecha+", ¿deseas confirmar?  ", 
			function () { Asignar_facturas();}, 
			function () {Alertify.log.info(" Aqui no pasa nada; mejor dicho, pasan tantas cosas juntas al mismo tiempo que es mejor decir que no pasa nada. -Jaime Sabines");});
}

function fncViewQueue(idinvoice_queue)
{	
	window.location.href="#divMoreDetail2";
	div=document.getElementById("divMoreDetail2");
	$(div).html("<img src='images/loading.gif' align='center'>");
	str_param="idinvoice_queue="+idinvoice_queue+"&viewInvoiceQueue=1";
	ajax_dynamic_div("POST",'integrate_invoice_face_get.php',str_param,div,false);
	$(div).html("<div><br/><br/>"+$(div).html()+'</div>');
}

function fnc_change_state(id, state)// cambio de fecha
{
	parametros="idinvoice_batch="+id+"&state="+state+"&updateStateBatch=1";
	ajax_dynamic_div("POST","gen_invoice_batch_update.php",parametros,document.getElementById('txtResult2'),false);
	show_table();
}

function fnc_change_queue_state(id, state)// cambio de fecha
{
	parametros="idinvoice_queue="+id+"&state="+state+"&updateStateQueue=1";
	ajax_dynamic_div("POST","gen_invoice_batch_update.php",parametros,document.getElementById('txtResult2'),false);
	show_table();
}

function Asignar_facturas()
{
	var i;
	if (document.forms['example'].op_modo[1].checked)
	{
		fecha = document.getElementById('date3').value;
		fecha = fecha.substring(6,10)+fecha.substring(3,5)+fecha.substring(0,2);
	}
	else
	{
		fecha = document.getElementById('date_modo_2').value;
		fecha = fecha.substring(6,10)+fecha.substring(3,5)+fecha.substring(0,2);
	}
	document.getElementById("txtHint").innerHTML="<img src='loadingcircle.gif' align='center'> Asignando Facturas";
	document.getElementById("txtHint2").innerHTML="<img src='loadingcircle.gif' align='center'> Asignando Facturas";
	str_id ='';
	ids="0";contador=0;
	for(i=1; i<document.getElementById('ultimo_x').value; i++)
	{
		if (document.getElementById('check_'+i+'').checked)
		{
			ids = ids + ", "+document.getElementById('check_'+i+'').value;
			document.getElementById('check_'+i+'').checked="false";
		}
	}
	str_update="ids="+ids+"&invoice_date="+fecha+"&insert_lote=1";
	ajaxpost2('integrate_invoice_face_update.php',str_update);
	show_table();
	document.getElementById("txtHint").innerHTML="";
	document.getElementById("txtHint2").innerHTML="";
}

function activar(control)
{
	document.getElementById("txtHint").innerHTML=" <button type=button name=Asignar value='Asignar' class='btn btn-success ' onclick='showRow();'><span class='glyphicon glyphicon-flash'></span> Asignar a Lote</button>";
	document.getElementById("txtHint2").innerHTML=" <button type=button name=Asignar value='Asignar' class='btn btn-success ' onclick='showRow();'><span class='glyphicon glyphicon-flash'></span> Asignar a Lote</button>";
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
	ajax_dynamic_div("POST",'integrate_store_einvoice_tabla.php',str_param,div);
	}

function ajaxpost2(ajax_page1, str_get1)
	{
	var reemplazo;
	reemplazo = document.getElementById('txtResult3');
	ajax=nuevoAjax();
	ajax.open("POST",ajax_page1,false);
	ajax.onreadystatechange=function() 
		{
		if (ajax.readyState==1)
			{
			document.getElementById('txtResult2').innerHTML = "<img src='loadingcircle.gif' align='center'> Aguarde por favor...";
			}
		if (ajax.readyState==4) 
			{
			document.getElementById('txtResult2').innerHTML = ajax.responseText;
			}
		}
	strparams = str_get1;
	ajax.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	ajax.setRequestHeader("Content-length", str_get1.length);
	ajax.send(str_get1);
	}
	
function change_modo(op)
{
	if (op == 2)
	{
		document.getElementById('div_modo2').style.visibility = 'visible';
		document.getElementById('div_modo3').style.visibility = 'hidden';
		document.getElementById('div_modo2').style.display = "inline";
		document.getElementById('div_modo3').style.display = "none";
	}
	else
	{
		document.getElementById('div_modo2').style.visibility = 'hidden';
		document.getElementById('div_modo3').style.visibility = 'visible';
		document.getElementById('div_modo2').style.display = "none";
		document.getElementById('div_modo3').style.display = "inline";
	}
}
