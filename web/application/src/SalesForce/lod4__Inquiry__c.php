<?php

namespace Application\Src\SalesForce;

class lod4__Inquiry__c
{

    /**
     * @return string
     */
    public function getAccommodation_Type__c()
    {
      return $this->Accommodation_Type__c;
    }

    /**
     * @param string $Accommodation_Type__c
     * @return lod4__Inquiry__c
     */
    public function setAccommodation_Type__c($Accommodation_Type__c)
    {
      $this->Accommodation_Type__c = $Accommodation_Type__c;
      return $this;
    }

    /**
     * @return boolean
     */
    public function getAccommodations_Needed__c()
    {
      return $this->Accommodations_Needed__c;
    }

    /**
     * @param boolean $Accommodations_Needed__c
     * @return lod4__Inquiry__c
     */
    public function setAccommodations_Needed__c($Accommodations_Needed__c)
    {
      $this->Accommodations_Needed__c = $Accommodations_Needed__c;
      return $this;
    }

    /**
     * @return QueryResult
     */
    public function getActivityHistories()
    {
      return $this->ActivityHistories;
    }

    /**
     * @param QueryResult $ActivityHistories
     * @return lod4__Inquiry__c
     */
    public function setActivityHistories($ActivityHistories)
    {
      $this->ActivityHistories = $ActivityHistories;
      return $this;
    }

    /**
     * @return string
     */
    public function getAdditional_Activities__c()
    {
      return $this->Additional_Activities__c;
    }

