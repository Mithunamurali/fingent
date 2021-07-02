<?php

//upload.php

session_start();

$error = '';

$html = '';

if($_FILES['file']['name'] != '')
{
 $file_array = explode(".", $_FILES['file']['name']);

 $extension = end($file_array);

 if($extension == 'csv')
 {
  $file_data = fopen($_FILES['file']['tmp_name'], 'r');

  $file_header = fgetcsv($file_data);
    if(count($file_header)<=5){

      $html .= '<table class="table table-bordered"><tr>';

      for($count = 0; $count < count($file_header); $count++)
      {
       $html .= '
        <th>
                <select name="set_column_data" class="form-control set_column_data" data-column_number="'.$count.'">
                 <option value="code">Employee code</option>
                 <option value="name">Employee name</option>
                 <option value="dept">Department</option>
                 <option value="dob">DOB</option>
                 <option value="joining">Joining Date</option>
                </select>
               </th>
       ';
      }

      $html .= '</tr>';

      $limit = 0;

      while(($row = fgetcsv($file_data)) !== FALSE)
      {
       $limit++;
       if($limit < 19){
           if($limit < 5)
           {
            $html .= '<tr>';

            for($count = 0; $count < count($row); $count++)
            {
             $html .= '<td>'.$row[$count].'</td>';
            }

            $html .= '</tr>';
           }

           $temp_data[] = $row;
        }else{
            $error = 'Only twenty rows are allowed';
        }
      }

      $_SESSION['file_data'] = $temp_data;

      $html .= '
      </table>
      <br />
      <div align="right">
       <button type="button" name="import" id="import" class="btn btn-success" disabled>Import</button>
      </div>
      <br />
      ';
    }else{
        $error = 'Only five columns are allowed';
    }
 }
 else
 {
  $error = 'Only <b>.csv</b> file allowed';
 }
}
else
{
 $error = 'Please Select CSV File';
}

$output = array(
 'error'  => $error,
 'output' => $html
);

echo json_encode($output);


?>