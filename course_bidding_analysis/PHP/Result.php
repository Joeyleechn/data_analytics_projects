<html> 
<body> 
<!-- THIS IS FOR Q5 -->
<!-- THIS IS FOR Q5 -->
<!-- THIS IS FOR Q5 -->
<?php
if (isset($_POST['sid_html']))
{echo'<table border="1">
<tr style="font-weight:bold">
<td>SID</td>
<td>Student Name</td>
<td>Email</td>
<td>PID</td>
<td>Programme Name</td>
<td>Double-Degree ID</td>
<td>Double-Degree Name</td>
<td>Initial Edolloar Amount</td>
<td>Final Edolloar Amount</td>
</tr>';

		//Step 1: Connect to the database server, to a specific database
		$conn = mysqli_connect('localhost', 'root', '', 'G2T12');
		if (!$conn)
		{  exit( 'Could not connect:'  
		.mysqli_connect_error($conn) ); 	
		}

		//Step 2a: Prepare Statement
		$pQuery1_1 = "select * from 
		(select t.*, p2.prog_name as d_degree_name,
		coalesce(final_edollar, t.init_edollars) as `final edollar amount` from
		(select s.stu_name, s.email, s.sid, p.pid, prog_name, 
		d.pid as d_degree_id, init_edollars
		  from student s 
		left join programme p
		on s.pid = p.pid
		left join double_degree d
		on s.sid = d.sid)t #all the student info and programme name for the first degree
		left join programme p2 #programme name for the second degree
		on t.d_degree_id = p2.pid
		left join
		(select s.sid, init_edollars,
		init_edollars - sum(edollars) as final_edollar from student s
		left join bidding b
		on s.sid = b.sid
		where outcome = 1
		group by s.sid)final_edollar#left amount in edollar account for each student
		on t.sid = final_edollar.sid) as t1_1 where sid = ?"; 
		$stmt1_1 = mysqli_prepare($conn, $pQuery1_1); 

		//Step 2b: Bind parameters
		mysqli_stmt_bind_param($stmt1_1, 's', $sid);#representing the '?'


		//Step 3: Perform the query (execute statement)
       		$sid = $_POST['sid_html'];
		mysqli_stmt_execute($stmt1_1);

		//Step 4a: Bind result variables
		mysqli_stmt_bind_result($stmt1_1, $stu_name_r, $email_r, $sid_r, $pid_r, $prog_name_r, $d_degree_id_r,
		 $init_edollars_r, $d_degree_name_r, $final_edollar_amount_r );#representing the outputs

		//Step 4b: Fetch Values – results
		while (mysqli_stmt_fetch($stmt1_1)) {
			echo "<tr>";
			echo "<td>".$sid_r."</td>";
			echo "<td>".$stu_name_r."</td>";
			echo "<td>".$email_r."</td>";
			echo "<td>".$pid_r."</td>";
			echo "<td>".$prog_name_r."</td>";
			echo "<td>".$d_degree_id_r."</td>";
			echo "<td>".$d_degree_name_r."</td>";
			echo "<td>".$init_edollars_r."</td>";
			echo "<td>".$final_edollar_amount_r."</td>";
			echo "</tr>";
		}

		echo '</table>';
		//Step 5a: Close Statement
		mysqli_stmt_close($stmt1_1);

		//Step 5b: Close the Connection
		mysqli_close($conn);
		echo"<br>";
	}
?>

