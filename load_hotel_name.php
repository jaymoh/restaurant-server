<?php
require('config.php');
if(!empty($_POST))
{
    if(empty($_POST['uname']))
    {
        //response
        $response["success"]=0;
        $response["message"]="No user id sent";
        
        die(json_encode($response));    
    }
    $query="SELECT * FROM hotel_name WHERE owner_username=:aa";
    $query_params=array(
        ':aa'=>$_POST['uname']
    );
    //execute the query
    try {
        $stmt=$db->prepare($query);
        $result=$stmt->execute($query_params);
    }
 catch (PDOException $ex)
 {
     $response["success"]=0;
     $response["message"]="Server failure... relaunch app";
     die(json_encode($response));
 }
 //retrieve the hotel ids
 $rows=$stmt->fetchAll();
 
 if($rows)
 {
     $response["success"]=1;
     $response["message"]="hotel ids available";
     $response["hotels"]=array();
     
     foreach($rows as $row)
     {
         $hotel=array();
         $hotel["id"]=$row["hotel_id"];
         $hotel["hname"]=$row["hotel_name"];
         
         //update the array
         array_push($response["hotels"], $hotel);
     }
     echo json_encode($response);
 }
 else
 {
     $response["success"]=0;
     $response["message"]="No restaurants set for that username";
     
     die(json_encode($response));
 }
}
/*
else
{
?>
<h1>Username</h1> 
        
		<form action="load_hotel_name.php" method="post"> 
		    Username:<br /> 
		    <input type="text" name="uname" placeholder="username" /> 
		    <br /><br /> 
		    
		    <input type="submit" value="Request" /> 
		</form> 

<?php
}
 * 
 */
?>