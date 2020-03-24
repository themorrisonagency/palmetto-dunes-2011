<?php
namespace Application\Block\ExternalForm\Form\Controller;
use Concrete\Core\Controller\AbstractController;
use Application\Src\NavisELM\NavisELM;
use Application\Src\NavisELM\NavisELM_Form;
use Application\Src\NavisReach\NavisReach;
use Application\Src\CDC\CDC;
use Concrete\Package\EcRecaptcha\Src\Captcha\RecaptchaController;

class TennisQuote extends AbstractController
{

    public function action_process_navis()
    {

		if(!empty( $_POST )) 
		{		
			$success = RecaptchaController::Check();

			if ($success == false)
			{
				throw new \Exception(t('Captcha Failed, try again.'));
			}
			// Form identifier use by ELM
			$_POST['form_identier'] = 'Form - Tennis Quote';
			
			// Reason for stay identifier
			$_POST['reason_for_stay'] = 'Tennis';

			$NavisElm_Form = new NavisELM_Form;
			$form_data = $NavisElm_Form->prepareFormData( $_POST );
			

			// Custom CDC Email Subject
			$subject  = 'Tennis ' . $_POST['arrive'];
			
			if(isset($_POST['promo']) && $_POST['promo'] != '')
				$subject .= ' (' . $_POST['promo'] . ')';
			
			$form_data['email_subject'] = $subject;
			
			$CDC = new CDC();
			$cdcResponse = $CDC->sendCDC($form_data, '35', '2479');


			// Process Navis ELM 
			$reach = new NavisELM();
			$response = $reach->addEnhancedWebContact( $form_data );

			// Check for error
			if(preg_match('/<Error>(.*?)<\/Error>/s', $response->AddEnhancedWebContactResult->any))
				\Log::addEntry('An error occurred while submitting form - tennis_quote.php!','Navis_ELM');


			// Process Navis Reach
			if(isset($_POST['yes_email']))
			{
				// Get subscription list items from attribute
				$c = \Page::getCurrentPage();
				$lists = $c->getAttribute('subscription_list');
				
				if(is_object($lists) || is_array($lists))
				{
					foreach ($lists as $list) {
						$list_array[] = $list;
					}

					$form_data['subscription_lists'] = implode(",", $list_array);

				}

				$reach = new NavisReach();
				$reachResponse = $reach->addUpdateSubscriber( $form_data );
	
				if(isset($reachResponse->AddUpdateSubscriberResult))
				{
					// Check for errors
                                        if(isset($_POST['green']) && strpos($reachResponse->AddUpdateSubscriberResult, 'Error:') === false)
                                                $this->redirect('/signup-thanks-green');
                                        elseif(strpos($reachResponse->AddUpdateSubscriberResult, 'Error:') !== false)
						\Log::addEntry(substr($reachResponse->AddUpdateSubscriberResult, 7) . ' - tennis_quote.php','Navis_Reach');
				} else
					\Log::addEntry('An error occurred while submitting form - tennis_quote.php!','Navis_Reach');
			}

			// Redirect
			$this->redirect('/activities/hilton-head-tennis-quote-thanks');

		}
	}
}
