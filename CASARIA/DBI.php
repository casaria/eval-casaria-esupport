<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN">
<html>
<head>
  <title>Casaria Database Oject factory TEST</title>
  <style type="text/css">
  body {
    font-family: Lucida Sans, "Times New Roman",
          Times, serif;
    color: blue;
    background-color: #FFCC33 }
  h1 {
    font-family: Helvetica, Geneva, Arial,
          SunSans-Regular, sans-serif; 
          color: orange;
    }
          
          
          

  body { margin-left: 5%; margin-right: 5%; }

          
  </style>
</head>
 <body>
<h1>DYNAMIC OBJECTS CREATED</h1>


<?php

// include class files
function __autoload($class_name) {
    require_once ''.$class_name.'class.php';
}

__autoload("DBIgenerator");

try {
    // connect to MySQL
    $db=new MySQL(array('host'=>'localhost','user'=>'casaria_hdesk1','password'=>'kuckaroo','database'=>'casaria_hdesk1'));
    $gn=new DBIgenerator('users',"user",'classes/');
    // generate class file
    $gn->generate();
    // get $user object
    $user=$gn->getObject();
    $user->setid(2);
    $row = $user->load();
      
    echo $row['last_name'] ;
    echo $row['first_name'];
    echo '<br>';
    echo $user->getuser_name();
       
    echo '<br>..User table row successfully read';

    $gn=new DBIgenerator('tickets',"ticket",'classes/');
    // generate class file
    $gn->generate();
    // get $user object
    $user=$gn->getObject();
    $user->setid(2006);
    $row = $user->load();

    echo '<br>';
    echo $user->getshort();
    echo '<br>..Ticket table row successfully read';

    $gn=new DBIgenerator('tmaterial',"tmaterial",'classes/');
    // generate class file
    $gn->generate();
    // get $user object
    $tmaterial=$gn->getObject();
    $tmaterial->setid(1);
    $row = $tmaterial->load();

    echo '<br>..Tmaterial table row successfully read';

    echo '<br>';
    echo $tmaterial->getourpartnum();

    echo '<b> SHORT:</b>';
    echo $tmaterial->getshort();

    echo '<b> DESCRIPTION:</b>';
    echo $tmaterial->getdescription();
    
    
    
    
    $gn=new DBIgenerator('tequipment',"tequipment",'classes/');
    // generate class file
    $gn->generate();    
    
    
    
    
    
}
catch (Exception $e){
    echo $e->getMessage();
    exit();
}
?>

</body>