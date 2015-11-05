function fncEdition(edition){
	//document.getElementById(edition).style.background = "#E0F8E0";
	div=document.getElementById("div_tabla2");
	$(div).html("<img src='images/loading.gif' align='center'>");
	str_param="edition="+edition+"&viewEdition=1";
	ajax_dynamic_div("POST",'gen_bitacora_get.php',str_param,div);
}
	
function fncEvent(edition){
	div=document.getElementById("div_tabla2");
	$(div).html("<img src='images/loading.gif' align='center'>");
	str_param="edition="+edition+"&viewEvent=1";
	ajax_dynamic_div("POST",'gen_bitacora_get.php',str_param,div);
}
	
function fncShowMoreDetail(edition){
	div=document.getElementById("divMoreDetail");
	$(div).html("<img src='images/loading.gif' align='center'>");
	str_param="edition="+edition+"&showMoreDetail=1";
	ajax_dynamic_div("POST",'gen_bitacora_get.php',str_param,div);
}
function fncShowPublicationType(edition){
	div=document.getElementById("divMoreDetail2");
	$(div).html("<img src='images/loading.gif' align='center'>");
	day1 = edition.substring(0,4)+edition.substring(5,7)+edition.substring(8,10);
	str_param="fi="+day1+"&ff="+day1+"&tipo=0&mod=2&orden=1";
	ajax_dynamic_div("GET",'gen_bitacora_reporte_regionales_tabla.php',str_param,div,false);
}
function show_chart(link,strparams)
	{
	div=document.getElementById('ajaxresult2detail');
	$(div).html("");
	ajax_dynamic_div("POST",link,strparams,div,false);
	}


function fncNewEvent(edition){
	div=document.getElementById("divNewEvent");
	$(div).html("<img src='images/loading.gif' align='center'>");
	edition = edition.substring(0,4)+edition.substring(5,7)+edition.substring(8,10);
	str_param="edition="+edition+"&showNewEvent=1";
	ajax_dynamic_div("POST",'gen_bitacora_get.php',str_param,div);
}

function fncNewFront(edition){
	div=document.getElementById("divNewEvent");
	$(div).html("<img src='images/loading.gif' align='center'>");
	edition = edition.substring(0,4)+edition.substring(5,7)+edition.substring(8,10);
	str_param="edition="+edition+"&showNewFront=1";
	ajax_dynamic_div("POST",'gen_bitacora_get.php',str_param,div);
}

function fncEditEvent(){
	document.getElementById("idevent_damage").disabled = false;
	document.getElementById("idevent_type").disabled = false;
	document.getElementById("event_title").readOnly = false;
	document.getElementById("event_description").readOnly = false;
	document.getElementById("deleteEditButton").style.visibility="hidden";
	document.getElementById("editButton").style.visibility="hidden";
	document.getElementById("saveEditButton").style.visibility="visible";
}

function fncEditFront(){
	document.getElementById("idfront_type").disabled = false;
	document.getElementById("idholder_type").disabled = false;
	document.getElementById("front_description").readOnly = false;
	document.getElementById("deleteEditButton").style.visibility="hidden";
	document.getElementById("editButton").style.visibility="hidden";
	document.getElementById("saveEditButton").style.visibility="visible";
}	
	
function fncViewEvent(id){
	div=document.getElementById("divNewEvent");
	$(div).html("<img src='images/loading.gif' align='center'>");
	str_param="id="+id+"&showViewEvent=1";
	ajax_dynamic_div("POST",'gen_bitacora_get.php',str_param,div);
}	

function fncViewFront(id){
	div=document.getElementById("divNewEvent");
	$(div).html("<img src='images/loading.gif' align='center'>");
	str_param="id="+id+"&showViewFront=1";
	ajax_dynamic_div("POST",'gen_bitacora_get.php',str_param,div);
}	

