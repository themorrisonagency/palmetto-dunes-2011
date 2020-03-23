<?php

namespace Concrete\Package\PalmettoDunesSpecialOffers\Src\Page\Type\Validator;

use Concrete\Core\Attribute\Key\CollectionKey;
use Concrete\Core\Attribute\Key\Key;
use Concrete\Core\Page\Page;
use Concrete\Core\Page\Type\Composer\Control\Control;
use Concrete\Core\Page\Type\Composer\Control\CorePageProperty\DateTimeCorePageProperty;
use \Concrete\Core\Page\Type\Validator\StandardValidator;

class SpecialOfferValidator extends StandardValidator
{

    public function validatePublishDraftRequest(Page $page = null)
    {
        $e = parent::validatePublishDraftRequest($page);
        $controls = Control::getList($this->type);
        foreach($controls as $control) {
            if ($control instanceof DateTimeCorePageProperty) {
                $start = $control;
                break;
            }
        }

        $start->setPageObject($page);
        $startdate = $start->getRequestValue();
        if (!$startdate) {
            $startdate = $start->getPageTypeComposerControlDraftValue();
        }
        if (is_array($startdate)) {
            $starttime = strtotime(\Core::make("helper/form/date_time")->translate('date_time', $startdate));
        } else {
            $starttime = strtotime($startdate);
        }

        $data = CollectionKey::getByHandle('special_offer_end_date')->getController()->post();
        $endtime = strtotime(\Core::make("helper/form/date_time")->translate('value', $data));

        if ($endtime && ($starttime > $endtime || $starttime == 0)) {
            $e->add(t('You cannot have a start date after an end date.'));
        }
        return $e;
    }

}
