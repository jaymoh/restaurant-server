<?php
require('config.php');

if(!empty($_POST))
{
    if(empty($_POST['mname'])|| empty($_POST['mprice']) 
            || empty($_POST['uname']) ||empty($_POST['hname']))
    {
        $response["success"]=0;
        $response["message"]="Please fill all fields";
        
        die(json_encode($response));
    }
    
    //check for hotel name id existence
    $query="SELECT hotel_id FROM hotel_name WHERE owner_username=:dd AND hotel_name=:ee";
    $query_params=array(
        ':dd'=>$_POST['uname'],
        ':ee'=>$_POST['hname']
    );
    try
    {
        $stmt=$db->prepare($query);
        $result=$stmt->execute($query_params);
    }
    catch(PDOException $ex)
    {
        $response["success"]=0;
        $response["message"]="Server failure... please try again";
    }
    
    $row=$stmt->fetch();
    if(!$row)
    {
        $response["success"]=0;
        $response["message"]="restaurant name or username does not exist!";
        die(json_encode($response));
    }
    
    //else pick hotel id and set update parameters
    
    $hotel_id=$row["hotel_id"];
    
    //check if same meal same hotel id has been entered
    $query="SELECT 1 FROM meal_setter WHERE meal_name=:aa AND hotel_id=:bb";
    
    //set params
    $query_params=array(
        ':aa'=>$_POST['mname'],
        ':bb'=>$hotel_id
    );
    //execute query
    try
    {
        $stmt=$db->prepare($query);
       $result=$stmt->execute($query_params);
    }
    //catch exceptions
    catch(PDOException $ex)
    {
        $response["success"]=0;
        $response["message"]="Server failure... please try again ";
        
        //kill the processing
        die(json_encode($response));
    }
    //if result is returned, then same meal exists
    $row=$stmt->fetch();
    if($row)
    {
        $response["success"]=0;
        $response["message"]="Same meal exists, you might want to edit from your settings panel";
        
        die(json_encode($response));
    }
    
    //else insert the meal
    $query="INSERT INTO meal_setter (meal_name, meal_price, hotel_id)
        VALUES(:aa, :bb, :cc)";
   
    //set the params
            $query_params=array(
                ':aa'=>$_POST['mname'],
                ':bb'=>$_POST['mprice'],
                ':cc'=>$hotel_id
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
            //else insertion was successful
            $response["success"]=1;
            $response["message"]="Added successfully!";
            
            echo json_encode($response);
}
//else display some form
/*
else {
 ?>

<h1> Set meal prices</h1>
               
                <form action="meal_setter.php" method="post">
                
	
			 Meal Name:<br /> 
	    <input type="text" name="mname" value="" /> 
	    <br /> <br /> 
            
            
			 Meal Price:<br /> 
                         <input type="text" name="mprice" value="" onkeypress="if ( isNaN( String.fromCharCode(event.keyCode) )) return false;"/> 
	    <br /> <br /> 
            
            
            Username:&nbsp;&nbsp;<br /> 
	    <input type="text" name="uname" value="" /> 
	    <br /> <br /> 
            
            Hotel name:&nbsp;&nbsp;<br /> 
	    <input type="text" name="hname" value="" /> 
	    <br /> <br />
         
	    <input type="submit" value="Add" /> 
	</form>

<?php
}
 * 
 */
?>


