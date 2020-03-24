<?php
namespace Application\Block\ExternalForm\Form\Controller;
use Concrete\Core\Controller\AbstractController;
use Application\Src\NavisELM\NavisELM;
use Application\Src\NavisELM\NavisELM_Form;
use Application\Src\NavisReach\NavisReach;
use Application\Src\CDC\CDC;
use Application\Src\SalesForce\SforceBaseClient;
use Application\Src\SalesForce\SforceEmail;
use Application\Src\SalesForce\SforceProcessRequest;
use Application\Src\SalesForce\ProxySettings;
use Application\Src\SalesForce\SforceHeaderOptions;
use Application\Src\SalesForce\SforceEnterpriseClient;
use Application\Src\SalesForce\Contact;
use Application\Src\SalesForce\lod4__Inquiry__c;

class RealEstateForm extends AbstractController
{
    public function action_process_navis()
    {
	
		if(!empty( $_POST )) 
		{

			// Form identifier use by ELM
			$_POST['form_identier'] = 'Form - Real Estate';
			$_POST['email_subject'] = 'Contact Us--Real Estate please';

			// Setup form data
			$NavisElm_Form = new NavisELM_Form;
			$form_data = $NavisElm_Form->prepareFormData( $_POST );
			

			if(isset($_POST['first_name']))
				$form_data['first_name'] = $_POST['first_name'];
				
			if(isset($_POST['last_name']))
				$form_data['last_name'] = $_POST['last_name'];
				
		    if(isset($_POST['phone']))
				$form_data['phone'] = $_POST['phone'];
				
			if(isset($_POST['email']))
				$form_data['email_address'] = $_POST['email'];

			if(isset($_POST['field-postal']))
				$form_data['zip'] = $_POST['field-postal'];

			$CDC = new CDC();
			$cdcResponse = $CDC->sendCDC($form_data, '39', '2489');

			// Process Navis Reach if opt in
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

				if(isset($subscription_lists))
					$form_data['subscription_lists'] = $subscription_lists;

				$reach = new NavisReach();
				$reachResponse = $reach->addUpdateSubscriber( $form_data );

				if(isset($reachResponse->AddUpdateSubscriberResult))
				{
					// Check for errors
					if(isset($_POST['green']) && strpos($reachResponse->AddUpdateSubscriberResult, 'Error:') === false)
						$this->redirect('/signup-thanks-green');
					elseif(strpos($reachResponse->AddUpdateSubscriberResult, 'Error:') === false)
						$this->redirect('/contact-us-thanks');
					else
						\Log::addEntry(substr($reachResponse->AddUpdateSubscriberResult, 7) . ' - real_estate_form.php','Navis_Reach');
				} else
					\Log::addEntry('An error occurred while submitting form - real_estate_form.php!','Navis_Reach');
			}

			// Redirect
			$this->redirect('/hilton-head-real-estate-thanks');
		}
	}
}







 
