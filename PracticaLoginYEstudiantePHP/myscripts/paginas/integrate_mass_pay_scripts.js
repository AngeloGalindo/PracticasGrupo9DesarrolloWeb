function show_date()
	{//modificado el rango de fechas
	}
	
function change_test(obj,num)
	{
	if(isNaN(obj.value) ||obj.value <=0 || obj.value == ""||Math.round(obj.value)>Math.round(document.getElementById('pendiente_'+num).value))
		{
		obj.value=0;
		}
	document.getElementById('check_'+num).checked=false;
	calcular();
	}
function calcular()
	{
	document.getElementById("txtHint").innerHTML="";
	ultimo_x=document.getElementById('ultimo_x').value;
	cant=0;
	monto_calculado=0;
	for(i=1;i<ultimo_x;i++)
		{
		monto_calculado=Math.round((monto_calculado+Math.round(document.getElementById('integrar_'+i).value*100)/100)*100)/100;
		document.getElementById("p_pendiente_"+i).value=Math.round((document.getElementById('pendiente_'+i).value-document.getElementById('integrar_'+i).value)*100)/100;
		}
	monto=Math.round(document.getElementById("monto").value*100)/100;
	document.getElementById("calculado").value=Math.round(monto_calculado*100)/100;
	if(monto==monto_calculado&&monto>0)
		{
		document.getElementById("txtHint").innerHTML="<INPUT TYPE=BUTTON onClick='cancelar()' VALUE=\"Cancelar\"> &nbsp;&nbsp;&nbsp;&nbsp; <INPUT TYPE=BUTTON onClick='operar_pago()' VALUE=\"Integrar Pago\">";
		}
	else if(monto>monto_calculado)
		{
		diff=Math.round((monto-monto_calculado)*100)/100;
		document.getElementById("txtHint").innerHTML=" 	Pendiente de integrar <b> Q. "+diff+" </b> ";
		}
	else if(monto<monto_calculado)
		{
		diff=Math.round((monto_calculado-monto)*100)/100;
		document.getElementById("txtHint").innerHTML=" 	Supero el monto a integrar por <b> Q. "+diff+" </b> ";
		}
	document.getElementById("txtHint2").innerHTML=document.getElementById("txtHint").innerHTML;
	}
function check_items(obj,num)
	{
	if(obj.checked)
		document.getElementById('integrar_'+num).value=Math.round(document.getElementById('pendiente_'+num).value*100)/100;
	else 
		document.getElementById('integrar_'+num).value=0;
	calcular();
	}
function show_account()
	{
	div=document.getElementById("div_account");
	ib=document.getElementById('banco').value;
	str_param="ib="+ib;
	ajax_dynamic_div("GET",'getacc.php',str_param,div,false);
	}	
	
function show_tabla(op)
	{
	if(op==1)
		{
		document.getElementById("div_boton").style.visibility = 'visible';
		document.getElementById("div_boton3").style.visibility = 'hidden';
		}
	else
		{
		document.getElementById("div_boton3").style.visibility = 'visible';
		document.getElementById("div_boton").style.visibility = 'hidden';
		document.getElementById("div_boton4").style.visibility = 'hidden';
		}
	div=document.getElementById("div_tabla");
	$(div).html("");
	$(div).html("<img src='images/loading.gif' align='center'>");
	document.getElementById("txtHint").innerHTML="";
	document.getElementById("txtHint2").innerHTML="";
	fac1=document.getElementById('numero1').value;
	fac2=document.getElementById('numero2').value;
	day1=document.getElementById('date1').value;
	day1 = day1.substring(6,10)+day1.substring(3,5)+day1.substring(0,2);
	day2=document.getElementById('date2').value;
	day2 = day2.substring(6,10)+day2.substring(3,5)+day2.substring(0,2);
	cana=document.getElementById('canal').value;
	cobr=document.getElementById('cobrador').value;
	str_param="day1="+day1+"&day2="+day2+"&fac1="+fac1+"&fac2="+fac2+"&cobr="+cobr+"&cana="+cana+"&op="+op+"&ver_tabla=1";
	ajax_dynamic_div("POST",'integrate_mass_pay_tabla.php',str_param,div,false);
	}

