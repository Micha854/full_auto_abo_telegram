<?php
$query = "SELECT id FROM version";
$result = mysqli_query($mysqli, $query);
if(empty($result)) {
  $query = "CREATE TABLE version (id double NOT NULL)";
  $result = mysqli_query($mysqli, $query);
  if ($result === TRUE) {
    $mysqli->query("INSERT INTO version set id = 2.41");
    $version = 2.41;
  }
} else{
  $get_version = $result->fetch_array();
  $version = $get_version['id'];
}
?>