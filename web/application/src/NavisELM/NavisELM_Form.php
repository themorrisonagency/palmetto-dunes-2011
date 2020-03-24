<?php
namespace Application\Src\NavisELM;

class NavisELM_Form
{


	/**
	 * Takes form data and prepares it for the NavisELM class
	 * @param  array $form_data Form $_POST data
	 * @return array
	 */
	public function prepareFormData( $form_data ) {

		// Standard Navis ELM Fields
		if(isset($form_data['first_name']))
			$elm_data['first_name'] = $form_data['first_name'];
		if(isset($form_data['last_name']))
			$elm_data['last_name'] = $form_data['last_name'];
		if(isset($form_data['email']))
			$elm_data['email_address'] = $form_data['email'];
		if(isset($form_data['phone']))
			$elm_data['phone'] = $form_data['phone'];
		if(isset($form_data['arrive']))
			$elm_data['check_in'] = $form_data['arrive'];
		if(isset($form_data['depart']))
			$elm_data['check_out'] = $form_data['depart'];
		if(isset($form_data['num_adults']))
			$elm_data['adults'] = $form_data['num_adults'];
		if(isset($form_data['num_children']))
			$elm_data['children'] = $form_data['num_children'];
		if(isset($form_data['accom_type']))
			$elm_data['unit'] = $form_data['accom_type'];
		if(isset($form_data['reason_for_stay']))
			$elm_data['reason_for_stay'] = $form_data['reason_for_stay'];
		if(isset($form_data['keyword']))
			$elm_data['keyword'] = $form_data['keyword'];

		// Extra Fields go into Message
		if(isset($form_data['promo']) && $form_data['promo'] != '')
			$message[] = "Promo: " . $form_data['promo'];
		if(isset($form_data['num_bedrooms']) && $form_data['num_bedrooms'] != '')
			$message[] = "Rooms: " . $this->removeAllButInt($form_data['num_bedrooms']);
		if(isset($form_data['num_rooms']) && $form_data['num_rooms'] != '')
			$message[] = "Rooms: " . $this->removeAllButInt($form_data['num_rooms']);
		if(isset($form_data['num_rounds']) && $form_data['num_rounds'] != '')
			$message[] = "Number of Rounds: " . $form_data['num_rounds'];
		if(isset($form_data['num_players']) && $form_data['num_players'] != '') {

			$playerArry = $this->numPlayerInfo($form_data['num_players']);
			
			$message[] = "Players: " . $playerArry['text'];
			$elm_data['more_than_nine_players'] = $playerArry ['greater_than_nine'];
			
		}
		if(isset($form_data['less_players']) && $form_data['less_players'] != '')
			$message[] = "Number of Players: " . $form_data['less_players'];

		if(isset($form_data['view_type']) && $form_data['view_type'] != '')
			$message[] = "Views: " . $this->viewTypes($form_data['view_type']);
		if(isset($form_data['home_view']) && $form_data['home_view'] != '')
			$message[] = "Home Views: " . $this->homeView($form_data['home_view']);
		if(isset($form_data['villa_view']) && $form_data['villa_view'] != '' && $form_data['villa_view'] != 'NoAnswer')
			$message[] = "Villa Views: " . $this->villaViews($form_data['villa_view']);
		if(isset($form_data['player_level']) && $form_data['player_level'] != '')
			$message[] = "Level of Player: " . $this->playerLevels($form_data['player_level']);
		if(isset($form_data['instruct_type']) && $form_data['instruct_type'] != '')
			$message[] = "Type of Instruction: " . $this->instructionTypes($form_data['instruct_type']);

		// Activities label
		if(isset($form_data['meals']) || isset($form_data['tennis']) || isset($form_data['canoes_kayaking']) || isset($form_data['boat_rentals']) || isset($form_data['kids_activities']))
			$activities_title = 'Additional Activities: ';

		// Activites
		if(isset($form_data['golf']) && $form_data['golf'] != '')
			$activities[] = "Golf";
		if(isset($form_data['meals']) && $form_data['meals'] != '')
			$activities[] = "Meals";
		if(isset($form_data['tennis']) && $form_data['tennis'] != '')
			$activities[] = "Tennis";
		if(isset($form_data['canoes_kayaking']) && $form_data['canoes_kayaking'] != '')
			$activities[] = "Canoes and Kayaking";
		if(isset($form_data['bike_rental']) && $form_data['bike_rental'] != '')
			$activities[] = "Bike Rental";
		if(isset($form_data['boat_rentals']) && $form_data['boat_rentals'] != '')
		{
			$activities[] = "Boating";
			$activities[]='Fishing';
		}
		if(isset($form_data['kids_activities']) && $form_data['kids_activities'] != '')
			$activities[] = "Kids Activities and Camps";
		if(isset($form_data['golf_lessons']) && $form_data['golf_lessons'] != '')
			$activities[] = "Golf Lessons/Instruction";

		if(isset($activities)) {
			
			$elm_data['activities'] = implode(', ', $activities);
			$message[] = $activities_title . $elm_data['activities'];
		
		}
		// Form Identifier
		if(isset($form_data['form_identier']) && $form_data['form_identier'] != '')
			$message[] = "Form: " . $form_data['form_identier'];

		// Comments
		if(isset($form_data['event_details']) && $form_data['event_details'] != '')
			$message[] = "Comments: " . $form_data['event_details'];

		// Add to message field
		if(isset($message))
			$elm_data['message'] = implode("<br />", $message);
			
		// Opt In
		if(isset($form_data['yes_email'])) {
			$elm_data['opt_in'] = true;
		} else
			$elm_data['opt_in'] = false;

	return $elm_data;

	} // prepareFormData()


