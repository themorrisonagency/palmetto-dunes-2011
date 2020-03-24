<?php
namespace Application\Block\ExternalForm\Form\Controller;
use Concrete\Core\Controller\AbstractController;
use Application\Src\NavisReach\NavisReach;

class SignUp extends AbstractController {
    public function action_process_navis()
    {

		if(!empty( $_POST ))
		{
            $secretKey = $_POST['g-recaptcha-response'];
            $response = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=6LfOOLQUAAAAABkOp6O5tfG6KzWsByN20Z0kf23n&response={$secretKey}");
            $return = json_decode($response);

            if ($return->success == false && $return->score < 0.7) {
                $this->redirect('/resort-enews-signup');
            }

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
            $form_data['first_name'] = $_POST['first_name'];
            $form_data['last_name'] = $_POST['last_name'];
            $form_data['postal_code'] = $_POST['postal_code'];
			if(isset($subscription_lists))
				$form_data['subscription_lists'] = $subscription_lists;

			$reach = new NavisReach();
			$response = $reach->addUpdateSubscriber( $form_data );

			if(isset($response->AddUpdateSubscriberResult))
			{
				// Check for errors
				if(isset($_POST['adventure']) && strpos($response->AddUpdateSubscriberResult, 'Error:') === false)
					$this->redirect('/signup-thanks-outfitters');
				if(isset($_POST['green']) && strpos($response->AddUpdateSubscriberResult, 'Error:') === false)
					$this->redirect('/signup-thanks-green');
                                elseif(strpos($response->AddUpdateSubscriberResult, 'Error:') === false)
					$this->redirect('/signup-thanks-footer');
				else
					\Log::addEntry(substr($response->AddUpdateSubscriberResult, 7) - ' - sign_up.php','Navis_Reach');
			} else
				\Log::addEntry('An error occurred while submitting form for - sign_up.php!','Navis_Reach');
		}
	}
}