    /**
     * @param string $Additional_Activities__c
     * @return lod4__Inquiry__c
     */
    public function setAdditional_Activities__c($Additional_Activities__c)
    {
      $this->Additional_Activities__c = $Additional_Activities__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getAdditional_Services__c()
    {
      return $this->Additional_Services__c;
    }

    /**
     * @param string $Additional_Services__c
     * @return lod4__Inquiry__c
     */
    public function setAdditional_Services__c($Additional_Services__c)
    {
      $this->Additional_Services__c = $Additional_Services__c;
      return $this;
    }

    /**
     * @return ID
     */
    public function getAgency__c()
    {
      return $this->Agency__c;
    }

    /**
     * @param ID $Agency__c
     * @return lod4__Inquiry__c
     */
    public function setAgency__c($Agency__c)
    {
      $this->Agency__c = $Agency__c;
      return $this;
    }

    /**
     * @return Account
     */
    public function getAgency__r()
    {
      return $this->Agency__r;
    }

    /**
     * @param Account $Agency__r
     * @return lod4__Inquiry__c
     */
    public function setAgency__r($Agency__r)
    {
      $this->Agency__r = $Agency__r;
      return $this;
    }

    /**
     * @return string
     */
    public function getAlert_Golf_Team__c()
    {
      return $this->Alert_Golf_Team__c;
    }

    /**
     * @param string $Alert_Golf_Team__c
     * @return lod4__Inquiry__c
     */
    public function setAlert_Golf_Team__c($Alert_Golf_Team__c)
    {
      $this->Alert_Golf_Team__c = $Alert_Golf_Team__c;
      return $this;
    }

    /**
     * @return date
     */
    public function getAlternate_Date__c()
    {
      return $this->Alternate_Date__c;
    }

    /**
     * @param date $Alternate_Date__c
     * @return lod4__Inquiry__c
     */
    public function setAlternate_Date__c($Alternate_Date__c)
    {
      $this->Alternate_Date__c = $Alternate_Date__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getAlternate_Villa_Rental__c()
    {
      return $this->Alternate_Villa_Rental__c;
    }

    /**
     * @param string $Alternate_Villa_Rental__c
     * @return lod4__Inquiry__c
     */
    public function setAlternate_Villa_Rental__c($Alternate_Villa_Rental__c)
    {
      $this->Alternate_Villa_Rental__c = $Alternate_Villa_Rental__c;
      return $this;
    }

    /**
     * @return QueryResult
     */
    public function getAttachedContentDocuments()
    {
      return $this->AttachedContentDocuments;
    }

    /**
     * @param QueryResult $AttachedContentDocuments
     * @return lod4__Inquiry__c
     */
    public function setAttachedContentDocuments($AttachedContentDocuments)
    {
      $this->AttachedContentDocuments = $AttachedContentDocuments;
      return $this;
    }

    /**
     * @return QueryResult
     */
    public function getAttachments()
    {
      return $this->Attachments;
    }

    /**
     * @param QueryResult $Attachments
     * @return lod4__Inquiry__c
     */
    public function setAttachments($Attachments)
    {
      $this->Attachments = $Attachments;
      return $this;
    }

    /**
     * @return string
     */
    public function getBedrooms__c()
    {
      return $this->Bedrooms__c;
    }

    /**
     * @param string $Bedrooms__c
     * @return lod4__Inquiry__c
     */
    public function setBedrooms__c($Bedrooms__c)
    {
      $this->Bedrooms__c = $Bedrooms__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getBest_time_to_call__c()
    {
      return $this->Best_time_to_call__c;
    }

    /**
     * @param string $Best_time_to_call__c
     * @return lod4__Inquiry__c
     */
    public function setBest_time_to_call__c($Best_time_to_call__c)
    {
      $this->Best_time_to_call__c = $Best_time_to_call__c;
      return $this;
    }

    /**
     * @return float
     */
    public function getBooked_Accommodations_Revenue__c()
    {
      return $this->Booked_Accommodations_Revenue__c;
    }

    /**
     * @param float $Booked_Accommodations_Revenue__c
     * @return lod4__Inquiry__c
     */
    public function setBooked_Accommodations_Revenue__c($Booked_Accommodations_Revenue__c)
    {
      $this->Booked_Accommodations_Revenue__c = $Booked_Accommodations_Revenue__c;
      return $this;
    }

    /**
     * @return float
     */
    public function getBooked_Activity_Revenue__c()
    {
      return $this->Booked_Activity_Revenue__c;
    }

    /**
     * @param float $Booked_Activity_Revenue__c
     * @return lod4__Inquiry__c
     */
    public function setBooked_Activity_Revenue__c($Booked_Activity_Revenue__c)
    {
      $this->Booked_Activity_Revenue__c = $Booked_Activity_Revenue__c;
      return $this;
    }

    /**
     * @return float
     */
    public function getBooked_Golf_Revenue__c()
    {
      return $this->Booked_Golf_Revenue__c;
    }

    /**
     * @param float $Booked_Golf_Revenue__c
     * @return lod4__Inquiry__c
     */
    public function setBooked_Golf_Revenue__c($Booked_Golf_Revenue__c)
    {
      $this->Booked_Golf_Revenue__c = $Booked_Golf_Revenue__c;
      return $this;
    }

    /**
     * @return float
     */
    public function getBooked_Revenue_Total__c()
    {
      return $this->Booked_Revenue_Total__c;
    }

    /**
     * @param float $Booked_Revenue_Total__c
     * @return lod4__Inquiry__c
     */
    public function setBooked_Revenue_Total__c($Booked_Revenue_Total__c)
    {
      $this->Booked_Revenue_Total__c = $Booked_Revenue_Total__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getBooking_Agent__c()
    {
      return $this->Booking_Agent__c;
    }

    /**
     * @param string $Booking_Agent__c
     * @return lod4__Inquiry__c
     */
    public function setBooking_Agent__c($Booking_Agent__c)
    {
      $this->Booking_Agent__c = $Booking_Agent__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getBride_s_First_Name__c()
    {
      return $this->Bride_s_First_Name__c;
    }

    /**
     * @param string $Bride_s_First_Name__c
     * @return lod4__Inquiry__c
     */
    public function setBride_s_First_Name__c($Bride_s_First_Name__c)
    {
      $this->Bride_s_First_Name__c = $Bride_s_First_Name__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getBride_s_Last_Name__c()
    {
      return $this->Bride_s_Last_Name__c;
    }

    /**
     * @param string $Bride_s_Last_Name__c
     * @return lod4__Inquiry__c
     */
    public function setBride_s_Last_Name__c($Bride_s_Last_Name__c)
    {
      $this->Bride_s_Last_Name__c = $Bride_s_Last_Name__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getBrowser__c()
    {
      return $this->Browser__c;
    }

    /**
     * @param string $Browser__c
     * @return lod4__Inquiry__c
     */
    public function setBrowser__c($Browser__c)
    {
      $this->Browser__c = $Browser__c;
      return $this;
    }

    /**
     * @return float
     */
    public function getBudget_Points__c()
    {
      return $this->Budget_Points__c;
    }

    /**
     * @param float $Budget_Points__c
     * @return lod4__Inquiry__c
     */
    public function setBudget_Points__c($Budget_Points__c)
    {
      $this->Budget_Points__c = $Budget_Points__c;
      return $this;
    }

    /**
     * @return boolean
     */
    public function getCeremony_Required__c()
    {
      return $this->Ceremony_Required__c;
    }

    /**
     * @param boolean $Ceremony_Required__c
     * @return lod4__Inquiry__c
     */
    public function setCeremony_Required__c($Ceremony_Required__c)
    {
      $this->Ceremony_Required__c = $Ceremony_Required__c;
      return $this;
    }

    /**
     * @return QueryResult
     */
    public function getCombinedAttachments()
    {
      return $this->CombinedAttachments;
    }

    /**
     * @param QueryResult $CombinedAttachments
     * @return lod4__Inquiry__c
     */
    public function setCombinedAttachments($CombinedAttachments)
    {
      $this->CombinedAttachments = $CombinedAttachments;
      return $this;
    }

    /**
     * @return boolean
     */
    public function getCommissionable__c()
    {
      return $this->Commissionable__c;
    }

    /**
     * @param boolean $Commissionable__c
     * @return lod4__Inquiry__c
     */
    public function setCommissionable__c($Commissionable__c)
    {
      $this->Commissionable__c = $Commissionable__c;
      return $this;
    }

    /**
     * @return QueryResult
     */
    public function getContacts__r()
    {
      return $this->Contacts__r;
    }

    /**
     * @param QueryResult $Contacts__r
     * @return lod4__Inquiry__c
     */
    public function setContacts__r($Contacts__r)
    {
      $this->Contacts__r = $Contacts__r;
      return $this;
    }

    /**
     * @return User
     */
    public function getCreatedBy()
    {
      return $this->CreatedBy;
    }

    /**
     * @param User $CreatedBy
     * @return lod4__Inquiry__c
     */
    public function setCreatedBy($CreatedBy)
    {
      $this->CreatedBy = $CreatedBy;
      return $this;
    }

    /**
     * @return ID
     */
    public function getCreatedById()
    {
      return $this->CreatedById;
    }

    /**
     * @param ID $CreatedById
     * @return lod4__Inquiry__c
     */
    public function setCreatedById($CreatedById)
    {
      $this->CreatedById = $CreatedById;
      return $this;
    }

    /**
     * @return \DateTime
     */
    public function getCreatedDate()
    {
      if ($this->CreatedDate == null) {
        return null;
      } else {
        try {
          return new \DateTime($this->CreatedDate);
        } catch (\Exception $e) {
          return false;
        }
      }
    }

    /**
     * @param \DateTime $CreatedDate
     * @return lod4__Inquiry__c
     */
    public function setCreatedDate(\DateTime $CreatedDate = null)
    {
      if ($CreatedDate == null) {
       $this->CreatedDate = null;
      } else {
        $this->CreatedDate = $CreatedDate->format(\DateTime::ATOM);
      }
      return $this;
    }

    /**
     * @return string
     */
    public function getCurrencyIsoCode()
    {
      return $this->CurrencyIsoCode;
    }

    /**
     * @param string $CurrencyIsoCode
     * @return lod4__Inquiry__c
     */
    public function setCurrencyIsoCode($CurrencyIsoCode)
    {
      $this->CurrencyIsoCode = $CurrencyIsoCode;
      return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDate_Booked__c()
    {
      if ($this->Date_Booked__c == null) {
        return null;
      } else {
        try {
          return new \DateTime($this->Date_Booked__c);
        } catch (\Exception $e) {
          return false;
        }
      }
    }

    /**
     * @param \DateTime $Date_Booked__c
     * @return lod4__Inquiry__c
     */
    public function setDate_Booked__c(\DateTime $Date_Booked__c = null)
    {
      if ($Date_Booked__c == null) {
       $this->Date_Booked__c = null;
      } else {
        $this->Date_Booked__c = $Date_Booked__c->format(\DateTime::ATOM);
      }
      return $this;
    }

    /**
     * @return \DateTime
     */
    public function getDate_Created__c()
    {
      if ($this->Date_Created__c == null) {
        return null;
      } else {
        try {
          return new \DateTime($this->Date_Created__c);
        } catch (\Exception $e) {
          return false;
        }
      }
    }

    /**
     * @param \DateTime $Date_Created__c
     * @return lod4__Inquiry__c
     */
    public function setDate_Created__c(\DateTime $Date_Created__c = null)
    {
      if ($Date_Created__c == null) {
       $this->Date_Created__c = null;
      } else {
        $this->Date_Created__c = $Date_Created__c->format(\DateTime::ATOM);
      }
      return $this;
    }

    /**
     * @return boolean
     */
    public function getDate_not_Set__c()
    {
      return $this->Date_not_Set__c;
    }

    /**
     * @param boolean $Date_not_Set__c
     * @return lod4__Inquiry__c
     */
    public function setDate_not_Set__c($Date_not_Set__c)
    {
      $this->Date_not_Set__c = $Date_not_Set__c;
      return $this;
    }

    /**
     * @return float
     */
    public function getDelta_Booking_Number__c()
    {
      return $this->Delta_Booking_Number__c;
    }

    /**
     * @param float $Delta_Booking_Number__c
     * @return lod4__Inquiry__c
     */
    public function setDelta_Booking_Number__c($Delta_Booking_Number__c)
    {
      $this->Delta_Booking_Number__c = $Delta_Booking_Number__c;
      return $this;
    }

    /**
     * @return float
     */
    public function getDelta_Booking__c()
    {
      return $this->Delta_Booking__c;
    }

    /**
     * @param float $Delta_Booking__c
     * @return lod4__Inquiry__c
     */
    public function setDelta_Booking__c($Delta_Booking__c)
    {
      $this->Delta_Booking__c = $Delta_Booking__c;
      return $this;
    }

    /**
     * @return QueryResult
     */
    public function getDuplicateRecordItems()
    {
      return $this->DuplicateRecordItems;
    }

    /**
     * @param QueryResult $DuplicateRecordItems
     * @return lod4__Inquiry__c
     */
    public function setDuplicateRecordItems($DuplicateRecordItems)
    {
      $this->DuplicateRecordItems = $DuplicateRecordItems;
      return $this;
    }

    /**
     * @return string
     */
    public function getEmail_2__c()
    {
      return $this->Email_2__c;
    }

    /**
     * @param string $Email_2__c
     * @return lod4__Inquiry__c
     */
    public function setEmail_2__c($Email_2__c)
    {
      $this->Email_2__c = $Email_2__c;
      return $this;
    }

    /**
     * @return QueryResult
     */
    public function getEmails()
    {
      return $this->Emails;
    }

    /**
     * @param QueryResult $Emails
     * @return lod4__Inquiry__c
     */
    public function setEmails($Emails)
    {
      $this->Emails = $Emails;
      return $this;
    }

    /**
     * @return string
     */
    public function getEstimated_Budget__c()
    {
      return $this->Estimated_Budget__c;
    }

    /**
     * @param string $Estimated_Budget__c
     * @return lod4__Inquiry__c
     */
    public function setEstimated_Budget__c($Estimated_Budget__c)
    {
      $this->Estimated_Budget__c = $Estimated_Budget__c;
      return $this;
    }

    /**
     * @return date
     */
    public function getEstimated_Departure__c()
    {
      return $this->Estimated_Departure__c;
    }

    /**
     * @param date $Estimated_Departure__c
     * @return lod4__Inquiry__c
     */
    public function setEstimated_Departure__c($Estimated_Departure__c)
    {
      $this->Estimated_Departure__c = $Estimated_Departure__c;
      return $this;
    }

    /**
     * @return QueryResult
     */
    public function getEventRelations()
    {
      return $this->EventRelations;
    }

    /**
     * @param QueryResult $EventRelations
     * @return lod4__Inquiry__c
     */
    public function setEventRelations($EventRelations)
    {
      $this->EventRelations = $EventRelations;
      return $this;
    }

    /**
     * @return string
     */
    public function getEvent_Description__c()
    {
      return $this->Event_Description__c;
    }

    /**
     * @param string $Event_Description__c
     * @return lod4__Inquiry__c
     */
    public function setEvent_Description__c($Event_Description__c)
    {
      $this->Event_Description__c = $Event_Description__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getEvent_Type__c()
    {
      return $this->Event_Type__c;
    }

    /**
     * @param string $Event_Type__c
     * @return lod4__Inquiry__c
     */
    public function setEvent_Type__c($Event_Type__c)
    {
      $this->Event_Type__c = $Event_Type__c;
      return $this;
    }

    /**
     * @return QueryResult
     */
    public function getEvents()
    {
      return $this->Events;
    }

    /**
     * @param QueryResult $Events
     * @return lod4__Inquiry__c
     */
    public function setEvents($Events)
    {
      $this->Events = $Events;
      return $this;
    }

    /**
     * @return QueryResult
     */
    public function getFeedSubscriptionsForEntity()
    {
      return $this->FeedSubscriptionsForEntity;
    }

    /**
     * @param QueryResult $FeedSubscriptionsForEntity
     * @return lod4__Inquiry__c
     */
    public function setFeedSubscriptionsForEntity($FeedSubscriptionsForEntity)
    {
      $this->FeedSubscriptionsForEntity = $FeedSubscriptionsForEntity;
      return $this;
    }

    /**
     * @return string
     */
    public function getFewer_than_9_Players__c()
    {
      return $this->Fewer_than_9_Players__c;
    }

    /**
     * @param string $Fewer_than_9_Players__c
     * @return lod4__Inquiry__c
     */
    public function setFewer_than_9_Players__c($Fewer_than_9_Players__c)
    {
      $this->Fewer_than_9_Players__c = $Fewer_than_9_Players__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getGolf_Courses__c()
    {
      return $this->Golf_Courses__c;
    }

    /**
     * @param string $Golf_Courses__c
     * @return lod4__Inquiry__c
     */
    public function setGolf_Courses__c($Golf_Courses__c)
    {
      $this->Golf_Courses__c = $Golf_Courses__c;
      return $this;
    }

    /**
     * @return boolean
     */
    public function getGolf_Travel__c()
    {
      return $this->Golf_Travel__c;
    }

    /**
     * @param boolean $Golf_Travel__c
     * @return lod4__Inquiry__c
     */
    public function setGolf_Travel__c($Golf_Travel__c)
    {
      $this->Golf_Travel__c = $Golf_Travel__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getGroom_s_First_Name__c()
    {
      return $this->Groom_s_First_Name__c;
    }

    /**
     * @param string $Groom_s_First_Name__c
     * @return lod4__Inquiry__c
     */
    public function setGroom_s_First_Name__c($Groom_s_First_Name__c)
    {
      $this->Groom_s_First_Name__c = $Groom_s_First_Name__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getGroom_s_Last_Name__c()
    {
      return $this->Groom_s_Last_Name__c;
    }

    /**
     * @param string $Groom_s_Last_Name__c
     * @return lod4__Inquiry__c
     */
    public function setGroom_s_Last_Name__c($Groom_s_Last_Name__c)
    {
      $this->Groom_s_Last_Name__c = $Groom_s_Last_Name__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getGroup_Name__c()
    {
      return $this->Group_Name__c;
    }

    /**
     * @param string $Group_Name__c
     * @return lod4__Inquiry__c
     */
    public function setGroup_Name__c($Group_Name__c)
    {
      $this->Group_Name__c = $Group_Name__c;
      return $this;
    }

    /**
     * @return QueryResult
     */
    public function getGuestrooms__r()
    {
      return $this->Guestrooms__r;
    }

    /**
     * @param QueryResult $Guestrooms__r
     * @return lod4__Inquiry__c
     */
    public function setGuestrooms__r($Guestrooms__r)
    {
      $this->Guestrooms__r = $Guestrooms__r;
      return $this;
    }

    /**
     * @return QueryResult
     */
    public function getHistories()
    {
      return $this->Histories;
    }

    /**
     * @param QueryResult $Histories
     * @return lod4__Inquiry__c
     */
    public function setHistories($Histories)
    {
      $this->Histories = $Histories;
      return $this;
    }

    /**
     * @return string
     */
    public function getInquiry_number__c()
    {
      return $this->Inquiry_number__c;
    }

    /**
     * @param string $Inquiry_number__c
     * @return lod4__Inquiry__c
     */
    public function setInquiry_number__c($Inquiry_number__c)
    {
      $this->Inquiry_number__c = $Inquiry_number__c;
      return $this;
    }

    /**
     * @return boolean
     */
    public function getIsDeleted()
    {
      return $this->IsDeleted;
    }

    /**
     * @param boolean $IsDeleted
     * @return lod4__Inquiry__c
     */
    public function setIsDeleted($IsDeleted)
    {
      $this->IsDeleted = $IsDeleted;
      return $this;
    }

    /**
     * @return float
     */
    public function getIs_Booked__c()
    {
      return $this->Is_Booked__c;
    }

    /**
     * @param float $Is_Booked__c
     * @return lod4__Inquiry__c
     */
    public function setIs_Booked__c($Is_Booked__c)
    {
      $this->Is_Booked__c = $Is_Booked__c;
      return $this;
    }

    /**
     * @return date
     */
    public function getLastActivityDate()
    {
      return $this->LastActivityDate;
    }

    /**
     * @param date $LastActivityDate
     * @return lod4__Inquiry__c
     */
    public function setLastActivityDate($LastActivityDate)
    {
      $this->LastActivityDate = $LastActivityDate;
      return $this;
    }

    /**
     * @return User
     */
    public function getLastModifiedBy()
    {
      return $this->LastModifiedBy;
    }

    /**
     * @param User $LastModifiedBy
     * @return lod4__Inquiry__c
     */
    public function setLastModifiedBy($LastModifiedBy)
    {
      $this->LastModifiedBy = $LastModifiedBy;
      return $this;
    }

    /**
     * @return ID
     */
    public function getLastModifiedById()
    {
      return $this->LastModifiedById;
    }

    /**
     * @param ID $LastModifiedById
     * @return lod4__Inquiry__c
     */
    public function setLastModifiedById($LastModifiedById)
    {
      $this->LastModifiedById = $LastModifiedById;
      return $this;
    }

    /**
     * @return \DateTime
     */
    public function getLastModifiedDate()
    {
      if ($this->LastModifiedDate == null) {
        return null;
      } else {
        try {
          return new \DateTime($this->LastModifiedDate);
        } catch (\Exception $e) {
          return false;
        }
      }
    }

    /**
     * @param \DateTime $LastModifiedDate
     * @return lod4__Inquiry__c
     */
    public function setLastModifiedDate(\DateTime $LastModifiedDate = null)
    {
      if ($LastModifiedDate == null) {
       $this->LastModifiedDate = null;
      } else {
        $this->LastModifiedDate = $LastModifiedDate->format(\DateTime::ATOM);
      }
      return $this;
    }

    /**
     * @return \DateTime
     */
    public function getLastReferencedDate()
    {
      if ($this->LastReferencedDate == null) {
        return null;
      } else {
        try {
          return new \DateTime($this->LastReferencedDate);
        } catch (\Exception $e) {
          return false;
        }
      }
    }

    /**
     * @param \DateTime $LastReferencedDate
     * @return lod4__Inquiry__c
     */
    public function setLastReferencedDate(\DateTime $LastReferencedDate = null)
    {
      if ($LastReferencedDate == null) {
       $this->LastReferencedDate = null;
      } else {
        $this->LastReferencedDate = $LastReferencedDate->format(\DateTime::ATOM);
      }
      return $this;
    }

    /**
     * @return \DateTime
     */
    public function getLastViewedDate()
    {
      if ($this->LastViewedDate == null) {
        return null;
      } else {
        try {
          return new \DateTime($this->LastViewedDate);
        } catch (\Exception $e) {
          return false;
        }
      }
    }

    /**
     * @param \DateTime $LastViewedDate
     * @return lod4__Inquiry__c
     */
    public function setLastViewedDate(\DateTime $LastViewedDate = null)
    {
      if ($LastViewedDate == null) {
       $this->LastViewedDate = null;
      } else {
        $this->LastViewedDate = $LastViewedDate->format(\DateTime::ATOM);
      }
      return $this;
    }

    /**
     * @return date
     */
    public function getLast_Activity_Date__c()
    {
      return $this->Last_Activity_Date__c;
    }

    /**
     * @param date $Last_Activity_Date__c
     * @return lod4__Inquiry__c
     */
    public function setLast_Activity_Date__c($Last_Activity_Date__c)
    {
      $this->Last_Activity_Date__c = $Last_Activity_Date__c;
      return $this;
    }

    /**
     * @return float
     */
    public function getLead_Time__c()
    {
      return $this->Lead_Time__c;
    }

    /**
     * @param float $Lead_Time__c
     * @return lod4__Inquiry__c
     */
    public function setLead_Time__c($Lead_Time__c)
    {
      $this->Lead_Time__c = $Lead_Time__c;
      return $this;
    }

    /**
     * @return boolean
     */
    public function getList_golf__c()
    {
      return $this->List_golf__c;
    }

    /**
     * @param boolean $List_golf__c
     * @return lod4__Inquiry__c
     */
    public function setList_golf__c($List_golf__c)
    {
      $this->List_golf__c = $List_golf__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getLocation__c()
    {
      return $this->Location__c;
    }

    /**
     * @param string $Location__c
     * @return lod4__Inquiry__c
     */
    public function setLocation__c($Location__c)
    {
      $this->Location__c = $Location__c;
      return $this;
    }

    /**
     * @return QueryResult
     */
    public function getLookedUpFromActivities()
    {
      return $this->LookedUpFromActivities;
    }

    /**
     * @param QueryResult $LookedUpFromActivities
     * @return lod4__Inquiry__c
     */
    public function setLookedUpFromActivities($LookedUpFromActivities)
    {
      $this->LookedUpFromActivities = $LookedUpFromActivities;
      return $this;
    }

    /**
     * @return date
     */
    public function getLost_Date__c()
    {
      return $this->Lost_Date__c;
    }

    /**
     * @param date $Lost_Date__c
     * @return lod4__Inquiry__c
     */
    public function setLost_Date__c($Lost_Date__c)
    {
      $this->Lost_Date__c = $Lost_Date__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getLost_Description__c()
    {
      return $this->Lost_Description__c;
    }

    /**
     * @param string $Lost_Description__c
     * @return lod4__Inquiry__c
     */
    public function setLost_Description__c($Lost_Description__c)
    {
      $this->Lost_Description__c = $Lost_Description__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getLost_Reason__c()
    {
      return $this->Lost_Reason__c;
    }

    /**
     * @param string $Lost_Reason__c
     * @return lod4__Inquiry__c
     */
    public function setLost_Reason__c($Lost_Reason__c)
    {
      $this->Lost_Reason__c = $Lost_Reason__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getMark_Test_Field__c()
    {
      return $this->Mark_Test_Field__c;
    }

    /**
     * @param string $Mark_Test_Field__c
     * @return lod4__Inquiry__c
     */
    public function setMark_Test_Field__c($Mark_Test_Field__c)
    {
      $this->Mark_Test_Field__c = $Mark_Test_Field__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getMeals__c()
    {
      return $this->Meals__c;
    }

    /**
     * @param string $Meals__c
     * @return lod4__Inquiry__c
     */
    public function setMeals__c($Meals__c)
    {
      $this->Meals__c = $Meals__c;
      return $this;
    }

    /**
     * @return boolean
     */
    public function getMeeting_Space_Requested__c()
    {
      return $this->Meeting_Space_Requested__c;
    }

    /**
     * @param boolean $Meeting_Space_Requested__c
     * @return lod4__Inquiry__c
     */
    public function setMeeting_Space_Requested__c($Meeting_Space_Requested__c)
    {
      $this->Meeting_Space_Requested__c = $Meeting_Space_Requested__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getMiddle_Name_or_Initial__c()
    {
      return $this->Middle_Name_or_Initial__c;
    }

    /**
     * @param string $Middle_Name_or_Initial__c
     * @return lod4__Inquiry__c
     */
    public function setMiddle_Name_or_Initial__c($Middle_Name_or_Initial__c)
    {
      $this->Middle_Name_or_Initial__c = $Middle_Name_or_Initial__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getMonth_of_Travel__c()
    {
      return $this->Month_of_Travel__c;
    }

    /**
     * @param string $Month_of_Travel__c
     * @return lod4__Inquiry__c
     */
    public function setMonth_of_Travel__c($Month_of_Travel__c)
    {
      $this->Month_of_Travel__c = $Month_of_Travel__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getName()
    {
      return $this->Name;
    }

    /**
     * @param string $Name
     * @return lod4__Inquiry__c
     */
    public function setName($Name)
    {
      $this->Name = $Name;
      return $this;
    }

    /**
     * @return QueryResult
     */
    public function getNotes()
    {
      return $this->Notes;
    }

    /**
     * @param QueryResult $Notes
     * @return lod4__Inquiry__c
     */
    public function setNotes($Notes)
    {
      $this->Notes = $Notes;
      return $this;
    }

    /**
     * @return QueryResult
     */
    public function getNotesAndAttachments()
    {
      return $this->NotesAndAttachments;
    }

    /**
     * @param QueryResult $NotesAndAttachments
     * @return lod4__Inquiry__c
     */
    public function setNotesAndAttachments($NotesAndAttachments)
    {
      $this->NotesAndAttachments = $NotesAndAttachments;
      return $this;
    }

    /**
     * @return string
     */
    public function getNotify_Manager__c()
    {
      return $this->Notify_Manager__c;
    }

    /**
     * @param string $Notify_Manager__c
     * @return lod4__Inquiry__c
     */
    public function setNotify_Manager__c($Notify_Manager__c)
    {
      $this->Notify_Manager__c = $Notify_Manager__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getNumber_of_Adults__c()
    {
      return $this->Number_of_Adults__c;
    }

    /**
     * @param string $Number_of_Adults__c
     * @return lod4__Inquiry__c
     */
    public function setNumber_of_Adults__c($Number_of_Adults__c)
    {
      $this->Number_of_Adults__c = $Number_of_Adults__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getNumber_of_Children__c()
    {
      return $this->Number_of_Children__c;
    }

    /**
     * @param string $Number_of_Children__c
     * @return lod4__Inquiry__c
     */
    public function setNumber_of_Children__c($Number_of_Children__c)
    {
      $this->Number_of_Children__c = $Number_of_Children__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getNumber_of_Guests__c()
    {
      return $this->Number_of_Guests__c;
    }

    /**
     * @param string $Number_of_Guests__c
     * @return lod4__Inquiry__c
     */
    public function setNumber_of_Guests__c($Number_of_Guests__c)
    {
      $this->Number_of_Guests__c = $Number_of_Guests__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getNumber_of_Players__c()
    {
      return $this->Number_of_Players__c;
    }

    /**
     * @param string $Number_of_Players__c
     * @return lod4__Inquiry__c
     */
    public function setNumber_of_Players__c($Number_of_Players__c)
    {
      $this->Number_of_Players__c = $Number_of_Players__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getNumber_of_Rooms__c()
    {
      return $this->Number_of_Rooms__c;
    }

    /**
     * @param string $Number_of_Rooms__c
     * @return lod4__Inquiry__c
     */
    public function setNumber_of_Rooms__c($Number_of_Rooms__c)
    {
      $this->Number_of_Rooms__c = $Number_of_Rooms__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getNumber_of_Rounds__c()
    {
      return $this->Number_of_Rounds__c;
    }

    /**
     * @param string $Number_of_Rounds__c
     * @return lod4__Inquiry__c
     */
    public function setNumber_of_Rounds__c($Number_of_Rounds__c)
    {
      $this->Number_of_Rounds__c = $Number_of_Rounds__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getOLDSIID__c()
    {
      return $this->OLDSIID__c;
    }

    /**
     * @param string $OLDSIID__c
     * @return lod4__Inquiry__c
     */
    public function setOLDSIID__c($OLDSIID__c)
    {
      $this->OLDSIID__c = $OLDSIID__c;
      return $this;
    }

    /**
     * @return QueryResult
     */
    public function getOpenActivities()
    {
      return $this->OpenActivities;
    }

    /**
     * @param QueryResult $OpenActivities
     * @return lod4__Inquiry__c
     */
    public function setOpenActivities($OpenActivities)
    {
      $this->OpenActivities = $OpenActivities;
      return $this;
    }

    /**
     * @return boolean
     */
    public function getOpt_in__c()
    {
      return $this->Opt_in__c;
    }

    /**
     * @param boolean $Opt_in__c
     * @return lod4__Inquiry__c
     */
    public function setOpt_in__c($Opt_in__c)
    {
      $this->Opt_in__c = $Opt_in__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getOther_Activity__c()
    {
      return $this->Other_Activity__c;
    }

    /**
     * @param string $Other_Activity__c
     * @return lod4__Inquiry__c
     */
    public function setOther_Activity__c($Other_Activity__c)
    {
      $this->Other_Activity__c = $Other_Activity__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getOutside_Wedding_Location__c()
    {
      return $this->Outside_Wedding_Location__c;
    }

    /**
     * @param string $Outside_Wedding_Location__c
     * @return lod4__Inquiry__c
     */
    public function setOutside_Wedding_Location__c($Outside_Wedding_Location__c)
    {
      $this->Outside_Wedding_Location__c = $Outside_Wedding_Location__c;
      return $this;
    }

    /**
     * @return Name
     */
    public function getOwner()
    {
      return $this->Owner;
    }

    /**
     * @param Name $Owner
     * @return lod4__Inquiry__c
     */
    public function setOwner($Owner)
    {
      $this->Owner = $Owner;
      return $this;
    }

    /**
     * @return string
     */
    public function getOwnerID__c()
    {
      return $this->OwnerID__c;
    }

    /**
     * @param string $OwnerID__c
     * @return lod4__Inquiry__c
     */
    public function setOwnerID__c($OwnerID__c)
    {
      $this->OwnerID__c = $OwnerID__c;
      return $this;
    }

    /**
     * @return ID
     */
    public function getOwnerId()
    {
      return $this->OwnerId;
    }

    /**
     * @param ID $OwnerId
     * @return lod4__Inquiry__c
     */
    public function setOwnerId($OwnerId)
    {
      $this->OwnerId = $OwnerId;
      return $this;
    }

    /**
     * @return string
     */
    public function getOwner_Alias__c()
    {
      return $this->Owner_Alias__c;
    }

    /**
     * @param string $Owner_Alias__c
     * @return lod4__Inquiry__c
     */
    public function setOwner_Alias__c($Owner_Alias__c)
    {
      $this->Owner_Alias__c = $Owner_Alias__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getPhone_Comments__c()
    {
      return $this->Phone_Comments__c;
    }

    /**
     * @param string $Phone_Comments__c
     * @return lod4__Inquiry__c
     */
    public function setPhone_Comments__c($Phone_Comments__c)
    {
      $this->Phone_Comments__c = $Phone_Comments__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getPlanning_Comments__c()
    {
      return $this->Planning_Comments__c;
    }

    /**
     * @param string $Planning_Comments__c
     * @return lod4__Inquiry__c
     */
    public function setPlanning_Comments__c($Planning_Comments__c)
    {
      $this->Planning_Comments__c = $Planning_Comments__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getPlanning_Status__c()
    {
      return $this->Planning_Status__c;
    }

    /**
     * @param string $Planning_Status__c
     * @return lod4__Inquiry__c
     */
    public function setPlanning_Status__c($Planning_Status__c)
    {
      $this->Planning_Status__c = $Planning_Status__c;
      return $this;
    }

    /**
     * @return date
     */
    public function getPlanning_Survey_Date__c()
    {
      return $this->Planning_Survey_Date__c;
    }

    /**
     * @param date $Planning_Survey_Date__c
     * @return lod4__Inquiry__c
     */
    public function setPlanning_Survey_Date__c($Planning_Survey_Date__c)
    {
      $this->Planning_Survey_Date__c = $Planning_Survey_Date__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getPlayer_Ability__c()
    {
      return $this->Player_Ability__c;
    }

    /**
     * @param string $Player_Ability__c
     * @return lod4__Inquiry__c
     */
    public function setPlayer_Ability__c($Player_Ability__c)
    {
      $this->Player_Ability__c = $Player_Ability__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getPreferred_Method_of_Contact__c()
    {
      return $this->Preferred_Method_of_Contact__c;
    }

    /**
     * @param string $Preferred_Method_of_Contact__c
     * @return lod4__Inquiry__c
     */
    public function setPreferred_Method_of_Contact__c($Preferred_Method_of_Contact__c)
    {
      $this->Preferred_Method_of_Contact__c = $Preferred_Method_of_Contact__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getPreferred_Tee_TImes__c()
    {
      return $this->Preferred_Tee_TImes__c;
    }

    /**
     * @param string $Preferred_Tee_TImes__c
     * @return lod4__Inquiry__c
     */
    public function setPreferred_Tee_TImes__c($Preferred_Tee_TImes__c)
    {
      $this->Preferred_Tee_TImes__c = $Preferred_Tee_TImes__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getPrefix__c()
    {
      return $this->Prefix__c;
    }

    /**
     * @param string $Prefix__c
     * @return lod4__Inquiry__c
     */
    public function setPrefix__c($Prefix__c)
    {
      $this->Prefix__c = $Prefix__c;
      return $this;
    }

    /**
     * @return QueryResult
     */
    public function getProcessInstances()
    {
      return $this->ProcessInstances;
    }

    /**
     * @param QueryResult $ProcessInstances
     * @return lod4__Inquiry__c
     */
    public function setProcessInstances($ProcessInstances)
    {
      $this->ProcessInstances = $ProcessInstances;
      return $this;
    }

    /**
     * @return QueryResult
     */
    public function getProcessSteps()
    {
      return $this->ProcessSteps;
    }

    /**
     * @param QueryResult $ProcessSteps
     * @return lod4__Inquiry__c
     */
    public function setProcessSteps($ProcessSteps)
    {
      $this->ProcessSteps = $ProcessSteps;
      return $this;
    }

    /**
     * @return string
     */
    public function getPromo_Code__c()
    {
      return $this->Promo_Code__c;
    }

    /**
     * @param string $Promo_Code__c
     * @return lod4__Inquiry__c
     */
    public function setPromo_Code__c($Promo_Code__c)
    {
      $this->Promo_Code__c = $Promo_Code__c;
      return $this;
    }

    /**
     * @return float
     */
    public function getQtrYr_Points__c()
    {
      return $this->QtrYr_Points__c;
    }

    /**
     * @param float $QtrYr_Points__c
     * @return lod4__Inquiry__c
     */
    public function setQtrYr_Points__c($QtrYr_Points__c)
    {
      $this->QtrYr_Points__c = $QtrYr_Points__c;
      return $this;
    }

    /**
     * @return float
     */
    public function getQuality_Points__c()
    {
      return $this->Quality_Points__c;
    }

    /**
     * @param float $Quality_Points__c
     * @return lod4__Inquiry__c
     */
    public function setQuality_Points__c($Quality_Points__c)
    {
      $this->Quality_Points__c = $Quality_Points__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getRFP_Comments__c()
    {
      return $this->RFP_Comments__c;
    }

    /**
     * @param string $RFP_Comments__c
     * @return lod4__Inquiry__c
     */
    public function setRFP_Comments__c($RFP_Comments__c)
    {
      $this->RFP_Comments__c = $RFP_Comments__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getRFP_Type__c()
    {
      return $this->RFP_Type__c;
    }

    /**
     * @param string $RFP_Type__c
     * @return lod4__Inquiry__c
     */
    public function setRFP_Type__c($RFP_Type__c)
    {
      $this->RFP_Type__c = $RFP_Type__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getRecTypeID__c()
    {
      return $this->RecTypeID__c;
    }

    /**
     * @param string $RecTypeID__c
     * @return lod4__Inquiry__c
     */
    public function setRecTypeID__c($RecTypeID__c)
    {
      $this->RecTypeID__c = $RecTypeID__c;
      return $this;
    }

    /**
     * @return QueryResult
     */
    public function getRecordAssociatedGroups()
    {
      return $this->RecordAssociatedGroups;
    }

    /**
     * @param QueryResult $RecordAssociatedGroups
     * @return lod4__Inquiry__c
     */
    public function setRecordAssociatedGroups($RecordAssociatedGroups)
    {
      $this->RecordAssociatedGroups = $RecordAssociatedGroups;
      return $this;
    }

    /**
     * @return RecordType
     */
    public function getRecordType()
    {
      return $this->RecordType;
    }

    /**
     * @param RecordType $RecordType
     * @return lod4__Inquiry__c
     */
    public function setRecordType($RecordType)
    {
      $this->RecordType = $RecordType;
      return $this;
    }

    /**
     * @return ID
     */
    public function getRecordTypeId()
    {
      return $this->RecordTypeId;
    }

    /**
     * @param ID $RecordTypeId
     * @return lod4__Inquiry__c
     */
    public function setRecordTypeId($RecordTypeId)
    {
      $this->RecordTypeId = $RecordTypeId;
      return $this;
    }

    /**
     * @return string
     */
    public function getReferred_by__c()
    {
      return $this->Referred_by__c;
    }

    /**
     * @param string $Referred_by__c
     * @return lod4__Inquiry__c
     */
    public function setReferred_by__c($Referred_by__c)
    {
      $this->Referred_by__c = $Referred_by__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getRegion__c()
    {
      return $this->Region__c;
    }

    /**
     * @param string $Region__c
     * @return lod4__Inquiry__c
     */
    public function setRegion__c($Region__c)
    {
      $this->Region__c = $Region__c;
      return $this;
    }

    /**
     * @return ID
     */
    public function getRelated_Booking__c()
    {
      return $this->Related_Booking__c;
    }

    /**
     * @param ID $Related_Booking__c
     * @return lod4__Inquiry__c
     */
    public function setRelated_Booking__c($Related_Booking__c)
    {
      $this->Related_Booking__c = $Related_Booking__c;
      return $this;
    }

    /**
     * @return lod4__Event__c
     */
    public function getRelated_Booking__r()
    {
      return $this->Related_Booking__r;
    }

    /**
     * @param lod4__Event__c $Related_Booking__r
     * @return lod4__Inquiry__c
     */
    public function setRelated_Booking__r($Related_Booking__r)
    {
      $this->Related_Booking__r = $Related_Booking__r;
      return $this;
    }

    /**
     * @return string
     */
    public function getRelationship__c()
    {
      return $this->Relationship__c;
    }

    /**
     * @param string $Relationship__c
     * @return lod4__Inquiry__c
     */
    public function setRelationship__c($Relationship__c)
    {
      $this->Relationship__c = $Relationship__c;
      return $this;
    }

    /**
     * @return boolean
     */
    public function getRepeat_Business__c()
    {
      return $this->Repeat_Business__c;
    }

    /**
     * @param boolean $Repeat_Business__c
     * @return lod4__Inquiry__c
     */
    public function setRepeat_Business__c($Repeat_Business__c)
    {
      $this->Repeat_Business__c = $Repeat_Business__c;
      return $this;
    }

    /**
     * @return float
     */
    public function getRoom_Rate__c()
    {
      return $this->Room_Rate__c;
    }

    /**
     * @param float $Room_Rate__c
     * @return lod4__Inquiry__c
     */
    public function setRoom_Rate__c($Room_Rate__c)
    {
      $this->Room_Rate__c = $Room_Rate__c;
      return $this;
    }

    /**
     * @return float
     */
    public function getRound_Robin_ID__c()
    {
      return $this->Round_Robin_ID__c;
    }

    /**
     * @param float $Round_Robin_ID__c
     * @return lod4__Inquiry__c
     */
    public function setRound_Robin_ID__c($Round_Robin_ID__c)
    {
      $this->Round_Robin_ID__c = $Round_Robin_ID__c;
      return $this;
    }

    /**
     * @return boolean
     */
    public function getSpecial_Event_Occastion__c()
    {
      return $this->Special_Event_Occastion__c;
    }

    /**
     * @param boolean $Special_Event_Occastion__c
     * @return lod4__Inquiry__c
     */
    public function setSpecial_Event_Occastion__c($Special_Event_Occastion__c)
    {
      $this->Special_Event_Occastion__c = $Special_Event_Occastion__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getSpecial_Requests__c()
    {
      return $this->Special_Requests__c;
    }

    /**
     * @param string $Special_Requests__c
     * @return lod4__Inquiry__c
     */
    public function setSpecial_Requests__c($Special_Requests__c)
    {
      $this->Special_Requests__c = $Special_Requests__c;
      return $this;
    }

    /**
     * @return boolean
     */
    public function getSubmitted__c()
    {
      return $this->Submitted__c;
    }

    /**
     * @param boolean $Submitted__c
     * @return lod4__Inquiry__c
     */
    public function setSubmitted__c($Submitted__c)
    {
      $this->Submitted__c = $Submitted__c;
      return $this;
    }

    /**
     * @return \DateTime
     */
    public function getSystemModstamp()
    {
      if ($this->SystemModstamp == null) {
        return null;
      } else {
        try {
          return new \DateTime($this->SystemModstamp);
        } catch (\Exception $e) {
          return false;
        }
      }
    }

    /**
     * @param \DateTime $SystemModstamp
     * @return lod4__Inquiry__c
     */
    public function setSystemModstamp(\DateTime $SystemModstamp = null)
    {
      if ($SystemModstamp == null) {
       $this->SystemModstamp = null;
      } else {
        $this->SystemModstamp = $SystemModstamp->format(\DateTime::ATOM);
      }
      return $this;
    }

    /**
     * @return QueryResult
     */
    public function getTaskRelations()
    {
      return $this->TaskRelations;
    }

    /**
     * @param QueryResult $TaskRelations
     * @return lod4__Inquiry__c
     */
    public function setTaskRelations($TaskRelations)
    {
      $this->TaskRelations = $TaskRelations;
      return $this;
    }

    /**
     * @return QueryResult
     */
    public function getTasks()
    {
      return $this->Tasks;
    }

    /**
     * @param QueryResult $Tasks
     * @return lod4__Inquiry__c
     */
    public function setTasks($Tasks)
    {
      $this->Tasks = $Tasks;
      return $this;
    }

    /**
     * @return QueryResult
     */
    public function getTopicAssignments()
    {
      return $this->TopicAssignments;
    }

    /**
     * @param QueryResult $TopicAssignments
     * @return lod4__Inquiry__c
     */
    public function setTopicAssignments($TopicAssignments)
    {
      $this->TopicAssignments = $TopicAssignments;
      return $this;
    }

    /**
     * @return date
     */
    public function getTurndown_Date__c()
    {
      return $this->Turndown_Date__c;
    }

    /**
     * @param date $Turndown_Date__c
     * @return lod4__Inquiry__c
     */
    public function setTurndown_Date__c($Turndown_Date__c)
    {
      $this->Turndown_Date__c = $Turndown_Date__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getTurndown_Description__c()
    {
      return $this->Turndown_Description__c;
    }

    /**
     * @param string $Turndown_Description__c
     * @return lod4__Inquiry__c
     */
    public function setTurndown_Description__c($Turndown_Description__c)
    {
      $this->Turndown_Description__c = $Turndown_Description__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getTurndown_Reason__c()
    {
      return $this->Turndown_Reason__c;
    }

    /**
     * @param string $Turndown_Reason__c
     * @return lod4__Inquiry__c
     */
    public function setTurndown_Reason__c($Turndown_Reason__c)
    {
      $this->Turndown_Reason__c = $Turndown_Reason__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getType_of_Instruction__c()
    {
      return $this->Type_of_Instruction__c;
    }

    /**
     * @param string $Type_of_Instruction__c
     * @return lod4__Inquiry__c
     */
    public function setType_of_Instruction__c($Type_of_Instruction__c)
    {
      $this->Type_of_Instruction__c = $Type_of_Instruction__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getUSTA_Rating__c()
    {
      return $this->USTA_Rating__c;
    }

    /**
     * @param string $USTA_Rating__c
     * @return lod4__Inquiry__c
     */
    public function setUSTA_Rating__c($USTA_Rating__c)
    {
      $this->USTA_Rating__c = $USTA_Rating__c;
      return $this;
    }

    /**
     * @return UserRecordAccess
     */
    public function getUserRecordAccess()
    {
      return $this->UserRecordAccess;
    }

    /**
     * @param UserRecordAccess $UserRecordAccess
     * @return lod4__Inquiry__c
     */
    public function setUserRecordAccess($UserRecordAccess)
    {
      $this->UserRecordAccess = $UserRecordAccess;
      return $this;
    }

    /**
     * @return string
     */
    public function getView__c()
    {
      return $this->View__c;
    }

    /**
     * @param string $View__c
     * @return lod4__Inquiry__c
     */
    public function setView__c($View__c)
    {
      $this->View__c = $View__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getWeb_Medium__c()
    {
      return $this->Web_Medium__c;
    }

    /**
     * @param string $Web_Medium__c
     * @return lod4__Inquiry__c
     */
    public function setWeb_Medium__c($Web_Medium__c)
    {
      $this->Web_Medium__c = $Web_Medium__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getWeb_Source__c()
    {
      return $this->Web_Source__c;
    }

    /**
     * @param string $Web_Source__c
     * @return lod4__Inquiry__c
     */
    public function setWeb_Source__c($Web_Source__c)
    {
      $this->Web_Source__c = $Web_Source__c;
      return $this;
    }

    /**
     * @return date
     */
    public function getWedding_Date__c()
    {
      return $this->Wedding_Date__c;
    }

    /**
     * @param date $Wedding_Date__c
     * @return lod4__Inquiry__c
     */
    public function setWedding_Date__c($Wedding_Date__c)
    {
      $this->Wedding_Date__c = $Wedding_Date__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getWedding_Interests__c()
    {
      return $this->Wedding_Interests__c;
    }

    /**
     * @param string $Wedding_Interests__c
     * @return lod4__Inquiry__c
     */
    public function setWedding_Interests__c($Wedding_Interests__c)
    {
      $this->Wedding_Interests__c = $Wedding_Interests__c;
      return $this;
    }

    /**
     * @return float
     */
    public function getBooked_Activity_Revenue_total__c()
    {
      return $this->booked_Activity_Revenue_total__c;
    }

    /**
     * @param float $booked_Activity_Revenue_total__c
     * @return lod4__Inquiry__c
     */
    public function setBooked_Activity_Revenue_total__c($booked_Activity_Revenue_total__c)
    {
      $this->booked_Activity_Revenue_total__c = $booked_Activity_Revenue_total__c;
      return $this;
    }

    /**
     * @return ID
     */
    public function getLod4__Account__c()
    {
      return $this->lod4__Account__c;
    }

    /**
     * @param ID $lod4__Account__c
     * @return lod4__Inquiry__c
     */
    public function setLod4__Account__c($lod4__Account__c)
    {
      $this->lod4__Account__c = $lod4__Account__c;
      return $this;
    }

    /**
     * @return Account
     */
    public function getLod4__Account__r()
    {
      return $this->lod4__Account__r;
    }

    /**
     * @param Account $lod4__Account__r
     * @return lod4__Inquiry__c
     */
    public function setLod4__Account__r($lod4__Account__r)
    {
      $this->lod4__Account__r = $lod4__Account__r;
      return $this;
    }

    /**
     * @return boolean
     */
    public function getLod4__ApprovalRequired__c()
    {
      return $this->lod4__ApprovalRequired__c;
    }

    /**
     * @param boolean $lod4__ApprovalRequired__c
     * @return lod4__Inquiry__c
     */
    public function setLod4__ApprovalRequired__c($lod4__ApprovalRequired__c)
    {
      $this->lod4__ApprovalRequired__c = $lod4__ApprovalRequired__c;
      return $this;
    }

    /**
     * @return boolean
     */
    public function getLod4__Approved__c()
    {
      return $this->lod4__Approved__c;
    }

    /**
     * @param boolean $lod4__Approved__c
     * @return lod4__Inquiry__c
     */
    public function setLod4__Approved__c($lod4__Approved__c)
    {
      $this->lod4__Approved__c = $lod4__Approved__c;
      return $this;
    }

    /**
     * @return date
     */
    public function getLod4__ArrivalDate__c()
    {
      return $this->lod4__ArrivalDate__c;
    }

    /**
     * @param date $lod4__ArrivalDate__c
     * @return lod4__Inquiry__c
     */
    public function setLod4__ArrivalDate__c($lod4__ArrivalDate__c)
    {
      $this->lod4__ArrivalDate__c = $lod4__ArrivalDate__c;
      return $this;
    }

    /**
     * @return QueryResult
     */
    public function getLod4__Bookings__r()
    {
      return $this->lod4__Bookings__r;
    }

    /**
     * @param QueryResult $lod4__Bookings__r
     * @return lod4__Inquiry__c
     */
    public function setLod4__Bookings__r($lod4__Bookings__r)
    {
      $this->lod4__Bookings__r = $lod4__Bookings__r;
      return $this;
    }

    /**
     * @return string
     */
    public function getLod4__Channel__c()
    {
      return $this->lod4__Channel__c;
    }

    /**
     * @param string $lod4__Channel__c
     * @return lod4__Inquiry__c
     */
    public function setLod4__Channel__c($lod4__Channel__c)
    {
      $this->lod4__Channel__c = $lod4__Channel__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getLod4__City__c()
    {
      return $this->lod4__City__c;
    }

    /**
     * @param string $lod4__City__c
     * @return lod4__Inquiry__c
     */
    public function setLod4__City__c($lod4__City__c)
    {
      $this->lod4__City__c = $lod4__City__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getLod4__Company__c()
    {
      return $this->lod4__Company__c;
    }

    /**
     * @param string $lod4__Company__c
     * @return lod4__Inquiry__c
     */
    public function setLod4__Company__c($lod4__Company__c)
    {
      $this->lod4__Company__c = $lod4__Company__c;
      return $this;
    }

    /**
     * @return ID
     */
    public function getLod4__Contact__c()
    {
      return $this->lod4__Contact__c;
    }

    /**
     * @param ID $lod4__Contact__c
     * @return lod4__Inquiry__c
     */
    public function setLod4__Contact__c($lod4__Contact__c)
    {
      $this->lod4__Contact__c = $lod4__Contact__c;
      return $this;
    }

    /**
     * @return Contact
     */
    public function getLod4__Contact__r()
    {
      return $this->lod4__Contact__r;
    }

    /**
     * @param Contact $lod4__Contact__r
     * @return lod4__Inquiry__c
     */
    public function setLod4__Contact__r($lod4__Contact__r)
    {
      $this->lod4__Contact__r = $lod4__Contact__r;
      return $this;
    }

    /**
     * @return string
     */
    public function getLod4__CountryCode__c()
    {
      return $this->lod4__CountryCode__c;
    }

    /**
     * @param string $lod4__CountryCode__c
     * @return lod4__Inquiry__c
     */
    public function setLod4__CountryCode__c($lod4__CountryCode__c)
    {
      $this->lod4__CountryCode__c = $lod4__CountryCode__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getLod4__Country__c()
    {
      return $this->lod4__Country__c;
    }

    /**
     * @param string $lod4__Country__c
     * @return lod4__Inquiry__c
     */
    public function setLod4__Country__c($lod4__Country__c)
    {
      $this->lod4__Country__c = $lod4__Country__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getLod4__DUNSNumber__c()
    {
      return $this->lod4__DUNSNumber__c;
    }

    /**
     * @param string $lod4__DUNSNumber__c
     * @return lod4__Inquiry__c
     */
    public function setLod4__DUNSNumber__c($lod4__DUNSNumber__c)
    {
      $this->lod4__DUNSNumber__c = $lod4__DUNSNumber__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getLod4__Description__c()
    {
      return $this->lod4__Description__c;
    }

    /**
     * @param string $lod4__Description__c
     * @return lod4__Inquiry__c
     */
    public function setLod4__Description__c($lod4__Description__c)
    {
      $this->lod4__Description__c = $lod4__Description__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getLod4__DetailContent1__c()
    {
      return $this->lod4__DetailContent1__c;
    }

    /**
     * @param string $lod4__DetailContent1__c
     * @return lod4__Inquiry__c
     */
    public function setLod4__DetailContent1__c($lod4__DetailContent1__c)
    {
      $this->lod4__DetailContent1__c = $lod4__DetailContent1__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getLod4__DetailContent2__c()
    {
      return $this->lod4__DetailContent2__c;
    }

    /**
     * @param string $lod4__DetailContent2__c
     * @return lod4__Inquiry__c
     */
    public function setLod4__DetailContent2__c($lod4__DetailContent2__c)
    {
      $this->lod4__DetailContent2__c = $lod4__DetailContent2__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getLod4__DetailContent3__c()
    {
      return $this->lod4__DetailContent3__c;
    }

    /**
     * @param string $lod4__DetailContent3__c
     * @return lod4__Inquiry__c
     */
    public function setLod4__DetailContent3__c($lod4__DetailContent3__c)
    {
      $this->lod4__DetailContent3__c = $lod4__DetailContent3__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getLod4__DetailContent4__c()
    {
      return $this->lod4__DetailContent4__c;
    }

    /**
     * @param string $lod4__DetailContent4__c
     * @return lod4__Inquiry__c
     */
    public function setLod4__DetailContent4__c($lod4__DetailContent4__c)
    {
      $this->lod4__DetailContent4__c = $lod4__DetailContent4__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getLod4__DetailContent5__c()
    {
      return $this->lod4__DetailContent5__c;
    }

    /**
     * @param string $lod4__DetailContent5__c
     * @return lod4__Inquiry__c
     */
    public function setLod4__DetailContent5__c($lod4__DetailContent5__c)
    {
      $this->lod4__DetailContent5__c = $lod4__DetailContent5__c;
      return $this;
    }

    /**
     * @return boolean
     */
    public function getLod4__EmailVerificationRequired__c()
    {
      return $this->lod4__EmailVerificationRequired__c;
    }

    /**
     * @param boolean $lod4__EmailVerificationRequired__c
     * @return lod4__Inquiry__c
     */
    public function setLod4__EmailVerificationRequired__c($lod4__EmailVerificationRequired__c)
    {
      $this->lod4__EmailVerificationRequired__c = $lod4__EmailVerificationRequired__c;
      return $this;
    }

    /**
     * @return boolean
     */
    public function getLod4__EmailVerified__c()
    {
      return $this->lod4__EmailVerified__c;
    }

    /**
     * @param boolean $lod4__EmailVerified__c
     * @return lod4__Inquiry__c
     */
    public function setLod4__EmailVerified__c($lod4__EmailVerified__c)
    {
      $this->lod4__EmailVerified__c = $lod4__EmailVerified__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getLod4__Email__c()
    {
      return $this->lod4__Email__c;
    }

    /**
     * @param string $lod4__Email__c
     * @return lod4__Inquiry__c
     */
    public function setLod4__Email__c($lod4__Email__c)
    {
      $this->lod4__Email__c = $lod4__Email__c;
      return $this;
    }

    /**
     * @return float
     */
    public function getLod4__EstimatedFB__c()
    {
      return $this->lod4__EstimatedFB__c;
    }

    /**
     * @param float $lod4__EstimatedFB__c
     * @return lod4__Inquiry__c
     */
    public function setLod4__EstimatedFB__c($lod4__EstimatedFB__c)
    {
      $this->lod4__EstimatedFB__c = $lod4__EstimatedFB__c;
      return $this;
    }

    /**
     * @return float
     */
    public function getLod4__EstimatedGuestroomRevenue__c()
    {
      return $this->lod4__EstimatedGuestroomRevenue__c;
    }

    /**
     * @param float $lod4__EstimatedGuestroomRevenue__c
     * @return lod4__Inquiry__c
     */
    public function setLod4__EstimatedGuestroomRevenue__c($lod4__EstimatedGuestroomRevenue__c)
    {
      $this->lod4__EstimatedGuestroomRevenue__c = $lod4__EstimatedGuestroomRevenue__c;
      return $this;
    }

    /**
     * @return float
     */
    public function getLod4__EstimatedRate__c()
    {
      return $this->lod4__EstimatedRate__c;
    }

    /**
     * @param float $lod4__EstimatedRate__c
     * @return lod4__Inquiry__c
     */
    public function setLod4__EstimatedRate__c($lod4__EstimatedRate__c)
    {
      $this->lod4__EstimatedRate__c = $lod4__EstimatedRate__c;
      return $this;
    }

    /**
     * @return float
     */
    public function getLod4__EstimatedRental__c()
    {
      return $this->lod4__EstimatedRental__c;
    }

    /**
     * @param float $lod4__EstimatedRental__c
     * @return lod4__Inquiry__c
     */
    public function setLod4__EstimatedRental__c($lod4__EstimatedRental__c)
    {
      $this->lod4__EstimatedRental__c = $lod4__EstimatedRental__c;
      return $this;
    }

    /**
     * @return float
     */
    public function getLod4__EstimatedTotalRevenue__c()
    {
      return $this->lod4__EstimatedTotalRevenue__c;
    }

    /**
     * @param float $lod4__EstimatedTotalRevenue__c
     * @return lod4__Inquiry__c
     */
    public function setLod4__EstimatedTotalRevenue__c($lod4__EstimatedTotalRevenue__c)
    {
      $this->lod4__EstimatedTotalRevenue__c = $lod4__EstimatedTotalRevenue__c;
      return $this;
    }

    /**
     * @return date
     */
    public function getLod4__EventDate__c()
    {
      return $this->lod4__EventDate__c;
    }

    /**
     * @param date $lod4__EventDate__c
     * @return lod4__Inquiry__c
     */
    public function setLod4__EventDate__c($lod4__EventDate__c)
    {
      $this->lod4__EventDate__c = $lod4__EventDate__c;
      return $this;
    }

    /**
     * @return float
     */
    public function getLod4__EventDays__c()
    {
      return $this->lod4__EventDays__c;
    }

    /**
     * @param float $lod4__EventDays__c
     * @return lod4__Inquiry__c
     */
    public function setLod4__EventDays__c($lod4__EventDays__c)
    {
      $this->lod4__EventDays__c = $lod4__EventDays__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getLod4__ExternalId__c()
    {
      return $this->lod4__ExternalId__c;
    }

    /**
     * @param string $lod4__ExternalId__c
     * @return lod4__Inquiry__c
     */
    public function setLod4__ExternalId__c($lod4__ExternalId__c)
    {
      $this->lod4__ExternalId__c = $lod4__ExternalId__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getLod4__Fax__c()
    {
      return $this->lod4__Fax__c;
    }

    /**
     * @param string $lod4__Fax__c
     * @return lod4__Inquiry__c
     */
    public function setLod4__Fax__c($lod4__Fax__c)
    {
      $this->lod4__Fax__c = $lod4__Fax__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getLod4__Features__c()
    {
      return $this->lod4__Features__c;
    }

    /**
     * @param string $lod4__Features__c
     * @return lod4__Inquiry__c
     */
    public function setLod4__Features__c($lod4__Features__c)
    {
      $this->lod4__Features__c = $lod4__Features__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getLod4__FirstName__c()
    {
      return $this->lod4__FirstName__c;
    }

    /**
     * @param string $lod4__FirstName__c
     * @return lod4__Inquiry__c
     */
    public function setLod4__FirstName__c($lod4__FirstName__c)
    {
      $this->lod4__FirstName__c = $lod4__FirstName__c;
      return $this;
    }

    /**
     * @return float
     */
    public function getLod4__Guests__c()
    {
      return $this->lod4__Guests__c;
    }

    /**
     * @param float $lod4__Guests__c
     * @return lod4__Inquiry__c
     */
    public function setLod4__Guests__c($lod4__Guests__c)
    {
      $this->lod4__Guests__c = $lod4__Guests__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getLod4__IATANumber__c()
    {
      return $this->lod4__IATANumber__c;
    }

    /**
     * @param string $lod4__IATANumber__c
     * @return lod4__Inquiry__c
     */
    public function setLod4__IATANumber__c($lod4__IATANumber__c)
    {
      $this->lod4__IATANumber__c = $lod4__IATANumber__c;
      return $this;
    }

    /**
     * @return QueryResult
     */
    public function getLod4__InquiryHistory__r()
    {
      return $this->lod4__InquiryHistory__r;
    }

    /**
     * @param QueryResult $lod4__InquiryHistory__r
     * @return lod4__Inquiry__c
     */
    public function setLod4__InquiryHistory__r($lod4__InquiryHistory__r)
    {
      $this->lod4__InquiryHistory__r = $lod4__InquiryHistory__r;
      return $this;
    }

    /**
     * @return string
     */
    public function getLod4__InquirySource__c()
    {
      return $this->lod4__InquirySource__c;
    }

    /**
     * @param string $lod4__InquirySource__c
     * @return lod4__Inquiry__c
     */
    public function setLod4__InquirySource__c($lod4__InquirySource__c)
    {
      $this->lod4__InquirySource__c = $lod4__InquirySource__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getLod4__LastName__c()
    {
      return $this->lod4__LastName__c;
    }

    /**
     * @param string $lod4__LastName__c
     * @return lod4__Inquiry__c
     */
    public function setLod4__LastName__c($lod4__LastName__c)
    {
      $this->lod4__LastName__c = $lod4__LastName__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getLod4__LostBusinessReason__c()
    {
      return $this->lod4__LostBusinessReason__c;
    }

    /**
     * @param string $lod4__LostBusinessReason__c
     * @return lod4__Inquiry__c
     */
    public function setLod4__LostBusinessReason__c($lod4__LostBusinessReason__c)
    {
      $this->lod4__LostBusinessReason__c = $lod4__LostBusinessReason__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getLod4__MbRfpId__c()
    {
      return $this->lod4__MbRfpId__c;
    }

    /**
     * @param string $lod4__MbRfpId__c
     * @return lod4__Inquiry__c
     */
    public function setLod4__MbRfpId__c($lod4__MbRfpId__c)
    {
      $this->lod4__MbRfpId__c = $lod4__MbRfpId__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getLod4__MeetingType__c()
    {
      return $this->lod4__MeetingType__c;
    }

    /**
     * @param string $lod4__MeetingType__c
     * @return lod4__Inquiry__c
     */
    public function setLod4__MeetingType__c($lod4__MeetingType__c)
    {
      $this->lod4__MeetingType__c = $lod4__MeetingType__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getLod4__Milestone__c()
    {
      return $this->lod4__Milestone__c;
    }

    /**
     * @param string $lod4__Milestone__c
     * @return lod4__Inquiry__c
     */
    public function setLod4__Milestone__c($lod4__Milestone__c)
    {
      $this->lod4__Milestone__c = $lod4__Milestone__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getLod4__Mobile__c()
    {
      return $this->lod4__Mobile__c;
    }

    /**
     * @param string $lod4__Mobile__c
     * @return lod4__Inquiry__c
     */
    public function setLod4__Mobile__c($lod4__Mobile__c)
    {
      $this->lod4__Mobile__c = $lod4__Mobile__c;
      return $this;
    }

    /**
     * @return float
     */
    public function getLod4__Nights__c()
    {
      return $this->lod4__Nights__c;
    }

    /**
     * @param float $lod4__Nights__c
     * @return lod4__Inquiry__c
     */
    public function setLod4__Nights__c($lod4__Nights__c)
    {
      $this->lod4__Nights__c = $lod4__Nights__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getLod4__Other__c()
    {
      return $this->lod4__Other__c;
    }

    /**
     * @param string $lod4__Other__c
     * @return lod4__Inquiry__c
     */
    public function setLod4__Other__c($lod4__Other__c)
    {
      $this->lod4__Other__c = $lod4__Other__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getLod4__PhoneExtension__c()
    {
      return $this->lod4__PhoneExtension__c;
    }

    /**
     * @param string $lod4__PhoneExtension__c
     * @return lod4__Inquiry__c
     */
    public function setLod4__PhoneExtension__c($lod4__PhoneExtension__c)
    {
      $this->lod4__PhoneExtension__c = $lod4__PhoneExtension__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getLod4__Phone__c()
    {
      return $this->lod4__Phone__c;
    }

    /**
     * @param string $lod4__Phone__c
     * @return lod4__Inquiry__c
     */
    public function setLod4__Phone__c($lod4__Phone__c)
    {
      $this->lod4__Phone__c = $lod4__Phone__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getLod4__PostalCode__c()
    {
      return $this->lod4__PostalCode__c;
    }

    /**
     * @param string $lod4__PostalCode__c
     * @return lod4__Inquiry__c
     */
    public function setLod4__PostalCode__c($lod4__PostalCode__c)
    {
      $this->lod4__PostalCode__c = $lod4__PostalCode__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getLod4__PropertyInterest__c()
    {
      return $this->lod4__PropertyInterest__c;
    }

    /**
     * @param string $lod4__PropertyInterest__c
     * @return lod4__Inquiry__c
     */
    public function setLod4__PropertyInterest__c($lod4__PropertyInterest__c)
    {
      $this->lod4__PropertyInterest__c = $lod4__PropertyInterest__c;
      return $this;
    }

    /**
     * @return ID
     */
    public function getLod4__Property__c()
    {
      return $this->lod4__Property__c;
    }

    /**
     * @param ID $lod4__Property__c
     * @return lod4__Inquiry__c
     */
    public function setLod4__Property__c($lod4__Property__c)
    {
      $this->lod4__Property__c = $lod4__Property__c;
      return $this;
    }

    /**
     * @return lod4__Property__c
     */
    public function getLod4__Property__r()
    {
      return $this->lod4__Property__r;
    }

    /**
     * @param lod4__Property__c $lod4__Property__r
     * @return lod4__Inquiry__c
     */
    public function setLod4__Property__r($lod4__Property__r)
    {
      $this->lod4__Property__r = $lod4__Property__r;
      return $this;
    }

    /**
     * @return string
     */
    public function getLod4__Rating__c()
    {
      return $this->lod4__Rating__c;
    }

    /**
     * @param string $lod4__Rating__c
     * @return lod4__Inquiry__c
     */
    public function setLod4__Rating__c($lod4__Rating__c)
    {
      $this->lod4__Rating__c = $lod4__Rating__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getLod4__RfpStatus__c()
    {
      return $this->lod4__RfpStatus__c;
    }

    /**
     * @param string $lod4__RfpStatus__c
     * @return lod4__Inquiry__c
     */
    public function setLod4__RfpStatus__c($lod4__RfpStatus__c)
    {
      $this->lod4__RfpStatus__c = $lod4__RfpStatus__c;
      return $this;
    }

    /**
     * @return float
     */
    public function getLod4__Rooms__c()
    {
      return $this->lod4__Rooms__c;
    }

    /**
     * @param float $lod4__Rooms__c
     * @return lod4__Inquiry__c
     */
    public function setLod4__Rooms__c($lod4__Rooms__c)
    {
      $this->lod4__Rooms__c = $lod4__Rooms__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getLod4__Salutation__c()
    {
      return $this->lod4__Salutation__c;
    }

    /**
     * @param string $lod4__Salutation__c
     * @return lod4__Inquiry__c
     */
    public function setLod4__Salutation__c($lod4__Salutation__c)
    {
      $this->lod4__Salutation__c = $lod4__Salutation__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getLod4__Sender__c()
    {
      return $this->lod4__Sender__c;
    }

    /**
     * @param string $lod4__Sender__c
     * @return lod4__Inquiry__c
     */
    public function setLod4__Sender__c($lod4__Sender__c)
    {
      $this->lod4__Sender__c = $lod4__Sender__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getLod4__SendingLocation__c()
    {
      return $this->lod4__SendingLocation__c;
    }

    /**
     * @param string $lod4__SendingLocation__c
     * @return lod4__Inquiry__c
     */
    public function setLod4__SendingLocation__c($lod4__SendingLocation__c)
    {
      $this->lod4__SendingLocation__c = $lod4__SendingLocation__c;
      return $this;
    }

    /**
     * @return ID
     */
    public function getLod4__SourceSystem__c()
    {
      return $this->lod4__SourceSystem__c;
    }

    /**
     * @param ID $lod4__SourceSystem__c
     * @return lod4__Inquiry__c
     */
    public function setLod4__SourceSystem__c($lod4__SourceSystem__c)
    {
      $this->lod4__SourceSystem__c = $lod4__SourceSystem__c;
      return $this;
    }

    /**
     * @return lod4__System__c
     */
    public function getLod4__SourceSystem__r()
    {
      return $this->lod4__SourceSystem__r;
    }

    /**
     * @param lod4__System__c $lod4__SourceSystem__r
     * @return lod4__Inquiry__c
     */
    public function setLod4__SourceSystem__r($lod4__SourceSystem__r)
    {
      $this->lod4__SourceSystem__r = $lod4__SourceSystem__r;
      return $this;
    }

    /**
     * @return string
     */
    public function getLod4__State__c()
    {
      return $this->lod4__State__c;
    }

    /**
     * @param string $lod4__State__c
     * @return lod4__Inquiry__c
     */
    public function setLod4__State__c($lod4__State__c)
    {
      $this->lod4__State__c = $lod4__State__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getLod4__Status__c()
    {
      return $this->lod4__Status__c;
    }

    /**
     * @param string $lod4__Status__c
     * @return lod4__Inquiry__c
     */
    public function setLod4__Status__c($lod4__Status__c)
    {
      $this->lod4__Status__c = $lod4__Status__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getLod4__Street2__c()
    {
      return $this->lod4__Street2__c;
    }

    /**
     * @param string $lod4__Street2__c
     * @return lod4__Inquiry__c
     */
    public function setLod4__Street2__c($lod4__Street2__c)
    {
      $this->lod4__Street2__c = $lod4__Street2__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getLod4__Street3__c()
    {
      return $this->lod4__Street3__c;
    }

    /**
     * @param string $lod4__Street3__c
     * @return lod4__Inquiry__c
     */
    public function setLod4__Street3__c($lod4__Street3__c)
    {
      $this->lod4__Street3__c = $lod4__Street3__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getLod4__Street__c()
    {
      return $this->lod4__Street__c;
    }

    /**
     * @param string $lod4__Street__c
     * @return lod4__Inquiry__c
     */
    public function setLod4__Street__c($lod4__Street__c)
    {
      $this->lod4__Street__c = $lod4__Street__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getLod4__Suffix__c()
    {
      return $this->lod4__Suffix__c;
    }

    /**
     * @param string $lod4__Suffix__c
     * @return lod4__Inquiry__c
     */
    public function setLod4__Suffix__c($lod4__Suffix__c)
    {
      $this->lod4__Suffix__c = $lod4__Suffix__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getLod4__Title__c()
    {
      return $this->lod4__Title__c;
    }

    /**
     * @param string $lod4__Title__c
     * @return lod4__Inquiry__c
     */
    public function setLod4__Title__c($lod4__Title__c)
    {
      $this->lod4__Title__c = $lod4__Title__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getLod4__Type__c()
    {
      return $this->lod4__Type__c;
    }

    /**
     * @param string $lod4__Type__c
     * @return lod4__Inquiry__c
     */
    public function setLod4__Type__c($lod4__Type__c)
    {
      $this->lod4__Type__c = $lod4__Type__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getLod4__WebFormName__c()
    {
      return $this->lod4__WebFormName__c;
    }

    /**
     * @param string $lod4__WebFormName__c
     * @return lod4__Inquiry__c
     */
    public function setLod4__WebFormName__c($lod4__WebFormName__c)
    {
      $this->lod4__WebFormName__c = $lod4__WebFormName__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getLod4__Website__c()
    {
      return $this->lod4__Website__c;
    }

    /**
     * @param string $lod4__Website__c
     * @return lod4__Inquiry__c
     */
    public function setLod4__Website__c($lod4__Website__c)
    {
      $this->lod4__Website__c = $lod4__Website__c;
      return $this;
    }

}
