<?php
namespace Application\Block\ExternalForm\Form\Controller;
use Concrete\Core\Controller\AbstractController;
use Application\Src\NavisReach\NavisReach;

class SignupFlyout extends AbstractController
{
    public function action_process_navis()
    {
	
		if(!empty( $_POST )) 
		{

			$c = \Page::getCurrentPage();
			$lists = $c->getAttribute('subscription_list');
			
			if(is_object($lists) || is_array($lists))
			{
				foreach ($lists as $list) {
					$list_array[] = $list;
				}
			
				$subscription_lists = implode(",", $list_array);
			}

			if(isset($_POST['email']))
				$form_data['email_address'] = $_POST['email'];
			if(isset($subscription_lists))
				$form_data['subscription_lists'] = $subscription_lists;
		
			$reach = new NavisReach();
			$response = $reach->addUpdateSubscriber( $form_data );
    	
			if(isset($response->AddUpdateSubscriberResult))
			{
				// Check for errors
				if(strpos($response->AddUpdateSubscriberResult, 'Error:') === false)
					$this->redirect('/signup-thanks-green');
				else
					\Log::addEntry(substr($response->AddUpdateSubscriberResult, 7) - ' - signup_flyout.php','Navis_Reach');
			} else
				\Log::addEntry('An error occurred while submitting form - signup_flyout.php!','Navis_Reach');
		}
	}
}
