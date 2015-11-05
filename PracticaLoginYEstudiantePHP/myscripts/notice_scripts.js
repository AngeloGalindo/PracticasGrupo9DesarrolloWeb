function susc_state(){
show_notice_code();    
}

function edit_notice(posicion,op){
code=document.getElementById("cod_cliente").value    
sus_number=document.getElementById("filter").value    
document.getElementById("sus_notice").value=document.getElementById("idsuscription_notice_"+posicion).value;

    if(document.getElementById('check_state_'+posicion).checked){
    show_update();

    }else{
            alert("No es posible modificar notificaciones bloqueadas");    

    }

}
 function show_update(){ 
   	document.getElementById("div_actualizar").style.visibility="visible";
    document.getElementById('div_actualizar').style.display = 'inline';  
   	document.getElementById("div_reporte").style.visibility="hidden";
    document.getElementById('div_reporte').style.display = 'none';  
    document.getElementById("div_anular").style.visibility="hidden";
 	document.getElementById('div_anular').style.display = 'none';   
    document.getElementById("div_grabar").style.visibility="hidden";
 	document.getElementById('div_grabar').style.display = 'none';   
    document.getElementById("div_listar").style.visibility="hidden";
 	document.getElementById('div_listar').style.display = 'none'; 
    document.getElementById("div_buscar").style.visibility="hidden";
 	document.getElementById('div_buscar').style.display = 'none'; 
   	document.getElementById("div_code").style.visibility="visible";
    document.getElementById('div_code').style.display = 'inline';    
    document.getElementById("div_notice").style.visibility="visible";
 	document.getElementById('div_notice').style.display = 'inline'; 
    document.getElementById("div_sus").style.visibility="visible";
 	document.getElementById('div_sus').style.display = 'inline';  
          if(document.getElementById('cod_cliente').value ==''){
           alert("Proceda a ingresar el código del cliente");       
           }else if(document.getElementById('sus_number').value ==''){
           alert("Proceda a ingresar el número de suscripción");           
           }else if(document.getElementById('sus_notice').value ==''){
           alert("Proceda a ingresar el número de notificación");       
           }else{
    code=document.getElementById('cod_cliente').value;
    sus_number=document.getElementById('sus_number').value;
    sus_notice=document.getElementById('sus_notice').value;
    div=document.getElementById("div_actualizar");
	$(div).html("<img src='images/loading.gif' align='center'>");
	str_param="code="+code+"&sus_number="+sus_number+"&sus_notice="+sus_notice+"&actualizar_tabla=1";
	ajax_dynamic_div("POST",'suscription_notice_tabla.php',str_param,div);  
   }
 }


function change_status(check_ob,id)
	{
        idnotice_number=document.getElementById('idnotice_number_'+id).value;
		$('#div_'+id).html("<img src='images/loading.gif' align='center'>");
		if (check_ob.checked)
			{
	    document.getElementById('div_status_'+id).innerHTML = 'Activa';			 
        status='Activa';
		str_param="idnotice_number="+idnotice_number+"&status="+status+"&anular_notificacion=1";
		ajax_dynamic_div("POST",'suscription_notice_operations.php',str_param,"#div_result",false);
  			}
		else
			{
	    document.getElementById('div_status_'+id).innerHTML = 'Inactiva';
        status='Inactiva';
		str_param="idnotice_number="+idnotice_number+"&status="+status+"&anular_notificacion=1";
		ajax_dynamic_div("POST",'suscription_notice_operations.php',str_param,"#div_result",false);

			}
	}

function reporte_manana()
	{   
        div=document.getElementById("div_pdf");
    	$(div).html("<img src='images/loading.gif' align='center'>");
	    tomorrow=document.getElementById('manana').value;
        fecha_reporte=document.getElementById('fecha_reporte').value;
		str_param="fecha_manana="+tomorrow+"&reporte_manana=1";
		ajax_dynamic_div("POST",'notice_pdf.php',str_param,div,false);
	}


function reporte_completo(){

   	document.getElementById("div_pdf_2").style.visibility="visible";
    document.getElementById('div_pdf_2').style.display = 'inline'; 

        date_string1=document.getElementById('datepicker1_report').value;
        date1=date_string1.substring(6,10)+date_string1.substring(3,5)+date_string1.substring(0,2);
        date_string2=document.getElementById('datepicker2_report').value;
        date2=date_string2.substring(6,10)+date_string2.substring(3,5)+date_string2.substring(0,2)
        filter_state_report=document.getElementById('report_state').value;
        filter_notice_report=document.getElementById('notice_type_hidden').value;
        div2=document.getElementById("div_pdf_2");
     	$(div2).html("<img src='images/loading.gif' align='center'>");
     	str_param2="date1="+date1+"&date2="+date2+"&filter_notice_report="+filter_notice_report+"&filter_state_report="+filter_state_report+"&reporte_parametros_pdf=1";
		ajax_dynamic_div("POST",'suscription_notice_tabla.php',str_param2,div2,false);
}

