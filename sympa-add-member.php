<?php
require_once("/var/www/wordpress/wp-load.php");
// Absolute path to current directory

//$here = plugin_dir_path(__FILE__) ;

// Récupération des infos du formulaire

$last_name = $_POST['last_name'];
$first_name = $_POST['first_name'];
$user_name = $_POST['user_name'];
$user_pass = $_POST['user_pass'];
$user_email = $_POST['user_mail'];
$member_type = $_POST['member_type'];
$newsletter = $_POST['list_diff'];

// Creation d'un nouveau membre

$user_id = username_exists( $user_name );

if ( !$user_id and email_exists($user_email) == false ) 
  {
    
    // Adding user
    $user_id = wp_create_user( $user_name, $user_pass, $user_email );
    
    //Generating hash
    $salt = rand(0,1000);
    $hash = md5( $salt.$user_name.$user_pass );
    
    // Adding metadata
    add_user_meta( $user_id, 'first_name', $first_name, True);
    add_user_meta( $user_id, 'last_name', $last_name, True);
    add_user_meta( $user_id, 'newsletter', $newsletter, True);
    add_user_meta( $user_id, 'member_type', $member_type, True);
    add_user_meta( $user_id, 'member_contrib', '0', True);
    add_user_meta( $user_id, 'valid_email', '0', True);
    add_user_meta( $user_id, 'hash', $hash, True);

    //Sending confirmation email
    //$url_prefix = plugin_dir_path(__FILE__) ;
    $url_prefix = "http://localhost/wordpress/wp-content/plugins/wp-sympa-user-manager/";
    $url_suffix = "sympa-confirm-email.php?hash=".$hash ;
    
    $message = $first_name.", pour valider ton adresse email, suis le lien suivant : ".$url_prefix.$url_suffix ;
    
    $headers[] = 'From: Club Rock <clubrock.enpiste@gmail.com>';
    $subject="[Club Rock] Validation de l'inscription";
    
    wp_mail($user_email, $subject, $message, $headers);
    //$dest = $user_email ;
    //$res = mail($dest,$subject,$message);
    //echo $res;
    // Showing confirmation page
    include("sympa-confirm-inscription.php");
  } 
else 
  {
    //An error will be displayed, but for the moment being 
    include('registration-form.html');
  }

// Redirection vers page de confirmation

?>