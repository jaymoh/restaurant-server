<?php
require './gcm.php';
require './config.php';
$gcm=new GCM();

if(!empty($_POST))
{
    if(empty($_POST['hotel_id'])||empty($_POST['meal_name'])
            ||empty($_POST['message'])||empty($_POST['pword'])
            ||empty($_POST['customer']))
    {
        $response["success"]=0;
        $response["message"]="Some data is missing";
        die(json_encode($response));
    }
    //else pick gcm reg id
    //pick gcm regids for hotel_id
 $query="SELECT * FROM gcm_users WHERE user_id=:aa AND password=:bb";
 $query_params=array(
     ':aa'=>$_POST['customer'],
     ':bb'=>$_POST['pword']
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
     $response["message"]="server failure...";
     
     die(json_encode($response));
 }
 $row=$stmt->fetch();
 if($row)
 {
     $gcmRegs=$row['gcm_regid'];
 }
 else 
     {
     $response["success"]=0;
     $response["message"]="An error occured...";
     
      die(json_encode($response));
 }
 
 //pick hotel name
 $query="SELECT * FROM hotel_name WHERE hotel_id=:cc";
 $query_params=array(
     ':cc'=>$_POST['hotel_id']
 );
 
 try {
     $stmt=$db->prepare($query);
     $result=$stmt->execute($query_params);
 }
 catch (PDOException $ex)
 {
      $response["success"]=0;
     $response["message"]="Server failure please try again...";
     die(json_encode($response));
 }
   $row=$stmt->fetch();
   if($row)
   {
       $hotel_name=$row['hotel_name'];
   }
 //time to send the notification now
   $pword=99;
   $time=$_POST['timeto'];
   $notification=$_POST['message'];
   $meal_name=$_POST['meal_name'];
   
   $gcmRegIds=array($gcmRegs);
     $message=array("message"=>$notification,
             "meal_name"=>$meal_name,
         "customer"=>$hotel_name,
         "pword"=>$pword,
         "timeto"=>$time);
     $send= $gcm->sendPushNotificationToGCM($gcmRegIds, $message);
     
     if($send)
	 {
     $response["success"]=1;
     $response["message"]="Send successfully...";
     echo json_encode($response);
	 }
	 else
	 {
		  $response["success"]=0;
		 $response["message"]="Server failure... error in sending, please try again";
		echo json_encode($response); 
	 }
}
?>
