<?php
require __DIR__.'/classes/Database.php';
require __DIR__.'/AuthMiddleware.php';

$db_connection = new Database();
$allHeaders = getallheaders();
$db_connection = new Database();
$conn = $db_connection->dbConnection();
$auth = new Auth($conn, $allHeaders);

$lines = array();
if(file_exists('logfile.log')){
  foreach (file('logfile.log') as $line) {
     if(!empty($line)){
        $parts = explode(',', $line);
        $lines[] = array(
          'date' => isset($parts[0]) ? $parts[0] : null,
          'status' => isset($parts[1]) ? $parts[1] : null,
          'page' => isset($parts[2]) ? $parts[2] : null,
          'user' => isset($parts[3]) ? $parts[3] : null,
        );
      }
  }
  $userIds = array_count_values(
      array_column($lines, 'user')
  );
}
?>
<!DOCTYPE html>
<html>
<head>
  <title>Api Logs</title>
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.3/css/jquery.dataTables.css">
</head>
<body>
  <h1>Total api request <?php echo count($lines)?></h1>
  <h3>All Api logs</h6>
 
<table id="example" class="display" cellspacing="0" width="100%">
   <thead>
      <tr> 
        <th>User</th>
        <th>Status</th>
        <th>Page</th>      
        <th>Date</th>
      </tr>
   </thead>
   <tbody>
   <?php
   if(!empty($lines)){
    foreach ($lines as $line) {    
    ?>
    <tr>
      <td><?php echo empty(trim($line["user"])) ?  null : $auth->fetchUser($line["user"])["name"] ?></td>     
      <td><?php echo $line["status"] ?></td>
      <td><?php echo $line["page"] ?></td>
      <td><?php echo $line["date"] ?></td>
    </tr>
   <?php
      } 
    }?>
    </tbody>
</table>
</br>
</br>
<h3>Api Used per user</h6>
<table id="example1" class="display" cellspacing="0" width="100%">
   <thead>
      <tr> 
        <th>User</th>
        <th>Email</th>
        <th>Count</th>
      </tr>
   </thead>
   <tbody>
   <?php
   if(!empty($userIds)){
    foreach ($userIds as $key => $value) {  
    if($key!=null)  {
    ?>
    <tr>
      <td><?php echo $auth->fetchUser($key)["name"] ?></td>
      <td><?php echo $auth->fetchUser($key)["email"] ?></td>
      <td><?php echo $value; ?></td>
    </tr>
   <?php }
      }
    } ?>
    </tbody>
</table>
</body>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.11.3/js/jquery.dataTables.js"></script>

<script type="text/javascript">
 $(document).ready(function() {
     $('#example').dataTable();
     $('#example1').dataTable();
  });
</script>
</html>