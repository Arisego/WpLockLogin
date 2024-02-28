<?php
/**
 * @package LockLogin
 */
/*
Plugin Name: Lock login
Plugin URI: https://github.com/Arisego/LockLogin
Description: A minimal plugin to lock up your login url.
Version: 1.0.0
Author: Arisego
Author URI: https://blog.ch-wind.com/
License: MIT
Text Domain: LockLogin
*/

// Make sure we don't expose any info if called directly
if ( !function_exists( 'add_action' ) ) {
	echo 'Hi there!  I\'m just a plugin, not much I can do when called directly.';
	exit;
}



const LC_TOKEON_NAME = "lock";
const LC_TOKEON_VALUE = "some_string";
// wp-login.php?lock=some_string

// Catch wp-login.php visit and try add session with correct key pair
function forbid_login_with_lock()
{
    global $pagenow;

	if ( !is_user_logged_in() ) {

        if('wp-login.php' === $pagenow )
        {
            $lc_token_name =  LC_TOKEON_NAME;
            $lc_token_value =  LC_TOKEON_VALUE;
            if ( isset($_GET[$lc_token_name]) && strlen($lc_token_value)>0)
            {
                if($_GET[$lc_token_name] == $lc_token_value)
                {
                    if(!session_id())
                    {
                        session_start();
                        $_SESSION['lock_out'] = 1;
                        session_write_close();
                    }
                }
            }
			
			session_start();
			$lock_out = $_SESSION['lock_out'] ?? 0;
			session_write_close();

			if($lock_out !== 1)
			{
				wp_redirect(home_url(), 302 );
				exit();
			}
        }
	}
}
add_action('init', 'forbid_login_with_lock');


// Disable login authenticate if lock is still on
function my_authenticate($user, $username, $password) {
    if(is_null($username)|| strlen($username)==0)
    {
        return $user;
    }

    if(!session_id())
    {
        return NULL;
    }

    session_start();
    $lock_out = $_SESSION['lock_out'] ?? 0;
    session_write_close();

    if($lock_out == 1)
    {
        return $user;
    }

    return NULL;
}
add_filter('authenticate', 'my_authenticate', 25, 3);