<?php
if (isset($_POST['sid_html']))
{echo'<table border="1">
<tr style="font-weight:bold">
<td>SID</td>
<td>CID</td>
<td>Course Name</td>
<td>Section Number</td>
<td>Whether is Core for First Degree</td>
<td>Whether is Core for Double Degree</td>
<td>Bidding Amount</td>
</tr>';

		//Step 1: Connect to the database server, to a specific database
		$conn = mysqli_connect('localhost', 'root', '', 'G2T12');
		if (!$conn)
		{  exit( 'Could not connect:'  
		.mysqli_connect_error($conn) ); 	
		}

		//Step 2a: Prepare Statement
		$pQuery1_2 = "select sid, cid, title, sno, is_core1_1 , is_core_2, edollars from
		(select sid, cid, title, sno, m_pid, s_pid, coalesce(is_core1, 0) as is_core1_1, 
		case when (s_pid is not null and is_core2 is null) then 0 else is_core2 end as is_core_2,
		edollars
		from (select w4.sid, w4.cid, title, sno, m_pid, s_pid, is_core1, is_core2, edollars
		from (select sid, w3.cid, m_pid, s_pid, p1.is_core as is_core1, p2.is_core as is_core2
		from (select w2.sid, cid, m_pid, s_pid
		from (select sid, m_pid, s_pid
		from (select t5.sid, stu_name, m_pid, m_pro, s_pid, s_pro, (case when init_edollars is not null then init_edollars else 0 end) - (case when spent is not null then spent else 0 end) as final_edollars
		from (select t3.sid, t3.stu_name, m_pid, m_pro, s_pid, s_pro, init_edollars
		from (select t1.sid, stu_name, t1.pid as m_pid, t1.prog_name as m_pro, t2.pid as s_pid, t2.prog_name as s_pro
		from (select sid, stu_name, s.pid, prog_name 
		from student s, programme p 
		where s.pid = p.pid) as t1
		left join (select sid, d.pid, prog_name
		from double_degree d, programme p
		where d.pid = p.pid) as t2
		on t1.sid = t2.sid and t1.pid != t2.pid) as t3, student s 
		where s.sid = t3.sid) as t5
		left outer join (select sid, sum(edollars) as spent
		from bidding
		where outcome = 1
		group by sid) as t4
		on t5.sid = t4.sid) as t6) as w2
		left outer join (select sid, c.cid, title, sno, edollars, outcome
		from bidding b, course c
		where b.cid = c.cid and outcome = 1) as w1
		on w1.sid = w2.sid) as w3
		left outer join programme_courses p1
		on w3.m_pid = p1.pid and w3.cid = p1.cid
		left outer join programme_courses p2
		on w3.s_pid = p2.pid and w3.cid = p2.cid) as w4
		left outer join (select sid, c.cid, title, sno, edollars, outcome
		from bidding b, course c
		where b.cid = c.cid and outcome = 1) as w1
		on w4.sid = w1.sid and w4.cid = w1.cid) as w6
		where cid  is not null
		order by sid) as t1_2_final where sid = ?"; 
		$stmt1_2 = mysqli_prepare($conn, $pQuery1_2); 

		//Step 2b: Bind parameters
		mysqli_stmt_bind_param($stmt1_2, 's', $sid);#representing the '?'


		//Step 3: Perform the query (execute statement)
       		$sid = $_POST['sid_html'];
		mysqli_stmt_execute($stmt1_2);

		//Step 4a: Bind result variables
		mysqli_stmt_bind_result($stmt1_2,  $sid_r, $cid_r, $cname_r, $sno_r, $is_core_fd_r, $is_core_sd_r, $bid_amount_r );#representing the outputs

		//Step 4b: Fetch Values – results
		while (mysqli_stmt_fetch($stmt1_2)) {
			echo "<tr>";
			echo "<td>".$sid_r."</td>";
			echo "<td>".$cid_r."</td>";
			echo "<td>".$cname_r."</td>";
			echo "<td>".$sno_r."</td>";
			echo "<td>".$is_core_fd_r."</td>";
			echo "<td>".$is_core_sd_r."</td>";
			echo "<td>".$bid_amount_r."</td>";
			echo "</tr>";
		}

		echo '</table>';
		//Step 5a: Close Statement
		mysqli_stmt_close($stmt1_2);

		//Step 5b: Close the Connection
		mysqli_close($conn);
	}
?>