function reporte_parametros()
	{   
	   alert(); 
        date_string1=document.getElementById('datepicker1_report').value;
        date1=date_string1.substring(6,10)+"-"+date_string1.substring(3,5)+"-"+date_string1.substring(0,2);
        date_string2=document.getElementById('dateícker2_report').value;
        date2=date_string2.substring(6,10)+"-"+date_string2.substring(3,5)+"-"+date_string2.substring(0,2)

	}



function show_notice_code(op)
	{
	   document.getElementById('div_buscar').innerHTML = '';
  	if (op==1){
   	document.getElementById("div_buscar").style.visibility="hidden";
    document.getElementById('div_buscar').style.display = 'none';          
  	}else{
    document.getElementById("div_buscar").style.visibility="visible";
    document.getElementById('div_buscar').style.display = 'inline';
  	}
    document.getElementById("div_report").style.visibility="hidden";
    document.getElementById('div_report').style.display = 'none';  

    document.getElementById("div_pdf_2").style.visibility="hidden";
 	document.getElementById('div_pdf_2').style.display = 'none';     
    
    document.getElementById("div_anular").style.visibility="hidden";
 	document.getElementById('div_anular').style.display = 'none';     
 
    document.getElementById("div_insertar").style.visibility="hidden";
 	document.getElementById('div_insertar').style.display = 'none'; 
 
    document.getElementById("div_actualizar").style.visibility="hidden";
 	document.getElementById('div_actualizar').style.display = 'none';   
 
   	document.getElementById("div_code").style.visibility="visible";
    document.getElementById('div_code').style.display = 'inline';

    document.getElementById("div_notice").style.visibility="hidden";
 	document.getElementById('div_notice').style.display = 'none'; 

    document.getElementById("div_grabar").style.visibility="hidden";
 	document.getElementById('div_grabar').style.display = 'none'; 


    document.getElementById("div_listar").style.visibility="hidden";
 	document.getElementById('div_listar').style.display = 'none';
    
    document.getElementById("div_sus").style.visibility="hidden";
 	document.getElementById('div_sus').style.display = 'none'; 

    code=document.getElementById('cod_cliente').value;
        if(code=='') {
            alert("Ingrese código del cliente");
        }
        else{    
      	document.getElementById("div_reporte").style.visibility="hidden";
        document.getElementById('div_reporte').style.display = 'none';  
     	document.getElementById("div_filter").style.visibility="visible";
        document.getElementById('div_filter').style.display = 'inline';     
        filter_state=document.getElementById('filter_state').value;     

        div=document.getElementById("div_filter");
    	$(div).html("<img src='images/loading.gif' align='center'>");
    	str_param="code="+code+"&filter_state="+filter_state+"&ver_filter=1";
        ajax_dynamic_div("POST",'suscription_notice_tabla.php',str_param,div);

       
////     	div=document.getElementById("div_buscar");
//    	$(div).html("<img src='images/loading.gif' align='center'>");
//    	str_param="code="+code+"&ver_tabla=1";
//    	ajax_dynamic_div("POST",'suscription_notice_tabla.php',str_param,div);
    	}
    }
function show_notice_filter(objeto){            
   	document.getElementById("div_reporte").style.visibility="hidden";
    document.getElementById('div_reporte').style.display = 'none'; 
   	document.getElementById("div_filter").style.visibility="visible";
    document.getElementById('div_filter').style.display = 'inline'; 
    
    document.getElementById("sus_number").value=document.getElementById("filter").value;
    idsuscription_detail=document.getElementById("sus_number").value;
    code=document.getElementById("cod_cliente").value;
 	div=document.getElementById("div_buscar");
	$(div).html("<img src='images/loading.gif' align='center'>");
	str_param="code="+code+"&idsuscription_detail="+idsuscription_detail+"&ver_tabla=1";
	ajax_dynamic_div("POST",'suscription_notice_tabla.php',str_param,div);       
}


