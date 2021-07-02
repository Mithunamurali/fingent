<?php

if(isset($_POST["code"]))
{
 $servername = "localhost";
$username = "root";
$password = "root";
$dbname = "fingent";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

    
 session_start();

 $file_data = $_SESSION['file_data'];
 unset($_SESSION['file_data']);
 foreach($file_data as $row)
 {
  if (!preg_match ("/^[a-zA-z]*$/", $row[$_POST["name"]])) {  
    
     echo '<div class="alert alert-danger">Only alphabets and whitespace are allowed</div> ';    
     exit();    
  }
  $data[] = '( "'.$row[$_POST["name"]].'","'.$row[$_POST["code"]].'", "'.$row[$_POST["dept"]].'", "'.$row[$_POST["dob"]].'", "'.$row[$_POST["joining"]].'")';
 }

  
 if(isset($data))
 {

  $enter = "INSERT INTO users (name, code, dept, dob, joining_date)
                    VALUES ".implode(",", $data)."";
                    // echo $enter;
                    $conn->query($enter);


  
 }
 
    
     
       if($enter){
        echo 'data imported sucessfully';
       }
        // $sql = "SELECT * FROM users";
        // $result = $conn->query($sql);
        // $conn->close();
        // $_SESSION['file_data'] = $result;
        // // $output = array(
        // //  // 'error'  => $error,
        // //  'output' => $result
        // // );

        // // echo json_encode($output);
    }
   

   
      



?>