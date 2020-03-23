<?php
namespace Concrete\Package\ShsEvents\Controller\SinglePage;
use Concrete\Core\Page\Controller\PageController;
use Concrete\Core\Tree\Node\Node;
use Concrete\Core\Tree\Node\Type\TopicCategory;
use Concrete\Package\Calendar\Src\Attribute\Key\EventKey;
use Concrete\Package\Calendar\Attribute\Value\EventValue;
use PortlandLabs\Calendar\Calendar;
use Concrete\Package\Calendar\Src\PortlandLabs\Calendar\Event\EventOccurrence;
use Concrete\Package\Calendar\Src\PortlandLabs\Calendar\Event\EventOccurrenceList;
use Core;
use Concrete\Package\ShsEventsSeriesOne\Block\ShsEventCalendarSeriesOne;
use Concrete\Package\ShsEventsSeriesTwo\Block\ShsEventCalendarSeriesTwo;
use Concrete\Core\Block\BlockType\BlockType;
//use Concrete\Package\ShsEventsSeriesOne;
//use Concrete\Package\ShsEvents\Controller\SinglePage\Block;

class Event extends PageController
{
	
	public function on_before_render($block_id=null, $occurrence_id=null, $title=null)
	{
		$eventoccurrance =  EventOccurrence::getByID(intval($occurrence_id, 10));
		if(!is_null($eventoccurrance))
		{
			$event=$eventoccurrance->getEvent();
			$calendar=$event->getCalendar();
			$eventenddate=new \DateTime($event->getAttribute('event_end_date'));
			$currentdate=new \DateTime();
		}
		/*
		var_dump($eventoccurrance);
		var_dump(get_class_methods($event));
		var_dump($calendar);
		ob_flush();
		die('test');
		
		*/
		
		//Loader::model('section', 'multilingual');

		/*
		if(is_null($eventoccurrance) || $eventenddate<$currentdate || $event->getAttribute('event_is_deactivated')==1)
		{
			die('test');
			//$lang = MultilingualSection::getCurrentSection()->getLanguage();
			header('Location:/events');
			exit();
		}
		*/
	}

	public function view($block_id=null, $occurrence_id=null, $title=null)
	{
        $eventblock = \Block::getById($block_id);    
        $events=$eventblock->getBlockTypeObject();

		$events->controller->setbid($block_id);
		// grab Calendar ID from the Event Occurrence
		$eo = EventOccurrence::getById($occurrence_id);
		$e = $eo->getEvent();
		$cal = $e->getCalendarID();
		$events->controller->setcaid($cal);
		$events->controller->setsingleitem(true);
		$events->controller->action_event($cal, $occurrence_id, $title);
		$this->set('event', $events);
	}

}