function fncViewRoute(id){
	div=document.getElementById("divRelEventRoute");
	$(div).html("<img src='images/loading.gif' align='center'>");
	str_param="id="+id+"&showViewRoute=1";
	ajax_dynamic_div("POST",'gen_bitacora_get.php',str_param,div);
}	

function fncShowMenu(edition){
	div=document.getElementById("divMoreDetail2");
	$(div).html("<img src='images/loading.gif' align='center'>");
	day1 = edition.substring(0,4)+edition.substring(5,7)+edition.substring(8,10);
	str_param="day1="+day1+"&section=0&show=0&ver_tabla=1&BitacoraFlag=1";
	ajax_dynamic_div("POST",'menu_edition_table.php',str_param,div,false);
	$(div).html("<div><div ><image src='general_repository/image/close_48x48.png' width = 24 onclick='window.location.href=\"#close\"' class=\"close\"/><br/><br/>"+$(div).html()+'</div>');
}

function fncEdit(){
	div=document.getElementById("div_tabla");
	$(div).html("<img src='images/loading.gif' align='center'>");
	month=document.getElementById('month').value;
	year=document.getElementById('year').value;
	str_param="month="+month+"&year="+year+"&showEdit=1";
	ajax_dynamic_div("POST",'gen_bitacora_get.php',str_param,div);
	$(document.getElementById("div_tabla3")).html("");
	$(document.getElementById("div_tabla2")).html("");
}

function fncPrint(){
	div=document.getElementById("div_tabla3");
	$(div).html("<img src='images/loading.gif' align='center'>");
	month=document.getElementById('month').value;
	year=document.getElementById('year').value;
	str_param="month="+month+"&year="+year+"&showPrint=1";
	ajax_dynamic_div("POST",'gen_bitacora_get.php',str_param,div);
	$(document.getElementById("div_tabla")).html("");
	$(document.getElementById("div_tabla2")).html("");
}

function fncSaveEvent(){
	div=document.getElementById("divUpdate");
	$(div).html("<img src='images/loading.gif' align='center'>");
	event_edition=document.getElementById('event_edition').value;
	idevent_type=document.getElementById('idevent_type').value;
	idevent_damage=document.getElementById('idevent_damage').value;
	event_title=document.getElementById('event_title').value;
	event_description=document.getElementById('event_description').value;
	str_param="event_edition="+event_edition+"&idevent_type="+idevent_type+"&idevent_damage="+idevent_damage+"&event_title="+event_title+"&event_description="+event_description+"&insertEvent=1";
	ajax_dynamic_div("POST",'gen_bitacora_update.php',str_param,div,'false');
	show_ver(0);
	fncEvent(event_edition);
}	

function fncSaveEditionRoute(){
	div=document.getElementById("divUpdate");
	$(div).html("<img src='images/loading.gif' align='center'>");
	idevent=document.getElementById('idevent').value;
	idroute=document.getElementById('idroute').value;
	str_param="idroute="+idroute+"&idevent="+idevent+"&insertRoute=1";
	ajax_dynamic_div("POST",'gen_bitacora_update.php',str_param,div,'false');
	fncViewRoute(idevent);
};

function fncDeleteEditionRoute(id){
	div=document.getElementById("divUpdate");
	$(div).html("<img src='images/loading.gif' align='center'>");
	str_param="id="+id+"&deleteRoute=1";
	ajax_dynamic_div("POST",'gen_bitacora_update.php',str_param,div,'false');
	idevent=document.getElementById('idevent').value;
	fncViewRoute(idevent);
}

function fncRefreshEdition(edition){
	div=document.getElementById("divUpdate");
	$(div).html("<img src='images/loading.gif' align='center'>");
	str_param="edition="+edition+"&refreshEdition=1";
	ajax_dynamic_div("POST",'gen_bitacora_update.php',str_param,div,'false');
	show_ver(0);
	fncEdition(edition);
	
}

