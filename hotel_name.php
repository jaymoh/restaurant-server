<?php
require('config.php');
if(!empty($_POST))
{
    if(empty($_POST['uname'])|| empty($_POST['hname'])|| empty($_POST['location']))
    {
        //response
        $response["success"]=0;
        $response["message"]="Please fill all fields";
        
        die(json_encode($response));
    }
    //else pick the data
   $query="SELECT 1 FROM hotel_name WHERE hotel_name=:aa";
   
   //set params
   $query_params=array(
       ':aa'=>$_POST['hname']
   );
   
   //run the query
   try
   {
       $stmt=$db->prepare($query);
       $result=$stmt->execute($query_params);
   }
   //catch exceptions
   catch (PDOException $ex)
   {
       //pass error to json
       $response["success"]=0;
       $response["message"]="Server failure";
       //end the execution
       die(json_encode($response));
   }
   //in case data is returned by the query, the hotel name exists
   $row=$stmt->fetch();
   if($row)
   {
       //set responses
       $response["success"]=0;
       $response["message"]="that hotel name exists, you might want to choose a different one";
       
       die(json_encode($response));
   }
   //confirm username existence
   $query="SELECT 1 FROM hotel_owner WHERE username=:aa";
   $query_params=array(
       ':aa'=>$_POST['uname']
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
     $response["message"]="Server failure... please try again ";
     
     die(json_encode($response));
 }
  $row=$stmt->fetch();
  
  if(!$row)
  {
      $response["success"]=0;
     $response["message"]="Sorry, That user name doesn't exist...";
      
     die(json_encode($response));
  }
   //else insert the hotel
   $query="INSERT INTO hotel_name(owner_username, hotel_name, location)
       VALUES(:aa, :bb, :cc)";
   
   //set params
   $query_params=array(
       ':aa'=>$_POST['uname'],
       ':bb'=>$_POST['hname'],
       ':cc'=>$_POST['location']
   );
   
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
       $response["message"]="Server failure... please try again";
       
       die(json_encode($response));
   }
   
   /*the isertion was successful*/
   
   $response["success"]=1;
   $response["message"]="Restaurant details added";
   
   die(json_encode($response));
}

//else display a form
/*{
 ?>

<h1> Add hotel details</h1>
               
                <form action="hotel_name.php" method="post">
	
			 Owner username:<br /> 
	    <input type="text" name="uname" value="" /> 
	    <br /> <br /> 
            
            
           Restaurant name:&nbsp;&nbsp;<br /> 
	    <input type="text" name="hname" value="" /> 
	    <br /> <br /> 
            
	    Location:&nbsp;&nbsp;<br /> 
	    <input type="text" name="location" value="" /> 
	    <br /> <br /> 
	    <input type="submit" value="Add" /> 
	</form>

<?php
}
 * 
 */
?>