function show_notice(){ 
    document.getElementById("sus_number").value=document.getElementById("filter").value;
   	document.getElementById("div_reporte").style.visibility="hidden";
    document.getElementById('div_reporte').style.display = 'none';  
    document.getElementById("div_insertar").style.visibility="hidden";
 	document.getElementById('div_insertar').style.display = 'none'; 
    document.getElementById("div_anular").style.visibility="hidden";
 	document.getElementById('div_anular').style.display = 'none';   
    document.getElementById("div_grabar").style.visibility="hidden";
 	document.getElementById('div_grabar').style.display = 'none'; 
    document.getElementById("div_actualizar").style.visibility="hidden";
 	document.getElementById('div_actualizar').style.display = 'none';   
    document.getElementById("div_code").style.visibility="visible";
 	document.getElementById('div_code').style.display = 'inline'; 
   	document.getElementById("div_listar").style.visibility="hidden";
    document.getElementById('div_listar').style.display = 'none';  
    document.getElementById("div_buscar").style.visibility="visible";
 	document.getElementById('div_buscar').style.display = 'inline'; 
  	document.getElementById("div_notice").style.visibility="hidden";
    document.getElementById('div_notice').style.display = 'none';
    document.getElementById("div_sus").style.visibility="hidden";
 	document.getElementById('div_sus').style.display = 'none'; 
    sus_number=document.getElementById('sus_number').value;
        if(sus_number=='') {
            alert("Ingrese número de notificación");
        }
        else{
      	div=document.getElementById("div_listar");
        idsuscription_detail=document.getElementById("filter").value;
        code=document.getElementById("cod_cliente").value;
    	$(div).html("<img src='images/loading.gif' align='center'>");
    	str_param="code="+code+"&idsuscription_detail="+idsuscription_detail+"&ver_tabla=1";
    	ajax_dynamic_div("POST",'suscription_notice_tabla.php',str_param,div);  
        } 
 }

function show_insert(){


   	document.getElementById("div_grabar").style.visibility="visible";
    document.getElementById('div_grabar').style.display = 'inline';  
   	document.getElementById("div_reporte").style.visibility="hidden";
    document.getElementById('div_reporte').style.display = 'none';  
    document.getElementById("div_anular").style.visibility="hidden";
 	document.getElementById('div_anular').style.display = 'none';   
    document.getElementById("div_actualizar").style.visibility="hidden";
 	document.getElementById('div_actualizar').style.display = 'none';   
    document.getElementById("div_listar").style.visibility="hidden";
 	document.getElementById('div_listar').style.display = 'none'; 
    document.getElementById("div_buscar").style.visibility="hidden";
 	document.getElementById('div_buscar').style.display = 'none'; 
   	document.getElementById("div_code").style.visibility="visible";
    document.getElementById('div_code').style.display = 'inline';    
    document.getElementById("div_sus").style.visibility="hidden";
 	document.getElementById('div_sus').style.display = 'none'; 
    if(document.getElementById('cod_cliente').value ==''){
    alert("Proceda a ingresar el código del cliente");       
    }else if(document.getElementById('sus_number').value =='') {
    alert("Proceda a ingresar el número de suscripción");              
    }else{
    code=document.getElementById('cod_cliente').value;
    sus_number=document.getElementById('sus_number').value;
    div=document.getElementById("div_grabar");
	$(div).html("<img src='images/loading.gif' align='center'>");
	str_param="code="+code+"&sus_number="+sus_number+"&grabar_tabla=1";
	ajax_dynamic_div("POST",'suscription_notice_tabla.php',str_param,div);  




    }
 }
 
 function show_report(){ 

    
   	document.getElementById("div_code").style.visibility="hidden";
    document.getElementById('div_code').style.display = 'none';  

   	document.getElementById("div_report").style.visibility="visible";
    document.getElementById('div_report').style.display = 'inline';  

   	document.getElementById("div_actualizar").style.visibility="hidden";
    document.getElementById('div_actualizar').style.display = 'none';  
    document.getElementById("div_anular").style.visibility="hidden";
 	document.getElementById('div_anular').style.display = 'none';   
    document.getElementById("div_grabar").style.visibility="hidden";
 	document.getElementById('div_grabar').style.display = 'none';   
    document.getElementById("div_listar").style.visibility="hidden";
 	document.getElementById('div_listar').style.display = 'none'; 
    document.getElementById("div_buscar").style.visibility="hidden";
 	document.getElementById('div_buscar').style.display = 'none'; 
//   	document.getElementById("div_code").style.visibility="hidden";
//    document.getElementById('div_code').style.display = 'none';    
//    document.getElementById("div_notice").style.visibility="hidden";
// 	document.getElementById('div_notice').style.display = 'none'; 
//    document.getElementById("div_sus").style.visibility="hidden";
// 	document.getElementById('div_sus').style.display = 'none';  
    document.getElementById("div_filter").style.visibility="hidden";
 	document.getElementById('div_filter').style.display = 'none';  
//    div=document.getElementById("div_reporte");
//  	$(div).html("<img src='images/loading.gif' align='center'>");
//  	str_param="&reporte=1";
//  	ajax_dynamic_div("POST",'suscription_notice_tabla.php',str_param,div);  
} 
 
 
function grabar(){
    if(document.getElementById('cod_cliente').value ==''){
    alert("Proceda a ingresar el código del cliente");       
    }else if(document.getElementById('sus_number').value ==''){
    alert("Proceda a ingresar el número de suscripción");           
    }else if(document.getElementById('observacion').value ==''){
    alert("Proceda a ingresar lo que desea notificar");       
    }else{
    code=document.getElementById('cod_cliente').value;
    delivery_mode=document.getElementById('modo_entrega').value;
    notice_type=document.getElementById('notice_type_new').value;
    observation=document.getElementById('observacion').value;
    sus_number=document.getElementById('sus_number').value;
    date_string1=document.getElementById('date4').value;
    date1=date_string1.substring(6,10)+"-"+date_string1.substring(3,5)+"-"+date_string1.substring(0,2);
    div=document.getElementById("div_insertar");
	$(div).html("<img src='images/loading.gif' align='center'>");
  	str_param="code="+code+"&delivery_mode="+delivery_mode+"&notice_type="+notice_type+"&observation="+observation+"&sus_number="+sus_number+"&print_date="+date1+"&save=1";
    ajax_dynamic_div("POST",'suscription_notice_operations.php',str_param,div);
   }
}

