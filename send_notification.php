<?php
require './gcm.php';
require './config.php';

$gcm=new GCM();

if(!empty($_POST))
{
    if(empty($_POST['meal_id'])|| empty($_POST['hotel_id'])
            ||empty($_POST['options'])|| empty($_POST['user_id'])
            ||empty($_POST['pword']))
    {
        $response["success"]=0;
        $response["message"]="Some data is missing...";
        die(json_encode($response));
    }
    $customer=$_POST['user_id'];
    $pword=$_POST['pword'];
    
    //pick meal name from table
    $query="SELECT * FROM meal_setter WHERE meal_id=:aa";
    $query_params=array(
        ':aa'=>$_POST['meal_id']
    );
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
 $row=$stmt->fetch();
 if($row)
 {
     $meal_name=$row['meal_name'];
 }
 //pick gcm regids for hotel_id
 $query="SELECT * FROM gcm_users WHERE user_id=:bb";
 $query_params=array(
     ':bb'=>$_POST['hotel_id']
 );
 
 //execute query
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
     $response["message"]="that hotel has not registered for this service...";
     
      die(json_encode($response));
 }
 
 /*see what the user requested>> take away or sit_in
  * take_away>>00
  * sit_in>>11
  */
 $choice=$_POST['options'];
 if($choice==00)
 {
     //set message
     $notification="Take Away Request";
     $gcmRegIds=array($gcmRegs);
     $message=array("message"=>$notification,
             "meal_name"=>$meal_name,
         "customer"=>$customer,
         "pword"=>$pword);
     $send= $gcm->sendPushNotificationToGCM($gcmRegIds, $message);
     
     if($send)
     {
     $response["success"]=1;
     $response["message"]="Request send... wait for response";
     echo json_encode($response);
     }
 else {
         $response["success"]=0;
     $response["message"]="Error sending request...please try again";
     echo json_encode($response);
     }
 }
 elseif ($choice==11) 
    {
      $notification="Request for table reservation";
      
      $gcmRegIds=array($gcmRegs);
     $message=array("message"=>$notification,
             "meal_name"=>$meal_name,
         "customer"=>$customer,
         "pword"=>$pword);
     
     $send= $gcm->sendPushNotificationToGCM($gcmRegIds, $message);
     
     
	 if($send)
     {
     $response["success"]=1;
     $response["message"]="Request send... wait for response";
     echo json_encode($response);
	 }
         
	 else
	 {
		 $response["success"]=0;
		 $response["message"]="Server failure... error in sending, please try again";
		 echo json_encode($response);
	 }
 
    }
 
}
else
{
?>
<h1>Trial</h1> 
        
		<form action="send_notification.php" method="post"> 
		    Meal_id:<br /> 
		    <input type="text" name="meal_id" placeholder="m" /> 
		    <br /><br /> 
		    hotel_id:<br /> 
		    <input type="text" name="hotel_id" placeholder="h" value="" /> 
		    <br /><br /> 
                    options:<br /> 
		    <input type="text" name="options" placeholder="o" value="" /> 
		    <br /><br />
                    user_id:<br /> 
		    <input type="text" name="user_id" placeholder="u" value="" /> 
		    <br /><br />
                    password:<br /> 
		    <input type="text" name="pword" placeholder="p" value="" /> 
		    <br /><br />
		    <input type="submit" value="Submit" /> 
		</form> 

<?php
}
 
?>

