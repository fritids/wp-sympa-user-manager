<?php
/*  
Plugin Name: SYMPA user manager
Plugin URI: https://github.com/qagren/wp-sympa-user-manager.git
Description: A wordpress plugin to manage users and synchronize to SYMPA list server 
Author: Quentin Agren
Version: 0.1
 */
function admin_panel () 
{
  include "admin-panel.php";
}

// Add entry into admin panel
add_action('admin_menu', 'sympa_user_manager_admin_panel');
function sympa_user_manager_admin_panel () 
{
  add_users_page("Sympa users", "Sympa users", "list_users", "sympa-users", "admin_panel");
}


// Custom front registration page
// First solution : using a wp page..
add_shortcode('sympa-user-registration', 'sympa_front_registration');
function sympa_front_registration ()
{
  ob_start();
  include "registration-form.php";
  $ret = ob_get_contents();
  ob_end_clean();
  return $ret;
}


// Custom front registration 
// Secon solution : using registration hooks

add_action('register_form','sympa_register_form');
function sympa_register_form (){
  include('sympa-register-extras.php');
    }
?>