function fncSaveFront(){
	div=document.getElementById("divUpdate");
	$(div).html("<img src='images/loading.gif' align='center'>");
	edition=document.getElementById('event_edition').value;
	idholder_type=document.getElementById('idholder_type').value;
	idfront_type=document.getElementById('idfront_type').value;
	front_description=document.getElementById('front_description').value;
	str_param="edition="+edition+"&idholder_type="+idholder_type+"&idfront_type="+idfront_type+"&front_description="+front_description+"&insertFront=1";
	ajax_dynamic_div("POST",'gen_bitacora_update.php',str_param,div,'false');
	show_ver(0);
	fncEdition(edition);
}	
	
function fncDeleteEvent(id){
	div=document.getElementById("divUpdate");
	$(div).html("<img src='images/loading.gif' align='center'>");
	event_edition=document.getElementById('event_edition').value;
	str_param="id="+id+"&deleteEvent=1";
	ajax_dynamic_div("POST",'gen_bitacora_update.php',str_param,div,'false');
	show_ver(0);
	fncEvent(event_edition);
}

function fncDeleteFront(id){
	div=document.getElementById("divUpdate");
	$(div).html("<img src='images/loading.gif' align='center'>");
	front_edition=document.getElementById('front_edition').value;
	str_param="id="+id+"&deleteFront=1";
	ajax_dynamic_div("POST",'gen_bitacora_update.php',str_param,div,'false');
	show_ver(0);
	fncEdition(front_edition);
}
	
function fncSaveEditEvent(){
	div=document.getElementById("divUpdate");
	$(div).html("<img src='images/loading.gif' align='center'>");
	event_edition=document.getElementById('event_edition').value;
	idevent_type=document.getElementById('idevent_type').value;
	idevent_damage=document.getElementById('idevent_damage').value;
	event_title=document.getElementById('event_title').value;
	event_description=document.getElementById('event_description').value;
	idevent=document.getElementById('idevent').value;
	str_param="idevent="+idevent+"&event_edition="+event_edition+"&idevent_type="+idevent_type+"&idevent_damage="+idevent_damage+"&event_title="+event_title+"&event_description="+event_description+"&EditEvent=1";
	ajax_dynamic_div("POST",'gen_bitacora_update.php',str_param,div,'false');
	show_ver(0);
	fncEvent(event_edition);
}	

function fncSaveEditFront(){
	div=document.getElementById("divUpdate");
	$(div).html("<img src='images/loading.gif' align='center'>");
	idfront_type=document.getElementById('idfront_type').value;
	idholder_type=document.getElementById('idholder_type').value;
	front_description=document.getElementById('front_description').value;
	front_edition=document.getElementById('front_edition').value;
	idedition_front=document.getElementById('idedition_front').value;
	str_param="idfront_type="+idfront_type+"&idholder_type="+idholder_type+"&front_edition="+front_edition+"&front_description="+front_description+"&idedition_front="+idedition_front+"&EditFront=1";
	ajax_dynamic_div("POST",'gen_bitacora_update.php',str_param,div,'false');
	show_ver(0);
	fncEdition(front_edition);
}	

function fncChangeField(id,value,field){
	div=document.getElementById("divUpdate");
	$(div).html("<img src='general_repository/image/loading.gif' align='center' >Grabando Cambio...");
	strParam="id="+id+"&value="+value+"&field="+field+"&changeField=1";
	ajax_dynamic_div("POST",'gen_target_update.php',strParam,div,false);
}

function fncChangeFieldX(x,value,field){
	id=document.getElementById('idpromotion_'+x).value;
	if(id==0){
		id=document.getElementById('idaddress_'+x).value;
		div=document.getElementById("divUpdate");
		$(div).html("<img src='general_repository/image/loading.gif' align='center' >Grabando Cambio...");
		strParam="id="+id+"&x="+x+"&insertPromotion=1";
		ajax_dynamic_div("POST",'gen_target_update.php',strParam,div,false);
		id=document.getElementById('idpromotion_'+x).value;
	}
	fncChangeField(id,value,field);
}