
<!DOCTYPE html>
<html>
   <head>
     <title>CSV Column Mapping in PHP</title>
     <meta name="viewport" content="width=device-width, initial-scale=1.0">
      <script src="http://code.jquery.com/jquery.js"></script>
     <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
      <style>
      .table tbody tr th
      {
        min-width: 200px;
        padding: 5px !important;
      }

      .table tbody tr td
      {
       
        min-width: 200px ;
      }
.uploadcsv th,.uploadcsv td{
   padding: 5px;
}

      </style>
   </head>
   <body>
    <div class="container">

      <?php


$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "fingent";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);


 
    
       $sql = "SELECT * FROM users";
        $result = $conn->query($sql);


        $conn->close();
        
       

   

?>
<h1 align="center"> Page to display the Employee details </h3>

<table border="1" class="uploadcsv" align="center">
<tr>
<th> Employee code </th>
<th> Employee name </th>
<th> Department </th>
<th> Age </th> 
<th> Experience in the organisation </th> 

</tr>

<?php 
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()){
    $today=strtotime("now");
    $dob=strtotime($row['dob']);
    $joining=strtotime($row['joining_date']);
    $age=round(($today-$dob) / (60 * 60 * 24*365));
    $experience=round(($today-$joining) / (60 * 60 * 24*365));
      ?>
    <tr>
    <td><?php echo $row['code'];?></td>
    <td><?php echo $row['name'];?></td>
    <td><?php echo $row['dept'];?></td>
    <td><?php echo $age;?></td>
    <td><?php echo $experience;?></td>
    </tr>
    <?php
    } 
}?>
</table>
     <br />
     <br />
      <h3 align="center">Upload CSV file Here</h1>
      <br />
        <div id="message"></div>
      <div class="panel panel-default" align ="center">
          <div class="panel-heading">
            <h3 class="panel-title" id="panel-title">Select CSV File</h3>
          </div>
          <div class="panel-body">
            <div class="row" id="upload_area">
              <form method="post" id="upload_form" enctype="multipart/form-data">
                <div class="col-md-6" align="right">Select File</div>
                <div class="col-md-6">
                  <input type="file" name="file" id="csv_file" />
                </div>
                <br /><br /><br />
                <div class="col-md-12" align="center">
                  <input type="submit" name="upload_file" id="upload_file" class="btn btn-primary" value="Upload" />
                </div>
              </form>
              
            </div>
            <div class="table-responsive" id="process_area">

            </div>
          </div>
        </div>
     </div>
     
   </body>
</html>

<script>
$(document).ready(function(){

  $('#upload_form').on('submit', function(event){

    event.preventDefault();
    $.ajax({
      url:"csv_upload.php",
      method:"POST",
      data:new FormData(this),
      dataType:'json',
      contentType:false,
      cache:false,
      processData:false,
      success:function(data)
      {
        if(data.error != '')
        {
          $('#message').html('<div class="alert alert-danger">'+data.error+'</div>');
        }
        else
        {
          $('#process_area').html(data.output);
          $('#upload_area').css('display', 'none');
        }
      }
    });

  });

  var total_selection = 0;

  var code = 0;

  var name = "";

  var dept = "";
  var dob = "";
  var joining = "";
  

  var column_data = [];

  $(document).on('change', '.set_column_data', function(){

    var column_name = $(this).val();

    var column_number = $(this).data('column_number');

    if(column_name in column_data)
    {
      alert('You have already define '+column_name+ ' column');

      $(this).val('');

      return false;
    }

    if(column_name != '')
    {
      column_data[column_name] = column_number;

    }
    else
    {
      const entries = Object.entries(column_data);

      for(const [key, value] of entries)
      {
        if(value == column_number)
        {
          delete column_data[key];
        }
      }
    }

    total_selection = Object.keys(column_data).length;

    if(total_selection == 5)
    {
      $('#import').attr('disabled', false);

      name = column_data.name;

      code = column_data.code;

      dept = column_data.dept;
      dob = column_data.dob;
      joining = column_data.joining;
    }
    else
    {
      $('#import').attr('disabled', 'disabled');
    }

  });

  $(document).on('click', '#import', function(event){

    event.preventDefault();

    $.ajax({
      url:"import.php",
      method:"POST",
      data:{name:name, code:code, dept:dept,dob:dob,joining:joining},
      beforeSend:function(){
        $('#import').attr('disabled', 'disabled');
        $('#import').text('Importing...');
      },
      success:function(data)
      {
        $('#import').attr('disabled', false);
        $('#import').text('Import');
        $('#process_area').css('display', 'none');
        $('#upload_area').css('display', 'block');
        $('#upload_form')[0].reset();
        $('#message').html("<div class='alert alert-success'>"+data+"</div>");
        location.reload();

      }
    })

  });
  
});
</script>