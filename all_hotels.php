<?php
require('config.php');
//this simply retrieves all hotels from the hotel_name table
$query="SELECT * FROM hotel_name ORDER BY hotel_id ASC";
$query_params=null;

//execute the query
try
{
    $stmt=$db->prepare($query);
    $result=$stmt->execute($query_params);
} 
//catch any errors
catch (PDOException $ex)
{
    $response["success"]=0;
    $response["message"]="Server failure... relaunch app";
    die(json_encode($response));
}

//retrieve the returned rows
$rows=$stmt->fetchAll();

if($rows)
{
    $response["success"]=1;
    $response["message"]="Restaurant data available";
    $response["restaurants"]=array();
    
    //loop through the result set
    foreach ($rows as $row)
    {
        $restaurant=array();
        $restaurant["hotel_id"]=$row["hotel_id"];
        $restaurant["hname"]=$row["hotel_name"];
        $restaurant["location"]=$row["location"];
        
        //update array
        array_push($response["restaurants"], $restaurant);
    }
    //update json
    echo json_encode($response);
}
//if no rows were found in the data base
else {
    $response["success"]=0;
    $response["message"]="No data is available";
    
    die(json_encode($response));
    
}

?>
