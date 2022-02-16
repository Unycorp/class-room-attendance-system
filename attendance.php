<?php
//include '../connection/dbconnect.php';
include 'securitysystem.php';


$course_id = "";
if(isset($_GET['cid'])){
	$course_id = $_GET['cid'];
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <title>Edu Portal</title>
    
    
    <?php include "csslibraries.php" ?>
  <style type="text/css">
      .btn-icon-left {
  background: #fff;
  border-radius: 10rem;
  display: inline-block;
  margin: -0.375rem 0.625rem -0.375rem -1.188rem;
  padding: 0.375rem 0.75rem 0.375rem;
  float: left; }
    </style>
     
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
      <div class="col-12">
       <div class="box">
        <div class="box-header with-border">
          <h4 class="box-title">Attendance Management</h4>
        </div>
        <div style="text-align: right; padding-right: 35px;">
            	<a href='view_attendance.php?cid=<?php echo $course_id."&wk=1" ?>' class="btn btn-success">View Attendance</a>
          	</div>
        <!-- /.box-header -->
        <div class="box-body">
          <div class="table-responsive">
            <table id="example1" class="table text-fade table-striped">
            	<div class="form-group">
            		<label>Enter the week</label>
            		<input type="text" id="weeks" class="form-control" placeholder="Enter week number" value="1">
            	</div>
            <thead>
              <tr class="text-dark">
                <th>s/n</th>
                <th>Name of student</th>
                <th>Faculty</th>
                <th>Department</th>
                <th></th>
                <th></th>
              </tr>
            </thead>
            <tbody id="tb">

            </tbody>
            </table>
            <div style="text-align: right; padding-right: 35px;">
            	<button class="btn btn-primary" onclick="butAdd()">Mark Attendance</button>
          	</div>
          </div>
        </div>
        <!-- /.box-body -->
        </div>
        <!-- /.box -->
      </div>
		</section>
		<!-- /.content -->
	  </div>
  </div>
  <!-- /.content-wrapper -->
	
	<?php include 'footer.php'; ?>
  <!-- Side panel -->   	
</div>
<!-- ./wrapper -->
	
<!-- Delete Modal -->
<div class="modal fade" id="deleteModal">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content" id="delete_id">
            
        </div>
    </div>
</div>
<?php include 'jslibraries.php'; ?>
	
<script type="text/javascript">

var _staff_id = "<?php echo $staff_id ?>";
var _course_id = "<?php echo $course_id ?>";

refresh();
/*
function butDelete(student_id){

 var data = new FormData();
 var ajax = new XMLHttpRequest();

 data.append("d_staff_id", _staff_id);
 data.append("d_student_id", student_id);
 ajax.open("post", "attendanceAI.php", true);
 ajax.send(data);

 ajax.onreadystatechange = function() {
            if (ajax.readyState == 4 && ajax.status == 200) {
            var result = ajax.responseText;
            //document.getElementById("class_main_id").innerHTML = result;
            if(result){
              refresh();
              $('#deleteModal').modal('hide');
              speakSuccess("Deleted successfully");
             }
        }
    }
}
function butSaveEdit(student_id){
var course_code = document.getElementById("ucourse_code").value;
var course_title = document.getElementById("ucourse_title").value;
 var data = new FormData();
 var ajax = new XMLHttpRequest();

 data.append("course_title", course_title);
 data.append("course_code", course_code);
 data.append("admin_id", _staff_id);
 data.append("student_id", student_id);
 ajax.open("post", "attendanceAI.php", true);
 ajax.send(data);

 ajax.onreadystatechange = function() {
            if (ajax.readyState == 4 && ajax.status == 200) {
            var result = ajax.responseText;
            //document.getElementById("class_main_id").innerHTML = result;
            if(result){
              refresh();
              $('#editModal').modal('hide');
              speakSuccess("Updated successfully");
             }
            }
        }
}
function butDeleteModal(student_id){
 var data = new FormData();
 var ajax = new XMLHttpRequest();

 data.append("did", "");
 data.append("d_student_id", student_id);
 data.append("admin_id", _staff_id);

 ajax.open("post", "attendanceAI.php", true);
 ajax.send(data);

 ajax.onreadystatechange = function() {
            if (ajax.readyState == 4 && ajax.status == 200) {
            var result = ajax.responseText;
            document.getElementById("delete_id").innerHTML = result;
            }
        }
}
function butEditModal(student_id){
 var data = new FormData();
 var ajax = new XMLHttpRequest();

 data.append("student_id", student_id);
 data.append("admin_id", _staff_id);

 ajax.open("post", "attendanceAI.php", true);
 ajax.send(data);

 ajax.onreadystatechange = function() {
            if (ajax.readyState == 4 && ajax.status == 200) {
            var result = ajax.responseText;
            document.getElementById("edit_id").innerHTML = result;
            }
        }
}
*/
arr = [];
function butCheck(student_id){
	
		// where length is the number of items in the table
		var checkbox = document.getElementById("checkbox_"+student_id).checked;
		if(checkbox){
			// check if item exist in the array
				if (arr.indexOf(student_id) == -1){
					arr.push(student_id);
				}
		}else{
			// Get the position of the value in the array using the indexOf method
			pos = arr.indexOf(student_id)
			if(pos > -1){
				// The splice method is used to remove elements in an array
				// The first parameter(pos) defines the position where the element will be remove
				// The second parameter (1) defines how many elements should be removed.
				arr.splice(pos, 1);
			}
		}
		//alert(arr);
}
function butAdd(){
	var weeks = document.getElementById("weeks").value;

  var data = new FormData();
  var ajax = new XMLHttpRequest();

  data.append("student_ids", arr.toString());
  data.append("course_id", _course_id);
  data.append("staff_id", _staff_id);
  data.append("weeks", weeks);

  ajax.open("post", "attendanceAI.php", true);
  ajax.send(data);

  ajax.onreadystatechange = function() {
            if (ajax.readyState == 4 && ajax.status == 200) {
            var result = ajax.responseText;
            //document.getElementById("class_main_id").innerHTML = result;
            //alert(result);
            // do not remove this arr[]. help in security
            arr = [];
            if(result){
              refresh();
              //$('#add-modal').modal('hide');
              speakSuccess("Added successfully");
             }else{
              //alert("Email already exist. Please check and try again");
             }
            }
        }
}
function refresh(){

var data = new FormData();
 var ajax = new XMLHttpRequest();

 data.append("refresh_id", "");
 data.append("course_id", _course_id);

 ajax.open("post", "attendanceAI.php", true);
 ajax.send(data);

 ajax.onreadystatechange = function() {
            if (ajax.readyState == 4 && ajax.status == 200) {
            var result = ajax.responseText;
            document.getElementById("tb").innerHTML = result;
            //if(result){
            //  alert("Added Successfully");
            // }
            }
        }
}
</script>
</body>
</html>