<!-- THIS IS FOR Q6 -->
<!-- THIS IS FOR Q6 -->
<!-- THIS IS FOR Q6 -->
<?php
if (isset($_POST['sid_html2']))
{echo'<table border="1">
<tr style="font-weight:bold">
<td>SID</td>
<td>Student Name</td>
<td>Chosen Datetime</td>
<td>Pending Bidding</td>
<td>V1</td>
<td>V2</td>
<td>V3</td>
</tr>';

		//Step 1: Connect to the database server, to a specific database
		$conn = mysqli_connect('localhost', 'root', '', 'G2T12');
		if (!$conn)
		{  exit( 'Could not connect:'  
		.mysqli_connect_error($conn) ); 	
		}

		//Step 2a: Prepare Statement
		$pQuery2 = "select * from 
		(select distinct s.sid , s.stu_name , num_pend, V1, V2, V3
		from student s
		left outer join (select sid, bid_datetime, edollars, outcome, end_datetime
		from bidding b, round r
		where b.rid = r.rid) as tb on s.sid = tb.sid
		left outer join (select sid, count(sid) as num_pend from (select sid,  outcome, sum(edollars) as V3 from (select sid, bid_datetime, edollars, outcome, end_datetime
		from bidding b, round r
		where b.rid = r.rid) as tb where ? < end_datetime group by sid, outcome) as t3 group by sid) as t5 on s.sid = t5.sid
		left outer join (select sid, stu_name, init_edollars as V1 from student) as t1 on s.sid = t1.sid
		left outer join (select sid, outcome, sum(edollars) as V2 
		from (select sid, bid_datetime, edollars, outcome, end_datetime
			from bidding b, round r
			where b.rid = r.rid) as tb 
		where outcome = 1 and ? >= end_datetime group by sid, outcome) as t2 on s.sid = t2.sid
		left outer join (select sid, sum(edollars) as V3 from (select sid, bid_datetime, edollars, outcome, end_datetime
		from bidding b, round r
		where b.rid = r.rid) as tb where ? < end_datetime group by sid) as t4 on s.sid = t4.sid) 
		as t2_final where sid = ?"; 
		$stmt2 = mysqli_prepare($conn, $pQuery2); 

		//Step 2b: Bind parameters
		mysqli_stmt_bind_param($stmt2, 'ssss', $vdate1, $vdate2, $vdate3, $sid2);#representing the '?'


		//Step 3: Perform the query (execute statement)
			$vdate1 = $_POST['validtime_html'];
			$vdate2 = $_POST['validtime_html'];
			$vdate3 = $_POST['validtime_html'];
       		$sid2 = $_POST['sid_html2'];
		mysqli_stmt_execute($stmt2);

		//Step 4a: Bind result variables
		mysqli_stmt_bind_result($stmt2, $sid2_r, $stu_name_r, $num_pend_r, $v1_r, $v2_r, $v3_r);#representing the outputs
		
		//Step 4b: Fetch Values – results
		while (mysqli_stmt_fetch($stmt2)) {
			echo "<tr>";
			echo "<td>".$sid2_r."</td>";
			echo "<td>".$stu_name_r."</td>";
			echo "<td>".$vdate1."</td>";
			echo "<td>".$num_pend_r."</td>";
			echo "<td>".$v1_r."</td>";
			echo "<td>".$v2_r."</td>";
			echo "<td>".$v3_r."</td>";
			echo "</tr>";
		}
#student id, student name, bid_datetime, num_pend, V1, V2, V3, id
		echo '</table>';
		//Step 5a: Close Statement
		mysqli_stmt_close($stmt2);

		//Step 5b: Close the Connection
		mysqli_close($conn);
	}
?>



<!-- THIS IS FOR Q7 -->
<!-- THIS IS FOR Q7 -->
<!-- THIS IS FOR Q7 -->
<?php
if (isset($_POST['pid_html']))
{echo'<table border="1">
<tr style="font-weight:bold">
<td>Programme Name</td>
<td>Home School Name</td>
<td>Second School Name</td>
<td>Core Courses</td>
<td>Elective Courses</td>
<td>First Degree Students</td>
<td>Second Degree Students</td>
</tr>';

		//Step 1: Connect to the database server, to a specific database
		$conn = mysqli_connect('localhost', 'root', '', 'G2T12');
		if (!$conn)
		{  exit( 'Could not connect:'  
		.mysqli_connect_error($conn) ); 	
		}

		//Step 2a: Prepare Statement
		$pQuery3_1 = "select t2.prog_name, t2.homesch_name, t2.secsch_name, t2.CoreCourses,
		t4.ElectiveCourses, t5.FirstDegree, t6.SecondDegree from
	   (select t1.pid, t1.prog_name, t1.homesch_name, t1.secsch_name, count(pc.cid) as CoreCourses from
	   (select * from programme p where p.pid = ?) as t1
	   left join programme_courses pc
	   on t1.pid = pc.pid and is_core = 1) as t2
	   left join
	   (select t3.pid, count(pc.cid) as ElectiveCourses from 
	   (select * from programme p where p.pid = ?) as t3
	   left join programme_courses pc
	   on t3.pid = pc.pid and is_core = 0) as t4
	   on t2.pid = t4.pid
	   left join
	   (select s.pid, count(distinct sid) as FirstDegree from student s
	   where pid = ?) as t5
	   on t4.pid = t5.pid
	   left join
	   (select dd.pid, count(distinct sid) as SecondDegree from double_degree dd
	   where dd.pid = ?) as t6
	   on t5.pid = t6.pid "; 
		$stmt3_1 = mysqli_prepare($conn, $pQuery3_1); 

		//Step 2b: Bind parameters
		mysqli_stmt_bind_param($stmt3_1, 'ssss', $pid_1, $pid_2, $pid_3, $pid_4);#representing the '?'


		//Step 3: Perform the query (execute statement)
			$pid_1 = $_POST['pid_html'];
			$pid_2 = $_POST['pid_html'];
			$pid_3 = $_POST['pid_html'];
			$pid_4 = $_POST['pid_html'];
		mysqli_stmt_execute($stmt3_1);

		//Step 4a: Bind result variables
		mysqli_stmt_bind_result($stmt3_1, $prog_name_r, $homesch_name_r, $secsch_name_r, $CoreCourses_r, $ElectiveCourses_r, $FirstDegree_r, $SecondDegree_r);#representing the outputs
		//Step 4b: Fetch Values – results
		while (mysqli_stmt_fetch($stmt3_1)) {
			echo "<tr>";
			echo "<td>".$prog_name_r."</td>";
			echo "<td>".$homesch_name_r."</td>";
			echo "<td>".$secsch_name_r."</td>";
			echo "<td>".$CoreCourses_r."</td>";
			echo "<td>".$ElectiveCourses_r."</td>";
			echo "<td>".$FirstDegree_r."</td>";
			echo "<td>".$SecondDegree_r."</td>";
			echo "</tr>";
		}
#student id, student name, bid_datetime, num_pend, V1, V2, V3, id
		echo '</table>';
		//Step 5a: Close Statement
		mysqli_stmt_close($stmt3_1);

		//Step 5b: Close the Connection
		mysqli_close($conn);
		echo'<br>';
	}