function editar(){
    
    if(document.getElementById('cod_cliente').value ==''){
    alert("Proceda a ingresar el código del cliente");       
    }else if(document.getElementById('sus_number').value ==''){
    alert("Proceda a ingresar el número de suscripción");           
    }else if(document.getElementById('sus_notice').value ==''){
    alert("Proceda a ingresar el número de notificación");           
    }else if(document.getElementById('observation').value ==''){
    alert("Proceda a ingresar lo que desea notificar en el area de observaciones");       
    }else if(document.getElementById('delivery_mode').value ==''){
    alert("Proceda a ingresar lo que desea notificar en el area de modo de entrega");       
    }else{
    code=document.getElementById('cod_cliente').value;
    sus_number=document.getElementById('sus_number').value;
    sus_notice=document.getElementById('sus_notice').value;
    delivery_mode=document.getElementById('delivery_mode').value;
    notice_type=document.getElementById('notice_type').value;
    observation=document.getElementById('observation').value;
    idcustomer=document.getElementById('idcustomer').value;
    date_string1=document.getElementById('date3').value;
    date1=date_string1.substring(6,10)+"-"+date_string1.substring(3,5)+"-"+date_string1.substring(0,2);
    div=document.getElementById("div_editar");
	$(div).html("<img src='images/loading.gif' align='center'>");
  	str_param="code="+code+"&idcustomer="+idcustomer+"&delivery_mode="+delivery_mode+"&notice_type="+notice_type+"&observation="+observation+"&sus_notice="+sus_notice+"&sus_number="+sus_number+"&print_date="+date1+"&editar=1";
    ajax_dynamic_div("POST",'suscription_notice_operations.php',str_param,div);
    //show_notice();
}
}

function show_anular(){ 
    document.getElementById("div_reporte").style.visibility="hidden";
    document.getElementById('div_reporte').style.display = 'none';  
   	document.getElementById("div_anular").style.visibility="visible";
    document.getElementById('div_anular').style.display = 'inline';  
    document.getElementById("div_actualizar").style.visibility="hidden";
 	document.getElementById('div_actualizar').style.display = 'none';   
    document.getElementById("div_grabar").style.visibility="hidden";
 	document.getElementById('div_grabar').style.display = 'none';   
    document.getElementById("div_listar").style.visibility="hidden";
 	document.getElementById('div_listar').style.display = 'none'; 
    document.getElementById("div_buscar").style.visibility="hidden";
 	document.getElementById('div_buscar').style.display = 'none'; 
   	document.getElementById("div_code").style.visibility="visible";
    document.getElementById('div_code').style.display = 'inline';    
    document.getElementById("div_notice").style.visibility="visible";
 	document.getElementById('div_notice').style.display = 'inline'; 
    document.getElementById("div_sus").style.visibility="hidden";
 	document.getElementById('div_sus').style.display = 'none';  
          if(document.getElementById('cod_cliente').value ==''){
           alert("Proceda a ingresar el código del cliente");       
           }else if(document.getElementById('sus_number').value ==''){
           alert("Proceda a ingresar el número de suscripción");           
           }else{
    code=document.getElementById('cod_cliente').value;
    sus_number=document.getElementById('sus_number').value;
    div=document.getElementById("div_anular");
	$(div).html("<img src='images/loading.gif' align='center'>");
	str_param="code="+code+"&sus_number="+sus_number+"&anular=1";
	ajax_dynamic_div("POST",'suscription_notice_tabla.php',str_param,div);
   }
 }

 
