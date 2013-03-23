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

function sympa_user_manager_admin_panel () 
{
  add_users_page("Sympa users", "Sympa users", "list_users", "sympa-users", "admin_panel");
}

function sympa_user_manager_front_registration ()
{
  ob_start();
  include "registration-form.php";
  $ret = ob_get_contents();
  ob_end_clean();
  return $ret;
}

add_action('admin_menu', 'sympa_user_manager_admin_panel');

add_shortcode('sympa-user-registration', 'sympa_user_manager_front_registration');

?>
