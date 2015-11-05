function fnc_disable_name(valor)
{  
   
  if (valor=='Individual')   
   {
    document.getElementById("x_firstname").style.visibility = 'visible';
    document.getElementById("x_lastname").style.visibility = 'visible';     
    document.getElementById("x_company_name").style.visibility = 'hidden';     
	document.getElementById("x_company_name").value = ''
   }
  else
   {
	document.getElementById("x_firstname").style.visibility = 'hidden';
    document.getElementById("x_lastname").style.visibility = 'hidden';     
	document.getElementById("x_firstname").value = '';
	document.getElementById("x_lastname").value = '';
	
    document.getElementById("x_company_name").style.visibility = 'visible';     
   } 
}

function fnc_disable_amounts(lista)
{
	//alert(lista.selectedIndex);
	var valor = lista.options[lista.selectedIndex].innerHTML;
	var codigo ="";
	document.getElementById("x_cred_amount").value = 0.00;
	document.getElementById("x_deb_amount").value = 0.00;
	document.getElementById("x_cred_amount").style.visibility = (valor.indexOf("Credit")>0) ? 'visible' : 'hidden';
	document.getElementById("x_deb_amount").style.visibility = (valor.indexOf("Debit")>0) ? 'visible' : 'hidden';	
	document.getElementById("x_deb_amount").readOnly = (valor.indexOf("Debit")>0) ? false : true;	
	if (valor.indexOf("Credit")>0)
	{
		codigo  = document.getElementById("x_code").value;
		document.getElementById("x_cred_amount").readOnly = (codigo.indexOf("PAY")<0) ? false : true;			
		document.getElementById("x_cred_amount").focus();
		
	}
	else
	{	
		document.getElementById("x_deb_amount").focus() ;
		document.getElementById("x_deb_amount").select() ;
	}
	
	
}

function fnc_disable_money(lista)
{
	//alert(lista.selectedIndex);
	var valor = lista.options[lista.selectedIndex].innerHTML;
	valor = valor.replace(/^\s*|\s*$/g,"");
	document.getElementById("x_amount").readOnly = false;	
	switch(valor)
	{
		case 'Cheques':
			document.getElementById("x_idbank").style.visibility = 'visible';
			document.getElementById("x_idaccount").style.visibility = 'hidden';
			document.getElementById("x_external_account_number").style.visibility = 'visible';
			document.getElementById("x_cash_amount").style.visibility = 'hidden';
			document.getElementById("x_check_amount").style.visibility = 'hidden';
			document.getElementById("x_check_ob_amount").style.visibility = 'hidden';	
			//document.getElementById("x_idbank").focus();
			break;
		case 'Depositos':
			document.getElementById("x_idbank").style.visibility = 'visible';
			document.getElementById("x_idaccount").style.visibility = 'visible';
			document.getElementById("x_external_account_number").style.visibility = 'hidden';
			document.getElementById("x_document_number").style.visibility = 'visible';
			document.getElementById("x_cash_amount").style.visibility = 'visible';
			document.getElementById("x_check_amount").style.visibility = 'visible';
			document.getElementById("x_check_ob_amount").style.visibility = 'visible';	
			document.getElementById("x_amount").readOnly = true;	
			
			//document.getElementById("x_idaccount").focus();
			break;
		default:
			//alert(valor);
			document.getElementById("x_idbank").style.visibility = 'hidden';
			document.getElementById("x_idaccount").style.visibility = 'hidden';
			document.getElementById("x_external_account_number").style.visibility = 'hidden';
			document.getElementById("x_document_number").style.visibility = 'hidden';
			document.getElementById("x_cash_amount").style.visibility = 'hidden';
			document.getElementById("x_check_amount").style.visibility = 'hidden';
			document.getElementById("x_check_ob_amount").style.visibility = 'hidden';	
			//document.getElementById("x_amount").focus();
			break;
	}
	document.getElementById("x_idcurrency").focus();

}


function setDate(control)
{

	var currentTime = new Date();
	var month = currentTime.getMonth();
	var day = currentTime.getDate();
	var year = currentTime.getFullYear();
	document.getElementById(control).value = day + "/" + month + "/" + year;

	//document.write()
}

