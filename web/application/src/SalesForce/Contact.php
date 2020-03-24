<?php

namespace Application\Src\SalesForce;

class Contact
{
    

    /**
     * @return QueryResult
     */
    public function getAcceptedEventRelations()
    {
      return $this->AcceptedEventRelations;
    }

    /**
     * @param QueryResult $AcceptedEventRelations
     * @return Contact
     */
    public function setAcceptedEventRelations($AcceptedEventRelations)
    {
      $this->AcceptedEventRelations = $AcceptedEventRelations;
      return $this;
    }

    /**
     * @return Account
     */
    public function getAccount()
    {
      return $this->Account;
    }

    /**
     * @param Account $Account
     * @return Contact
     */
    public function setAccount($Account)
    {
      $this->Account = $Account;
      return $this;
    }

    /**
     * @return QueryResult
     */
    public function getAccountContactRoles()
    {
      return $this->AccountContactRoles;
    }

    /**
     * @param QueryResult $AccountContactRoles
     * @return Contact
     */
    public function setAccountContactRoles($AccountContactRoles)
    {
      $this->AccountContactRoles = $AccountContactRoles;
      return $this;
    }

    /**
     * @return ID
     */
    public function getAccountId()
    {
      return $this->AccountId;
    }

    /**
     * @param ID $AccountId
     * @return Contact
     */
    public function setAccountId($AccountId)
    {
      $this->AccountId = $AccountId;
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
     * @return Contact
     */
    public function setActivityHistories($ActivityHistories)
    {
      $this->ActivityHistories = $ActivityHistories;
      return $this;
    }

    /**
     * @return date
     */
    public function getArrival_Date__c()
    {
      return $this->Arrival_Date__c;
    }

    /**
     * @param date $Arrival_Date__c
     * @return Contact
     */
    public function setArrival_Date__c($Arrival_Date__c)
    {
      $this->Arrival_Date__c = $Arrival_Date__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getArrival_Month__c()
    {
      return $this->Arrival_Month__c;
    }

    /**
     * @param string $Arrival_Month__c
     * @return Contact
     */
    public function setArrival_Month__c($Arrival_Month__c)
    {
      $this->Arrival_Month__c = $Arrival_Month__c;
      return $this;
    }

    /**
     * @return QueryResult
     */
    public function getAssets()
    {
      return $this->Assets;
    }

    /**
     * @param QueryResult $Assets
     * @return Contact
     */
    public function setAssets($Assets)
    {
      $this->Assets = $Assets;
      return $this;
    }

    /**
     * @return string
     */
    public function getAssistantName()
    {
      return $this->AssistantName;
    }

    /**
     * @param string $AssistantName
     * @return Contact
     */
    public function setAssistantName($AssistantName)
    {
      $this->AssistantName = $AssistantName;
      return $this;
    }

    /**
     * @return string
     */
    public function getAssistantPhone()
    {
      return $this->AssistantPhone;
    }

    /**
     * @param string $AssistantPhone
     * @return Contact
     */
    public function setAssistantPhone($AssistantPhone)
    {
      $this->AssistantPhone = $AssistantPhone;
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
     * @return Contact
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
     * @return Contact
     */
    public function setAttachments($Attachments)
    {
      $this->Attachments = $Attachments;
      return $this;
    }

    /**
     * @return date
     */
    public function getBirthdate()
    {
      return $this->Birthdate;
    }

    /**
     * @param date $Birthdate
     * @return Contact
     */
    public function setBirthdate($Birthdate)
    {
      $this->Birthdate = $Birthdate;
      return $this;
    }

    /**
     * @return QueryResult
     */
    public function getBookings__r()
    {
      return $this->Bookings__r;
    }

    /**
     * @param QueryResult $Bookings__r
     * @return Contact
     */
    public function setBookings__r($Bookings__r)
    {
      $this->Bookings__r = $Bookings__r;
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
     * @return Contact
     */
    public function setBrowser__c($Browser__c)
    {
      $this->Browser__c = $Browser__c;
      return $this;
    }

    /**
     * @return QueryResult
     */
    public function getCampaignMembers()
    {
      return $this->CampaignMembers;
    }

    /**
     * @param QueryResult $CampaignMembers
     * @return Contact
     */
    public function setCampaignMembers($CampaignMembers)
    {
      $this->CampaignMembers = $CampaignMembers;
      return $this;
    }

    /**
     * @return QueryResult
     */
    public function getCaseContactRoles()
    {
      return $this->CaseContactRoles;
    }

    /**
     * @param QueryResult $CaseContactRoles
     * @return Contact
     */
    public function setCaseContactRoles($CaseContactRoles)
    {
      $this->CaseContactRoles = $CaseContactRoles;
      return $this;
    }

    /**
     * @return QueryResult
     */
    public function getCases()
    {
      return $this->Cases;
    }

    /**
     * @param QueryResult $Cases
     * @return Contact
     */
    public function setCases($Cases)
    {
      $this->Cases = $Cases;
      return $this;
    }

    /**
     * @return boolean
     */
    public function getCleaned__c()
    {
      return $this->Cleaned__c;
    }

    /**
     * @param boolean $Cleaned__c
     * @return Contact
     */
    public function setCleaned__c($Cleaned__c)
    {
      $this->Cleaned__c = $Cleaned__c;
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
     * @return Contact
     */
    public function setCombinedAttachments($CombinedAttachments)
    {
      $this->CombinedAttachments = $CombinedAttachments;
      return $this;
    }

    /**
     * @return string
     */
    public function getContact_Notes__c()
    {
      return $this->Contact_Notes__c;
    }

    /**
     * @param string $Contact_Notes__c
     * @return Contact
     */
    public function setContact_Notes__c($Contact_Notes__c)
    {
      $this->Contact_Notes__c = $Contact_Notes__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getContact_Type__c()
    {
      return $this->Contact_Type__c;
    }

    /**
     * @param string $Contact_Type__c
     * @return Contact
     */
    public function setContact_Type__c($Contact_Type__c)
    {
      $this->Contact_Type__c = $Contact_Type__c;
      return $this;
    }

    /**
     * @return QueryResult
     */
    public function getContractContactRoles()
    {
      return $this->ContractContactRoles;
    }

    /**
     * @param QueryResult $ContractContactRoles
     * @return Contact
     */
    public function setContractContactRoles($ContractContactRoles)
    {
      $this->ContractContactRoles = $ContractContactRoles;
      return $this;
    }

    /**
     * @return QueryResult
     */
    public function getContractsSigned()
    {
      return $this->ContractsSigned;
    }

    /**
     * @param QueryResult $ContractsSigned
     * @return Contact
     */
    public function setContractsSigned($ContractsSigned)
    {
      $this->ContractsSigned = $ContractsSigned;
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
     * @return Contact
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
     * @return Contact
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
     * @return Contact
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
     * @return Contact
     */
    public function setCurrencyIsoCode($CurrencyIsoCode)
    {
      $this->CurrencyIsoCode = $CurrencyIsoCode;
      return $this;
    }

    /**
     * @return string
     */
    public function getCurrent_Lead_Source__c()
    {
      return $this->Current_Lead_Source__c;
    }

    /**
     * @param string $Current_Lead_Source__c
     * @return Contact
     */
    public function setCurrent_Lead_Source__c($Current_Lead_Source__c)
    {
      $this->Current_Lead_Source__c = $Current_Lead_Source__c;
      return $this;
    }

    /**
     * @return QueryResult
     */
    public function getDeclinedEventRelations()
    {
      return $this->DeclinedEventRelations;
    }

    /**
     * @param QueryResult $DeclinedEventRelations
     * @return Contact
     */
    public function setDeclinedEventRelations($DeclinedEventRelations)
    {
      $this->DeclinedEventRelations = $DeclinedEventRelations;
      return $this;
    }

    /**
     * @return string
     */
    public function getDepartment()
    {
      return $this->Department;
    }

    /**
     * @param string $Department
     * @return Contact
     */
    public function setDepartment($Department)
    {
      $this->Department = $Department;
      return $this;
    }

    /**
     * @return date
     */
    public function getDeparture_Date__c()
    {
      return $this->Departure_Date__c;
    }

    /**
     * @param date $Departure_Date__c
     * @return Contact
     */
    public function setDeparture_Date__c($Departure_Date__c)
    {
      $this->Departure_Date__c = $Departure_Date__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getDescription()
    {
      return $this->Description;
    }

    /**
     * @param string $Description
     * @return Contact
     */
    public function setDescription($Description)
    {
      $this->Description = $Description;
      return $this;
    }

    /**
     * @return boolean
     */
    public function getDoNotCall()
    {
      return $this->DoNotCall;
    }

    /**
     * @param boolean $DoNotCall
     * @return Contact
     */
    public function setDoNotCall($DoNotCall)
    {
      $this->DoNotCall = $DoNotCall;
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
     * @return Contact
     */
    public function setDuplicateRecordItems($DuplicateRecordItems)
    {
      $this->DuplicateRecordItems = $DuplicateRecordItems;
      return $this;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
      return $this->Email;
    }

    /**
     * @param string $Email
     * @return Contact
     */
    public function setEmail($Email)
    {
      $this->Email = $Email;
      return $this;
    }

    /**
     * @return \DateTime
     */
    public function getEmailBouncedDate()
    {
      if ($this->EmailBouncedDate == null) {
        return null;
      } else {
        try {
          return new \DateTime($this->EmailBouncedDate);
        } catch (\Exception $e) {
          return false;
        }
      }
    }

    /**
     * @param \DateTime $EmailBouncedDate
     * @return Contact
     */
    public function setEmailBouncedDate(\DateTime $EmailBouncedDate = null)
    {
      if ($EmailBouncedDate == null) {
       $this->EmailBouncedDate = null;
      } else {
        $this->EmailBouncedDate = $EmailBouncedDate->format(\DateTime::ATOM);
      }
      return $this;
    }

    /**
     * @return string
     */
    public function getEmailBouncedReason()
    {
      return $this->EmailBouncedReason;
    }

    /**
     * @param string $EmailBouncedReason
     * @return Contact
     */
    public function setEmailBouncedReason($EmailBouncedReason)
    {
      $this->EmailBouncedReason = $EmailBouncedReason;
      return $this;
    }

    /**
     * @return QueryResult
     */
    public function getEmailMessageRelations()
    {
      return $this->EmailMessageRelations;
    }

    /**
     * @param QueryResult $EmailMessageRelations
     * @return Contact
     */
    public function setEmailMessageRelations($EmailMessageRelations)
    {
      $this->EmailMessageRelations = $EmailMessageRelations;
      return $this;
    }

    /**
     * @return QueryResult
     */
    public function getEmailStatuses()
    {
      return $this->EmailStatuses;
    }

    /**
     * @param QueryResult $EmailStatuses
     * @return Contact
     */
    public function setEmailStatuses($EmailStatuses)
    {
      $this->EmailStatuses = $EmailStatuses;
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
     * @return Contact
     */
    public function setEmail_2__c($Email_2__c)
    {
      $this->Email_2__c = $Email_2__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getEmail_Lists__c()
    {
      return $this->Email_Lists__c;
    }

    /**
     * @param string $Email_Lists__c
     * @return Contact
     */
    public function setEmail_Lists__c($Email_Lists__c)
    {
      $this->Email_Lists__c = $Email_Lists__c;
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
     * @return Contact
     */
    public function setEventRelations($EventRelations)
    {
      $this->EventRelations = $EventRelations;
      return $this;
    }

    /**
     * @return QueryResult
     */
    public function getEventWhoRelations()
    {
      return $this->EventWhoRelations;
    }

    /**
     * @param QueryResult $EventWhoRelations
     * @return Contact
     */
    public function setEventWhoRelations($EventWhoRelations)
    {
      $this->EventWhoRelations = $EventWhoRelations;
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
     * @return Contact
     */
    public function setEvents($Events)
    {
      $this->Events = $Events;
      return $this;
    }

    /**
     * @return string
     */
    public function getFacebook_Search__c()
    {
      return $this->Facebook_Search__c;
    }

    /**
     * @param string $Facebook_Search__c
     * @return Contact
     */
    public function setFacebook_Search__c($Facebook_Search__c)
    {
      $this->Facebook_Search__c = $Facebook_Search__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getFacebook_URL__c()
    {
      return $this->Facebook_URL__c;
    }

    /**
     * @param string $Facebook_URL__c
     * @return Contact
     */
    public function setFacebook_URL__c($Facebook_URL__c)
    {
      $this->Facebook_URL__c = $Facebook_URL__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getFax()
    {
      return $this->Fax;
    }

    /**
     * @param string $Fax
     * @return Contact
     */
    public function setFax($Fax)
    {
      $this->Fax = $Fax;
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
     * @return Contact
     */
    public function setFeedSubscriptionsForEntity($FeedSubscriptionsForEntity)
    {
      $this->FeedSubscriptionsForEntity = $FeedSubscriptionsForEntity;
      return $this;
    }

    /**
     * @return QueryResult
     */
    public function getFeeds()
    {
      return $this->Feeds;
    }

    /**
     * @param QueryResult $Feeds
     * @return Contact
     */
    public function setFeeds($Feeds)
    {
      $this->Feeds = $Feeds;
      return $this;
    }

    /**
     * @return string
     */
    public function getFirstName()
    {
      return $this->FirstName;
    }

    /**
     * @param string $FirstName
     * @return Contact
     */
    public function setFirstName($FirstName)
    {
      $this->FirstName = $FirstName;
      return $this;
    }

    /**
     * @return string
     */
    public function getGoogle_Image_Search__c()
    {
      return $this->Google_Image_Search__c;
    }

    /**
     * @param string $Google_Image_Search__c
     * @return Contact
     */
    public function setGoogle_Image_Search__c($Google_Image_Search__c)
    {
      $this->Google_Image_Search__c = $Google_Image_Search__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getGoogle_This_Contact__c()
    {
      return $this->Google_This_Contact__c;
    }

    /**
     * @param string $Google_This_Contact__c
     * @return Contact
     */
    public function setGoogle_This_Contact__c($Google_This_Contact__c)
    {
      $this->Google_This_Contact__c = $Google_This_Contact__c;
      return $this;
    }

    /**
     * @return boolean
     */
    public function getGroup_Leader__c()
    {
      return $this->Group_Leader__c;
    }

    /**
     * @param boolean $Group_Leader__c
     * @return Contact
     */
    public function setGroup_Leader__c($Group_Leader__c)
    {
      $this->Group_Leader__c = $Group_Leader__c;
      return $this;
    }

    /**
     * @return boolean
     */
    public function getHasOptedOutOfEmail()
    {
      return $this->HasOptedOutOfEmail;
    }

    /**
     * @param boolean $HasOptedOutOfEmail
     * @return Contact
     */
    public function setHasOptedOutOfEmail($HasOptedOutOfEmail)
    {
      $this->HasOptedOutOfEmail = $HasOptedOutOfEmail;
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
     * @return Contact
     */
    public function setHistories($Histories)
    {
      $this->Histories = $Histories;
      return $this;
    }

    /**
     * @return string
     */
    public function getHomePhone()
    {
      return $this->HomePhone;
    }

    /**
     * @param string $HomePhone
     * @return Contact
     */
    public function setHomePhone($HomePhone)
    {
      $this->HomePhone = $HomePhone;
      return $this;
    }

    /**
     * @return boolean
     */
    public function getImported__c()
    {
      return $this->Imported__c;
    }

    /**
     * @param boolean $Imported__c
     * @return Contact
     */
    public function setImported__c($Imported__c)
    {
      $this->Imported__c = $Imported__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getInquiry_Record_Type__c()
    {
      return $this->Inquiry_Record_Type__c;
    }

    /**
     * @param string $Inquiry_Record_Type__c
     * @return Contact
     */
    public function setInquiry_Record_Type__c($Inquiry_Record_Type__c)
    {
      $this->Inquiry_Record_Type__c = $Inquiry_Record_Type__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getInquiry_Status__c()
    {
      return $this->Inquiry_Status__c;
    }

    /**
     * @param string $Inquiry_Status__c
     * @return Contact
     */
    public function setInquiry_Status__c($Inquiry_Status__c)
    {
      $this->Inquiry_Status__c = $Inquiry_Status__c;
      return $this;
    }

    /**
     * @return ID
     */
    public function getInquiry__c()
    {
      return $this->Inquiry__c;
    }

    /**
     * @param ID $Inquiry__c
     * @return Contact
     */
    public function setInquiry__c($Inquiry__c)
    {
      $this->Inquiry__c = $Inquiry__c;
      return $this;
    }

    /**
     * @return lod4__Inquiry__c
     */
    public function getInquiry__r()
    {
      return $this->Inquiry__r;
    }

    /**
     * @param lod4__Inquiry__c $Inquiry__r
     * @return Contact
     */
    public function setInquiry__r($Inquiry__r)
    {
      $this->Inquiry__r = $Inquiry__r;
      return $this;
    }

    /**
     * @return string
     */
    public function getInterested_in__c()
    {
      return $this->Interested_in__c;
    }

    /**
     * @param string $Interested_in__c
     * @return Contact
     */
    public function setInterested_in__c($Interested_in__c)
    {
      $this->Interested_in__c = $Interested_in__c;
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
     * @return Contact
     */
    public function setIsDeleted($IsDeleted)
    {
      $this->IsDeleted = $IsDeleted;
      return $this;
    }

    /**
     * @return boolean
     */
    public function getIsEmailBounced()
    {
      return $this->IsEmailBounced;
    }

    /**
     * @param boolean $IsEmailBounced
     * @return Contact
     */
    public function setIsEmailBounced($IsEmailBounced)
    {
      $this->IsEmailBounced = $IsEmailBounced;
      return $this;
    }

    /**
     * @return string
     */
    public function getJigsaw()
    {
      return $this->Jigsaw;
    }

    /**
     * @param string $Jigsaw
     * @return Contact
     */
    public function setJigsaw($Jigsaw)
    {
      $this->Jigsaw = $Jigsaw;
      return $this;
    }

    /**
     * @return string
     */
    public function getJigsawContactId()
    {
      return $this->JigsawContactId;
    }

    /**
     * @param string $JigsawContactId
     * @return Contact
     */
    public function setJigsawContactId($JigsawContactId)
    {
      $this->JigsawContactId = $JigsawContactId;
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
     * @return Contact
     */
    public function setLastActivityDate($LastActivityDate)
    {
      $this->LastActivityDate = $LastActivityDate;
      return $this;
    }

    /**
     * @return \DateTime
     */
    public function getLastCURequestDate()
    {
      if ($this->LastCURequestDate == null) {
        return null;
      } else {
        try {
          return new \DateTime($this->LastCURequestDate);
        } catch (\Exception $e) {
          return false;
        }
      }
    }

    /**
     * @param \DateTime $LastCURequestDate
     * @return Contact
     */
    public function setLastCURequestDate(\DateTime $LastCURequestDate = null)
    {
      if ($LastCURequestDate == null) {
       $this->LastCURequestDate = null;
      } else {
        $this->LastCURequestDate = $LastCURequestDate->format(\DateTime::ATOM);
      }
      return $this;
    }

    /**
     * @return \DateTime
     */
    public function getLastCUUpdateDate()
    {
      if ($this->LastCUUpdateDate == null) {
        return null;
      } else {
        try {
          return new \DateTime($this->LastCUUpdateDate);
        } catch (\Exception $e) {
          return false;
        }
      }
    }

    /**
     * @param \DateTime $LastCUUpdateDate
     * @return Contact
     */
    public function setLastCUUpdateDate(\DateTime $LastCUUpdateDate = null)
    {
      if ($LastCUUpdateDate == null) {
       $this->LastCUUpdateDate = null;
      } else {
        $this->LastCUUpdateDate = $LastCUUpdateDate->format(\DateTime::ATOM);
      }
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
     * @return Contact
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
     * @return Contact
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
     * @return Contact
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
     * @return string
     */
    public function getLastName()
    {
      return $this->LastName;
    }

    /**
     * @param string $LastName
     * @return Contact
     */
    public function setLastName($LastName)
    {
      $this->LastName = $LastName;
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
     * @return Contact
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
     * @return Contact
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
     * @return string
     */
    public function getLatest_Golf_Group__c()
    {
      return $this->Latest_Golf_Group__c;
    }

    /**
     * @param string $Latest_Golf_Group__c
     * @return Contact
     */
    public function setLatest_Golf_Group__c($Latest_Golf_Group__c)
    {
      $this->Latest_Golf_Group__c = $Latest_Golf_Group__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getLeadSource()
    {
      return $this->LeadSource;
    }

    /**
     * @param string $LeadSource
     * @return Contact
     */
    public function setLeadSource($LeadSource)
    {
      $this->LeadSource = $LeadSource;
      return $this;
    }

    /**
     * @return string
     */
    public function getLinkedInSearch__c()
    {
      return $this->LinkedInSearch__c;
    }

    /**
     * @param string $LinkedInSearch__c
     * @return Contact
     */
    public function setLinkedInSearch__c($LinkedInSearch__c)
    {
      $this->LinkedInSearch__c = $LinkedInSearch__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getLinkedIn_URL__c()
    {
      return $this->LinkedIn_URL__c;
    }

    /**
     * @param string $LinkedIn_URL__c
     * @return Contact
     */
    public function setLinkedIn_URL__c($LinkedIn_URL__c)
    {
      $this->LinkedIn_URL__c = $LinkedIn_URL__c;
      return $this;
    }

    /**
     * @return boolean
     */
    public function getList_Banana__c()
    {
      return $this->List_Banana__c;
    }

    /**
     * @param boolean $List_Banana__c
     * @return Contact
     */
    public function setList_Banana__c($List_Banana__c)
    {
      $this->List_Banana__c = $List_Banana__c;
      return $this;
    }

    /**
     * @return boolean
     */
    public function getList_Golf__c()
    {
      return $this->List_Golf__c;
    }

    /**
     * @param boolean $List_Golf__c
     * @return Contact
     */
    public function setList_Golf__c($List_Golf__c)
    {
      $this->List_Golf__c = $List_Golf__c;
      return $this;
    }

    /**
     * @return boolean
     */
    public function getList_Group_Golf__c()
    {
      return $this->List_Group_Golf__c;
    }

    /**
     * @param boolean $List_Group_Golf__c
     * @return Contact
     */
    public function setList_Group_Golf__c($List_Group_Golf__c)
    {
      $this->List_Group_Golf__c = $List_Group_Golf__c;
      return $this;
    }

    /**
     * @return boolean
     */
    public function getList_HHO_Adventure_Club__c()
    {
      return $this->List_HHO_Adventure_Club__c;
    }

    /**
     * @param boolean $List_HHO_Adventure_Club__c
     * @return Contact
     */
    public function setList_HHO_Adventure_Club__c($List_HHO_Adventure_Club__c)
    {
      $this->List_HHO_Adventure_Club__c = $List_HHO_Adventure_Club__c;
      return $this;
    }

    /**
     * @return boolean
     */
    public function getList_Locals__c()
    {
      return $this->List_Locals__c;
    }

    /**
     * @param boolean $List_Locals__c
     * @return Contact
     */
    public function setList_Locals__c($List_Locals__c)
    {
      $this->List_Locals__c = $List_Locals__c;
      return $this;
    }

    /**
     * @return boolean
     */
    public function getList_Monthly_Golf_Pass__c()
    {
      return $this->List_Monthly_Golf_Pass__c;
    }

    /**
     * @param boolean $List_Monthly_Golf_Pass__c
     * @return Contact
     */
    public function setList_Monthly_Golf_Pass__c($List_Monthly_Golf_Pass__c)
    {
      $this->List_Monthly_Golf_Pass__c = $List_Monthly_Golf_Pass__c;
      return $this;
    }

    /**
     * @return boolean
     */
    public function getList_Owners__c()
    {
      return $this->List_Owners__c;
    }

    /**
     * @param boolean $List_Owners__c
     * @return Contact
     */
    public function setList_Owners__c($List_Owners__c)
    {
      $this->List_Owners__c = $List_Owners__c;
      return $this;
    }

    /**
     * @return boolean
     */
    public function getList_Property_Management__c()
    {
      return $this->List_Property_Management__c;
    }

    /**
     * @param boolean $List_Property_Management__c
     * @return Contact
     */
    public function setList_Property_Management__c($List_Property_Management__c)
    {
      $this->List_Property_Management__c = $List_Property_Management__c;
      return $this;
    }

    /**
     * @return boolean
     */
    public function getList_Resort_Newsletter__c()
    {
      return $this->List_Resort_Newsletter__c;
    }

    /**
     * @param boolean $List_Resort_Newsletter__c
     * @return Contact
     */
    public function setList_Resort_Newsletter__c($List_Resort_Newsletter__c)
    {
      $this->List_Resort_Newsletter__c = $List_Resort_Newsletter__c;
      return $this;
    }

    /**
     * @return boolean
     */
    public function getList_SCM_Slip_Rentals__c()
    {
      return $this->List_SCM_Slip_Rentals__c;
    }

    /**
     * @param boolean $List_SCM_Slip_Rentals__c
     * @return Contact
     */
    public function setList_SCM_Slip_Rentals__c($List_SCM_Slip_Rentals__c)
    {
      $this->List_SCM_Slip_Rentals__c = $List_SCM_Slip_Rentals__c;
      return $this;
    }

    /**
     * @return boolean
     */
    public function getList_SC_Harbor__c()
    {
      return $this->List_SC_Harbor__c;
    }

    /**
     * @param boolean $List_SC_Harbor__c
     * @return Contact
     */
    public function setList_SC_Harbor__c($List_SC_Harbor__c)
    {
      $this->List_SC_Harbor__c = $List_SC_Harbor__c;
      return $this;
    }

    /**
     * @return boolean
     */
    public function getList_Special_Offers__c()
    {
      return $this->List_Special_Offers__c;
    }

    /**
     * @param boolean $List_Special_Offers__c
     * @return Contact
     */
    public function setList_Special_Offers__c($List_Special_Offers__c)
    {
      $this->List_Special_Offers__c = $List_Special_Offers__c;
      return $this;
    }

    /**
     * @return boolean
     */
    public function getList_Tennis__c()
    {
      return $this->List_Tennis__c;
    }

    /**
     * @param boolean $List_Tennis__c
     * @return Contact
     */
    public function setList_Tennis__c($List_Tennis__c)
    {
      $this->List_Tennis__c = $List_Tennis__c;
      return $this;
    }

    /**
     * @return boolean
     */
    public function getList_Weddings__c()
    {
      return $this->List_Weddings__c;
    }

    /**
     * @param boolean $List_Weddings__c
     * @return Contact
     */
    public function setList_Weddings__c($List_Weddings__c)
    {
      $this->List_Weddings__c = $List_Weddings__c;
      return $this;
    }

    /**
     * @return boolean
     */
    public function getList_social__c()
    {
      return $this->List_social__c;
    }

    /**
     * @param boolean $List_social__c
     * @return Contact
     */
    public function setList_social__c($List_social__c)
    {
      $this->List_social__c = $List_social__c;
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
     * @return Contact
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
     * @return Contact
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
     * @return Contact
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
     * @return Contact
     */
    public function setLost_Reason__c($Lost_Reason__c)
    {
      $this->Lost_Reason__c = $Lost_Reason__c;
      return $this;
    }

    /**
     * @return address
     */
    public function getMailingAddress()
    {
      return $this->MailingAddress;
    }

    /**
     * @param address $MailingAddress
     * @return Contact
     */
    public function setMailingAddress($MailingAddress)
    {
      $this->MailingAddress = $MailingAddress;
      return $this;
    }

    /**
     * @return string
     */
    public function getMailingCity()
    {
      return $this->MailingCity;
    }

    /**
     * @param string $MailingCity
     * @return Contact
     */
    public function setMailingCity($MailingCity)
    {
      $this->MailingCity = $MailingCity;
      return $this;
    }

    /**
     * @return string
     */
    public function getMailingCountry()
    {
      return $this->MailingCountry;
    }

    /**
     * @param string $MailingCountry
     * @return Contact
     */
    public function setMailingCountry($MailingCountry)
    {
      $this->MailingCountry = $MailingCountry;
      return $this;
    }

    /**
     * @return string
     */
    public function getMailingGeocodeAccuracy()
    {
      return $this->MailingGeocodeAccuracy;
    }

    /**
     * @param string $MailingGeocodeAccuracy
     * @return Contact
     */
    public function setMailingGeocodeAccuracy($MailingGeocodeAccuracy)
    {
      $this->MailingGeocodeAccuracy = $MailingGeocodeAccuracy;
      return $this;
    }

    /**
     * @return float
     */
    public function getMailingLatitude()
    {
      return $this->MailingLatitude;
    }

    /**
     * @param float $MailingLatitude
     * @return Contact
     */
    public function setMailingLatitude($MailingLatitude)
    {
      $this->MailingLatitude = $MailingLatitude;
      return $this;
    }

    /**
     * @return float
     */
    public function getMailingLongitude()
    {
      return $this->MailingLongitude;
    }

    /**
     * @param float $MailingLongitude
     * @return Contact
     */
    public function setMailingLongitude($MailingLongitude)
    {
      $this->MailingLongitude = $MailingLongitude;
      return $this;
    }

    /**
     * @return string
     */
    public function getMailingPostalCode()
    {
      return $this->MailingPostalCode;
    }

    /**
     * @param string $MailingPostalCode
     * @return Contact
     */
    public function setMailingPostalCode($MailingPostalCode)
    {
      $this->MailingPostalCode = $MailingPostalCode;
      return $this;
    }

    /**
     * @return string
     */
    public function getMailingState()
    {
      return $this->MailingState;
    }

    /**
     * @param string $MailingState
     * @return Contact
     */
    public function setMailingState($MailingState)
    {
      $this->MailingState = $MailingState;
      return $this;
    }

    /**
     * @return string
     */
    public function getMailingStreet()
    {
      return $this->MailingStreet;
    }

    /**
     * @param string $MailingStreet
     * @return Contact
     */
    public function setMailingStreet($MailingStreet)
    {
      $this->MailingStreet = $MailingStreet;
      return $this;
    }

    /**
     * @return Contact
     */
    public function getMasterRecord()
    {
      return $this->MasterRecord;
    }

    /**
     * @param Contact $MasterRecord
     * @return Contact
     */
    public function setMasterRecord($MasterRecord)
    {
      $this->MasterRecord = $MasterRecord;
      return $this;
    }

    /**
     * @return ID
     */
    public function getMasterRecordId()
    {
      return $this->MasterRecordId;
    }

    /**
     * @param ID $MasterRecordId
     * @return Contact
     */
    public function setMasterRecordId($MasterRecordId)
    {
      $this->MasterRecordId = $MasterRecordId;
      return $this;
    }

    /**
     * @return string
     */
    public function getMobilePhone()
    {
      return $this->MobilePhone;
    }

    /**
     * @param string $MobilePhone
     * @return Contact
     */
    public function setMobilePhone($MobilePhone)
    {
      $this->MobilePhone = $MobilePhone;
      return $this;
    }

    /**
     * @return boolean
     */
    public function getMonthly_Golf_Membership_Active__c()
    {
      return $this->Monthly_Golf_Membership_Active__c;
    }

    /**
     * @param boolean $Monthly_Golf_Membership_Active__c
     * @return Contact
     */
    public function setMonthly_Golf_Membership_Active__c($Monthly_Golf_Membership_Active__c)
    {
      $this->Monthly_Golf_Membership_Active__c = $Monthly_Golf_Membership_Active__c;
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
     * @return Contact
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
     * @return Contact
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
     * @return Contact
     */
    public function setNotesAndAttachments($NotesAndAttachments)
    {
      $this->NotesAndAttachments = $NotesAndAttachments;
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
     * @return Contact
     */
    public function setNumber_of_Players__c($Number_of_Players__c)
    {
      $this->Number_of_Players__c = $Number_of_Players__c;
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
     * @return Contact
     */
    public function setOpenActivities($OpenActivities)
    {
      $this->OpenActivities = $OpenActivities;
      return $this;
    }

    /**
     * @return QueryResult
     */
    public function getOpportunities()
    {
      return $this->Opportunities;
    }

    /**
     * @param QueryResult $Opportunities
     * @return Contact
     */
    public function setOpportunities($Opportunities)
    {
      $this->Opportunities = $Opportunities;
      return $this;
    }

    /**
     * @return QueryResult
     */
    public function getOpportunityContactRoles()
    {
      return $this->OpportunityContactRoles;
    }

    /**
     * @param QueryResult $OpportunityContactRoles
     * @return Contact
     */
    public function setOpportunityContactRoles($OpportunityContactRoles)
    {
      $this->OpportunityContactRoles = $OpportunityContactRoles;
      return $this;
    }

    /**
     * @return boolean
     */
    public function getOpt_In__c()
    {
      return $this->Opt_In__c;
    }

    /**
     * @param boolean $Opt_In__c
     * @return Contact
     */
    public function setOpt_In__c($Opt_In__c)
    {
      $this->Opt_In__c = $Opt_In__c;
      return $this;
    }

    /**
     * @return address
     */
    public function getOtherAddress()
    {
      return $this->OtherAddress;
    }

    /**
     * @param address $OtherAddress
     * @return Contact
     */
    public function setOtherAddress($OtherAddress)
    {
      $this->OtherAddress = $OtherAddress;
      return $this;
    }

    /**
     * @return string
     */
    public function getOtherCity()
    {
      return $this->OtherCity;
    }

    /**
     * @param string $OtherCity
     * @return Contact
     */
    public function setOtherCity($OtherCity)
    {
      $this->OtherCity = $OtherCity;
      return $this;
    }

    /**
     * @return string
     */
    public function getOtherCountry()
    {
      return $this->OtherCountry;
    }

    /**
     * @param string $OtherCountry
     * @return Contact
     */
    public function setOtherCountry($OtherCountry)
    {
      $this->OtherCountry = $OtherCountry;
      return $this;
    }

    /**
     * @return string
     */
    public function getOtherGeocodeAccuracy()
    {
      return $this->OtherGeocodeAccuracy;
    }

    /**
     * @param string $OtherGeocodeAccuracy
     * @return Contact
     */
    public function setOtherGeocodeAccuracy($OtherGeocodeAccuracy)
    {
      $this->OtherGeocodeAccuracy = $OtherGeocodeAccuracy;
      return $this;
    }

    /**
     * @return float
     */
    public function getOtherLatitude()
    {
      return $this->OtherLatitude;
    }

    /**
     * @param float $OtherLatitude
     * @return Contact
     */
    public function setOtherLatitude($OtherLatitude)
    {
      $this->OtherLatitude = $OtherLatitude;
      return $this;
    }

    /**
     * @return float
     */
    public function getOtherLongitude()
    {
      return $this->OtherLongitude;
    }

    /**
     * @param float $OtherLongitude
     * @return Contact
     */
    public function setOtherLongitude($OtherLongitude)
    {
      $this->OtherLongitude = $OtherLongitude;
      return $this;
    }

    /**
     * @return string
     */
    public function getOtherPhone()
    {
      return $this->OtherPhone;
    }

    /**
     * @param string $OtherPhone
     * @return Contact
     */
    public function setOtherPhone($OtherPhone)
    {
      $this->OtherPhone = $OtherPhone;
      return $this;
    }

    /**
     * @return string
     */
    public function getOtherPostalCode()
    {
      return $this->OtherPostalCode;
    }

    /**
     * @param string $OtherPostalCode
     * @return Contact
     */
    public function setOtherPostalCode($OtherPostalCode)
    {
      $this->OtherPostalCode = $OtherPostalCode;
      return $this;
    }

    /**
     * @return string
     */
    public function getOtherState()
    {
      return $this->OtherState;
    }

    /**
     * @param string $OtherState
     * @return Contact
     */
    public function setOtherState($OtherState)
    {
      $this->OtherState = $OtherState;
      return $this;
    }

    /**
     * @return string
     */
    public function getOtherStreet()
    {
      return $this->OtherStreet;
    }

    /**
     * @param string $OtherStreet
     * @return Contact
     */
    public function setOtherStreet($OtherStreet)
    {
      $this->OtherStreet = $OtherStreet;
      return $this;
    }

    /**
     * @return User
     */
    public function getOwner()
    {
      return $this->Owner;
    }

    /**
     * @param User $Owner
     * @return Contact
     */
    public function setOwner($Owner)
    {
      $this->Owner = $Owner;
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
     * @return Contact
     */
    public function setOwnerId($OwnerId)
    {
      $this->OwnerId = $OwnerId;
      return $this;
    }

    /**
     * @return string
     */
    public function getPartner_Spouse_Name__c()
    {
      return $this->Partner_Spouse_Name__c;
    }

    /**
     * @param string $Partner_Spouse_Name__c
     * @return Contact
     */
    public function setPartner_Spouse_Name__c($Partner_Spouse_Name__c)
    {
      $this->Partner_Spouse_Name__c = $Partner_Spouse_Name__c;
      return $this;
    }

    /**
     * @return QueryResult
     */
    public function getPersonas()
    {
      return $this->Personas;
    }

    /**
     * @param QueryResult $Personas
     * @return Contact
     */
    public function setPersonas($Personas)
    {
      $this->Personas = $Personas;
      return $this;
    }

    /**
     * @return string
     */
    public function getPhone()
    {
      return $this->Phone;
    }

    /**
     * @param string $Phone
     * @return Contact
     */
    public function setPhone($Phone)
    {
      $this->Phone = $Phone;
      return $this;
    }

    /**
     * @return string
     */
    public function getPhotoUrl()
    {
      return $this->PhotoUrl;
    }

    /**
     * @param string $PhotoUrl
     * @return Contact
     */
    public function setPhotoUrl($PhotoUrl)
    {
      $this->PhotoUrl = $PhotoUrl;
      return $this;
    }

    /**
     * @return date
     */
    public function getPlanning_Comments__c()
    {
      return $this->Planning_Comments__c;
    }

    /**
     * @param date $Planning_Comments__c
     * @return Contact
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
     * @return Contact
     */
    public function setPlanning_Status__c($Planning_Status__c)
    {
      $this->Planning_Status__c = $Planning_Status__c;
      return $this;
    }

    /**
     * @return date
     */
    public function getPlanning_survey_date__c()
    {
      return $this->Planning_survey_date__c;
    }

    /**
     * @param date $Planning_survey_date__c
     * @return Contact
     */
    public function setPlanning_survey_date__c($Planning_survey_date__c)
    {
      $this->Planning_survey_date__c = $Planning_survey_date__c;
      return $this;
    }

    /**
     * @return boolean
     */
    public function getPlays_with_9_or_More__c()
    {
      return $this->Plays_with_9_or_More__c;
    }

    /**
     * @param boolean $Plays_with_9_or_More__c
     * @return Contact
     */
    public function setPlays_with_9_or_More__c($Plays_with_9_or_More__c)
    {
      $this->Plays_with_9_or_More__c = $Plays_with_9_or_More__c;
      return $this;
    }

    /**
     * @return QueryResult
     */
    public function getPosts()
    {
      return $this->Posts;
    }

    /**
     * @param QueryResult $Posts
     * @return Contact
     */
    public function setPosts($Posts)
    {
      $this->Posts = $Posts;
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
     * @return Contact
     */
    public function setPreferred_Method_of_Contact__c($Preferred_Method_of_Contact__c)
    {
      $this->Preferred_Method_of_Contact__c = $Preferred_Method_of_Contact__c;
      return $this;
    }

    /**
     * @return QueryResult
     */
    public function getPreferred_Partner_Cards__r()
    {
      return $this->Preferred_Partner_Cards__r;
    }

    /**
     * @param QueryResult $Preferred_Partner_Cards__r
     * @return Contact
     */
    public function setPreferred_Partner_Cards__r($Preferred_Partner_Cards__r)
    {
      $this->Preferred_Partner_Cards__r = $Preferred_Partner_Cards__r;
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
     * @return Contact
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
     * @return Contact
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
     * @return Contact
     */
    public function setPromo_Code__c($Promo_Code__c)
    {
      $this->Promo_Code__c = $Promo_Code__c;
      return $this;
    }

    /**
     * @return float
     */
    public function getProperty_of_Interest_ID__c()
    {
      return $this->Property_of_Interest_ID__c;
    }

    /**
     * @param float $Property_of_Interest_ID__c
     * @return Contact
     */
    public function setProperty_of_Interest_ID__c($Property_of_Interest_ID__c)
    {
      $this->Property_of_Interest_ID__c = $Property_of_Interest_ID__c;
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
     * @return Contact
     */
    public function setRecordAssociatedGroups($RecordAssociatedGroups)
    {
      $this->RecordAssociatedGroups = $RecordAssociatedGroups;
      return $this;
    }

    /**
     * @return Contact
     */
    public function getReportsTo()
    {
      return $this->ReportsTo;
    }

    /**
     * @param Contact $ReportsTo
     * @return Contact
     */
    public function setReportsTo($ReportsTo)
    {
      $this->ReportsTo = $ReportsTo;
      return $this;
    }

    /**
     * @return ID
     */
    public function getReportsToId()
    {
      return $this->ReportsToId;
    }

    /**
     * @param ID $ReportsToId
     * @return Contact
     */
    public function setReportsToId($ReportsToId)
    {
      $this->ReportsToId = $ReportsToId;
      return $this;
    }

    /**
     * @return date
     */
    public function getResort_Giveaway_Date__c()
    {
      return $this->Resort_Giveaway_Date__c;
    }

    /**
     * @param date $Resort_Giveaway_Date__c
     * @return Contact
     */
    public function setResort_Giveaway_Date__c($Resort_Giveaway_Date__c)
    {
      $this->Resort_Giveaway_Date__c = $Resort_Giveaway_Date__c;
      return $this;
    }

    /**
     * @return boolean
     */
    public function getResort_Giveaway__c()
    {
      return $this->Resort_Giveaway__c;
    }

    /**
     * @param boolean $Resort_Giveaway__c
     * @return Contact
     */
    public function setResort_Giveaway__c($Resort_Giveaway__c)
    {
      $this->Resort_Giveaway__c = $Resort_Giveaway__c;
      return $this;
    }

    /**
     * @return boolean
     */
    public function getSFSSDupeCatcher__Override_DupeCatcher__c()
    {
      return $this->SFSSDupeCatcher__Override_DupeCatcher__c;
    }

    /**
     * @param boolean $SFSSDupeCatcher__Override_DupeCatcher__c
     * @return Contact
     */
    public function setSFSSDupeCatcher__Override_DupeCatcher__c($SFSSDupeCatcher__Override_DupeCatcher__c)
    {
      $this->SFSSDupeCatcher__Override_DupeCatcher__c = $SFSSDupeCatcher__Override_DupeCatcher__c;
      return $this;
    }

    /**
     * @return QueryResult
     */
    public function getSFSSDupeCatcher__Potential_Duplicates__r()
    {
      return $this->SFSSDupeCatcher__Potential_Duplicates__r;
    }

    /**
     * @param QueryResult $SFSSDupeCatcher__Potential_Duplicates__r
     * @return Contact
     */
    public function setSFSSDupeCatcher__Potential_Duplicates__r($SFSSDupeCatcher__Potential_Duplicates__r)
    {
      $this->SFSSDupeCatcher__Potential_Duplicates__r = $SFSSDupeCatcher__Potential_Duplicates__r;
      return $this;
    }

    /**
     * @return QueryResult
     */
    public function getSales_Inquiries__r()
    {
      return $this->Sales_Inquiries__r;
    }

    /**
     * @param QueryResult $Sales_Inquiries__r
     * @return Contact
     */
    public function setSales_Inquiries__r($Sales_Inquiries__r)
    {
      $this->Sales_Inquiries__r = $Sales_Inquiries__r;
      return $this;
    }

    /**
     * @return ID
     */
    public function getSales_Inquiry__c()
    {
      return $this->Sales_Inquiry__c;
    }

    /**
     * @param ID $Sales_Inquiry__c
     * @return Contact
     */
    public function setSales_Inquiry__c($Sales_Inquiry__c)
    {
      $this->Sales_Inquiry__c = $Sales_Inquiry__c;
      return $this;
    }

    /**
     * @return Sales_Inquiry__c
     */
    public function getSales_Inquiry__r()
    {
      return $this->Sales_Inquiry__r;
    }

    /**
     * @param Sales_Inquiry__c $Sales_Inquiry__r
     * @return Contact
     */
    public function setSales_Inquiry__r($Sales_Inquiry__r)
    {
      $this->Sales_Inquiry__r = $Sales_Inquiry__r;
      return $this;
    }

    /**
     * @return string
     */
    public function getSalutation()
    {
      return $this->Salutation;
    }

    /**
     * @param string $Salutation
     * @return Contact
     */
    public function setSalutation($Salutation)
    {
      $this->Salutation = $Salutation;
      return $this;
    }

    /**
     * @return string
     */
    public function getSearch_Results_URL__c()
    {
      return $this->Search_Results_URL__c;
    }

    /**
     * @param string $Search_Results_URL__c
     * @return Contact
     */
    public function setSearch_Results_URL__c($Search_Results_URL__c)
    {
      $this->Search_Results_URL__c = $Search_Results_URL__c;
      return $this;
    }

    /**
     * @return QueryResult
     */
    public function getShares()
    {
      return $this->Shares;
    }

    /**
     * @param QueryResult $Shares
     * @return Contact
     */
    public function setShares($Shares)
    {
      $this->Shares = $Shares;
      return $this;
    }

    /**
     * @return string
     */
    public function getSource_Inquiry__c()
    {
      return $this->Source_Inquiry__c;
    }

    /**
     * @param string $Source_Inquiry__c
     * @return Contact
     */
    public function setSource_Inquiry__c($Source_Inquiry__c)
    {
      $this->Source_Inquiry__c = $Source_Inquiry__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getSource__c()
    {
      return $this->Source__c;
    }

    /**
     * @param string $Source__c
     * @return Contact
     */
    public function setSource__c($Source__c)
    {
      $this->Source__c = $Source__c;
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
     * @return Contact
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
     * @return Contact
     */
    public function setTaskRelations($TaskRelations)
    {
      $this->TaskRelations = $TaskRelations;
      return $this;
    }

    /**
     * @return QueryResult
     */
    public function getTaskWhoRelations()
    {
      return $this->TaskWhoRelations;
    }

    /**
     * @param QueryResult $TaskWhoRelations
     * @return Contact
     */
    public function setTaskWhoRelations($TaskWhoRelations)
    {
      $this->TaskWhoRelations = $TaskWhoRelations;
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
     * @return Contact
     */
    public function setTasks($Tasks)
    {
      $this->Tasks = $Tasks;
      return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
      return $this->Title;
    }

    /**
     * @param string $Title
     * @return Contact
     */
    public function setTitle($Title)
    {
      $this->Title = $Title;
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
     * @return Contact
     */
    public function setTopicAssignments($TopicAssignments)
    {
      $this->TopicAssignments = $TopicAssignments;
      return $this;
    }

    /**
     * @return float
     */
    public function getTotal_Points_This_Qtr__c()
    {
      return $this->Total_Points_This_Qtr__c;
    }

    /**
     * @param float $Total_Points_This_Qtr__c
     * @return Contact
     */
    public function setTotal_Points_This_Qtr__c($Total_Points_This_Qtr__c)
    {
      $this->Total_Points_This_Qtr__c = $Total_Points_This_Qtr__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getTwitter_Search__c()
    {
      return $this->Twitter_Search__c;
    }

    /**
     * @param string $Twitter_Search__c
     * @return Contact
     */
    public function setTwitter_Search__c($Twitter_Search__c)
    {
      $this->Twitter_Search__c = $Twitter_Search__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getTwitter_URL__c()
    {
      return $this->Twitter_URL__c;
    }

    /**
     * @param string $Twitter_URL__c
     * @return Contact
     */
    public function setTwitter_URL__c($Twitter_URL__c)
    {
      $this->Twitter_URL__c = $Twitter_URL__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getTypically_Plays__c()
    {
      return $this->Typically_Plays__c;
    }

    /**
     * @param string $Typically_Plays__c
     * @return Contact
     */
    public function setTypically_Plays__c($Typically_Plays__c)
    {
      $this->Typically_Plays__c = $Typically_Plays__c;
      return $this;
    }

    /**
     * @return QueryResult
     */
    public function getUndecidedEventRelations()
    {
      return $this->UndecidedEventRelations;
    }

    /**
     * @param QueryResult $UndecidedEventRelations
     * @return Contact
     */
    public function setUndecidedEventRelations($UndecidedEventRelations)
    {
      $this->UndecidedEventRelations = $UndecidedEventRelations;
      return $this;
    }

    /**
     * @return boolean
     */
    public function getUnengaged__c()
    {
      return $this->Unengaged__c;
    }

    /**
     * @param boolean $Unengaged__c
     * @return Contact
     */
    public function setUnengaged__c($Unengaged__c)
    {
      $this->Unengaged__c = $Unengaged__c;
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
     * @return Contact
     */
    public function setUserRecordAccess($UserRecordAccess)
    {
      $this->UserRecordAccess = $UserRecordAccess;
      return $this;
    }

    /**
     * @return string
     */
    public function getVRBO_Partner_Code__c()
    {
      return $this->VRBO_Partner_Code__c;
    }

    /**
     * @param string $VRBO_Partner_Code__c
     * @return Contact
     */
    public function setVRBO_Partner_Code__c($VRBO_Partner_Code__c)
    {
      $this->VRBO_Partner_Code__c = $VRBO_Partner_Code__c;
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
     * @return Contact
     */
    public function setWedding_Date__c($Wedding_Date__c)
    {
      $this->Wedding_Date__c = $Wedding_Date__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getWedding_Location__c()
    {
      return $this->Wedding_Location__c;
    }

    /**
     * @param string $Wedding_Location__c
     * @return Contact
     */
    public function setWedding_Location__c($Wedding_Location__c)
    {
      $this->Wedding_Location__c = $Wedding_Location__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getWelcome_email_Type__c()
    {
      return $this->Welcome_email_Type__c;
    }

    /**
     * @param string $Welcome_email_Type__c
     * @return Contact
     */
    public function setWelcome_email_Type__c($Welcome_email_Type__c)
    {
      $this->Welcome_email_Type__c = $Welcome_email_Type__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getBtydev__Picture_Id__c()
    {
      return $this->btydev__Picture_Id__c;
    }

    /**
     * @param string $btydev__Picture_Id__c
     * @return Contact
     */
    public function setBtydev__Picture_Id__c($btydev__Picture_Id__c)
    {
      $this->btydev__Picture_Id__c = $btydev__Picture_Id__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getBtydev__Picture__c()
    {
      return $this->btydev__Picture__c;
    }

    /**
     * @param string $btydev__Picture__c
     * @return Contact
     */
    public function setBtydev__Picture__c($btydev__Picture__c)
    {
      $this->btydev__Picture__c = $btydev__Picture__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getG1f1T__c()
    {
      return $this->g1f1T__c;
    }

    /**
     * @param string $g1f1T__c
     * @return Contact
     */
    public function setG1f1T__c($g1f1T__c)
    {
      $this->g1f1T__c = $g1f1T__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getG1f1__c()
    {
      return $this->g1f1__c;
    }

    /**
     * @param string $g1f1__c
     * @return Contact
     */
    public function setG1f1__c($g1f1__c)
    {
      $this->g1f1__c = $g1f1__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getG2f1T__c()
    {
      return $this->g2f1T__c;
    }

    /**
     * @param string $g2f1T__c
     * @return Contact
     */
    public function setG2f1T__c($g2f1T__c)
    {
      $this->g2f1T__c = $g2f1T__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getG2f1__c()
    {
      return $this->g2f1__c;
    }

    /**
     * @param string $g2f1__c
     * @return Contact
     */
    public function setG2f1__c($g2f1__c)
    {
      $this->g2f1__c = $g2f1__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getG2f2T__c()
    {
      return $this->g2f2T__c;
    }

    /**
     * @param string $g2f2T__c
     * @return Contact
     */
    public function setG2f2T__c($g2f2T__c)
    {
      $this->g2f2T__c = $g2f2T__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getG2f2__c()
    {
      return $this->g2f2__c;
    }

    /**
     * @param string $g2f2__c
     * @return Contact
     */
    public function setG2f2__c($g2f2__c)
    {
      $this->g2f2__c = $g2f2__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getG2f3T__c()
    {
      return $this->g2f3T__c;
    }

    /**
     * @param string $g2f3T__c
     * @return Contact
     */
    public function setG2f3T__c($g2f3T__c)
    {
      $this->g2f3T__c = $g2f3T__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getG2f3__c()
    {
      return $this->g2f3__c;
    }

    /**
     * @param string $g2f3__c
     * @return Contact
     */
    public function setG2f3__c($g2f3__c)
    {
      $this->g2f3__c = $g2f3__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getG2f4T__c()
    {
      return $this->g2f4T__c;
    }

    /**
     * @param string $g2f4T__c
     * @return Contact
     */
    public function setG2f4T__c($g2f4T__c)
    {
      $this->g2f4T__c = $g2f4T__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getG2f4__c()
    {
      return $this->g2f4__c;
    }

    /**
     * @param string $g2f4__c
     * @return Contact
     */
    public function setG2f4__c($g2f4__c)
    {
      $this->g2f4__c = $g2f4__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getG3f1T__c()
    {
      return $this->g3f1T__c;
    }

    /**
     * @param string $g3f1T__c
     * @return Contact
     */
    public function setG3f1T__c($g3f1T__c)
    {
      $this->g3f1T__c = $g3f1T__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getG3f1__c()
    {
      return $this->g3f1__c;
    }

    /**
     * @param string $g3f1__c
     * @return Contact
     */
    public function setG3f1__c($g3f1__c)
    {
      $this->g3f1__c = $g3f1__c;
      return $this;
    }

    /**
     * @return float
     */
    public function getLenphone__c()
    {
      return $this->lenphone__c;
    }

    /**
     * @param float $lenphone__c
     * @return Contact
     */
    public function setLenphone__c($lenphone__c)
    {
      $this->lenphone__c = $lenphone__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getLod4__AutoLink__c()
    {
      return $this->lod4__AutoLink__c;
    }

    /**
     * @param string $lod4__AutoLink__c
     * @return Contact
     */
    public function setLod4__AutoLink__c($lod4__AutoLink__c)
    {
      $this->lod4__AutoLink__c = $lod4__AutoLink__c;
      return $this;
    }

    /**
     * @return QueryResult
     */
    public function getLod4__Bookings1__r()
    {
      return $this->lod4__Bookings1__r;
    }

    /**
     * @param QueryResult $lod4__Bookings1__r
     * @return Contact
     */
    public function setLod4__Bookings1__r($lod4__Bookings1__r)
    {
      $this->lod4__Bookings1__r = $lod4__Bookings1__r;
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
     * @return Contact
     */
    public function setLod4__Bookings__r($lod4__Bookings__r)
    {
      $this->lod4__Bookings__r = $lod4__Bookings__r;
      return $this;
    }

    /**
     * @return \DateTime
     */
    public function getLod4__Bounced__c()
    {
      if ($this->lod4__Bounced__c == null) {
        return null;
      } else {
        try {
          return new \DateTime($this->lod4__Bounced__c);
        } catch (\Exception $e) {
          return false;
        }
      }
    }

    /**
     * @param \DateTime $lod4__Bounced__c
     * @return Contact
     */
    public function setLod4__Bounced__c(\DateTime $lod4__Bounced__c = null)
    {
      if ($lod4__Bounced__c == null) {
       $this->lod4__Bounced__c = null;
      } else {
        $this->lod4__Bounced__c = $lod4__Bounced__c->format(\DateTime::ATOM);
      }
      return $this;
    }

    /**
     * @return boolean
     */
    public function getLod4__ByService__c()
    {
      return $this->lod4__ByService__c;
    }

    /**
     * @param boolean $lod4__ByService__c
     * @return Contact
     */
    public function setLod4__ByService__c($lod4__ByService__c)
    {
      $this->lod4__ByService__c = $lod4__ByService__c;
      return $this;
    }

    /**
     * @return QueryResult
     */
    public function getLod4__ContactChilds__r()
    {
      return $this->lod4__ContactChilds__r;
    }

    /**
     * @param QueryResult $lod4__ContactChilds__r
     * @return Contact
     */
    public function setLod4__ContactChilds__r($lod4__ContactChilds__r)
    {
      $this->lod4__ContactChilds__r = $lod4__ContactChilds__r;
      return $this;
    }

    /**
     * @return QueryResult
     */
    public function getLod4__ContactPMSLinksChilds__r()
    {
      return $this->lod4__ContactPMSLinksChilds__r;
    }

    /**
     * @param QueryResult $lod4__ContactPMSLinksChilds__r
     * @return Contact
     */
    public function setLod4__ContactPMSLinksChilds__r($lod4__ContactPMSLinksChilds__r)
    {
      $this->lod4__ContactPMSLinksChilds__r = $lod4__ContactPMSLinksChilds__r;
      return $this;
    }

    /**
     * @return QueryResult
     */
    public function getLod4__Contact_PMS_Links__r()
    {
      return $this->lod4__Contact_PMS_Links__r;
    }

    /**
     * @param QueryResult $lod4__Contact_PMS_Links__r
     * @return Contact
     */
    public function setLod4__Contact_PMS_Links__r($lod4__Contact_PMS_Links__r)
    {
      $this->lod4__Contact_PMS_Links__r = $lod4__Contact_PMS_Links__r;
      return $this;
    }

    /**
     * @return string
     */
    public function getLod4__Contact_Role__c()
    {
      return $this->lod4__Contact_Role__c;
    }

    /**
     * @param string $lod4__Contact_Role__c
     * @return Contact
     */
    public function setLod4__Contact_Role__c($lod4__Contact_Role__c)
    {
      $this->lod4__Contact_Role__c = $lod4__Contact_Role__c;
      return $this;
    }

    /**
     * @return QueryResult
     */
    public function getLod4__Email_Campaign_History__r()
    {
      return $this->lod4__Email_Campaign_History__r;
    }

    /**
     * @param QueryResult $lod4__Email_Campaign_History__r
     * @return Contact
     */
    public function setLod4__Email_Campaign_History__r($lod4__Email_Campaign_History__r)
    {
      $this->lod4__Email_Campaign_History__r = $lod4__Email_Campaign_History__r;
      return $this;
    }

    /**
     * @return QueryResult
     */
    public function getLod4__Email_Offers__r()
    {
      return $this->lod4__Email_Offers__r;
    }

    /**
     * @param QueryResult $lod4__Email_Offers__r
     * @return Contact
     */
    public function setLod4__Email_Offers__r($lod4__Email_Offers__r)
    {
      $this->lod4__Email_Offers__r = $lod4__Email_Offers__r;
      return $this;
    }

    /**
     * @return QueryResult
     */
    public function getLod4__Events__r()
    {
      return $this->lod4__Events__r;
    }

    /**
     * @param QueryResult $lod4__Events__r
     * @return Contact
     */
    public function setLod4__Events__r($lod4__Events__r)
    {
      $this->lod4__Events__r = $lod4__Events__r;
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
     * @return Contact
     */
    public function setLod4__ExternalId__c($lod4__ExternalId__c)
    {
      $this->lod4__ExternalId__c = $lod4__ExternalId__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getLod4__Family__c()
    {
      return $this->lod4__Family__c;
    }

    /**
     * @param string $lod4__Family__c
     * @return Contact
     */
    public function setLod4__Family__c($lod4__Family__c)
    {
      $this->lod4__Family__c = $lod4__Family__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getLod4__Freq_Stay_Id__c()
    {
      return $this->lod4__Freq_Stay_Id__c;
    }

    /**
     * @param string $lod4__Freq_Stay_Id__c
     * @return Contact
     */
    public function setLod4__Freq_Stay_Id__c($lod4__Freq_Stay_Id__c)
    {
      $this->lod4__Freq_Stay_Id__c = $lod4__Freq_Stay_Id__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getLod4__Gender__c()
    {
      return $this->lod4__Gender__c;
    }

    /**
     * @param string $lod4__Gender__c
     * @return Contact
     */
    public function setLod4__Gender__c($lod4__Gender__c)
    {
      $this->lod4__Gender__c = $lod4__Gender__c;
      return $this;
    }

    /**
     * @return QueryResult
     */
    public function getLod4__Groups__r()
    {
      return $this->lod4__Groups__r;
    }

    /**
     * @param QueryResult $lod4__Groups__r
     * @return Contact
     */
    public function setLod4__Groups__r($lod4__Groups__r)
    {
      $this->lod4__Groups__r = $lod4__Groups__r;
      return $this;
    }

    /**
     * @return QueryResult
     */
    public function getLod4__GuestActivities__r()
    {
      return $this->lod4__GuestActivities__r;
    }

    /**
     * @param QueryResult $lod4__GuestActivities__r
     * @return Contact
     */
    public function setLod4__GuestActivities__r($lod4__GuestActivities__r)
    {
      $this->lod4__GuestActivities__r = $lod4__GuestActivities__r;
      return $this;
    }

    /**
     * @return float
     */
    public function getLod4__GuestNights__c()
    {
      return $this->lod4__GuestNights__c;
    }

    /**
     * @param float $lod4__GuestNights__c
     * @return Contact
     */
    public function setLod4__GuestNights__c($lod4__GuestNights__c)
    {
      $this->lod4__GuestNights__c = $lod4__GuestNights__c;
      return $this;
    }

    /**
     * @return float
     */
    public function getLod4__GuestRoomRevenue__c()
    {
      return $this->lod4__GuestRoomRevenue__c;
    }

    /**
     * @param float $lod4__GuestRoomRevenue__c
     * @return Contact
     */
    public function setLod4__GuestRoomRevenue__c($lod4__GuestRoomRevenue__c)
    {
      $this->lod4__GuestRoomRevenue__c = $lod4__GuestRoomRevenue__c;
      return $this;
    }

    /**
     * @return float
     */
    public function getLod4__GuestStays__c()
    {
      return $this->lod4__GuestStays__c;
    }

    /**
     * @param float $lod4__GuestStays__c
     * @return Contact
     */
    public function setLod4__GuestStays__c($lod4__GuestStays__c)
    {
      $this->lod4__GuestStays__c = $lod4__GuestStays__c;
      return $this;
    }

    /**
     * @return float
     */
    public function getLod4__GuestTotalRevenue__c()
    {
      return $this->lod4__GuestTotalRevenue__c;
    }

    /**
     * @param float $lod4__GuestTotalRevenue__c
     * @return Contact
     */
    public function setLod4__GuestTotalRevenue__c($lod4__GuestTotalRevenue__c)
    {
      $this->lod4__GuestTotalRevenue__c = $lod4__GuestTotalRevenue__c;
      return $this;
    }

    /**
     * @return QueryResult
     */
    public function getLod4__Guest_Incidents__r()
    {
      return $this->lod4__Guest_Incidents__r;
    }

    /**
     * @param QueryResult $lod4__Guest_Incidents__r
     * @return Contact
     */
    public function setLod4__Guest_Incidents__r($lod4__Guest_Incidents__r)
    {
      $this->lod4__Guest_Incidents__r = $lod4__Guest_Incidents__r;
      return $this;
    }

    /**
     * @return string
     */
    public function getLod4__HomeEmail__c()
    {
      return $this->lod4__HomeEmail__c;
    }

    /**
     * @param string $lod4__HomeEmail__c
     * @return Contact
     */
    public function setLod4__HomeEmail__c($lod4__HomeEmail__c)
    {
      $this->lod4__HomeEmail__c = $lod4__HomeEmail__c;
      return $this;
    }

    /**
     * @return boolean
     */
    public function getLod4__Inactive__c()
    {
      return $this->lod4__Inactive__c;
    }

    /**
     * @param boolean $lod4__Inactive__c
     * @return Contact
     */
    public function setLod4__Inactive__c($lod4__Inactive__c)
    {
      $this->lod4__Inactive__c = $lod4__Inactive__c;
      return $this;
    }

    /**
     * @return QueryResult
     */
    public function getLod4__Inquiries__r()
    {
      return $this->lod4__Inquiries__r;
    }

    /**
     * @param QueryResult $lod4__Inquiries__r
     * @return Contact
     */
    public function setLod4__Inquiries__r($lod4__Inquiries__r)
    {
      $this->lod4__Inquiries__r = $lod4__Inquiries__r;
      return $this;
    }

    /**
     * @return string
     */
    public function getLod4__Languages__c()
    {
      return $this->lod4__Languages__c;
    }

    /**
     * @param string $lod4__Languages__c
     * @return Contact
     */
    public function setLod4__Languages__c($lod4__Languages__c)
    {
      $this->lod4__Languages__c = $lod4__Languages__c;
      return $this;
    }

    /**
     * @return \DateTime
     */
    public function getLod4__LastModByService__c()
    {
      if ($this->lod4__LastModByService__c == null) {
        return null;
      } else {
        try {
          return new \DateTime($this->lod4__LastModByService__c);
        } catch (\Exception $e) {
          return false;
        }
      }
    }

    /**
     * @param \DateTime $lod4__LastModByService__c
     * @return Contact
     */
    public function setLod4__LastModByService__c(\DateTime $lod4__LastModByService__c = null)
    {
      if ($lod4__LastModByService__c == null) {
       $this->lod4__LastModByService__c = null;
      } else {
        $this->lod4__LastModByService__c = $lod4__LastModByService__c->format(\DateTime::ATOM);
      }
      return $this;
    }

    /**
     * @return string
     */
    public function getLod4__LastNameAndInitial__c()
    {
      return $this->lod4__LastNameAndInitial__c;
    }

    /**
     * @param string $lod4__LastNameAndInitial__c
     * @return Contact
     */
    public function setLod4__LastNameAndInitial__c($lod4__LastNameAndInitial__c)
    {
      $this->lod4__LastNameAndInitial__c = $lod4__LastNameAndInitial__c;
      return $this;
    }

    /**
     * @return QueryResult
     */
    public function getLod4__List_Members__r()
    {
      return $this->lod4__List_Members__r;
    }

    /**
     * @param QueryResult $lod4__List_Members__r
     * @return Contact
     */
    public function setLod4__List_Members__r($lod4__List_Members__r)
    {
      $this->lod4__List_Members__r = $lod4__List_Members__r;
      return $this;
    }

    /**
     * @return QueryResult
     */
    public function getLod4__MemberAccounts__r()
    {
      return $this->lod4__MemberAccounts__r;
    }

    /**
     * @param QueryResult $lod4__MemberAccounts__r
     * @return Contact
     */
    public function setLod4__MemberAccounts__r($lod4__MemberAccounts__r)
    {
      $this->lod4__MemberAccounts__r = $lod4__MemberAccounts__r;
      return $this;
    }

    /**
     * @return string
     */
    public function getLod4__Middle_Name__c()
    {
      return $this->lod4__Middle_Name__c;
    }

    /**
     * @param string $lod4__Middle_Name__c
     * @return Contact
     */
    public function setLod4__Middle_Name__c($lod4__Middle_Name__c)
    {
      $this->lod4__Middle_Name__c = $lod4__Middle_Name__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getLod4__Name__c()
    {
      return $this->lod4__Name__c;
    }

    /**
     * @param string $lod4__Name__c
     * @return Contact
     */
    public function setLod4__Name__c($lod4__Name__c)
    {
      $this->lod4__Name__c = $lod4__Name__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getLod4__OtherEmail__c()
    {
      return $this->lod4__OtherEmail__c;
    }

    /**
     * @param string $lod4__OtherEmail__c
     * @return Contact
     */
    public function setLod4__OtherEmail__c($lod4__OtherEmail__c)
    {
      $this->lod4__OtherEmail__c = $lod4__OtherEmail__c;
      return $this;
    }

    /**
     * @return ID
     */
    public function getLod4__ParentContact__c()
    {
      return $this->lod4__ParentContact__c;
    }

    /**
     * @param ID $lod4__ParentContact__c
     * @return Contact
     */
    public function setLod4__ParentContact__c($lod4__ParentContact__c)
    {
      $this->lod4__ParentContact__c = $lod4__ParentContact__c;
      return $this;
    }

    /**
     * @return Contact
     */
    public function getLod4__ParentContact__r()
    {
      return $this->lod4__ParentContact__r;
    }

    /**
     * @param Contact $lod4__ParentContact__r
     * @return Contact
     */
    public function setLod4__ParentContact__r($lod4__ParentContact__r)
    {
      $this->lod4__ParentContact__r = $lod4__ParentContact__r;
      return $this;
    }

    /**
     * @return string
     */
    public function getLod4__PassportNumber__c()
    {
      return $this->lod4__PassportNumber__c;
    }

    /**
     * @param string $lod4__PassportNumber__c
     * @return Contact
     */
    public function setLod4__PassportNumber__c($lod4__PassportNumber__c)
    {
      $this->lod4__PassportNumber__c = $lod4__PassportNumber__c;
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
     * @return Contact
     */
    public function setLod4__PhoneExtension__c($lod4__PhoneExtension__c)
    {
      $this->lod4__PhoneExtension__c = $lod4__PhoneExtension__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getLod4__PhoneOnlyDigits__c()
    {
      return $this->lod4__PhoneOnlyDigits__c;
    }

    /**
     * @param string $lod4__PhoneOnlyDigits__c
     * @return Contact
     */
    public function setLod4__PhoneOnlyDigits__c($lod4__PhoneOnlyDigits__c)
    {
      $this->lod4__PhoneOnlyDigits__c = $lod4__PhoneOnlyDigits__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getLod4__Phone_Stripped__c()
    {
      return $this->lod4__Phone_Stripped__c;
    }

    /**
     * @param string $lod4__Phone_Stripped__c
     * @return Contact
     */
    public function setLod4__Phone_Stripped__c($lod4__Phone_Stripped__c)
    {
      $this->lod4__Phone_Stripped__c = $lod4__Phone_Stripped__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getLod4__PrimaryMemberCard__c()
    {
      return $this->lod4__PrimaryMemberCard__c;
    }

    /**
     * @param string $lod4__PrimaryMemberCard__c
     * @return Contact
     */
    public function setLod4__PrimaryMemberCard__c($lod4__PrimaryMemberCard__c)
    {
      $this->lod4__PrimaryMemberCard__c = $lod4__PrimaryMemberCard__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getLod4__Primary_Contact__c()
    {
      return $this->lod4__Primary_Contact__c;
    }

    /**
     * @param string $lod4__Primary_Contact__c
     * @return Contact
     */
    public function setLod4__Primary_Contact__c($lod4__Primary_Contact__c)
    {
      $this->lod4__Primary_Contact__c = $lod4__Primary_Contact__c;
      return $this;
    }

    /**
     * @return QueryResult
     */
    public function getLod4__Profile_Amenities_Notes_Specials__r()
    {
      return $this->lod4__Profile_Amenities_Notes_Specials__r;
    }

    /**
     * @param QueryResult $lod4__Profile_Amenities_Notes_Specials__r
     * @return Contact
     */
    public function setLod4__Profile_Amenities_Notes_Specials__r($lod4__Profile_Amenities_Notes_Specials__r)
    {
      $this->lod4__Profile_Amenities_Notes_Specials__r = $lod4__Profile_Amenities_Notes_Specials__r;
      return $this;
    }

    /**
     * @return QueryResult
     */
    public function getLod4__Queues__r()
    {
      return $this->lod4__Queues__r;
    }

    /**
     * @param QueryResult $lod4__Queues__r
     * @return Contact
     */
    public function setLod4__Queues__r($lod4__Queues__r)
    {
      $this->lod4__Queues__r = $lod4__Queues__r;
      return $this;
    }

    /**
     * @return QueryResult
     */
    public function getLod4__R00N30000002jmRyEAI__r()
    {
      return $this->lod4__R00N30000002jmRyEAI__r;
    }

    /**
     * @param QueryResult $lod4__R00N30000002jmRyEAI__r
     * @return Contact
     */
    public function setLod4__R00N30000002jmRyEAI__r($lod4__R00N30000002jmRyEAI__r)
    {
      $this->lod4__R00N30000002jmRyEAI__r = $lod4__R00N30000002jmRyEAI__r;
      return $this;
    }

    /**
     * @return QueryResult
     */
    public function getLod4__R00N70000002BndBEAS__r()
    {
      return $this->lod4__R00N70000002BndBEAS__r;
    }

    /**
     * @param QueryResult $lod4__R00N70000002BndBEAS__r
     * @return Contact
     */
    public function setLod4__R00N70000002BndBEAS__r($lod4__R00N70000002BndBEAS__r)
    {
      $this->lod4__R00N70000002BndBEAS__r = $lod4__R00N70000002BndBEAS__r;
      return $this;
    }

    /**
     * @return string
     */
    public function getLod4__Rank__c()
    {
      return $this->lod4__Rank__c;
    }

    /**
     * @param string $lod4__Rank__c
     * @return Contact
     */
    public function setLod4__Rank__c($lod4__Rank__c)
    {
      $this->lod4__Rank__c = $lod4__Rank__c;
      return $this;
    }

    /**
     * @return QueryResult
     */
    public function getLod4__Reservations__r()
    {
      return $this->lod4__Reservations__r;
    }

    /**
     * @param QueryResult $lod4__Reservations__r
     * @return Contact
     */
    public function setLod4__Reservations__r($lod4__Reservations__r)
    {
      $this->lod4__Reservations__r = $lod4__Reservations__r;
      return $this;
    }

    /**
     * @return boolean
     */
    public function getLod4__SendUpdateToHMS__c()
    {
      return $this->lod4__SendUpdateToHMS__c;
    }

    /**
     * @param boolean $lod4__SendUpdateToHMS__c
     * @return Contact
     */
    public function setLod4__SendUpdateToHMS__c($lod4__SendUpdateToHMS__c)
    {
      $this->lod4__SendUpdateToHMS__c = $lod4__SendUpdateToHMS__c;
      return $this;
    }

    /**
     * @return QueryResult
     */
    public function getLod4__Service_Orders_Incidents__r()
    {
      return $this->lod4__Service_Orders_Incidents__r;
    }

    /**
     * @param QueryResult $lod4__Service_Orders_Incidents__r
     * @return Contact
     */
    public function setLod4__Service_Orders_Incidents__r($lod4__Service_Orders_Incidents__r)
    {
      $this->lod4__Service_Orders_Incidents__r = $lod4__Service_Orders_Incidents__r;
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
     * @return Contact
     */
    public function setLod4__Suffix__c($lod4__Suffix__c)
    {
      $this->lod4__Suffix__c = $lod4__Suffix__c;
      return $this;
    }

    /**
     * @return QueryResult
     */
    public function getLod4__Survey_Answers__r()
    {
      return $this->lod4__Survey_Answers__r;
    }

    /**
     * @param QueryResult $lod4__Survey_Answers__r
     * @return Contact
     */
    public function setLod4__Survey_Answers__r($lod4__Survey_Answers__r)
    {
      $this->lod4__Survey_Answers__r = $lod4__Survey_Answers__r;
      return $this;
    }

    /**
     * @return QueryResult
     */
    public function getLod4__Survey_Reservations__r()
    {
      return $this->lod4__Survey_Reservations__r;
    }

    /**
     * @param QueryResult $lod4__Survey_Reservations__r
     * @return Contact
     */
    public function setLod4__Survey_Reservations__r($lod4__Survey_Reservations__r)
    {
      $this->lod4__Survey_Reservations__r = $lod4__Survey_Reservations__r;
      return $this;
    }

    /**
     * @return boolean
     */
    public function getLod4__TaxExempt__c()
    {
      return $this->lod4__TaxExempt__c;
    }

    /**
     * @param boolean $lod4__TaxExempt__c
     * @return Contact
     */
    public function setLod4__TaxExempt__c($lod4__TaxExempt__c)
    {
      $this->lod4__TaxExempt__c = $lod4__TaxExempt__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getLod4__Twitter_Screen_Name__c()
    {
      return $this->lod4__Twitter_Screen_Name__c;
    }

    /**
     * @param string $lod4__Twitter_Screen_Name__c
     * @return Contact
     */
    public function setLod4__Twitter_Screen_Name__c($lod4__Twitter_Screen_Name__c)
    {
      $this->lod4__Twitter_Screen_Name__c = $lod4__Twitter_Screen_Name__c;
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
     * @return Contact
     */
    public function setLod4__Type__c($lod4__Type__c)
    {
      $this->lod4__Type__c = $lod4__Type__c;
      return $this;
    }

    /**
     * @return \DateTime
     */
    public function getLod4__UpdateTimestamp__c()
    {
      if ($this->lod4__UpdateTimestamp__c == null) {
        return null;
      } else {
        try {
          return new \DateTime($this->lod4__UpdateTimestamp__c);
        } catch (\Exception $e) {
          return false;
        }
      }
    }

    /**
     * @param \DateTime $lod4__UpdateTimestamp__c
     * @return Contact
     */
    public function setLod4__UpdateTimestamp__c(\DateTime $lod4__UpdateTimestamp__c = null)
    {
      if ($lod4__UpdateTimestamp__c == null) {
       $this->lod4__UpdateTimestamp__c = null;
      } else {
        $this->lod4__UpdateTimestamp__c = $lod4__UpdateTimestamp__c->format(\DateTime::ATOM);
      }
      return $this;
    }

    /**
     * @return float
     */
    public function getLod4__Updated__c()
    {
      return $this->lod4__Updated__c;
    }

    /**
     * @param float $lod4__Updated__c
     * @return Contact
     */
    public function setLod4__Updated__c($lod4__Updated__c)
    {
      $this->lod4__Updated__c = $lod4__Updated__c;
      return $this;
    }

    /**
     * @return QueryResult
     */
    public function getLod4__VolumeContractContacts__r()
    {
      return $this->lod4__VolumeContractContacts__r;
    }

    /**
     * @param QueryResult $lod4__VolumeContractContacts__r
     * @return Contact
     */
    public function setLod4__VolumeContractContacts__r($lod4__VolumeContractContacts__r)
    {
      $this->lod4__VolumeContractContacts__r = $lod4__VolumeContractContacts__r;
      return $this;
    }

    /**
     * @return QueryResult
     */
    public function getLod4__VolumeContracts__r()
    {
      return $this->lod4__VolumeContracts__r;
    }

    /**
     * @param QueryResult $lod4__VolumeContracts__r
     * @return Contact
     */
    public function setLod4__VolumeContracts__r($lod4__VolumeContracts__r)
    {
      $this->lod4__VolumeContracts__r = $lod4__VolumeContracts__r;
      return $this;
    }

    /**
     * @return string
     */
    public function getLod4__gtd__c()
    {
      return $this->lod4__gtd__c;
    }

    /**
     * @param string $lod4__gtd__c
     * @return Contact
     */
    public function setLod4__gtd__c($lod4__gtd__c)
    {
      $this->lod4__gtd__c = $lod4__gtd__c;
      return $this;
    }

    /**
     * @return string
     */
    public function getLod4__vip__c()
    {
      return $this->lod4__vip__c;
    }

    /**
     * @param string $lod4__vip__c
     * @return Contact
     */
    public function setLod4__vip__c($lod4__vip__c)
    {
      $this->lod4__vip__c = $lod4__vip__c;
      return $this;
    }

}
