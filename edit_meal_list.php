<?php
require ('config.php');
if(!empty($_POST))
{
    if(empty($_POST['hname']) ||empty($_POST['uname']))
    {
        $response["success"]=0;
        $response["message"]="No id posted";
        die(json_encode($response));
    }
    //else create query
    
    $query="SELECT hotel_id FROM hotel_name WHERE hotel_name=:aa AND owner_username=:bb";
    $query_params=array(
        ':aa'=>$_POST['hname'],
        ':bb'=>$_POST['uname']
    );
    
    //execute 
    try
    {
        $stmt=$db->prepare($query);
        $result=$stmt->execute($query_params);
    }
 catch (PDOException $ex)
 {
     $response["success"]=0;
     $response["message"]="Server failure... please try again";
 }
 $row=$stmt->fetch();
 if($row)
 {
     $hotel_id=$row['hotel_id'];
     //load the menu list for this hotel ids
     $query="SELECT * FROM meal_setter WHERE hotel_id=:cc";
     $query_params=array(
         ':cc'=>$hotel_id
     );
     //execute
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
 
 //pick all the returned values
 $rows=$stmt->fetchAll();
 if($rows)
 {
     $response["success"]=1;
     $response["message"]="List available";
     $response["meals"]=array();
     
     //loop through the result set
    foreach ($rows as $row)
    {
        $meal=array();
        $meal["mname"]=$row["meal_name"];
        $meal["mprice"]=$row["meal_price"];
        $meal["id"]=$row["meal_id"];
        
        //update json
        array_push($response["meals"], $meal);
    }
    //update json
    echo json_encode($response);
 }
 }
 else
 {
     $response["success"]=0;
     $response["message"]="mismatch in username and hotel name";
     die(json_encode($response));
 }
}
//display fields
else
{
?>
<h1>Edit Credentials</h1> 
        
		<form action="edit_meal_list.php" method="post"> 
		    Hotel Name:<br /> 
		    <input type="text" name="hname" placeholder="h name" /> 
		    <br /><br /> 
                     User Name:<br /> 
		    <input type="text" name="uname" placeholder="u name" /> 
		    <br /><br />
		    
		    <input type="submit" value="Request" /> 
		</form> 

<?php
}
?>
