<html>
<body>
<!-- THIS IS FOR Q5 -->
<!-- THIS IS FOR Q5 -->
<!-- THIS IS FOR Q5 -->
<h3>Part1</h3>
<h4>Check for student information and course information:</h4>
<?php
//Step 1: Connect to the database server, to a specific database

$conn = mysqli_connect('localhost', 'root', '', 'G2T12');

if (!$conn)
{  exit( 'Could not connect:'  
           .mysqli_connect_error($conn) ); 	
}

//Step 2a: Prepare Statement
$pQuery1 = "select distinct(sid) from student order by sid asc"; 

$stmt1 = mysqli_prepare($conn, $pQuery1); 

//Step 2b: Bind parameters
mysqli_stmt_execute($stmt1);

//Step 4a: Bind result variables
mysqli_stmt_bind_result($stmt1, $sid_r);

//Step 4b: Fetch Values – results

echo '<form action="Result.php" method="post">
<select name="sid_html">
<option value="" selected>-- Select SID --</option>';
while (mysqli_stmt_fetch($stmt1)) {
echo '<option value='.$sid_r.'>'.$sid_r.'</option>';
     }
echo '</select><p></p>
<input type="submit" value="Get Student Information" />
</form>';

//Step 5a: Close Statement
mysqli_stmt_close($stmt1);

//Step 5b: Close the Connection
mysqli_close($conn);
?>
<br>


<!-- THIS IS FOR Q6 -->
<!-- THIS IS FOR Q6 -->
<!-- THIS IS FOR Q6 -->
<h3>Part2</h3>
<h4>Check for bidding detail:</h4>
<?php
//Step 1: Connect to the database server, to a specific database

$conn = mysqli_connect('localhost', 'root', '', 'G2T12');

if (!$conn)
{  exit( 'Could not connect:'  
           .mysqli_connect_error($conn) ); 	
}

//Step 2a: Prepare Statement
$pQuery2_1 = "select distinct(sid) from student order by sid asc"; 
$stmt2_1 = mysqli_prepare($conn, $pQuery2_1); 

//Step 2b: Bind parameters
mysqli_stmt_execute($stmt2_1);

//Step 4a: Bind result variables
mysqli_stmt_bind_result($stmt2_1, $sid_r);

//Step 4b: Fetch Values – results
echo '<form action="Result.php" method="post">
<select name="sid_html2">
<option value="" selected>-- Select SID --</option>';
while (mysqli_stmt_fetch($stmt2_1)) {
echo '<option value='.$sid_r.'>'.$sid_r.'</option>';
     }
echo '</select><p></p>';


//Step 5a: Close Statement
mysqli_stmt_close($stmt2_1);


//Step 5b: Close the Connection
mysqli_close($conn);
?>


<?php
//Step 1: Connect to the database server, to a specific database

$conn = mysqli_connect('localhost', 'root', '', 'G2T12');

if (!$conn)
{  exit( 'Could not connect:'  
           .mysqli_connect_error($conn) ); 	
}

//Step 2a: Prepare Statement

$pQuery2_2 = "select * from validtime"; 


$stmt2_2 = mysqli_prepare($conn, $pQuery2_2); 
//Step 2b: Bind parameters

mysqli_stmt_execute($stmt2_2);
//Step 4a: Bind result variables

mysqli_stmt_bind_result($stmt2_2, $validtime_r);

//Step 4b: Fetch Values – results

echo'<select name="validtime_html">
<option value="" selected>-- Select Date --</option>';
while (mysqli_stmt_fetch($stmt2_2)) {
echo '<option value='.$validtime_r.'>'.$validtime_r.'</option>';
     }
echo '</select><p></p>';

echo'<input type="submit" value="Get Bidding Details" />
</form>';

//Step 5a: Close Statement

mysqli_stmt_close($stmt2_2);

//Step 5b: Close the Connection
mysqli_close($conn);
?>

<br>












<!-- THIS IS FOR Q7 -->
<!-- THIS IS FOR Q7 -->
<!-- THIS IS FOR Q7 -->
<h3>Part3</h3>
<h4>Check for programme information:</h4>
<?php
//Step 1: Connect to the database server, to a specific database

$conn = mysqli_connect('localhost', 'root', '', 'G2T12');

if (!$conn)
{  exit( 'Could not connect:'  
           .mysqli_connect_error($conn) ); 	
}

//Step 2a: Prepare Statement
$pQuery3 = "select distinct(pid) from programme order by pid asc"; 

$stmt3 = mysqli_prepare($conn, $pQuery3); 

//Step 2b: Bind parameters
mysqli_stmt_execute($stmt3);

//Step 4a: Bind result variables
mysqli_stmt_bind_result($stmt3, $pid_r);

//Step 4b: Fetch Values – results

echo '<form action="Result.php" method="post">
<select name="pid_html">
<option value="" selected>-- Select PID --</option>';
while (mysqli_stmt_fetch($stmt3)) {
echo '<option value='.$pid_r.'>'.$pid_r.'</option>';
     }

echo '</select><p></p>';
echo'
<input type="submit" value="Get Programme Information" />
</form>';

//Step 5a: Close Statement
mysqli_stmt_close($stmt3);

//Step 5b: Close the Connection
mysqli_close($conn);
?>

<br>


<!-- THIS IS FOR Q8 -->
<!-- THIS IS FOR Q8 -->
<!-- THIS IS FOR Q8 -->
<h3>Part4</h3>
<h4>Check for bidding information:</h4>
<?php
//Step 1: Connect to the database server, to a specific database

$conn = mysqli_connect('localhost', 'root', '', 'G2T12');

if (!$conn)
{  exit( 'Could not connect:'  
           .mysqli_connect_error($conn) ); 	
}

//Step 2a: Prepare Statement
$pQuery4 = "select cid from bidding
group by cid order by cid asc"; 

$stmt4 = mysqli_prepare($conn, $pQuery4); 

//Step 2b: Bind parameters
mysqli_stmt_execute($stmt4);

//Step 4a: Bind result variables
mysqli_stmt_bind_result($stmt4, $cid_r);

//Step 4b: Fetch Values – results

echo '<form action="Result.php" method="post">
<select name="cid_html">
<option value="" selected>-- Select CID --</option>';
while (mysqli_stmt_fetch($stmt4)) {
echo '<option value='.$cid_r.'>'.$cid_r.'</option>';
     }


echo '</select><p></p>';
echo'
<input type="submit" value="Get Bidding Information" />
</form>';

//Step 5a: Close Statement
mysqli_stmt_close($stmt4);

//Step 5b: Close the Connection
mysqli_close($conn);
?>
</body>
</html>