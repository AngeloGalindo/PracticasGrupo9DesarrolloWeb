function refresh ()
	{}

function show_cliente(cod)
	{//mostramos datos del cliente
	document.getElementById("div_cli").innerHTML="";
	str_param = "cod="+cod+"&up=0&o_cliente=1";
	ajax_dynamic_div("POST",'sus_pay_get.php',str_param,"#div_cli");
	}
function show_boleta(cod)
	{//evalua, muestra y restringe los numeros de boletas
	document.getElementById("div_boleta").innerHTML="a";
	if(cod!="")
		{
		codcli=document.getElementById('cod_cliente').value;
		t3 = document.getElementById("date3").value;
		f3 = t3.substring(6,10)+"-"+t3.substring(3,5)+"-"+t3.substring(0,2);	
		str_param = "cod="+cod+"&fecha="+f3+"&codcli="+codcli+"&up=0&op=0";
		ajax_dynamic_div("GET",'get_boleta.php',str_param,"#div_boleta",false);
		}
	}
	
function show_data()
	{//mostramos el formulario para ingresar los datos del pago
	band=0;
	try { 
		A=document.getElementById("date3").value;
		band=1;
		}catch(e) { }
	if(band==1)
		{
		op_reg=2;
		if (document.forms['example'].op_reg[0].checked)
			{
			op_reg=0;
			}
		else if (document.forms['example'].op_reg[1].checked)
			{
			op_reg=1;
			}
		else if (document.forms['example'].op_reg[2].checked)
			{
			op_reg=2;
			}
		else if (document.forms['example'].op_reg[3].checked)
			{
			op_reg=3;
			}
		//money=document.getElementById("optype_money").value;
		ban = document.getElementById("master_idbank").value;
		moneda=document.getElementById("optype_moneda").value;
		str_param = "moneda="+moneda+"&op_reg="+op_reg+"&ban="+ban+"&o_detalles=1";
		ajax_dynamic_div("GET",'sus_pay_get.php',str_param,"#div_det");
		}
	else
		{
		document.getElementById('div_bot').innerHTML='';
		document.getElementById('div_bot2').innerHTML='';
		document.getElementById("div_det").innerHTML="";
		}
	}

function enter(e)
	{// si se preciona enter
	tecla=(document.all) ? e.keyCode : e.which;
	if(tecla==13)
		{
		try { 
			A=document.getElementById("opera").value;
			if(document.getElementById("opera").style.visibility)
				{
				editado ("d_ob");
				operar(0);
				}
			} catch(e) { } 
		}
	}	
function cancelar()
	{//reset todo
	document.getElementById('cod_cliente').value='';
	document.getElementById('div_det').innerHTML='';
	document.getElementById('div_cli').innerHTML='';
	document.getElementById('div_bot').innerHTML='';
	document.getElementById('div_bot2').innerHTML='';
	document.getElementById('cod_cliente').focus();
	document.getElementById('cod_cliente').select();
	document.getElementById("div_op_reg").style.visibility="visible";
	}
	
function show_date()
	{}
	
function editado (name)
	{//funcion para editar una boleta
	if(isNaN(document.getElementById(name).value) || document.getElementById(name).value <0 || document.getElementById(name).value =="")
		{
		document.getElementById(name).value =0;
		}
	monto_sus= parseFloat(document.getElementById("monto_sus").value);
	
	suma = parseFloat(document.getElementById("d_efe").value) + parseFloat(document.getElementById("d_che").value) + parseFloat(document.getElementById("d_ob").value);
	document.getElementById("d_monto").value = Math.round(suma*100)/100;
	total = Math.round(document.getElementById("e_monto").value*100)/100 + Math.round(document.getElementById("d_monto").value*100)/100 + Math.round(document.getElementById("c_monto").value*100)/100+Math.round(document.getElementById("t_monto").value*100)/100+Math.round(document.getElementById("ex_monto").value*100)/100;
	document.getElementById("total").value = Math.round(total*100)/100;
	document.getElementById("total_2").value = document.getElementById("total").value;
	if (total==monto_sus)
		{
		document.getElementById("opera").style.visibility = 'visible';
		document.getElementById("opera_2").style.visibility = 'visible';
		}
	else
		{
		document.getElementById("opera").style.visibility = 'hidden';
		document.getElementById("opera_2").style.visibility = 'hidden';
		}
	}
	