?>

<?php
if (isset($_POST['pid_html']))
{echo'<table border="1">
<tr style="font-weight:bold">
<td>PID</td>
<td>Course ID</td>
<td>Course name</td>
<td>Popularity Score</td>
</tr>';

		//Step 1: Connect to the database server, to a specific database
		$conn = mysqli_connect('localhost', 'root', '', 'G2T12');
		if (!$conn)
		{  exit( 'Could not connect:'  
		.mysqli_connect_error($conn) ); 	
		}

		//Step 2a: Prepare Statement
		$pQuery3_2 = "select * from
		(select t2.pid, t1.cid as `Course id`, c1.title as `Course name`, D/S as P from
		(select pc1.cid, sum(no_seats) as S from programme_courses pc1, round_release rr
		where pc1.pid = ? and pc1.is_core = 0 and pc1.cid = rr.cid
		group by pc1.cid) as t1,
		(select pid, pc2.cid, count(b1.sid) as D from bidding b1, programme_courses pc2
		where pc2.pid = ? and pc2.is_core = 0 and b1.cid = pc2.cid
		group by pc2.cid) as t2,
		course c1
		where t1.cid = t2.cid and t1.cid = c1.cid) as t3
		where 3 > (select count(distinct p) from 
		(select (D/S) as p, t1.cid, t2.pid from
		(select pc1.cid, sum(no_seats) as S from programme_courses pc1, round_release rr
		where pc1.pid = ? and pc1.is_core = 0 and pc1.cid = rr.cid
		group by pc1.cid) as t1,
		(select pid, pc2.cid, count(b1.sid) as D from bidding b1, programme_courses pc2
		where pc2.pid = ? and pc2.is_core = 0 and b1.cid = pc2.cid
		group by pc2.cid) as t2,
		course c1
		where t1.cid = t2.cid and t1.cid = c1.cid)t4
		where t4.p > t3.p and t3.pid = t4.pid)
		order by P desc; "; 
		$stmt3_2 = mysqli_prepare($conn, $pQuery3_2); 

		//Step 2b: Bind parameters
		mysqli_stmt_bind_param($stmt3_2, 'ssss', $pid_1, $pid_2, $pid_3, $pid_4);#representing the '?'


		//Step 3: Perform the query (execute statement)
			$pid_1 = $_POST['pid_html'];
			$pid_2 = $_POST['pid_html'];
			$pid_3 = $_POST['pid_html'];
			$pid_4 = $_POST['pid_html'];
		mysqli_stmt_execute($stmt3_2);

		//Step 4a: Bind result variables
		mysqli_stmt_bind_result($stmt3_2, $pid_r, $course_id_r, $course_name_r, $P_r);#representing the outputs
		//Step 4b: Fetch Values – results
		while (mysqli_stmt_fetch($stmt3_2)) {
			echo "<tr>";
			echo "<td>".$pid_r."</td>";
			echo "<td>".$course_id_r."</td>";
			echo "<td>".$course_name_r."</td>";
			echo "<td>".$P_r."</td>";
			echo "</tr>";
		}
#student id, student name, bid_datetime, num_pend, V1, V2, V3, id
		echo '</table>';
		//Step 5a: Close Statement
		mysqli_stmt_close($stmt3_2);

		//Step 5b: Close the Connection
		mysqli_close($conn);
	}
