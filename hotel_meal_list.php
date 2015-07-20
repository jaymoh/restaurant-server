<?php
require('config.php');
//retrieves meals list based on a hotel id
if(!empty($_POST))
{
    if(empty($_POST['hotel_id']))
    {
        //response
        $response["success"]=0;
        $response["message"]="No hotel id sent";
        
        die(json_encode($response));
    }
$query="SELECT * FROM meal_setter WHERE hotel_id=:aa ORDER BY meal_price DESC";
$query_params=array(
    ':aa'=>$_POST['hotel_id']
);

//execute the query
try {
    $stmt=$db->prepare($query);
    $result=$stmt->execute($query_params);
}
//catch errors
catch(PDOException $ex)
{
    $response["success"]=0;
    $response["message"]="Server failure...";
    die(json_encode($response));
}
//retrieve the details
$rows=$stmt->fetchAll();

if($rows)
{
    $response["success"]=1;
    $response["message"]="List available";
    $response["meals"]=array();
    
    //loop through the result set
    foreach ($rows as $row)
    {
        $meal=array();
        $meal["mname"]=$row["meal_name"];
        $meal["mprice"]=$row["meal_price"];
        $meal["id"]=$row["meal_id"];
        
        //update json
        array_push($response["meals"], $meal);
    }
    //update json
    echo json_encode($response);
}
//else no meal list from that hotel
    else {
        $response["success"]=0;
        $response["message"]="No menu from that restaurant";
        
        die(json_encode($response));
    }
}
else
{
?>
<h1>Meal List</h1> 
        
		<form action="hotel_meal_list.php" method="post"> 
		    Hotel id:<br /> 
		    <input type="text" name="hotel_id" placeholder="id" /> 
		    <br /><br /> 
		    
		    <input type="submit" value="Request" /> 
		</form> 

<?php
}
?>