-- --------------------Q1 
select t1.sid as `Student id`, t1.stu_name as `Student Name`, email as `Student Email`, 
t2.sch_day as `Day of Week`, count_of_day as `Count of Days` from
(select s.sid, stu_name, email, sum(outcome) as total_bid from bidding b , student s
where s.sid = b.sid
group by sid
having total_bid >= 4)t1
join
(select t.sid, x.sch_day, t.max_value from
(select sid, s.sch_day, count(s.sno) total_sec from bidding b
join section s
on b.cid = s.cid and b.sno = s.sno
where outcome = 1
group by sid, s.sch_day
having total_sec >= 2)x
join
(select distinct sid, max(total_sec) as max_value from
(select b.sid, sec.sch_day, count(sec.sno) as total_sec from section sec
join bidding b
on sec.cid = b.cid and sec.sno = b.sno
where outcome = 1
group by sid, sec.sch_day
having total_sec >= 2
)y
group by sid)t
on x.sid = t.sid and t.max_value = x.total_sec)t2
on t1.sid = t2.sid
left join
(select sid, count(distinct sec.sch_day) as count_of_day from section sec
join bidding b
on sec.cid = b.cid and b.sno = sec.sno
where outcome = 1
group by sid)t3
on t3.sid = t2.sid;

-- --------------------Q2

select t6.pid as 'Programme', t6.sch_day as 'Busiest Day',  max_section as 'No.of', 
t5.sch_day as 'Second Busiest', t5.max_sec as 'No.of' from 
(select t1.pid, t1.sch_day, t2.max_section from
(select pid, sch_day, count(sno) as num_section from programme_courses pc
join section s
on pc.cid = s.cid
where is_core = 1
group by pid, sch_day)t1
join 
(select pid, max(num_section) as max_section from 
(
select pid, sch_day, count(sno) as num_section from programme_courses pc
join section s
on pc.cid = s.cid
where is_core = 1
group by pid, sch_day)t
group by pid)t2
on t1.pid = t2.pid and t1.num_section = t2.max_section)t6
join #second busiest day
(select t4.pid, t4.sch_day, t3.max_sec from
(select pid, sch_day, count(sno) as num_section from programme_courses pc
join section s
on pc.cid = s.cid
where is_core = 1
group by pid, sch_day)t4
join
(
select t1.pid,  max(t1.num_section) as max_sec from
(select pid, sch_day, count(sno) as num_section from programme_courses pc
join section s
on pc.cid = s.cid
where is_core = 1
group by pid, sch_day)t1
join 
(select pid, max(num_section) as max_section from 
(
select pid, sch_day, count(sno) as num_section from programme_courses pc
join section s
on pc.cid = s.cid
where is_core = 1
group by pid, sch_day)t
group by pid)t2
on t1.pid = t2.pid and t1.num_section < t2.max_section
group by pid)t3
on t3.pid = t4.pid and t3.max_sec = t4.num_section)t5
on t5.pid = t6.pid;

-- -------------------Q3
select t1.sid as 'Student id', s.stu_name as 'Student', t1.col3 as 'Avg$(suss)', t2.col4 as 'Avg$(fail)', t3.col5 as 'Total Count'
from student s, 
(select avg(edollars) as col3, sid 
from bidding where outcome = 1
group by sid) as t1,
(select avg(edollars) as col4, sid 
from bidding where outcome = 0
group by sid) as t2,
(select sid, count(distinct bid_datetime) as col5 from bidding group by sid) as t3
where t1.sid = s.sid and t1.sid=t2.sid and t2.sid = t3.sid
order by t3.col5 desc, t1.sid asc;


-- -------------------Q4

select table1.cid as 'Course id', table1.title as 'Course name', TopMin, table1.sno as TopSec , BottomMin, 
table2.sno as BottomSec, TopMin-BottomMin as Difference from

(select b1.cid, b1.sno, min(edollars) as TopMin, title from bidding b1 left join course c on b1.cid = c.cid
where b1.cid in
	(select bnew.cid from (select cid from bidding where outcome = 1 group by cid, sno) as bnew
	group by bnew.cid
	having count(bnew.cid) > 1)
and outcome = 1
group by b1.cid, b1.sno) as table1,

(select b1.cid, b1.sno, min(edollars) as BottomMin, title from bidding b1 left join course c on b1.cid = c.cid
where b1.cid in
	(select bnew.cid from (select cid from bidding where outcome = 1 group by cid, sno) as bnew
	group by bnew.cid
	having count(bnew.cid) > 1)
and outcome = 1
group by b1.cid, b1.sno) as table2

where table1.cid = table2.cid and table1.sno <> table2.sno and TopMin >= BottomMin
order by Difference desc;

