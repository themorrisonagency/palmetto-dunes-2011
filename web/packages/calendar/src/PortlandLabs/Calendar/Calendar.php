<?php
namespace Concrete\Package\Calendar\Src\PortlandLabs\Calendar;
use Database;

/**
 * @Entity
 * @Table(name="Calendars")
 */
class Calendar
{

    const DEFAULT_COLOR = '#3988ED';

    /**
     * @return mixed
     */
    public function getName()
    {
        return $this->caName;
    }

    /**
     * @param mixed $caName
     */
    public function setName($caName)
    {
        $this->caName = $caName;
    }

    /**
     * @return mixed
     */
    public function getID()
    {
        return $this->caID;
    }

    /**
     * @param mixed $caID
     */
    public function setID($caID)
    {
        $this->caID = $caID;
    }

    /**
     * @Column(type="string")
     */
    protected $caName;

    /**
     * @Id @Column(columnDefinition="integer unsigned")
     * @GeneratedValue
     */
    protected $caID;

    public static function getList()
    {
        $db = Database::get();
        $em = $db->getEntityManager();
        return $em->getRepository('Concrete\Package\Calendar\Src\PortlandLabs\Calendar\Calendar')->findBy(array(), array('caName' => 'asc'));
    }

    public function save()
    {
        $db = Database::get();
        $em = $db->getEntityManager();
        $em->persist($this);
        $em->flush();
    }

    public function delete()
    {
        // delete all events bound to the given calendar
        $db = Database::get();
        $r = $db->Execute('select eventID from CalendarEvents where caID = ?', array($this->getID()));
        while ($row = $r->fetchRow()) {
            $db->delete('CalendarEventOccurrences', array('eventID' => $row['eventID']));
        }
        $db->delete('CalendarEvents', array('caID' => $this->getID()));

        $em = Database::get()->getEntityManager();
        $em->remove($this);
        $em->flush();
    }

    public static function getByID($id)
    {
        $db = Database::get();
        $em = $db->getEntityManager();
        $r = $em->find('Concrete\Package\Calendar\Src\PortlandLabs\Calendar\Calendar', $id);
        return $r;
    }
}