function validate_myform(form_name)
{
	var fecha1= form_name.date1.value;
	var fecha2= form_name.date2.value;   
	var dfecha1,dfecha2;
	if (fecha1.length<10 || fecha2.length<10)
	{
		alert ('Fechas no validas');
		return false;
	}
	else
	{
		dfecha1 = new Date();
		dfecha2 = new Date();
		//dfecha1.setFullYear(fecha1.substring(6,10),fecha1.substring(3,5),fecha1.substring(0,2));
		//dfecha2.setFullYear(fecha2.substring(6,10),fecha2.substring(3,5),fecha2.substring(0,2));
		dfecha1=fecha1.substring(6,10)+""+fecha1.substring(3,5)+""+fecha1.substring(0,2);
		dfecha2=fecha2.substring(6,10)+""+fecha2.substring(3,5)+""+fecha2.substring(0,2);
		
		if (dfecha1>dfecha2)
		{
			alert ('La fecha de inicio debe ser menor o igual a la de finalizacion');
			return false;
		}
		else
		{
			return true;
		}
	}
}

function checkdate(control)
  {
    // regular expression to match required date format
    re = /^(\d{1,2})\/(\d{1,2})\/(\d{4})$/;

    if(valor.value != '') {
      if(regs = valor.value.match(re)) {
        if(regs[1] < 1 || regs[1] > 31) {
          alert("Invalid value for day: " + regs[1]);
          valor.focus();
          return false;
        }
        if(regs[2] < 1 || regs[2] > 12) {
          alert("Invalid value for month: " + regs[2]);
          valor.focus();
          return false;
        }
        if(regs[3] < 1902 || regs[3] > (new Date()).getFullYear()) {
          alert("Invalid value for year: " + regs[3] + " - must be between 1902 and " + (new Date()).getFullYear());
          valor.focus();
          return false;
        }
		return true;
      } else {
        alert("Invalid date format: " + valor.value);
        valor.focus();
        return false;
      }
    }
}

function fnc_disable_components(valor, opcion)
{
	
	if (opcion == 1)
	{
	
		if (valor == 1)
		{
			document.getElementById("x_idcustomer").style.visibility = 'hidden';	
			//document.getElementById("x_allow_new_order").style.visibility = 'hidden';	
			var elementos = document.getElementsByName("x_allow_new_order")
			//comente para que no se oculte la opcion
			//for(i=1; i<elementos.length; i++)
			//{	
			//elementos[i].style.visibility = 'hidden';	
			//}
			document.getElementById("x_reason").focus();
		}
		else
		{
			document.getElementById("x_idcustomer").style.visibility = 'visible';	
			var elementos = document.getElementsByName("x_allow_new_order")
			//comente para que no se oculte la opcion
			//for(i=1; i<elementos.length; i++)
			//{
			//	elementos[i].style.visibility = 'visible';	
			//}
			document.getElementById("x_idcustomer").focus();
		}
	}
	else
	{
		if (valor == 1)
		{
			document.getElementById("x_idroute").style.visibility = 'visible';	
			document.getElementById("x_idroute").focus();
		}
		else
		{
			document.getElementById("x_idroute").style.visibility = 'hidden';	
		}
	}
}

function fnc_calc_total_amount()
{
	var c1 = parseFloat(document.getElementById("x_cash_amount").value);
	var c2 = parseFloat(document.getElementById("x_check_amount").value);
	var c3 = parseFloat(document.getElementById("x_check_ob_amount").value);
	document.getElementById("x_amount").value =  Math.round((c1+c2+c3)*100)/100;
}

function fnc_calc_refund(control)
{
	var controlname = control.name;
	var line = controlname.substring(1,controlname.indexOf('_'));
	var otroctrl = "x"+line+"_item_price";
	var totctrl= "x"+line+"_total";
	var valor_t = parseFloat(control.value)*parseFloat(document.getElementById(otroctrl).value);
	
	document.getElementById(totctrl).value = Math.round(valor_t*100)/100;
}
 