?>




<!-- THIS IS FOR Q8 -->
<!-- THIS IS FOR Q8 -->
<!-- THIS IS FOR Q8 -->
<?php
if (isset($_POST['cid_html']))
{echo'<table border="1">
<tr style="font-weight:bold">
<td>CID</td>
<td>Section Number</td>
<td>RID</td>
<td>Number of Success</td>
<td>Number of Failure</td>
<td>Total Bidding</td>
<td>Success Rate(%)</td>
<td>Failure Rate(%)</td>
<td>Minimum Edollar for Sucess</td>
<td>Maximum Edollar for Failure</td>
</tr>';

		//Step 1: Connect to the database server, to a specific database
		$conn = mysqli_connect('localhost', 'root', '', 'G2T12');
		if (!$conn)
		{  exit( 'Could not connect:'  
		.mysqli_connect_error($conn) ); 	
		}

		//Step 2a: Prepare Statement
		$pQuery4 = "select t1.cid, t1.sno, t1.rid, coalesce(success,0), coalesce(failure, 0) as failure,
		coalesce(success,0)+coalesce(failure,0) as Total,
		round(coalesce(success,0)/(coalesce(success,0)+coalesce(failure,0))*100, 2) as `succ_rate(%)`,
		round(coalesce(failure,0)/(coalesce(success,0)+coalesce(failure,0))*100, 2) as `fail_rate(%)`,
		`Min_Succ_Bid`, `Max_Fail_Bid` from
		(select * from bidding b1
		group by cid, rid, sno) t1
		left join
		(select cid, rid, sno, count(*) as success, min(edollars) as `Min_Succ_Bid` from bidding b2
		where outcome = 1
		group by cid, rid, sno) as t2
		on t1.cid = t2.cid and t1.rid = t2.rid and t1.sno = t2.sno
		left join
		(select cid, rid, sno, count(*) as failure, max(edollars) as `Max_Fail_Bid` from bidding b3
		where outcome = 0
		group by cid, rid, sno) as t3
		on t1.cid = t3.cid and t1.rid = t3.rid and t1.sno = t3.sno
		where t1.cid = ?
		order by t1.cid;"; 
		$stmt4 = mysqli_prepare($conn, $pQuery4); 

		//Step 2b: Bind parameters
		mysqli_stmt_bind_param($stmt4, 's', $cid);#representing the '?'


		//Step 3: Perform the query (execute statement)
			$cid = $_POST['cid_html'];
		mysqli_stmt_execute($stmt4);

		//Step 4a: Bind result variables
		mysqli_stmt_bind_result($stmt4, $cid_r, $sno_r, $rid_r, $succ__num_r, 
		 $fail__num_r, $total_r, $succ_rate_r, $fail_rate_r, $Min_Succ_Bid_r, $Max_Fail_Bid_r);#representing the outputs
		//Step 4b: Fetch Values – results
		while (mysqli_stmt_fetch($stmt4)) {
			echo "<tr>";
			echo "<td>".$cid_r."</td>";
			echo "<td>".$sno_r."</td>";
			echo "<td>".$rid_r."</td>";
			echo "<td>".$succ__num_r."</td>";
			echo "<td>".$fail__num_r."</td>";
			echo "<td>".$total_r."</td>";
			echo "<td>".$succ_rate_r."</td>";
			echo "<td>".$fail_rate_r."</td>";
			echo "<td>".$Min_Succ_Bid_r."</td>";
			echo "<td>".$Max_Fail_Bid_r."</td>";
			echo "</tr>";
		}
#student id, student name, bid_datetime, num_pend, V1, V2, V3, id
		echo '</table>';
		//Step 5a: Close Statement
		mysqli_stmt_close($stmt4);

		//Step 5b: Close the Connection
		mysqli_close($conn);
	}
?>
</body> 
</html> 