	public function numPlayerInfo( $value ) {

		$result['greater_than_nine'] = true;

		switch($value) {
			case "play1":
				$result['greater_than_nine'] = false;
				$result['text'] = "Fewer than 9 Players";
			break;
			case "play2":
				$result['text'] = "9-24 Players";
			break;
			case "play3":
				$result['text'] = "25-48 Players";
			break;
			case "play4":
				$result['text'] = "49+ Players";
			break;
			default:
				$result['greater_than_nine'] = false;
			break;
		}

	return $result;
	}

	public function playerLevels( $value ) {

		switch($value) {
			case "level1":
				$result = "Beginner";
			break;
			case "level2":
				$result = "Intermediate";
			break;
			case "level3":
				$result = "Advanced";
			break;
		}

	return $result;
	}

	public function instructionTypes( $value ) {

		switch($value) {
			case "instruct1":
				$result = "Customized Group Clinic";
			break;
			case "instruct2":
				$result = "Scheduled Clinics";
			break;
			case "instruct3":
				$result = "Private Lessons";
			break;
		}

	return $result;
	}
	
	public function homeView( $value ) {

		switch($value) {
			case "10889":
				$result = "Any View";
			break;
			case "11043":
				$result = "Courtyard View";
			break;
			case "10893":
				$result = "Lagoon View";
			break;
			case "10895":
				$result = "Near Ocean";
			break;
			case "10898":
				$result = "Pool View";
			break;
		}

	return $result;
	}

	public function villaViews( $value ) {

		switch($value) {
			case "10805":
				$result = "Any View";
			break;
			case "10814":
				$result = "Courtyard View";
			break;
			case "10815":
				$result = "Golf Course View";
			break;
			case "10816":
				$result = "Lagoon View";
			break;
			case "11042":
				$result = "Marina View";
			break;
			case "10818":
				$result = "Near Ocean";
			break;
			case "10819":
				$result = "Ocean View";
			break;
			case "10820":
				$result = "Oceanfront";
			break;
			case "10821":
				$result = "Pool View";
			break;
			case "10822":
				$result = "Resort View";
			break;
		}

	return $result;
	}	
	
	/**
	 * We only need the int portion for some form fields 
	 * (which in some cases are returned as rooms1, rooms2 for example)
	 * this is a general method to remove all but the int portion of the value
	 */
	public function removeAllButInt( $value ) {

		return preg_replace("/[^0-9]/", "", $value);

	}
	
	public function viewTypes( $value ) {

		switch($value) {
			case "view1":
				$result = "Any View";
			break;
			case "view2":
				$result = "Coastal Waterway View";
			break;
			case "view3":
				$result = "Courtyard View";
			break;
			case "view4":
				$result = "Golf Course View";
			break;
			case "view5":
				$result = "Lagoon View";
			break;
			case "view6":
				$result = "Marina View";
			break;
			case "view7":
				$result = "Near Ocean";
			break;
			case "view8":
				$result = "Ocean View";
			break;
			case "view9":
				$result = "Oceanfront";
			break;
			case "view10":
				$result = "Pool View";
			break;
			case "view11":
				$result = "Resort View";
			break;
		}
		
		// If view is not located in here let's check the others
		if(!isset($result))
			$result = $this->homeView($value);
		if(!isset($result))
			$result = $this->villaViews($value);
		
		
	return $result;
	}	

} // class