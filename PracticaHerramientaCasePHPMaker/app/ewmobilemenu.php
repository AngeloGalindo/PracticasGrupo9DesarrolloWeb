<!-- Begin Main Menu -->
<?php

// Generate all menu items
$RootMenu->IsRoot = TRUE;
$RootMenu->AddMenuItem(28, "mmci_Cate1logo", $Language->MenuPhrase("28", "MenuText"), "", -1, "", TRUE, FALSE, TRUE);
$RootMenu->AddMenuItem(21, "mmi_tipo_turno", $Language->MenuPhrase("21", "MenuText"), "tipo_turnolist.php", 28, "", TRUE, FALSE);
$RootMenu->AddMenuItem(18, "mmi_servicio_medico", $Language->MenuPhrase("18", "MenuText"), "servicio_medicolist.php", 28, "", TRUE, FALSE);
$RootMenu->AddMenuItem(2, "mmi_continente", $Language->MenuPhrase("2", "MenuText"), "continentelist.php", 28, "", TRUE, FALSE);
$RootMenu->AddMenuItem(16, "mmi_pais", $Language->MenuPhrase("16", "MenuText"), "paislist.php?cmd=resetall", 2, "", TRUE, FALSE);
$RootMenu->AddMenuItem(4, "mmi_departamento", $Language->MenuPhrase("4", "MenuText"), "departamentolist.php?cmd=resetall", 16, "", TRUE, FALSE);
$RootMenu->AddMenuItem(13, "mmi_municipio", $Language->MenuPhrase("13", "MenuText"), "municipiolist.php?cmd=resetall", 4, "", TRUE, FALSE);
$RootMenu->AddMenuItem(9, "mmi_especialidad", $Language->MenuPhrase("9", "MenuText"), "especialidadlist.php", 28, "", TRUE, FALSE);
$RootMenu->AddMenuItem(29, "mmi_laboratorio", $Language->MenuPhrase("29", "MenuText"), "laboratoriolist.php?cmd=resetall", 28, "", TRUE, FALSE);
$RootMenu->AddMenuItem(30, "mmi_medicina", $Language->MenuPhrase("30", "MenuText"), "medicinalist.php?cmd=resetall", 29, "", TRUE, FALSE);
$RootMenu->AddMenuItem(11, "mmi_hospital", $Language->MenuPhrase("11", "MenuText"), "hospitallist.php?cmd=resetall", -1, "", TRUE, FALSE);
$RootMenu->AddMenuItem(14, "mmi_nivel", $Language->MenuPhrase("14", "MenuText"), "nivellist.php?cmd=resetall", 11, "", TRUE, FALSE);
$RootMenu->AddMenuItem(17, "mmi_sala", $Language->MenuPhrase("17", "MenuText"), "salalist.php?cmd=resetall", 14, "", TRUE, FALSE);
$RootMenu->AddMenuItem(10, "mmi_habitacion", $Language->MenuPhrase("10", "MenuText"), "habitacionlist.php?cmd=resetall", 17, "", TRUE, FALSE);
$RootMenu->AddMenuItem(22, "mmi_turno", $Language->MenuPhrase("22", "MenuText"), "turnolist.php?cmd=resetall", 11, "", TRUE, FALSE);
$RootMenu->AddMenuItem(5, "mmi_doctor", $Language->MenuPhrase("5", "MenuText"), "doctorlist.php?cmd=resetall", 22, "", TRUE, FALSE);
$RootMenu->AddMenuItem(6, "mmi_doctor_especialidad", $Language->MenuPhrase("6", "MenuText"), "doctor_especialidadlist.php?cmd=resetall", 5, "", TRUE, FALSE);
$RootMenu->AddMenuItem(8, "mmi_empleado", $Language->MenuPhrase("8", "MenuText"), "empleadolist.php?cmd=resetall", 11, "", TRUE, FALSE);
$RootMenu->AddMenuItem(15, "mmi_paciente", $Language->MenuPhrase("15", "MenuText"), "pacientelist.php", -1, "", TRUE, FALSE);
$RootMenu->AddMenuItem(12, "mmi_internado", $Language->MenuPhrase("12", "MenuText"), "internadolist.php?cmd=resetall", 15, "", TRUE, FALSE);
$RootMenu->AddMenuItem(3, "mmi_cuenta", $Language->MenuPhrase("3", "MenuText"), "cuentalist.php?cmd=resetall", 15, "", TRUE, FALSE);
$RootMenu->AddMenuItem(1, "mmi_consulta", $Language->MenuPhrase("1", "MenuText"), "consultalist.php?cmd=resetall", 3, "", TRUE, FALSE);
$RootMenu->AddMenuItem(19, "mmi_servicio_medico_prestado", $Language->MenuPhrase("19", "MenuText"), "servicio_medico_prestadolist.php?cmd=resetall", 3, "", TRUE, FALSE);
$RootMenu->AddMenuItem(7, "mmi_doctor_servicio_medico_prestado", $Language->MenuPhrase("7", "MenuText"), "doctor_servicio_medico_prestadolist.php?cmd=resetall", 19, "", TRUE, FALSE);
$RootMenu->AddMenuItem(31, "mmi_internado_diario", $Language->MenuPhrase("31", "MenuText"), "internado_diariolist.php?cmd=resetall", 3, "", TRUE, FALSE);
$RootMenu->AddMenuItem(32, "mmi_receta", $Language->MenuPhrase("32", "MenuText"), "recetalist.php?cmd=resetall", 3, "", TRUE, FALSE);
$RootMenu->Render();
?>
<!-- End Main Menu -->
