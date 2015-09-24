-- Reporte 1 (cantidad de noticias por medio)
select m.name, count(nd.id) as news
from media as m
inner join news_details as nd
on m.id = nd.media_id
inner join news as n
on n.id = nd.news_id
where
	n.client_id = 101 and
    (nd.type = 1 or nd.type = 2 ) and
	nd.created_at <= '2015-09-30' and nd.created_at >= '2015-09-1'
group by m.name
order by m.name;

-- Reporte 2 (Cantidad de noticias por tema)
select t.name, count(nd.id) as news
from topics as t
inner join news_details as nd
on t.id = nd.topic_id
inner join news as n
on n.id = nd.news_id
where
	n.client_id = 4 and
    (nd.type = 1 or nd.type = 2) and
	nd.created_at <= '2015-09-30' and nd.created_at >= '2015-09-1'
group by t.name
order by t.name;

-- Reporte 3 (cantidad de noticias positivias, negativas y neutras por cliente)
-- Positivas
select count(nd.id) as positive
from news_details as nd
inner join news as n
on n.id = nd.news_id
where 
	n.client_id = 101 and
	(nd.type = 1 or nd.type = 2) and
	nd.tendency = 1 and
    nd.created_at <= '2015-09-30' and nd.created_at >= '2015-09-1';
-- Negativas
select count(nd.id) as negative
from news_details as nd
inner join news as n
on n.id = nd.news_id
where 
	n.client_id = 101 and
	(nd.type = 1 or nd.type = 2) and
	nd.tendency = 2 and
    nd.created_at <= '2015-09-30' and nd.created_at >= '2015-09-1';
-- Neutras
select count(nd.id) as neutral
from news_details as nd
inner join news as n
on n.id = nd.news_id
where 
	n.client_id = 101 and
    (nd.type = 1 or nd.type = 2) and
	nd.tendency = 3 and
    nd.created_at <= '2015-09-30' and nd.created_at >= '2015-09-1';

-- Reporte 4 (cantidad de noticias por genero)
select nd.gender, count(nd.gender) as news
from news_details as nd
inner join news as n
on n.id = nd.news_id
where
	n.client_id = 101 and
    (nd.type = 1 or nd.type = 2) and
	ifnull(length(nd.gender), 0) > 0 and
	nd.created_at <= '2015-09-30' and nd.created_at >= '2015-09-1'
group by nd.gender;

-- Reporte 5 (cantidad de noticias por fuente)
select nd.source, count(nd.source) as news
from news_details as nd
inner join news as n
on n.id = nd.news_id
where 
	n.client_id = 101 and
    (nd.type = 1 or nd.type = 2) and
	ifnull(length(nd.source), 0) > 0 and
	nd.created_at <= '2015-09-30' and nd.created_at >= '2015-09-1'
group by nd.source;

-- Reporte 6 (cantidad de noticias basadas en tendencia por medio)
-- Positivas
select m.name, count(nd.id) as positive
from media as m
inner join news_details as nd
on m.id = nd.media_id
inner join news as n
on n.id = nd.news_id
where 
	n.client_id = 101 and
    (nd.type = 1 or nd.type = 2) and
	nd.created_at <= '2015-09-30' and nd.created_at >= '2015-09-1' and
	nd.tendency = 1
group by m.name
order by m.name;
-- Negativas
select m.name, count(nd.id) as negative
from media as m
inner join news_details as nd
on m.id = nd.media_id
inner join news as n
on n.id = nd.news_id
where 
	n.client_id = 101 and
    (nd.type = 1 or nd.type = 2) and 
    nd.created_at <= '2015-09-30' and nd.created_at >= '2015-09-1' and
	nd.tendency = 2
group by m.name
order by m.name;
-- Neutras
select m.name, count(nd.id) as neutral
from media as m
inner join news_details as nd
on m.id = nd.media_id
inner join news as n
on n.id = nd.news_id
where 
	n.client_id = 101 and
    (nd.type = 1 or nd.type = 2) and
    nd.created_at <= '2015-09-30' and nd.created_at >= '2015-09-1' and
	nd.tendency = 3
group by m.name
order by m.name;

-- Reporte 7 (cantidad de noticias basadas en tendencia por fuente)
-- Positive
select source, count(nd.source) as positive
from news_details as nd
inner join news as n
on n.id = nd.news_id
where 
	n.client_id = 101 and
    (nd.type = 1 or nd.type = 2) and
	nd.tendency = 1 and
	(not isnull(nd.source) and length(source) > 0 ) and
	nd.created_at <= '2015-09-30' and nd.created_at >= '2015-09-1'
group by source;
-- Negative
select source, count(nd.tendency) as negative
from news_details as nd
inner join news as n
on n.id = nd.news_id
where 
	n.client_id = 101 and
    (nd.type = 1 or nd.type = 2) and
	nd.tendency = 2 and
	not isnull(nd.source) and
	nd.created_at <= '2015-09-30' and nd.created_at >= '2015-09-1'
group by source;
-- Neutral
select source, count(nd.tendency) as neutral
from news_details as nd
inner join news as n
on n.id = nd.news_id
where 
	n.client_id = 101 and
    (nd.type = 1 or nd.type = 2) and
	nd.tendency = 3 and
	not isnull(nd.source) and
	nd.created_at <= '2015-09-30' and nd.created_at >= '2015-09-1'
group by source;

-- Reporte General A
-- Prensa
select count(nd.id) as press
from news_details as nd
inner join news as n
on n.id = nd.news_id
where
	n.client_id = 101 and
    (nd.type = 1 or nd.type = 2) and
    nd.created_at <= '2015-09-30' and nd.created_at >= '2015-09-1';
-- Radio
select count(nd.id) as radio
from news_details as nd
inner join news as n
on n.id = nd.news_id
where
	n.client_id = 101 and
    nd.type = 3 and
    nd.created_at <= '2015-09-30' and nd.created_at >= '2015-09-1';
-- Tv
select count(nd.id) as tv
from news_details as nd
inner join news as n
on n.id = nd.news_id
where
	n.client_id = 101 and
    nd.type = 4 and
    nd.created_at <= '2015-09-30' and nd.created_at >= '2015-09-1';

-- Reporte General B
-- Generated Programatically