<?php
include '../connection/dbconnect.php';
// for marking attendance i.e add student to attendance table
if(isset($_POST['student_ids']) && isset($_POST['course_id']) && isset($_POST['staff_id']) && isset($_POST['weeks'])){
    $student_ids = $_POST['student_ids'];
    $course_id = $_POST['course_id'];
    $staff_id = $_POST['staff_id'];
    $weeks = $_POST['weeks'];

    $arr_student_ids = explode(",", $student_ids);

    $today = date("Y/m/d");
    $day_name = date("l", strtotime($today));

    $day_name = strtolower($day_name);
    //$day_name = "saturday";

    $my_student_not_mark_as_attended_array = array();
    $percentage_attend = 0;

            // Here, we are checking those student that was not mark as attendance. Our main mission is to also insert them into the table but as false record days.
        // More info is available in the 2nd FOR LOOP
         $query9 = "SELECT * FROM course_registration WHERE course_id='$course_id' AND staff_id='$staff_id'";
         $result9 = mysqli_query($conn, $query9);
         if(mysqli_num_rows($result9)>0){
            while($row = mysqli_fetch_array($result9)){
               $student_id_course_reg = $row['student_id'];
                // pass all the student ID into an array
                array_push($my_student_not_mark_as_attended_array, $student_id_course_reg);
            }
        }

    for($i=0; $i<count($arr_student_ids); $i++){
        $student_id = $arr_student_ids[$i];

        /*  We are updating the percentage attendance record of the student.
            The student can come to class from monday-sunday.
        */
        $query5 = "SELECT * FROM attendance WHERE course_id='$course_id' AND staff_id='$staff_id' AND student_id='$student_id' AND weeks='$weeks' AND $day_name='false' ORDER BY id DESC LIMIT 1";
         $result5 = mysqli_query($conn, $query5);
         if(mysqli_num_rows($result5)>0){
            while($row = mysqli_fetch_array($result5)){
                $percentage_attend = $row['percentage_attend'];
                $percentage_attend = (int)$percentage_attend + 20;

                $query8 = "UPDATE attendance SET percentage_attend='$percentage_attend' WHERE student_id='$student_id' AND course_id = '$course_id' AND staff_id='$staff_id' AND weeks='$weeks'";
                $result8 = mysqli_query($conn, $query8);
            }

         }

         //update record if it is the same week
         $query6 = "SELECT * FROM attendance WHERE course_id='$course_id' AND staff_id='$staff_id' AND weeks='$weeks' AND student_id='$student_id' AND $day_name='true' LIMIT 1";
         $result6 = mysqli_query($conn, $query6);
         if(mysqli_num_rows($result6)>0){
                // update records here
                $query7 = "UPDATE attendance SET $day_name='true' WHERE student_id='$student_id' AND course_id = '$course_id' AND staff_id='$staff_id' AND weeks='$weeks'";
                $result7 = mysqli_query($conn, $query7);
         }else{

                $query11 = "SELECT * FROM attendance WHERE $day_name='false' AND course_id='$course_id' AND staff_id='$staff_id' AND weeks='$weeks' AND student_id='$student_id' LIMIT 1";
                $result11 = mysqli_query($conn, $query11);
                if(mysqli_num_rows($result11)>0){
                    $query7j = "UPDATE attendance SET $day_name='true' WHERE student_id='$student_id' AND course_id = '$course_id' AND staff_id='$staff_id' AND weeks='$weeks'";
                    $result7j = mysqli_query($conn, $query7j);
                }else{
                    if($student_id != ""){
                        $attendance_id = uniqid();
                        $query3 = "INSERT INTO attendance (attendance_id, staff_id, student_id, course_id, weeks, percentage_attend) 
                        VALUES('$attendance_id', '$staff_id', '$student_id', '$course_id', '$weeks', '20')";
                        $result3 = mysqli_query($conn, $query3);
                        if($result3){
                            $query4 = "UPDATE attendance SET $day_name='true' WHERE student_id='$student_id' AND course_id = '$course_id' AND staff_id='$staff_id'";
                            $result4 = mysqli_query($conn, $query4);
                            if($result4){
                                echo true;
                            }
                        }
                    }
                }
            }
    }
    
    // we are going to compare two array and pick out value not present in both array
    $compare_diff = array_diff($my_student_not_mark_as_attended_array, $arr_student_ids);
    // reorder the index value
    $reorder_array = array_values($compare_diff);
    //print_r($reorder_array);
    // This is used to insert student that was not mark as attended
    for($j=0; $j<count($reorder_array); $j++){
        if(count($reorder_array) > 0){
            $student_id = $reorder_array[$j];
            
            $query10 = "SELECT * FROM attendance WHERE $day_name='true' AND course_id='$course_id' AND staff_id='$staff_id' AND weeks='$weeks' AND student_id='$student_id' LIMIT 1";
            $result10 = mysqli_query($conn, $query10);
            if(mysqli_num_rows($result10)>0){
                // do nothing
            }else{

                $query11 = "SELECT * FROM attendance WHERE $day_name='false' AND course_id='$course_id' AND staff_id='$staff_id' AND weeks='$weeks' AND student_id='$student_id' LIMIT 1";
                $result11 = mysqli_query($conn, $query11);
                if(mysqli_num_rows($result11)>0){
                    // do nothing
                }else{
                    $attendance_id = uniqid();
                    $query3i = "INSERT INTO attendance (attendance_id, staff_id, student_id, course_id, weeks, percentage_attend, $day_name) 
                    VALUES('$attendance_id', '$staff_id', '$student_id', '$course_id', '$weeks', '0', 'false')";
                    $result3i = mysqli_query($conn, $query3i);
                    if($result3i){
                        echo true;
                    }
                }
            }

        }
    }

}
// for viewing all students
if(isset($_POST['refresh_id']) && isset($_POST['course_id'])){
    $name = "";
    $date = "";
    $sn = 0;
    $search = $_POST['refresh_id'];
    $course_id = $_POST['course_id'];

   $query = "SELECT * FROM course_registration WHERE course_id='$course_id'";
   $result = mysqli_query($conn, $query);
   if(mysqli_num_rows($result)>0){
       while($row = mysqli_fetch_array($result)){
       		$sn++;
       	  $id = $row['id'];
       	  $student_id = $row['student_id'];
          $staff_id = $row['staff_id'];

   $student_name = ""; 
   $faculty_id = "";
   $department_id = "";      
   $query3 = "SELECT * FROM students WHERE student_id='$student_id' LIMIT 1";
   $result3 = mysqli_query($conn, $query3);
   if(mysqli_num_rows($result3)>0){
       while($row = mysqli_fetch_array($result3)){
          $id = $row['id'];
          $student_name = $row['name'];
          $faculty_id = $row['faculty_id'];
          $department_id = $row['department_id'];
      }
  }

   $query2 = "SELECT * FROM faculty WHERE faculty_id='$faculty_id' LIMIT 1";
   $result2 = mysqli_query($conn, $query2);
   if(mysqli_num_rows($result2)>0){
       while($row = mysqli_fetch_array($result2)){
          $id = $row['id'];
          $faculty_name = $row['name'];
      }
  }
   $query3 = "SELECT * FROM department WHERE department_id='$department_id' LIMIT 1";
   $result3 = mysqli_query($conn, $query3);
   if(mysqli_num_rows($result3)>0){
       while($row = mysqli_fetch_array($result3)){
          $id = $row['id'];
          $depart_name = $row['name'];
      }
  }
          echo "<tr>
                                                <td><strong>$sn</strong></td>
                                                <td class='text-dark'>$student_name</td>
                                                <td class='text-dark'>$faculty_name</td>
                                                <td class='text-dark'>$depart_name</td>
                                                <td><div class='checkbox'>
                                                        <input type='checkbox' id='checkbox_$student_id' onclick='butCheck(\"$student_id\")'>
                                                        <label for='checkbox_$student_id'></label>
                                                    </div>
                                                </td>
                                            </tr>";
       }
       
   }
}

?>