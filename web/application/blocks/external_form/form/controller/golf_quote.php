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

class GolfQuote extends AbstractController
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
			$_POST['form_identier'] = 'Form - Golf Quote';
			
			// Reason for stay identifier
			$_POST['reason_for_stay'] = 'Golf';
			
			// Setup form data
			$NavisElm_Form = new NavisELM_Form;
			$form_data = $NavisElm_Form->prepareFormData( $_POST );
			
						
			// Custom CDC Email Subject
			$subject  = 'Golf ' . $_POST['arrive'];
			
			if(isset($_POST['promo']) && $_POST['promo'] != '')
				$subject .= ' (' . $_POST['promo'] . ')';
			
			if(isset($_POST['num_players']) && $_POST['num_players'] != '')
			{
				$numPlayers = $NavisElm_Form->numPlayerInfo($_POST['num_players']);
				$subject .= ' ' . $numPlayers['text'];
			}

			$form_data['email_subject'] = $subject;
			
			$CDC = new CDC();
			$cdcResponse = $CDC->sendCDC($form_data, '37', '2481');

			// If number of players is 9 or more don't send to Navis ELM
			if($form_data['more_than_nine_players'] == false)
			{
				// Process Navis ELM 
				$reach = new NavisELM();
				$response = $reach->addEnhancedWebContact( $form_data );

				// Check for error
				if(preg_match('/<Error>(.*?)<\/Error>/s', $response->AddEnhancedWebContactResult->any))
					\Log::addEntry('An error occurred while submitting form - golf_quote.php!','Navis_ELM');
			
			} else {
								
				// More then 9 goes to Delphi
				try {
		
                    // Login
                    $mySforceConnection = new SforceEnterpriseClient();

                    $mySforceConnection->createConnection("wsdl.xml");
                    $mySforceConnection->login();

                    // Libra is now Delphi
                    // TODO: if we use this in more than just the golf quote form, then we should make a class out of it
                    $delphi = new \stdClass();

                    // default fields
                    $delphi->ahspt_RFP_Type__c = 'Group Golf';
                    $delphi->ahspt_Event_Type__c = 'Group Golf';
                    $delphi->nihrm__Property__c = 'Palmetto Dunes';
                    $delphi->nihrm__Status__c = 'Open';

                    // form fields
                    $delphi->nihrm__FirstName__c = $_POST['first_name'];
                    $delphi->nihrm__LastName__c = $_POST['last_name'];
                    $delphi->nihrm__Email__c = $_POST['email'];
                    $delphi->nihrm__Phone__c = $_POST['phone'];
                    $delphi->ahspt_Promo_Code__c = $_POST['promo'];

                    $delphi->nihrm__ArrivalDate__c = (strtotime($_POST['arrive']) !== false) ? date("Y-m-d", strtotime($_POST['arrive'])) : "";
                    $delphi->Estimated_Departure__c = (strtotime($_POST['depart']) !== false) ? date("Y-m-d", strtotime($_POST['depart'])) : "";
                    // unix timestamp departure and arrival times, subtract those timestamps and divide by number of seconds in a day and round to whole number
                    // (depart timestamp - arrival timestamp) / 86400
                    $delphi->nihrm__Nights__c = round((strtotime($_POST['depart']) - strtotime($_POST['arrive'])) / (60 * 60 * 24));

                    $delphi->ahspt_Number_of_Players__c = $numPlayers['text'];
                    $delphi->ahspt_Number_of_Rounds__c = $_POST['num_rounds'];
                    $delphi->ahspt_View__c = isset($_POST['view_type']) ? $NavisElm_Form->viewTypes($_POST['view_type']) : '';
                    $delphi->ahspt_Number_of_Rooms__c = isset($_POST['num_bedrooms']) ? $_POST['num_bedrooms'] : '';
                    $delphi->ahspt_Meals__c = isset($_POST['meals']) ? true : false;
                    $delphi->Golf_Lessons_Instruction__c = isset($_POST['golf_lessons']) ? true : false;
                    $delphi->ahspt_RFP_Comments__c = $_POST['event_details'];

                    $response = $mySforceConnection->create(array($delphi), 'nihrm__Inquiry__c');

                    foreach ($response as $i => $result) {

                        $result->success == 1
                            ? \Log::addEntry("Delphi record created with id: ".$result->id . ' - golf_quote.php','Delphi')
                            : \Log::addEntry("Error: ".$result->errors->message . ' - golf_quote.php','Delphi');

                    }
		
				} catch (Exception $e) {
						\Log::addEntry("Exception: ".$e->faultstring . ' - golf_quote.php','Delphi');
						
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
						\Log::addEntry(substr($reachResponse->AddUpdateSubscriberResult, 7) . ' - golf_quote.php','Navis_Reach');
				} else 
					\Log::addEntry('An error occurred while submitting form - golf_quote.php!','Navis_Reach');
			}

			// Redirect
			if ( $_POST['num_players'] == 'play1' ) {
				$this->redirect('/golf/hilton-head-golf-quote-thankyou/');
			} else {
				$this->redirect('/golf/hilton-head-golf-quote-thanks');
			}
			
		}
	}
}
