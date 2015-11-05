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
	switch(valor)
	{
		case 'Cheques':
			document.getElementById("x_idbank").style.visibility = 'visible';
			document.getElementById("x_idaccount").style.visibility = 'hidden';
			document.getElementById("x_external_account_number").style.visibility = 'visible';
			//document.getElementById("x_idbank").focus();
			break;
		case 'Depositos':
			document.getElementById("x_idbank").style.visibility = 'visible';
			document.getElementById("x_idaccount").style.visibility = 'visible';
			document.getElementById("x_external_account_number").style.visibility = 'hidden';
			//document.getElementById("x_idaccount").focus();
			break;
		default:
			//alert(valor);
			document.getElementById("x_idbank").style.visibility = 'hidden';
			document.getElementById("x_idaccount").style.visibility = 'hidden';
			document.getElementById("x_external_account_number").style.visibility = 'hidden';
			document.getElementById("x_document_number").style.visibility = 'hidden';
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
		dfecha1.setFullYear(fecha1.substring(6,10),fecha1.substring(3,5),fecha1.substring(0,2));
		dfecha2.setFullYear(fecha2.substring(6,10),fecha2.substring(3,5),fecha2.substring(0,2));
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
			for(i=1; i<elementos.length; i++)
			{
				elementos[i].style.visibility = 'hidden';	
			}
			document.getElementById("x_reason").focus();
		}
		else
		{
			document.getElementById("x_idcustomer").style.visibility = 'visible';	
			var elementos = document.getElementsByName("x_allow_new_order")
			for(i=1; i<elementos.length; i++)
			{
				elementos[i].style.visibility = 'visible';	
			}
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

function fnc_calc_refund(control)
{
	var controlname = control.name;
	var line = controlname.substring(1,controlname.indexOf('_'));
	var otroctrl = "x"+line+"_item_price";
	var totctrl= "x"+line+"_total";
	
	document.getElementById(totctrl).value = parseFloat(control.value)*parseFloat(document.getElementById(otroctrl).value);
}
 
function validate_myform_cash(form_name)
{
	
	var cuenta = form_name.cuentas.value; 
	var ndoc = form_name.ndoc.value; 
	var marcacaja = form_name.marcacaja.value;
	if (cuenta == 0 || ndoc == 0 || marcacaja == 0)
		{
		alert ("Ingresar datos necesarios en campos vacios");
		return false;
		}
	else
		{
		return true;
		}
}