function validate_myform_cash(form_name)
{
	
	var cuenta = form_name.cuentas.value; 
	var ndoc = form_name.ndoc.value; 
	if (cuenta == 0 || ndoc == 0 )
		{
		alert ("Ingresar datos necesarios en campos vacios");
		return false;
		}
	else
		{
		return true;
		}
}

function validate_date(form_name)
{
	var date1 = form_name.date1.value.substring(6,10)+form_name.date1.value.substring(3,5)+form_name.date1.value.substring(0,2); 
	var date2 = form_name.date2.value.substring(6,10)+form_name.date2.value.substring(3,5)+form_name.date2.value.substring(0,2); 
	if (date1>date2 )
		{
		form_name.date2.value =form_name.date1.value;
		}
}

function modal_Dialog(src, hei,wid, col, ph, pw)
	{
	src=src.split("?");
	window.location.href="#modalDialogHeader";
	div=document.getElementById("modalDialogHeader");
	$(div).html("<img src='images/loading.gif' align='center'>");
	ajax_dynamic_div("GET",src[0],src[1],div,false);
	$(div).html('<div><image src="general_repository/image/close_48x48.png" width = 20  class=\'close\'onclick="window.location.href=\'#close\'"/>'+$(div).html()+'</div>');
	}
function modal_njf(src, hei,wid, col)
	{
	if(hei===undefined)
		{
		hei=500;
		}
	if(wid===undefined)
		{
		wid=450;
		}
	if(col===undefined)
		{
		col="#59f";
		}

	$.modal('<iframe src="' + src + '" height="'+hei+'" width="'+wid+'" style="border:0">', 
		{
			closeHTML:"",
			opacity:80,
			position: ["20%","32%"],
			overlayCss: {backgroundColor:col},
			containerCss:{backgroundColor:"#fff",borderColor:"#fff"},
			onOpen: function (dialog) 
				{
				dialog.overlay.fadeIn('fast', function () {dialog.data.hide();dialog.container.fadeIn('fast', function () {dialog.data.slideDown('fast');});});
				},
			onClose: function (dialog) 
				{
				dialog.data.fadeOut('fast', function () {dialog.container.hide('fast', function () {dialog.overlay.slideUp('fast', function () {$.modal.close();show_date();});});});
				},
			overlayClose:true,
			autoResize:true
		});
	}
function modal_invoice_onDemand(src, hei,wid, col, ph, pw)
	{
	if(hei===undefined)
		{
		hei=500;
		}
	if(wid===undefined)
		{
		wid=450;
		}
	if(col===undefined)
		{
		col="#59f";
		}
	if(ph===undefined)
		{
		ph="20";
		}
	if(pw===undefined)
		{
		pw="32";
		}
	
	$.modal('<iframe src="' + src + '" height="'+hei+'" width="'+wid+'" style="border:0">', 
		{
			closeHTML:"",
			opacity:80,
			position: [ph+"%",pw+"%"],
			overlayCss: {backgroundColor:col},
			containerCss:{backgroundColor:"#fff",borderColor:"#fff"},
			onOpen: function (dialog) 
				{
				dialog.overlay.fadeIn('fast', function () {dialog.data.hide();dialog.container.fadeIn('fast', function () {dialog.data.slideDown('fast');});});
				},
			onClose: function (dialog) 
				{
				dialog.data.fadeOut('fast', function () {dialog.container.hide('fast', function () {dialog.overlay.slideUp('fast', function () {$.modal.close();aler();});});});
				},
			overlayClose:true,
			autoResize:true
		});
	}
function modal_nd(src, hei,wid, col)
	{
	if(hei===undefined)
		{
		hei=500;
		}
	if(wid===undefined)
		{
		wid=450;
		}
	if(col===undefined)
		{
		col="#59f";
		}

	$.modal('<iframe src="' + src + '" height="'+hei+'" width="'+wid+'" style="border:0">', 
		{
			closeHTML:"",
			opacity:80,
			position: ["20%","32%"],
			overlayCss: {backgroundColor:col},
			containerCss:{backgroundColor:"#fff",borderColor:"#fff"},
			onOpen: function (dialog) 
				{
				dialog.overlay.fadeIn('fast', function () {dialog.data.hide();dialog.container.fadeIn('fast', function () {dialog.data.slideDown('fast');});});
				},
			onClose: function (dialog) 
				{
				dialog.data.fadeOut('fast', function () {dialog.container.hide('fast', function () {dialog.overlay.slideUp('fast', function () {$.modal.close();show_date();});});});
				},
			overlayClose:true,
			autoResize:true
		});
	}	
