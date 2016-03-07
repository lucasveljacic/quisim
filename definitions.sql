use quiniela;

drop table loterias;
create table loterias (
	id int PRIMARY KEY,
	nombre varchar(32)
);

drop table sorteos;
create table sorteos (
	id int PRIMARY KEY,
	nombre varchar(32)
);

-- drop table sorteo_numero;
create table sorteo_numero (
	sorteo_id int,
	loteria_id int,
    fecha date,
    posicion int(2),
	numero_completo int(4),
	numero int(2)
);


insert into sorteos (id, nombre) value (1, 'sorteo.primera');
insert into sorteos (id, nombre) value (2, 'sorteo.matutino');
insert into sorteos (id, nombre) value (3, 'sorteo.vespertino');
insert into sorteos (id, nombre) value (4, 'sorteo.nocturno');

insert into loterias (id, nombre) value (1, 'loteria.nacional');
insert into loterias (id, nombre) value (2, 'loteria.provincia');
insert into loterias (id, nombre) value (3, 'loteria.santa_fe');
insert into loterias (id, nombre) value (4, 'loteria.montevideo');
insert into loterias (id, nombre) value (5, 'loteria.cordoba');
insert into loterias (id, nombre) value (6, 'loteria.entre_rios');



ALTER TABLE sorteo_numero ADD PRIMARY KEY (loteria_id, sorteo_id, fecha, posicion);



-- drop table simulation;
create table simulation (
	id int auto_increment PRIMARY KEY,
	description varchar(255)
);

 -- drop table simulation_item;
create table simulation_item (
	simulation_id int,
    fecha date,
    invest float,
	invest_acum float,
    income float,
    income_acum float,
    profit float,
    profit_acum float
);

ALTER TABLE simulation_item 
        ADD FOREIGN KEY fk_simulation (simulation_id) 
 REFERENCES simulation (id)
  ON DELETE CASCADE
          ;