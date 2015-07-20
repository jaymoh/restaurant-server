<?php
require ('config.php');
if(!empty($_POST))
{
    if(empty($_POST['meal_id'])|| empty($_POST['mname']) || empty($_POST['mprice']))
    {
        $response["success"]=0;
        $response["message"]="Update all fields";
        
        die(json_encode($response));
    }
    //perform editing of given row
    $query="UPDATE meal_setter SET meal_name=:aa, meal_price=:bb WHERE meal_id=:cc";
    $query_params=array(
        ':aa'=>$_POST['mname'],
        ':bb'=>$_POST['mprice'],
        ':cc'=>$_POST['meal_id']
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
     $response["message"]="Server failure... please try again";
     
     die(json_encode($response));
 }
 
 $response["success"]=1;
 $response["message"]="Updated successfully!";
 
 echo json_encode($response);
}
/*else
{
?>
<h1>Edit Options</h1> 
        
		<form action="update_meal_details.php" method="post"> 
		    Meal ID:<br /> 
		    <input type="text" name="meal_id" placeholder="meal_id" /> 
		    <br /><br /> 
                     Meal Name:<br /> 
		    <input type="text" name="mname" placeholder="m name" /> 
		    <br /><br />
                    
                    Meal Price:<br /> 
		    <input type="text" name="mprice" placeholder="m price" /> 
		    <br /><br />
		    
		    <input type="submit" value="Update" /> 
		</form> 

<?php
 
}
 * 
 */
?>
