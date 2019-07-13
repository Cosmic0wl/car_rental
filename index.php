<!DOCTYPE html>
<html>
<head>
	<title>Car Rental</title>
	<link href="//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
	<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
</head>
</head>
<body>
	<?php
class Database {

    public $db_host = "localhost";
    public $db_name = "cr09_valentina_panetta_carrental";
    public $db_user = "root";
    public $db_pw = "";
    public $connection = '';
    public function connect() {
    //the @ sign will remove any warnings from mysqli!
        $this->connection = @mysqli_connect($this->db_host,$this->db_user,$this->db_pw,$this->db_name);
    //this is only for debugging, should not be used in a productive system
    /*    if (!$this->connection) {
            echo "Error: Unable to connect to database.<br>";
            echo "Debugging errno: " . mysqli_connect_errno() ."<br>";
            echo "Debugging error: " . mysqli_connect_error();
        } else {
            echo "Success: Connection to database was established! <br>";
            echo "Host information: " . mysqli_get_host_info($this->connection);
        }*/
    }
    public function read($table, $fields='*', $join='',$where='',$orderby='') {
        $this->connect();
        $fields = is_array($fields) ? implode(", ", $fields) : $fields;
        $join = is_array($join) ? implode(" ", $join) : $join;
        $sql = "SELECT ".$fields." FROM ".$table." ".$join." ".$where." ".$orderby." ;";
         //echo $sql; //only for testing
        $result = $this->connection->query($sql);
        if($result->num_rows == 1){
        	$return = $result->fetch_assoc();
        }else {
        $return = $result->fetch_all(MYSQLI_ASSOC);

        }
        mysqli_close($this->connection);
        return $return;
    }

    public function update($table,$set,$condition) {
        $this->connect();
        $sql = '';
        $where= '';
        foreach ($set as $key => $value) {
            // $sql = first_name = 'serri', last_name = ghiath
            if($sql != ''){
                  $sql .=", ";
             }
            $sql .= $key . "='".$value."' ";
        }
        foreach ($condition as $key => $value) {
            if($where != ''){
                  $where .=" AND ";
             }
            $where .= $key . "='" . $value . "'";
        }
        $sql = "UPDATE ".$table." SET ".$sql." WHERE ".$where.";";
        $this->connection->query($sql);
        mysqli_close($this->connection);
    }
    public function insert($table, $fields, $values) {
        $this->connect();
        $fields = is_array($fields) ? implode(", ", $fields) : $fields;
        //$values = implode("','", $values);
        $sql = '';
        if (is_array($values)){
            foreach ($values as $value) {
                        if ($sql !=''){
                            $sql .=", ";
                        }
                        $sql .= "'".mysqli_real_escape_string($this->connection,$value)."'";
                    }
        } else {
            $sql = $values;
        }
        
        $sql = "INSERT INTO ".$table." (".$fields.") VALUES (".$sql.");";
        $res = $this->connection->query($sql);
        mysqli_close($this->connection);
    }
    public function delete($table,$condition) {
        $this->connect();
        $sql='';
        foreach ($condition as $key => $value) {
            if($sql != ''){
                  $sql .=" AND ";
             }
            $sql .= $key . "='" . $value . "'";
        }
        $sql="DELETE FROM ".$table." WHERE ".$sql;
        $result = $this->connection->query($sql);
        mysqli_close($this->connection);
    }
}
$obj = new Database ();

$mary = $obj->read("customers","*","",$where=" WHERE customer_id = 2 ");
// echo $mary["first_name"]."<br>";
$rows = $obj->read("vehicles");
// foreach($rows as $row){
// 	echo $row["first_name"]."<br>";
// }
?>
<div class="container">
	<div class="jumbotron jumbotron-fluid bg-primary">
  <div class="container">
    <h1 class="display-4 text-white">Rental Car-rental</h1>
    <p class="lead text-white">Our cool cars</p>
  </div>
	</div>
	<div class="row">
		
	<?php foreach($rows as $row){
	echo "<div class='col-4 my-3'><div class='card'>
  <img class='card-img-top border-bottom p-2' src='icon.png'>
  <div class='card-body'>
    <h5 class='card-title'>".$row['model']."</h5>
    <p class='card-text'>Vehicle Type: ".$row['vehicle_type']."</p>
    <p class='card-text'>Fuel Type: ".$row['fuel_type']."</p>
    <p class='card-text'>Capacity: ".$row['capacity']." seats</p>
    <p class='card-text'>Current Mileage: ".$row['current_mileage']." km</p>
    <a href='#' class='btn btn-primary'>Book this car</a>
  </div>
</div></div>";
} ?>
</div>
</div>

</body>
</html>