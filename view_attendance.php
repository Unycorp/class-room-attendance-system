<?php
//include 'connection/dbconnect.php';
include 'securitysystem.php';
$course_id = "";
$week = "";
if(isset($_GET['cid'])){
	$course_id = $_GET['cid'];
	$week = $_GET['wk'];
}

$course_code = "";
$course_title = "";
		$query = "SELECT * FROM courses WHERE course_id='$course_id'";
            $result = mysqli_query($conn, $query);
            if(mysqli_num_rows($result)>0){
            while($row = mysqli_fetch_array($result)){
                $id = $row['id'];
                $course_code = $row['course_code'];
                $course_title = $row['course_title'];
              }
            }
?>
<!DOCTYPE html>
<html lang="en">
<head>

    <title>Edu Portal</title>
    
    <?php include "csslibraries.php" ?>

  </head>

<body class="hold-transition light-skin sidebar-mini theme-primary fixed">
	
<div class="wrapper">
	<div id="loader"></div>
	
  <header class="main-header">
	<?php include 'header.php'; ?>
  </header>
  
  <aside class="main-sidebar">
    <!-- sidebar-->
    <?php include "sidebar_left.php" ?>
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
	  <div class="container-full">
		<!-- Main content -->
		<section class="content">
			 <div class="box">
        <div class="box-header with-border" style="text-align:center;">
          <h4 class="box-title">Week <?php echo $week ?> Attendance for <?php echo $course_code." ($course_title)" ?></h4>
        </div>
			<!-- /.box-header -->
        <div class="box-body">
          <div class="table-responsive">
            <table id="example1" class="table text-fade table-bordered table-striped">
            <thead>
              <tr class="text-dark" style="text-align:center;">
                <th>student name</th>
                <th>Monday</th>
                <th>Tuesday</th>
                <th>Wednessday</th>
                <th>Thursday</th>
                <th>Friday</th>
                <th></th>
              </tr>
            </thead>
            <tbody>
            	<?php
            	$student_id = "";
            	$monday_check = "";
            	$percentage = 0;
            $query = "SELECT * FROM course_registration WHERE course_id='$course_id' AND staff_id='$staff_id'";
            $result = mysqli_query($conn, $query);
            if(mysqli_num_rows($result)>0){
            while($row = mysqli_fetch_array($result)){
                $id = $row['id'];
                $student_id = $row['student_id'];

            $days = "";
            $query1 = "SELECT * FROM attendance WHERE course_id='$course_id' AND student_id='$student_id' AND weeks='$week'";
            $result1 = mysqli_query($conn, $query1);
            if(mysqli_num_rows($result1)>0){
            while($row = mysqli_fetch_array($result1)){
                $id = $row['id'];
                $monday = $row['monday'];
                $tuesday = $row['tuesday'];
                $wednesday = $row['wednesday'];
                $thursday = $row['thursday'];
                $friday = $row['friday'];
                $student_id_ = $row['student_id'];
                $percentage = $row['percentage_attend'];

             	
               if($monday=="true"){
               	$monday_check = "check";
               	$monday_text = "success";
               }else{
               	$monday_check = "close";
               	$monday_text = "danger";
               }
              
               if($tuesday=="true"){
               	$tuesday_check = "check";
               	$tuesday_text = "success";
               }else{
               	$tuesday_check = "close";
               	$tuesday_text = "danger";
               }

               if($wednesday=="true"){
               	$wednessday_check = "check";
               	$wednessday_text = "success";
               }else{
               	$wednessday_check = "close";
               	$wednessday_text = "danger";
               }

               if($thursday=="true"){
               	$thursday_check = "check";
               	$thursday_text = "success";
               }else{
               	$thursday_check = "close";
               	$thursday_text = "danger";
               }
               if($friday=="true"){
               	$friday_check = "check";
               	$friday_text = "success";
               }else{
               	$friday_check = "close";
               	$friday_text = "danger";
               }

             }}
               
            $student_name = "";
            $query2 = "SELECT * FROM students WHERE student_id='$student_id' LIMIT 1";
            $result2 = mysqli_query($conn, $query2);
            if(mysqli_num_rows($result2)>0){
            while($row = mysqli_fetch_array($result2)){
                $id = $row['id'];
                $student_name = $row['name'];
              	}
              }
               
                        
            	?>
            	<tr>
            		<td><?php echo $student_name ?></td>
            		<span><td style="text-align: center; font-size: 20px;" class="text-<?php echo $monday_text ?>"><i class="fa fa-<?php echo $monday_check ?>"></i></td></span>
            		<td style="text-align: center; font-size: 20px;" class="text-<?php echo $tuesday_text ?>"><i class="fa fa-<?php echo $tuesday_check ?>"></i></td>
            		<td style="text-align: center; font-size: 20px;" class="text-<?php echo $wednessday_text ?>"><i class="fa fa-<?php echo $wednessday_check ?>"></i></td>
            		<td style="text-align: center; font-size: 20px;" class="text-<?php echo $thursday_text ?>"><i class="fa fa-<?php echo $thursday_check ?>"></i></td>
            		<td style="text-align: center; font-size: 20px;" class="text-<?php echo $friday_text ?>"><i class="fa fa-<?php echo $friday_check ?>"></i></td>
            		<td style="text-align: center; font-size: 20px;"><?php echo $percentage."%"; ?></td>
            	</tr>
            <?php }} ?>
            </tbody>
            </table>
          </div>
        </div>
      </div>
        <!-- /.box-body -->
		</section>
		<!-- /.content -->
	  </div>
  </div>
  <!-- /.content-wrapper -->
	
	<?php include 'footer.php'; ?>
  <!-- Side panel -->   	
</div>
<!-- ./wrapper -->
	
<?php include 'chat.php'; ?>
	<!-- Page Content overlay -->
	
	
<?php include 'jslibraries.php'; ?>
	
</body>
</html>