function permite(elEvento, permitidos) // Variables que definen los caracteres permitidos
	{
	var numeros = "0123456789";
	var numeros_guion = "0123456789-";
	var caracteres = " abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZ";
	var numeros_caracteres = numeros + caracteres;
	var numeros_caracteres_guion = numeros + caracteres+"-";
	var teclas_especiales = [8, 37, 39, 46];// 8 = BackSpace, 46 = Supr, 37 = flecha izquierda, 39 = flecha derecha
	 
	switch(permitidos)// Seleccionar los caracteres a partir del parámetro de la función 
		{
		case 'num':
			permitidos = numeros;
			break;
		case 'num2':
			permitidos = numeros_guion;
			break;
		case 'car':
			permitidos = caracteres;
			break;
		case 'num_car':
			permitidos = numeros_caracteres;
			break;
		case 'num_car2':
			permitidos = numeros_caracteres_guion;
			break;
		}
	 
	var evento = elEvento || window.event; // Obtener la tecla pulsada
	var codigoCaracter = evento.charCode || evento.keyCode;
	var caracter = String.fromCharCode(codigoCaracter);
	 
	var tecla_especial = false;// Comprobar si la tecla pulsada es alguna de las teclas especiales (teclas de borrado y flechas horizontales)
	for(var i in teclas_especiales) 
		{
		if(codigoCaracter == teclas_especiales[i]) 
			{
			tecla_especial = true;
			break;
			}
		}
	return permitidos.indexOf(caracter) != -1 || tecla_especial;// Comprobar si la tecla pulsada se encuentra en los caracteres permitidos o si es una tecla especial
	}

// function ajax_dynamic_div(tipo, ajax_page, params, div_resp)
		// {
		// $.ajax({type: tipo,
				// url: ajax_page,
				// data: params,
				// dataType: "text/html",
				// success: function(html){$(div_resp).html(html);}
				// }); 
		// }
function ajax_dynamic_div(tipo, ajax_page, params, div_resp, modo)
		{
		if(modo===undefined)
			{
			modo=true;
			}
		//$(div_resp).html("<img src='images/loading.gif' align='center'>");
		$.ajax({type: tipo,
				url: ajax_page,
				data: params,
				async:modo,
				dataType: "text/html",
				success: function(html){$(div_resp).html(html);}
				}); 
		}
function nuevoAjax()
	{ //función cross-browser 
	var xmlhttp=false;
	try 
		{
		xmlhttp = new ActiveXObject("Msxml2.XMLHTTP");
		} 
	catch (e) 
		{
		try {
			xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
			} 
		catch (E) 
			{
			xmlhttp = false;
			}
		}
	if (!xmlhttp) 
		{
		if (typeof XMLHttpRequest!='undefined')
		xmlhttp = new XMLHttpRequest();
		}
	return xmlhttp;
	}
function show_ticket_dev(cod,div,ref)
	{
	div.innerHTML="";
	if(cod!="")
		{
		str_param = "cod="+cod+"&up=0&op=1"+"&ref="+ref;
		ajax_dynamic_div("GET",'get_boleta.php',str_param,div);
		}
	}
function nombre_dia(num_dia) // Variables que definen los caracteres permitidos
	{
	switch(num_dia)// Seleccionar los caracteres a partir del parámetro de la función 
		{
		case 1:
			name = "lunes";
			break;
		case 2:
			name = "martes";
			break;
		case 3:
			$name = "mi&eacute;rcoles";
			break;
		case 4:
			name = "jueves";
			break;
		case 5:
			name = "viernes";
			break;
		case 6:
			name = "s&aacute;bado";
			break;
		case 7:
			name = "domingo";
			break;
		}
	return name;
	}