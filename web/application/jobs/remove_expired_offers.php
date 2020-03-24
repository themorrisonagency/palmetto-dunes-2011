<?php
namespace Application\Job;

use Concrete\Core\Cache\Cache;
use Config;
use \Job as AbstractJob;
use Loader;
use PermissionKey;
use Group;
use DateTime;
use CollectionAttributeKey;
use Page;
use Events;

class RemoveExpiredOffers extends AbstractJob
{

    /** Returns the job name.
     * @return string
     */
    public function getJobName()
    {
        return t('Remove Expired Offers');
    }

    /** Returns the job description.
     * @return string
     */
    public function getJobDescription()
    {
        return t('Remove expired Special Offer pages from your site.');
    }

    public static $removedPages = 0;

    /** Executes the job.
     * @throws \Exception Throws an exception in case of errors.
     * @return string Returns a string describing the job result in case of success.
     */
    public function run()
    {
        Cache::disableAll();
        try {
            $now = new DateTime('now');
            $rs = Loader::db()->Query("SELECT cID FROM Pages INNER JOIN PageTypes ON PageTypes.ptID = Pages.ptID WHERE PageTypes.ptHandle = 'special_offer'");
            while ($row = $rs->FetchRow()) {
                self::removePage(intval($row['cID']), $now);
            }
            $rs->Close();

            if (self::$removedPages > 0) {
                return t(
                    'Successfully moved %1$d Special Offer page(s) to the trash.',
                    self::$removedPages
                );
            } else {
                return t('No expired offers were found.');
            }
        } catch (\Exception $x) {
            throw $x;
        }
    }

    /** Check if the specified page should be moved to the Trash
     * @param int $cID The page collection id.
     * @throws \Exception Throws an exception in case of errors.
     */
    private static function removePage($cID, $now) {
        $page = Page::getByID($cID, 'ACTIVE');

        if ($page->isInTrash()) {
            return;
        }
        $pageVersion = $page->getVersionObject();
        if ($pageVersion && !$pageVersion->isApproved()) {
            return;
        }
        
        $expireDate = new DateTime($page->getCollectionAttributeValue('special_offer_end_date'));

        if ($expireDate > $now) {
            return;
        }

        $page->moveToTrash();
        self::$removedPages += 1;
    }
}
