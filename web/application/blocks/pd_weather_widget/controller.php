<?php
namespace Application\Block\PdWeatherWidget;

use \Concrete\Core\Block\BlockController;
use Loader;
use \Page;

class Controller extends BlockController
{

  protected $btDescription = "Weather Widget Block";
  protected $btName = "WeatherWidget";
  protected $btTable = 'btWeatherWidget';
  protected $btWrapperClass = 'ccm-ui';
  protected $btInterfaceWidth = "600";
  protected $btInterfaceHeight = "300";

  var $status;

  public function registerViewAssets() {
    $this->requireAsset('javascript', 'jquery');
    $this->requireAsset('css', 'font-awesome');
  }

  public function getLinkUrl()
  {
    if (!empty($this->link)) {
      return $this->link;
    }

    if (!empty($this->internalLinkCID)) {
      $linkToC = Page::getByID($this->internalLinkCID);

      return (empty($linkToC) || $linkToC->error)
        ? ''
        : \Core::make('helper/navigation')->getLinkToCollection($linkToC);
    }

    return '';
  }

  public function view()
  {
    $this->set('linkUrl', $this->getLinkUrl());
  }

  public function save($data)
  {
    switch (intval($data['linkType'])) {
        // another page
        case 1:
            $data['link'] = '';
            break;
        // external link
        case 2:
            $data['internalLinkCID'] = 0;
            break;
        default:
            $data['link'] = '';
            $data['internalLinkCID'] = 0;
            break;
    }
    unset($data['linkType']);

    parent::save($data);
  }
}
