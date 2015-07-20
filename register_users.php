<?php
require './config.php';
if(!empty($_POST))
{
    if(empty($_POST['user_id'])|| empty($_POST['gcm_regid'])
            ||empty($_POST['email'])|| empty($_POST['pword']))
    {
        $response["success"]=0;
        $response["message"]="Some data is missing";
        
        die(json_encode($response));
    }
    
    $query="INSERT INTO gcm_users (user_id, gcm_regid, email, password)
        VALUES(:aa, :bb, :cc, :dd)";
    $query_params=array(
        ':aa'=>$_POST['user_id'],
        ':bb'=>$_POST['gcm_regid'],
        ':cc'=>$_POST['email'] ,
        ':dd'=>$_POST['pword']
    );
    
    //execute the query
    try
    {
        $stmt=$db->prepare($query);
        $result=$stmt->execute($query_params);
    }
 catch (PDOException $ex)
 {
     $response["success"]=0;
    $response["message"]="Server failure please try again...";
    
    die(json_encode($response));
 }
 //insertion successful
 $response["success"]=1;
 $response["message"]="Successfully registered for notifications";
 echo json_encode($response);
 
}
?>