function operar(op)
	{
	band_pago=0;
	if (document.getElementById("d_monto").value>0 )
		{
		if (document.getElementById("cuenta").value!=0 )
			{
			if (document.getElementById("d_n_doc").value!="" )
				{
				show_boleta(document.getElementById("d_n_doc").value)
				}
			
			if (document.getElementById("d_n_doc").value!="" )
				{
				band_pago=1;
				}
			else
				{
				band_pago=0;
				alert(" Ingrese el Numero de la Boleta ");
				document.getElementById('d_n_doc').focus();
				document.getElementById('d_n_doc').select();
				}
			}
		else
			{
			band_pago=0;
			alert(" Elija un banco que posea una cuenta");
			document.getElementById('d_opbanco').focus();
			}
		document.getElementById('master_idbank').value=document.getElementById('d_opbanco').value;
		}
	if (document.getElementById("e_monto").value>0)
		{
		band_pago=1;
		}
	if (document.getElementById("c_monto").value>0)
		{
		if (document.getElementById("c_cuenta_ex").value!="" )
			{
			if (document.getElementById("c_n_doc").value!="" )
				{
				band_pago=1;
				}
			else
				{
				band_pago=0;
				alert(" Ingrese el Numero de Documento del pago en cheque ");
				document.getElementById('c_n_doc').focus();
				document.getElementById('c_n_doc').select();
				}
			}
		else
			{
			band_pago=0;
			alert(" Ingrese el numero de cuenta del pago en Cheque");
			document.getElementById('c_cuenta_ex').focus();
			document.getElementById('c_cuenta_ex').select();
			}
		}
	if (document.getElementById("t_monto").value>0)
		{
		if (document.getElementById("t_cuenta_ex").value!="" )
			{
			if (document.getElementById("t_n_doc").value!="" )
				{
				if (document.getElementById("t_m_agua").value!="" )
					{
					band_pago=1;
					}
				else
					{
					band_pago=0;
					alert(" Ingrese el Numero de Referencia del pago en tarjeta ");
					document.getElementById('t_m_agua').focus();
					document.getElementById('t_m_agua').select();
					}
				}
			else
				{
				band_pago=0;
				alert(" Ingrese el Numero de Autorizacion del pago en tarjeta ");
				document.getElementById('t_n_doc').focus();
				document.getElementById('t_n_doc').select();
				}
			}
		else
			{
			band_pago=0;
			alert(" Ingrese el numero de Tarjeta del pago en Tarjeta");
			document.getElementById('t_cuenta_ex').focus();
			document.getElementById('t_cuenta_ex').select();
			}
		}
	if (document.getElementById("ex_monto").value>0)
		{
		if (document.getElementById("ex_n_doc").value!="" )
			{
			band_pago=1;					
			}
		else
			{
			band_pago=0;
			alert(" Ingrese el Numero del Documento de Exencion ");
			document.getElementById('ex_n_doc').focus();
			document.getElementById('ex_n_doc').select();
			}
		
		}
	if (band_pago==1)
		{
		ajaxpost(document.forms['example'],0);
		movi = document.getElementById('movi').value;
		if (movi!="");
			{
			document.getElementById('cod_cliente').value='';
			document.getElementById('div_det').innerHTML='';
			document.getElementById('div_cli').innerHTML='';
			document.getElementById('div_bot2').innerHTML='';
			document.getElementById('div_bot').innerHTML="<big><font color='#045FB4'>El Pago se ingreso exitosamente<br> Movimiento No.<big></font><a href='distribution_paid.php?idm="+movi+"&nm=1'><b>"+movi+"</b><img src='images/page_refresh.png' alt='Distribuir' title='Distribuir' width='16' height='16' border='0'></a>";
			}
		}
	}