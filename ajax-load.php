<?php
$conn = mysqli_connect("localhost","root","","test") or die("connection failed");

$sql = "SELECT * FROM students";
$result = mysqli_query($conn, $sql) or die("sql query failed");
$output = "";
if(mysqli_num_rows($result) > 0){
  $output = '<table border="1" width="100%" cellspacing="0" cellpadding="10px">
                <tr>
                  <th style="background:rgb(13, 85, 85); color:white;">Id</th>
                  <th style="background:rgb(13, 85, 85); color:white;">Name</th>
                  <th style="background:rgb(13, 85, 85); width: 100px; color:white;">Edit</th>
                  <th style="background:rgb(13, 85, 85); width: 100px; color:white;">Delete</th>
                </tr>';

                while($row = mysqli_fetch_assoc($result)){
                  $output .= "<tr><td>{$row['id']}</td><td>{$row['first_name']} {$row['last_name']}</td><td><button class='edit-btn' data-eid='{$row["id"]}'>Edit</button></td><td><button class='delete-btn' data-id='{$row["id"]}'>Delete</button></td></tr>";
                }
  $output .= "</table>";

  mysqli_close($conn);

  echo $output;
}else{
    echo "<h2>no record found</h2>";
}
?>