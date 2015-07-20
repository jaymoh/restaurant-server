<?php
require ('config.php');
if(!empty($_POST))
{
    if(empty($_POST['meal_id'])|| empty($_POST['options']))
    {
        $response["success"]=0;
        $response["message"]="No options sent";
        
        die(json_encode($response));
    }
    $send_options=$_POST['options'];
    
    if($send_options==11)
    {
        //pick the values and send to the application
        $query="SELECT meal_id, meal_name, meal_price FROM meal_setter WHERE meal_id=:aa";
        $query_params=array(
            ':aa'=>$_POST['meal_id']
        );
        
       try
       {
            //execute
        $stmt=$db->prepare($query);
        $result=$stmt->execute($query_params);
    }
 catch (PDOException $ex)
 {
     $response["success"]=0;
     $response["message"]="Server failure... try again";
     
     die(json_encode($response));
 }
 $row=$stmt->fetch();
 
 if($row)
 {
     $response["success"]=1;
     $response["message"]="Meal id exists";
     $response['meal']=array();
     
     $meal=array();
     $meal["meal_id"]=$row["meal_id"];
     $meal["mname"]=$row["meal_name"];
     $meal["price"]=$row["meal_price"];
     
     array_push($response["meal"], $meal);
     
   echo   json_encode($response);
 }
        
    }
    elseif ($send_options==00)
        {
    //perform deletion
        $query="DELETE FROM meal_setter WHERE meal_id=:bb";
        $query_params=array(
            ':bb'=>$_POST['meal_id']
        );
        
        try {
            //perform deletion
        $stmt=$db->prepare($query);
        $result=$stmt->execute($query_params);
        }
        
 catch (PDOException $ex)
 {
     $response["success"]=0;
     $response["message"]="Server failure... please try again";
     
     die(json_encode($response));
 }
 
       $response["success"]=1;
       $response["message"]="Deletion successful";
       
       echo json_encode($response);
        }
    
}
//display fields
/*else
{
?>
<h1>Edit Options</h1> 
        
		<form action="meal_edit_options.php" method="post"> 
		    Meal ID:<br /> 
		    <input type="text" name="meal_id" placeholder="meal_id" /> 
		    <br /><br /> 
                     Options:<br /> 
		    <input type="text" name="options" placeholder="options" /> 
		    <br /><br />
		    
		    <input type="submit" value="Request" /> 
		</form> 

<?php
 
}
 * 
 */
?>
