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
use Concrete\Package\EcRecaptcha\Src\Captcha\RecaptchaController;


class ContactPlanner extends AbstractController
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
			$_POST['form_identier'] = 'Form - Contact Vacation Planner';
			
			// Reason for stay identifier
			$_POST['reason_for_stay'] = 'Vacation';

			// Setup form data
			$NavisElm_Form = new NavisELM_Form;
			$form_data = $NavisElm_Form->prepareFormData( $_POST );
			
						
			// Custom CDC Email Subject
			$subject  = 'ResReq ' . $_POST['arrive'];
			
			if(isset($_POST['promo']) && $_POST['promo'] != '')
				$subject .= ' (' . $_POST['promo'] . ')';
			
			if(isset($_POST['num_players']) && $_POST['num_players'] != '')
			{
				$numPlayers = $NavisElm_Form->numPlayerInfo($_POST['num_players']);
				$subject .= ' ' . $numPlayers['text'];
			}
			
			$form_data['email_subject'] = $subject;
			
			$CDC = new CDC();
			$cdcResponse = $CDC->sendCDC($form_data, '35', '2477');
	
	
			// If number of players is 9 or more don't send to Navis ELM
			if($form_data['more_than_nine_players'] == false)
			{

				// Process Navis ELM 
				$reach = new NavisELM();
				$response = $reach->addEnhancedWebContact( $form_data );

				// Check for error
				if(preg_match('/<Error>(.*?)<\/Error>/s', $response->AddEnhancedWebContactResult->any))
					\Log::addEntry('An error occurred while submitting form - contact_planner.php!','Navis_ELM');

			} else {

				// More then 9 goes to Libra
				try {
				
						// Login
						$mySforceConnection = new SforceEnterpriseClient();
				
						$mySforceConnection->createConnection("wsdl.jsp.xml");
						$mySforceConnection->login();
				
						$lod4__Inquiry__c = new lod4__Inquiry__c();
						
						$lod4__Inquiry__c->setLod4__InquirySource__c('PD Accommodations RFP');
						$lod4__Inquiry__c->setLod4__Property__c('a0SU00000009tN4');
						$lod4__Inquiry__c->setRFP_Type__c('Group Golf');
						$lod4__Inquiry__c->setLod4__Status__c('New');
						
						if(isset($_POST['first_name']))
								$lod4__Inquiry__c->setLod4__FirstName__c($_POST['first_name']);
						if(isset($_POST['last_name']))
								$lod4__Inquiry__c->setLod4__LastName__c($_POST['last_name']);
						if(isset($_POST['email']))
								$lod4__Inquiry__c->setLod4__Email__c($_POST['email']);
						if(isset($_POST['phone']))
								$lod4__Inquiry__c->setLod4__Phone__c($_POST['phone']);
						if(isset($_POST['arrive']) && ($time = strtotime($_POST['arrive'])) !== false)
								$lod4__Inquiry__c->setLod4__ArrivalDate__c(date("Y-m-d", $time));
						if(isset($_POST['depart']) && ($time = strtotime($_POST['depart'])) !== false)
								$lod4__Inquiry__c->setEstimated_Departure__c(date("Y-m-d", $time));
						if(isset($_POST['num_adults']))
								$lod4__Inquiry__c->setNumber_of_Adults__c($_POST['num_adults']);
						if(isset($_POST['num_children']))
								$lod4__Inquiry__c->setNumber_of_Children__c($_POST['num_children']);
						if(isset($_POST['accom_type']))
								$lod4__Inquiry__c->setAccommodation_Type__c($_POST['accom_type']);
						if(isset($_POST['promo']))
								$lod4__Inquiry__c->setPromo_Code__c($_POST['promo']);
						if(isset($_POST['num_bedrooms']))
								$lod4__Inquiry__c->setNumber_of_Rooms__c($_POST['num_bedrooms']);
						if(isset($_POST['num_rounds']))
								$lod4__Inquiry__c->setNumber_of_Rounds__c($_POST['num_rounds']);
						if(isset($_POST['event_details']))
								$lod4__Inquiry__c->setRFP_Comments__c($_POST['event_details']);
						
						if(isset($_POST['meals']))
								$lod4__Inquiry__c->setMeals__c(true);
						
						if(isset($numPlayers['text']))
								$lod4__Inquiry__c->setNumber_of_Players__c($numPlayers['text']);
						
						if(isset($_POST['view_type']))
								$lod4__Inquiry__c->setView__c($NavisElm_Form->viewTypes($_POST['view_type']));
						if(isset($_POST['villa_view']))
								$lod4__Inquiry__c->setView__c($NavisElm_Form->villaViews($_POST['villa_view']));
						
						if(isset($form_data['activities'])&&strlen($form_data['activities'])>0)
								$lod4__Inquiry__c->setAdditional_Activities__c($form_data['activities']);
						
						if(isset($_POST['yes_email']))
								$lod4__Inquiry__c->setOpt_in__c(true);
				
						$response = $mySforceConnection->create(array($lod4__Inquiry__c), 'lod4__Inquiry__c');
						
						$ids = array();
						foreach ($response as $i => $result) {
								
								$result->success == 1
										? \Log::addEntry("Libra record created with id: ".$result->id . ' - contact_planner.php','Libra')
										: \Log::addEntry("Error: ".$result->errors->message . ' - contact_planner.php','Libra');
				
						}
				
				
				} catch (Exception $e) {
						
					\Log::addEntry("Exception: ".$e->faultstring . ' - contact_planner.php','Libra');
						
				}
			}

			
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
			
				$reach = new NavisReach();
				$reachResponse = $reach->addUpdateSubscriber( $form_data );
				
				if(isset($reachResponse->AddUpdateSubscriberResult))
				{
					// Check for errors
					if(isset($_POST['green']) && strpos($reachResponse->AddUpdateSubscriberResult, 'Error:') === false)
						$this->redirect('/signup-thanks-green');
					elseif(strpos($reachResponse->AddUpdateSubscriberResult, 'Error:') !== false)
						\Log::addEntry(substr($reachResponse->AddUpdateSubscriberResult, 7) . ' - contact_planner.php','Navis_Reach');
				} else
					\Log::addEntry('An error occurred while submitting form - contact_planner.php!','Navis_Reach');
			}
			
			// Redirect
			$this->redirect('/vacation-rentals/hilton-head-vacation-planner-thanks');

		}
	}
}
