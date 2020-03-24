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


class ContestEntry extends AbstractController
{

    public function action_process_navis()
    {
	
		if(!empty( $_POST )) 
		{

			// Form identifier use by ELM
			$_POST['form_identier'] = 'Form - Contest Entry';
			
			// Reason for stay identifier
			$_POST['reason_for_stay'] = 'Vacation';
			
			$NavisElm_Form = new NavisELM_Form;
			$form_data = $NavisElm_Form->prepareFormData( $_POST );
			
			$form_data['state']=$_POST['state'];
			$form_data['Age']=$_POST['Age'];
			$form_data['Contest Ideal Getaway']=$_POST['ContestIdealGetaway'];
			$form_data['Social Contest']='PDYourWay2017';
						
			// Custom CDC Email Subject
			$subject  = 'ResReq ' . $_POST['arrive'];
			
			
			$form_data['email_subject'] = $subject;
			
			$list_array=array('CustomContestIdealGetaway');
			if($_REQUEST['subscribe']=='Yes')
				$list_array[]='SpecialOffers';
			
			// Get subscription list items from attribute
			$c = \Page::getCurrentPage();
			$lists = $c->getAttribute('subscription_list');
			
			$form_data['subscription_lists'] = implode(",", $list_array);
		
			$form_data['SocialContest']='PDYourWay2017';
			
			$reach = new NavisReach();
			$reachResponse = $reach->extendedAddUpdateSubscriber( $form_data, array('Contest Ideal Getaway', 'Age','Social Contest') );
			
			if(isset($reachResponse->AddUpdateSubscriberResult))
			{
				// Check for errors
				if(strpos($reachResponse->AddUpdateSubscriberResult, 'Error:') !== false)
					\Log::addEntry(substr($reachResponse->AddUpdateSubscriberResult, 7) . ' - contest_entry.php','Navis_Reach');
			} else
				\Log::addEntry('An error occurred while submitting form - contest_entry.php!','Navis_Reach');
			
			// Redirect
			$this->redirect('/contest-thank-you');

		}
	}
}