function operar_pago()
	{
	div=document.getElementById("div_update");
	ultimo_x=document.getElementById('ultimo_x').value;
	come=document.getElementById('comentario').value;
	banc=document.getElementById('banco').value;
	cuen=document.getElementById('cuenta').value;
	day3=document.getElementById('date3').value;
	day3 = day3.substring(6,10)+day3.substring(3,5)+day3.substring(0,2);
	bole=document.getElementById('boleta').value;
	
	if (bole=='')
		{
		alert('Ingrese numero de boleta');	
		}
	else
		{
		for(i=1;i<ultimo_x;i++)
			{
			if(document.getElementById('integrar_'+i).value>0)
				{
				seri=document.getElementById('serie_'+i).value;
				numb=document.getElementById('number_'+i).value;
				pend=document.getElementById('integrar_'+i).value;
				cust=document.getElementById('idcustomer_'+i).value;
				str_param="day3="+day3+"&come="+come+"&banc="+banc+"&cuen="+cuen+"&bole="+bole+"&seri="+seri+"&cust="+cust+"&numb="+numb+"&pend="+pend+"&o_insertar=1";
				ajax_dynamic_div("POST",'integrate_mass_pay_update.php',str_param,div,false);
				}
			}
		show_tabla(2);
		}
	}
function cancelar(op)
	{
	show_tabla(1);
	}
function show_boleta(num_bol)
	{//evalua, muestra y restringe los numeros de boletas
	document.getElementById("div_boleta").innerHTML="";
	if(num_bol!="")
		{
		codcli='C00000';
		f3 = '20111001';
		str_param = "cod="+num_bol+"&fecha="+f3+"&codcli="+codcli+"&up=0&op=0";
		ajax_dynamic_div("GET",'get_boleta.php',str_param,"#div_boleta",false);
		}
	}
function show_inicio()
	{
	show_account();
	document.getElementById("div_date").style.visibility = 'hidden';
	document.getElementById("div_cobrador").style.visibility = 'hidden';
	document.getElementById("div_boton").style.visibility = 'hidden';
	document.getElementById("div_nofacturas").style.visibility = 'hidden';
	document.getElementById("div_boton3").style.visibility = 'hidden';
	document.getElementById("div_boton2").style.visibility = 'visible';
	document.getElementById("div_boton4").style.visibility = 'visible';
	document.getElementById("banco").style.visibility = 'visible';
	document.getElementById("banco").readOnly=false;
	document.getElementById("cuenta").readOnly=false;
	document.getElementById("div_date3").style.visibility = 'visible';
	document.getElementById("div_boleta").innerHTML="";
	document.getElementById("div_date3b").innerHTML="";
	document.getElementById("div_bancob").innerHTML="";
	document.getElementById("div_accountb").innerHTML="";
	document.getElementById("div_tabla").innerHTML="";
	document.getElementById("txtHint").innerHTML="";
	document.getElementById("txtHint2").innerHTML="";
	document.getElementById("boleta").value="";
	document.getElementById("boleta").readOnly=false;
	document.getElementById("monto").value=0;
	document.getElementById("monto").readOnly=false;
	document.getElementById("comentario").readOnly=false;
	
	}
function show_integrar()
	{
	bole=document.getElementById('boleta').value;
	monto=document.getElementById('monto').value;
	if (bole=='')
		{
		alert('Ingrese numero de boleta');	
		}
	else if (monto<=0)
		{
		alert('Ingrese un Monto mayor a 0.00 a la boleta');	
		}
	else
		{
		document.getElementById("div_boton2").style.visibility = 'hidden';
		document.getElementById("div_date").style.visibility = 'visible';
		document.getElementById("div_cobrador").style.visibility = 'visible';
		document.getElementById("div_nofacturas").style.visibility = 'visible';
		document.getElementById("div_boton").style.visibility = 'visible';
		document.getElementById("banco").readOnly=true;
		document.getElementById("cuenta").readOnly=true;
		document.getElementById("div_date3").style.visibility = 'hidden';
		document.getElementById("banco").style.visibility = 'hidden';
		document.getElementById("cuenta").style.visibility = 'hidden';
		document.getElementById("div_date3b").innerHTML=document.getElementById("date3").value;
		document.getElementById("div_accountb").innerHTML=document.getElementById("cuenta").options[document.getElementById('cuenta').selectedIndex].text;
		document.getElementById("div_bancob").innerHTML=document.getElementById("banco").options[document.getElementById('banco').selectedIndex].text;
		document.getElementById("boleta").readOnly=true;
		document.getElementById("monto").readOnly=true;
		document.getElementById("comentario").readOnly=true;
		}
	}