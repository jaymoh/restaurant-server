<?php
require './config.php';

if(!empty($_POST))
{
    if(empty($_POST['uname'])|| empty($_POST['resname'])
            || empty($_POST['review']))
    {
        $response["success"]=0;
        $response["message"]="Some fields seems to be empty";
        
        die(json_encode($response));
    }
    
    //insert details
    $query="INSERT INTO hotel_reviews (res_name, username, review) 
        VALUES(:aa, :bb, :cc)";
    $query_params=array(
        ':aa'=>$_POST['resname'],
        ':bb'=>$_POST['uname'],
        ':cc'=>$_POST['review']
    );
    
    try 
    {
        $stmt=$db->prepare($query);
        $result=$stmt->execute($query_params);
    }
 catch (PDOException $ex)
 {
     $response["success"]=0;
     $response["message"]="Server failure... please try again";
     
     die(json_encode($response));
 }
 
 //else
 $response["success"]=1;
 $response["message"]="Your review has been added";
 
 echo json_encode($response);
}
?>
