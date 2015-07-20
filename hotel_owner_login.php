<?php
require ('config.php');
if(!empty($_POST))
{
    //validate send data
    if(empty($_POST['uname'])|| empty($_POST['pword']))
    {
        //pass error message
        $response["success"]=0;
        $response["message"]="Username and password fields seems to be empty, please fill them";
        
        die(json_encode($response));
    }
    //else perform aunthentication
    $query="SELECT username, login_password FROM hotel_owner WHERE username=:aa";
    $query_params=array(
        ':aa'=>$_POST['uname']
    );
    
    try
    {
        $stmt=$db->prepare($query);
        $result=$stmt->execute($query_params);  
    }
 catch (PDOException $ex)
 {
     //pass error message
     $response["success"]=0;
     $response["message"]="Server failure... please try again shortly";
     
     die(json_encode($response));
 }
 //now check for password match
 $login_ok=false;
 
 $row=$stmt->fetch();
 if($row)
 {
     if($_POST['pword']==$row['login_password'])
     {
         $login_ok=true;
     }
 }
 if($login_ok)
 {
     $response["success"]=1;
     $response["message"]="Login successful";
     
     die(json_encode($response));
 }
 else 
     
 {
     $response["success"]=0;
      $response["message"] = "Invalid Credentials! Try again..";
      die(json_encode($response));
 }
}
/*else display a simple login form, just incase 
 * some one decides to load this page from a browser
 * ideally the app is supposed to send data to this file, but whatever
 */
/*
else
{
?>
<h1>Login Here</h1> 
        
		<form action="hotel_owner_login.php" method="post"> 
		    USERNAME:<br /> 
		    <input type="text" name="uname" placeholder="username" /> 
		    <br /><br /> 
		    PASSWORD:<br /> 
		    <input type="password" name="pword" placeholder="password" value="" /> 
		    <br /><br /> 
		    <input type="submit" value="Login" /> 
		</form> 

<?php
}
 * 
 */
?>
