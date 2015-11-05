-- Seleccionar todos los medicos que hayan hecho una operacion pero que no haya sido del corazon.
select concat(d.nombre,' ',d.apellido) doctor, sm.descripcion
from servicio_medico_prestado smp 
inner join servicio_medico sm on smp.idservicio_medico = sm.idservicio_medico
inner join doctor_servicio_medico_prestado dsmp on dsmp.idservicio_medico_prestado = smp.idservicio_medico_prestado
inner join doctor d on d.iddoctor = dsmp.iddoctor
where sm.descripcion != 'Operacion de Corazon' and es_operacion = 'Si'
group by d.iddoctor;

-- Seleccione el top 10 de los medicamentos que mas sale del inventario en los turnos de la noche.
select m.descripcion, sum(cantidad) cantidad
from medicina m
inner join receta r on m.idmedicina = r.idmedicina
inner join turno t on t.idturno = r.idturno
inner join tipo_turno tt on tt.idtipo_turno = t.idtipo_turno
where tt.tipo = 'Nocturno'
group by m.idmedicina
order by cantidad desc
limit 10;

-- Seleccione todos los pacientes que no hayan estado internado en los ultimos 90 dias 
-- y que hayan estado internado por motivos de operacion en los anteriores 6 meses
select  p.idpaciente, p.nombre, p.apellido
from paciente p
inner join internado i on i.idpaciente = p.idpaciente
where i.fecha > date_format(DATE_SUB(now(), INTERVAL 6 month),'%Y%m%d') and es_operacion = 'Si'
	and p.idpaciente not in (select p2.idpaciente from paciente p2
								inner join internado i2 on i2.idpaciente = p2.idpaciente 
                                where i2.fecha > date_format(DATE_SUB(now(), INTERVAL 90 day),'%Y%m%d'));
 
-- Seleccione todos los medicamentos hayan salido en los ultimos 15 dias y que el laboratorio que lo produce sea europeo.
-- para cada medicamento mostrar la cantidad de unidades que haya salido en los ultimos 6 meses.
SELECT m.descripcion, (select sum(r2.cantidad) from receta r2 where r2.idmedicina = m.idmedicina 
						and r2.fecha > date_format(DATE_SUB(now(), INTERVAL 6 month),'%Y%m%d')) cantidad
FROM medicina m
inner join laboratorio l on l.idlaboratorio = m.idlaboratorio
inner join pais p on p.idpais = l.idpais
inner join continente c on c.idcontinente = p.idcontinente and c.nombre = 'Europa'
inner join receta r on r.idmedicina = m.idmedicina
where r.fecha > date_format(DATE_SUB(now(), INTERVAL 15 day),'%Y%m%d');