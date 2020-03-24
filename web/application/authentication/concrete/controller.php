<?php
namespace Application\Authentication\Concrete;

use Concrete\Authentication\Concrete;
use Concrete\Core\Authentication\AuthenticationTypeController;
use User;
use UserInfo;


// SHS Authentication against SSO system
require_once('esite/classes/ShsssoC5Auth.class.php');


// Extend the default controller class (we want normal login to still work)
class Controller extends \Concrete\Authentication\Concrete\Controller
{

    private $dmsportal_url = 'https://dmsportal.sabrehospitality.com';


    /**
     * Called from SHS Portal to log in automatically
     */
    public function shsssoauth()
    {
        // Make sure the essential POST vars are here
        $this->validatepost();

        // Get post vars and make sure we have them
        $post = $this->post();
        if (empty($post['remoteuid'])) {
            throw new \Exception(t('An error occurred logging in. Please log into the Portal and try again.'));
        }

        // Try to log in this user by ID
        $user = User::loginByUserID($post['remoteuid']);
        if (!$user) {
            throw new \Exception(t('Error logging in. Please log into the Portal and try again, or contact your Account Manager.'));
        }

        // Remember this user's login
        $user->setAuthTypeCookie('concrete'); 

        // Complete authentication
        $this->completeAuthentication($user);
    }




    /**
     * Override the login screen, redirect the user to the DMS Portal
     */
    public function view()
    {
        // Don't let anyone in unless it's SHS
        if (!isset($_GET['shslogin'])) {
            header('location: '. $this->dmsportal_url);
            die;
        }
    }






    /* =====================================================================================
                                        Private Methods
     ===================================================================================== */






    /**
     * Called from SHS User Admin to add User to site
     */
    private function validatepost($params=array(), $returnerrors=false)
    {
        $post = $this->post();
        $params = array_merge(array('userid','siteid','ts','hash'), $params);
        foreach ($params as $field) {
            if (!isset($post[$field]) || empty($post[$field])) {
                $error = 'An error occurred logging in. Please log into the Portal and try again.';
                if ($returnerrors) {
                    return $error;
                } else {
                    throw new \Exception(t($error));
                }
            }
        }

        // Instantiate the SHS Auth class
        $shsauth = new \Sabre\ShsssoC5Auth;

        // Verify hash
        $ok = $shsauth->verifyhashanduser($post['userid'], $post['siteid'], $post['ts'], $post['hash']);
        if (!$ok) {
            $error = 'Your session has expired. Please log into the Portal and try again.';
            if ($returnerrors) {
                return $error;
            } else {
                throw new \Exception(t($error));
            }
        }

        return (!$returnerrors);
    }



}
