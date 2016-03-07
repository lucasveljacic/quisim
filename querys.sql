use quiniela;

select * from sorteo_numero;
select count(1) from sorteo_numero;

select * 
  from sorteo_numero
 where sorteo_id = 1
   and loteria_id = 1
   and fecha = '2015-03-20'
   -- and posicion = 16
     ; -- 6594


select * from sorteos;
select * from loterias;

-- delete from sorteo_numero;


-- numeros que se repitieron 2 veces en las ultimas 67 sorteos
-- sin contando que hay 4 sorteos por dia. Son 17 dias para atras
select numero
  from sorteo_numero
 where 1=1
   and loteria_id = 1
   and posicion = 1
   and fecha > '2016-01-01'
   and fecha < DATE_ADD('2016-01-01', INTERVAL 20 DAY)
 group by loteria_id, numero
having count(numero) = 2
	;
    
	select *
	  from sorteo_numero
	 where 1=1
	   and loteria_id = 1
	   and posicion = 1
	   and fecha > '2016-01-01'
	   and fecha < DATE_ADD('2016-01-01', INTERVAL 20 DAY)
       and numero = 15
		;
   
   -- checkear si una redoblona salio ganadora
	select *
	  from sorteo_numero
	 where 1=1
	   and loteria_id = 1
	   and sorteo_id = 1
	   and posicion = 1
	   and fecha = ''
		 ;
-- delete from simulation;
select * from simulation order by 1 desc;
select * from simulation_item order by fecha desc; 
select count(1) from simulation_item; 


insert into simulation (description) values ('test');
insert into simulation_item (
		simulation_id,
        fecha,
        invest,
        invest_acum,
        income,
        income_acum,
        profit,
        profit_acum
	) 
	 values (
        LAST_INSERT_ID(), 
        '2014-12-22',
        1,
        2,
        3,
        4
	);
    

     
     select
            concat(
                'Date.UTC(',year(fecha),',',month(fecha),',',dayofmonth(fecha),')',
                invest,',',
                invest_acum,',',
                income,',',
                income_acum,',',
                profit,',',
                profit_acum
                ) as 'jsDateUTC'
              from simulation_item
             where simulation_id = 6
     ;


-- ganancia anual
select DATE_FORMAT(fecha, '%Y'), 
       sum(profit) as profit_per_year,  
       sum(invest) as invest_per_year,
       round(sum(profit)/sum(invest)*100, 1)  as profit_percent
  from simulation_item
 where simulation_id = 19
 group by DATE_FORMAT(fecha, '%Y')
 order by fecha desc
     ;

-- ganancia mensual
select DATE_FORMAT(fecha, '%Y-%m'), 
       sum(profit) as profit_per_month,  
       sum(invest) as invest_per_month,
       round(sum(profit)/sum(invest)*100, 1) as profit_percent
  from simulation_item
 where simulation_id = 19
 group by DATE_FORMAT(fecha, '%Y-%m')
 order by fecha
     ;



-- inversion promedio mensual
select DATE_FORMAT(fecha, '%Y'), 
       avg(invest)*30, 
       avg(profit)*30,
       round(100 * ((avg(profit)*30)/(avg(invest)*30)), 1)
  from simulation_item
 where simulation_id = 19
 group by DATE_FORMAT(fecha, '%Y')
 order by fecha desc
     ;






            select 1 as hayDatos
              from simulation_item
             where 1=1
               and fecha = '2006-01-03'
               and loteria_id = 1
               and sorteo_id
               and income is not null
               and income_acum is not null
               and invest is not null
               and invest_acum is not null
               and profit is not null
               and profit_acum is not null
                 ;
