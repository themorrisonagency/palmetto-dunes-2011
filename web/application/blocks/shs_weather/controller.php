<?php
namespace Application\Block\ShsWeather;

use Concrete\Core\Block\BlockController;
use Core;
use Loader;

defined('C5_EXECUTE') or die("Access Denied.");
	
class Controller extends BlockController {
	
	protected $btTable = "btShsWeather";
    protected $btInterfaceWidth = "420";
    protected $btInterfaceHeight = "430";

 	var $imagemap = array(
		'default'=>'sunny',
		'T-Storms'=>'rain-lightning',
		'Mostly Cloudy'=>'clouds',	
		'Partly Cloudy'=>'clouds',
		'cloud'=>'clouds',
		'overcast'=>'clouds',
		'Rain'=>'rain',
		'fog'=>'fog',
		'clear'=>'sunny',
		'sunny'=>'sunny',
		'snow'=>'snow',
		'mixed'=>'sleet',
		'wind'=>'wind',
		'sleet'=>'sleet',
		'blizzard'=>'snow',
		'hazy'=>'cloudy-sun',
		'drizzle'=>'light-rain',
		'showers'=>'rain'
		);

	public function getBlockTypeName() {
		return t('Weather');
	}

	public function getBlockTypeDescription() {
		return t('A block for adding weather to your site');
	}

    public function view()
    {
        $this->set('days', $this->getWeather());
    }

    public function validate($args)
    {
        $e = Loader::helper("validation/error");
        if(strlen((string)$args["zipCode"]) != 5){
            $e->add(t("A valid %s required.", "Zip Code"));
        }
        return $e;
    }

    private function getWeather()
    {
		$response_xml_data = 'http://weather.service.sabrehospitality.com.sabrecdn.com/v2/?override&zip='.$this->zipCode;
		$data = simplexml_load_file($response_xml_data);
		if (!$data) {
		   return "Error loading XML";
		} else {
			$days = $data->day;
			$newDays = array();

			foreach ($days as $day) {
				$new = array();
				$new['id'] = (int)$day->id;
				$new['date'] = (int)$day->date;
				$new['summary'] = (string)$day->summary;
				$new['image'] = $this->mapImage((string)$day->summary);;
				$new['maxtemp'] = $this->convertTemp((int)$day->maxtemp);
				$new['high'] = $this->convertTemp((int)$day->high);
				$new['mintemp'] = $this->convertTemp((int)$day->mintemp);
				$new['low'] = $this->convertTemp((int)$day->low);
				array_push($newDays, $new);
			}
		  return $newDays;
		}
    }

 	private function mapImage ($summary) {
		foreach($this->imagemap as $mapKey => $image)
		{
			if(preg_match('/\s*'.$mapKey.'\s*/is',$summary)) 
				return $this->imagemap[$mapKey];
		}
		return $this->imagemap['default'];
		
	
	}

	private function convertTemp ($temp) {

		if(strtolower($this->units) == 'c' && $temp !=32) {
		
			$temp = ($temp -32) / 1.8;
		
		} 
		return number_format($temp,0);
	
	}

}
