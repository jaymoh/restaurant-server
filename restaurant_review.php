<?php
require './config.php';

//select all, ordering from the last
$query="SELECT * FROM hotel_reviews ORDER BY id DESC";
try 
{
    $query_params=null;
    $stmt=$db->prepare($query);
    $result=$stmt->execute($query_params);
}
 catch (PDOException $ex)
 {
     $response["success"]=0;
     $response["message"]="Server failure...";
     die(json_encode($response));
 }
 
 //retrieve
 $rows=$stmt->fetchAll();
 
 if($rows)
 {
     $response["success"]=1;
     $response["message"]="Reviews available";
     $response["reviews"]=array();
     
     foreach($rows as $row)
     {
         $review=array();
         $review["id"]=$row["id"];
         $review["uname"]=$row["username"];
         $review["resname"]=$row["res_name"];
         $review["review"]=$row["review"];
         
         array_push($response["reviews"], $review);
     }
     echo json_encode($response);
 }
 else
 {
     $response["success"]=0;
     $response["message"]="No reviews been posted yet..";
     die(json_encode($response));
 }
?>
