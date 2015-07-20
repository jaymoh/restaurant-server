<?php
require('config.php');
if(!empty($_POST))
{
    if(empty($_POST['fname'])|| empty($_POST['lname'])|| 
            empty($_POST['uname'])||empty($_POST['pword']))
    {
        //response
        $response["success"]=0;
        $response["message"]="Please fill all the fields";
        
        die(json_encode($response));
    }
    //else pick the data and compare the user name
    $query="SELECT 1 FROM hotel_owner WHERE username=:aa";
    
    //set params
    $query_params=array(
        ':aa'=>$_POST['uname']
    );
    
    //run the comparison query
    try {
        $stmt=$db->prepare($query);
        $result=$stmt->execute($query_params);       
    }
    //catch any errors
    catch (PDOException $ex)
    {
        //pass error
        $response["success"]=0;
        $response["message"]="Server failure... please try again";
        
        die(json_encode($response));
    }
    //in case data is returned then the username is already taken
    $row=$stmt->fetch();
    if($row)
    {
        //the username exists
        $response["success"]=0;
        $response["message"]="that username is already taken, you might want to choose another one";
        
        die(json_encode($response));
    }
    //else add the owner to the database
    $query="INSERT INTO hotel_owner (owner_first_name, owner_last_name, username, login_password)
        VALUES(:aa, :bb, :cc, :dd)";
    
    //set params
    $query_params=array(
        ':aa'=>$_POST['fname'],
        ':bb'=>$_POST['lname'],
        ':cc'=>$_POST['uname'],
        ':dd'=>$_POST['pword']
    );
    
    //execute the query
    try
    {
        $stmt=$db->prepare($query);
        $result=$stmt->execute($query_params);
    }
    //catch exceptions
    catch (PDOException $ex)
    {
        $response["success"]=0;
        $response["message"]="Server failure.. please try again";
        
        die(json_encode($response));
    }
    //else the insertion was successful
    $response["success"]=1;
    $response["message"]="Added successfully";
    
    echo json_encode($response);
}
//else display the form, if post is empty
/*
else
{
    ?>
<h1> Sign Up form for Hotel owners</h1>
               
                <form action="hotel_owner.php" method="post">
                
	
			 First Name:<br /> 
	    <input type="text" name="fname" value="" /> 
	    <br /> <br /> 
            
            
			 Last Name:<br /> 
	    <input type="text" name="lname" value="" /> 
	    <br /> <br /> 
            
            
            User Name:&nbsp;&nbsp;<br /> 
	    <input type="text" name="uname" value="" /> 
	    <br /> <br /> 
            
	    Password:<br /> 
	    <input type="password" name="pword" value="" /> 
	    <br /> <br /> 
	    <input type="submit" value="Sign Up" /> 
	</form>

<?php
}
 * 
 */
?>
