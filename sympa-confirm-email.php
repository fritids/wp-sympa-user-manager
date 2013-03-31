<?php
require_once("/var/www/wordpress/wp-load.php");

$hash = $_GET['hash'];
$users = get_users(array('meta_key' => 'hash', 'meta_value' => $hash));

/* echo $hash; */
/* print_r($users); */
/* echo count($users); */
/* print_r($users[0]->); */
$user = $users[0];
$user_id = $user->ID ;
$email = $user->user_email;


if (count($users)>0)
  {
    $user = $users[0];
    $user_id = $user->ID ;
    $email = $user->user_email;
    $email_status = get_user_meta($user_id, 'valid_email') ;

    
    if ($email_status[0] == '0')
      {
	update_user_meta( $user_id, 'valid_email','1');	
	//Envoi d'un mail à SYMPA
	$dest = "sympa@listes.ens-lyon.fr";
	$subject="";
	$message = "ADD clubrock ".$email;
	$msg = mail($dest,$subject,$message,'From: quentin.agren@ens-lyon.fr', '-f quentin.agren@ens-lyon.fr');
       	echo "<h1> Voilà ! Sympa non ? </h1>";
      } 
  }
else 
  {
    echo "perdu";
    //wp_redirect( home_url() ); 
    exit;
  }

?>



