<?php

namespace weblink;

class Import {

    protected $fullpropertylist = array();
    protected $db;
    protected $singleproperty;
    protected $errors = array();
    protected $logger;

    public function setSingleProperty($id) {
        $this->singleproperty = $id;
    }

    public function __construct() {
        $this->db = new \Db;
        $this->db->throwExceptionsOnError = true;
    }

    //$properties = Property::getProperties($filters);
    //stuck this in a function so it's easier to move to another cli script if necessary
    public function propertyimport($type='full', Weblink $service, Logger $logger, $pull, $propertyids=array())
    {

        $this->logger = $logger;

        $properties_updated = 0;
		
        if ( count( $propertyids ) == 0 ) {
            $logger->log('No properties found, exiting', 'end');
            $pull->end();
            exit;
        }

        $owserror=array();
    	$importtime=new \DateTime();
		$insertsql='insert into properties(unit_number, property_name, property_area, address, location, 
			theview, property_type, num_bedrooms,num_bathrooms, num_sleeps, num_floors, description, short_description, 
			date_created, date_updated, latitude, longitude, is_active) values ';
		$insertlist=array(
			array('name'=>'unit_number', 'source'=>'strPropId'),
			array('name'=>'property_name', 'source'=>'strName'),
			array('name'=>'property_area', 'override'=>1),
			array('name'=>'address', 'override'=>''),
			array('name'=>'location', 'override'=>'location'),
			);
		$updatelist=array( 'property_name', 'property_area', 'address', 'location', 'theview', 'property_type', 'num_bedrooms',
			'num_bathrooms', 'num_sleeps', 'num_floors', 'description', 'short_description', 'date_updated', 'latitude', 'longitude', 'is_deleted');
		
		$insertupdatearray=array();
		foreach($updatelist as $l)
			$insertupdatearray[]=$l.'=values('.$l.')';
		$insertupdatesql=' on duplicate key update '.implode(',', $insertupdatearray);
		
		$fullpropertylist=array();
		$sql='select unit_number, id, is_active 
			from properties
			where 1=1
                ';

		// Selectively pull for partials
		if ( $type == 'partial' && !empty($propertyids) ) {
			$sql.= ' AND unit_number IN ( "' . implode('","', $propertyids) . '")';
		} else if ( $this->singleproperty != '' ) {
			$sql.= ' AND unit_number = "' . $this->singleproperty . '"';
		}

		$sql.= ' order by unit_number';
		
		$this->db->Query($sql);
		
		while($temp=$this->db->fetchAssoc())
			$fullpropertylist[$temp['unit_number']]=array(
				'unit_number'=>$temp['unit_number'],
				'isactive'=>$temp['is_active']
				);

		//pull amenities list for propertydescresponse setup
		
		$viewlist=new \ViewList();
		$locationlist=new \LocationList();
		$locationarealist=new \LocationareaList();
		$propertytypelist=new \PropertytypeList();
		$bedtypelist=new \BedtypeList();
		$bedroomtypelist=new \BedroomtypeList();
		$amenitylist = new \AmenityList;
	
		foreach ($propertyids as $propertyid) 
		{
	
			$logger->log( 'Updating data for property' . $propertyid );
	
			try 
			{
	
				if(!isset($fullpropertylist[$propertyid]))
				{
					$fullpropertylist[$propertyid]=array(
						'unit_number'=>$propertyid,
						'isactive'=>1,
						'insertdata'=>array()
						);
				}
	
				$propertydesc = $service->fetchpropertydesc($propertyid);

				$errors = $service->geterrormessages();
	
				// reset these
				$service->clearerrors();
	
				if ( !$propertydesc || (is_array($errors) && !empty($errors)) || $propertydesc->isEmptyResult() ) 
				{
	
					$error = $errors[0];
	
					// Skip if property doesnt exist
					if ( $error == 'Error:Property does not exist/returns an empty response in ISILink' ) {
						$logger->log( 'continuing as this property isnt in ISI:' . $property['unit_number'] );
						continue;
					}

                                        // if empty result, continue
					if ( $propertydesc->isEmptyResult() ) {
						$logger->log( 'Skipping ' . $property['unit_number'] . ', isEmptyResult() = true ' .  PHP_EOL);
						continue;
					}
	
					throw new \Exception($error);
				}

				$this->db->query('SELECT id FROM properties WHERE unit_number="' . $propertyid . '"');
				$internalpropid = 0;
				if ( $temp = $this->db->fetchassoc() ) {
					$internalpropid = $temp['id'];
				}

				$propertydesc->setInternalId($internalpropid);
	
				$logger->log( print_r($service->getdebuginfo(), true), 'end');

				$strtype = $propertydesc->strType == 'Condo' ? 'Villa' : $propertydesc->strType;
				
				$insertdata=array(
					'\''.addslashes($propertydesc->strPropId).'\'',
					'\''.addslashes($propertydesc->strName).'\'',
					$locationarealist->getid($propertydesc->strArea), //property_area
					'\''.addslashes($propertydesc->strAddress1.' '.$propertydesc->strCity.','.$propertydesc->strState.' '.$propertydesc->strZip).'\'',
					$locationlist->getid($propertydesc->location), //location, 
					$viewlist->getid($propertydesc->view),//theview, 
					$propertytypelist->getid($strtype),//property_type, 
					$propertydesc->dblBeds,//num_bedrooms,
					$propertydesc->dblBaths,//num_bathrooms, 
					$propertydesc->intOccu,//num_sleeps, 
					(is_numeric($propertydesc->stories)?$propertydesc->stories:0),//num_floors, 
					'\''.addslashes($propertydesc->strDesc).'\'',
					'\''.addslashes($propertydesc->strDescPlainText).'\'',//short_description, 
					'\''.$importtime->format('Y-m-d H:i:s').'\'',
					'\''.$importtime->format('Y-m-d H:i:s').'\'',
					$propertydesc->dblLatitude,
					$propertydesc->dblLongitude,
					1 // is_active
					);


				$fullpropertylist[$propertyid]['internalid'] = $propertydesc->getInternalId();
				$fullpropertylist[$propertyid]['insertdata']=$insertdata;
                                /*
				$fullpropertylist[$propertyid]['bedding']=$bedding;
				$fullpropertylist[$propertyid]['amenities']=$amenities;
				$fullpropertylist[$propertyid]['images']=$images;
                                 */
				$fullpropertylist[$propertyid]['propertydesc'] = $propertydesc;
				$fullpropertylist[$propertyid]['bedtypelist'] = $bedtypelist;
				$fullpropertylist[$propertyid]['bedroomtypelist'] = $bedroomtypelist;
				$fullpropertylist[$propertyid]['amenitylist'] = $amenitylist;

				$properties_updated++;
	
			}
			catch ( Exception $e ) 
			{
				$logger->log($e->getMessage());
				$logger->log( print_r($service->getdebuginfo(), true), 'end');
				$owserror[]=$e->getMessage();
			}
		}
		
		//print_r($fullpropertylist);
		//die();
		
		$insertarray=array();
		$deletedarray=array();
		foreach($fullpropertylist as $prop)
		{
			if(isset($prop['insertdata']))
			{
				if(count($prop['insertdata'])) //if there was an error this may be empty
					$insertarray[]='('.implode(',', $prop['insertdata']).')';
			}
			elseif($prop['isdeleted']==0)
			{
				$deletedarray[]='\''.addslashes($prop['unit_number']).'\'';
			}
		}
		
		if(count($insertarray))
		{
			$sql=$insertsql.implode(',',$insertarray).$insertupdatesql;
			$inserted = $this->db->Query($sql);
		}

                // moving this down further because newer properties need to be inserted to have an id..
		foreach($fullpropertylist as $unit_num => $prop)
		{

			if ( $prop['internalid'] == 0 ) {
				$this->db->query('SELECT id FROM properties WHERE unit_number="' . $unit_num . '"');
				$temp = $this->db->fetchassoc();
				if ( $temp ) {
					$prop['internalid'] = $temp['id'];
				}
			}

			// de-activated/deleted properties wont have this object set 
			// example:
			/*
			 *   ["unit_number"]=> *     string(10) "10 Long Bo"
			 *   ["isdeleted"]=> *         string(1) "1"
			 *   ["internalid"]=> *             string(3) "727"
			 *
			 */
			if ( isset($prop['propertydesc']) && is_object( $prop['propertydesc'] ) ) {

				$bedding = $this->parseBedding($prop['propertydesc'], $prop['bedtypelist'], $prop['bedroomtypelist']);
				$amenities = $this->parseAmenities($prop['propertydesc'], $unit_num, $prop['internalid'], $prop['amenitylist']);
				$images = $this->parseImages($prop['propertydesc'], $prop['internalid']);

				if(isset($prop['insertdata']) && $prop['internalid'] > 0)
				{
					$this->saveAmenities( $prop['propertydesc'], $amenities, $prop['internalid'] );
					$this->saveImages( $images, $prop['internalid'] );
					$this->saveBedding( $prop['propertydesc'], $bedding, $prop['internalid'] );
				}
			}
		}

		$this->fixOrdering();
		//$this->reorderImages();
		
		if(count($deletedarray))
		{
			$sql='update properties set is_deleted=1 where unit_number in ('.implode(',', $deletedarray).')';
			$this->db->Query($sql);
		}

		$this->fullpropertylist = $fullpropertylist;

		//print_r($fullpropertylist);
		
		//write all new data to properties
		//mark all properties not in isilink as deleted
	
		// Set the cron as finished
                $pull->end();
	
		$logger->log('Script finished. Properties Updated: ' . count($insertarray) . '. Properties deleted: ' . count($deletedarray) . '.', 'end');
	
		/// send email if owserror has values
		$emailaddresses = array('bobby.anderson@sabre.com');
		$developeremail = 'noreply@sabre.com';
	
		$fromname = 'Palmetto Dunes System';
		$subject = 'PD V12: Property ' . strtoupper($type) . ' Import';
		if (count($owserror) > 1)
		{
			#$message = implode("\n", $owserror);
			$message = implode("\n", $logger->getLogs());
			echo $subject . "\n" . $message."\n";
			if (is_array($emailaddresses) && !empty($emailaddresses)) {
					$mail = new Mailer(array_shift($emailaddresses), $developeremail, $fromname, $subject, $message, FALSE, FALSE);
					foreach ($emailaddresses as $emailaddress)
					{
							$mail->AddAddress($emailaddress);
					}
					$mail->sendMail();
			}
			else
			{
					echo "\nNO EMAIL ADDRESS SET FOR ABORTED PROPERTY RATE CACHING\n";
			}
		}
	}

    /*
     * Parse images and determine if we need to download them based on the last modify date
     * @param object $propertydesc
     * @param int $internalpropid
     * @return mixed
     */
    protected function parseImages($propertydesc, $internalpropid) {

        $images = $propertydesc->images;

        if ( empty($images) ) {
            return false;
        }

        $this->markDownloadedImages($images, $internalpropid);

        return $images;


    }

    /*
     * Mark downloaded images
     * @param array $images
     * @return void
     */
    private function markDownloadedImages(&$images, $internalpropid) {
        $sql = 'SELECT image_hash FROM property_images WHERE is_active=1 AND image_hash != "" AND property=' . $internalpropid;
        $this->db->query($sql);


        $stored_hashes = array();

        while ($temp = $this->db->fetchassoc()) {
            $stored_hashes[] = $temp['image_hash'];
        }

        #$order = 0;
        foreach ($images as $k => $image) {
            if ( in_array($image['strImageHash'], $stored_hashes) ) {
                $images[$k]['downloaded'] = 1;
            } else {
                $images[$k]['downloaded'] = 0;
            }
        }

    }

    /*
     * Check if the images have been updated
     * @param array $images
     * @param int $internalpropid
     * @return bool
     */
    private function doDownload(&$images, $internalpropid) {

        $do = false;

        foreach ($images as $image) {
            if ( $image['downloaded'] == 0 ) {
                $do = true;
            }
        }

        return $do;
    }

    /*
     * Run through and download images
     * @param array $images
     * @return array
     */
    private function downloadImages(&$images, $internalpropid) {

        $downloaded = array();

        foreach ($images as &$image) {
            if ( $image['downloaded'] == 0 ) {

                $basefilename = basename($image['strURL']);
                $filename = \Util::fixName( $basefilename, $internalpropid );

                // download the original image 
                //$original = $this->downloadImage($image, $filename, $internalpropid);

                // just download the large image for now
                $large = $this->downloadImage($image, $filename, $internalpropid, true);

                // If both were saved
                if ( $large ) {
                    $image['downloaded'] = 1;
                    $downloaded[] = array(
                        'large' => $large,
                        'original' => $original,
                        'hash' => $image['strImageHash'],
                        'caption' => $image['strImageName'],
                    );
                }

            }
        }

        return $downloaded;
    }

    /*
     * Make a directory or do nothing if it exists
     * @param string $dir
     * @return mixed
     */
    private function mkdir($dir) {

        if ( is_dir ($dir ) && is_writable ( $dir ) ) {
            return true;
        }

        if ( !is_dir( $dir ) ) {
            mkdir($dir);
           // chmod($dir, 0775);
        } else {
            //chmod($dir, 0775); //this won't even work unless this is run by root.  it's just erroring out
        }

        if ( !is_dir($dir) || !is_writable( $dir ) ) {
            throw new \Exception('Could not write to ' . $dir . '!!!' );
        }
    }


    /*
     * Download an individual image
     * @param array $image
     * @param string $filename
     * @param int $internalpropid
     * @param bool $large
     * @return bool
     */
    private function downloadImage($image, $filename, $internalpropid, $large=false) {

        $url = $image['strURL'];

        $dir = PROPERTY_PATH.$internalpropid.'/';

        $this->mkdir($dir);

        /*
        if (!is_dir(PROPERTY_PATH.$this->id.'/images'))
            mkdir(PROPERTY_PATH.$this->id.'/images');

        if (!is_dir(PROPERTY_PATH.$this->id.'/thumbs'))
            mkdir(PROPERTY_PATH.$this->id.'/thumbs');
         */


        if ( $large ) {
            $basename = basename($image['strURL']);
            $large = 'L' . $basename;
            $url= str_replace($basename, $large, $image['strURL']);
            
            # Save it in the original dir
            $dir.= 'images/';
        } else {
            $dir.= 'orig/';
        }

        $this->mkdir($dir);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, 0);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        $tmp = curl_exec($ch);
        curl_close($ch);

        if ( !$tmp ) {
            return false;
        }

        $imagepath = $dir . $filename;

        // Ensure we dont overwrite anything
        if ( !file_exists($imagepath) ) {
            $write = file_put_contents( $imagepath, $tmp );
        }

        if ( $write ) {
            return $imagepath;
        } else {
            $message = ('Could not write to ' . $imagepath);
            throw new \Exception($message);
        }

        return false;
    }

    /*
     * Parse the bedding stuff
     * @param object $propertydesc
     * @param object $bedtypelist
     * @param object $bedroomtypelist
     * @return array
     */
    protected function parseBedding($propertydesc, $bedtypelist, $bedroomtypelist) {

        $inserts = array();

        // Create the bedding inserts
        $beddings = $propertydesc->bedding;

        if ( !empty($beddings) ) {

            foreach ($beddings as $bedding) {
                $bedroomtypeid = $bedroomtypelist->getid($bedding['bedroomtype']);

                $bedtypeid = $bedtypelist->getid( $this->stripQuantity($bedding['bedtype']));

                $inserts[] = array(
                    'bedroomtypeid' => $bedroomtypeid,
                    'bedtypeid' => $bedtypeid,
                    'beds' => $this->getQuantity( $bedding['bedtype'] ) ,
                );
            }

        }

        return $inserts;
    }

    /*
     * Save images
     * @param array $images
     * @param int $internalpropid
     * @return void
     */
    private function saveImages($images, $internalpropid) {

        if ( empty( $images ) ) {
            return false;
        }

        if ( !empty($images) && $this->doDownload($images, $internalpropid) ) {

            try {
                $downloaded = $this->downloadImages($images, $internalpropid);
            } catch ( Exception $e ) {
                echo $e->getMessage();
                return false;
            }

            foreach ($downloaded as $download) {
                $this->db->autoInsert('property_images', array(
                    'property' => $internalpropid,
                    'file_name' => basename($download['large']),
                    'caption' => $download['caption'],
                    'date_created' => date('Y-m-d H:i:s'),
                    'is_active' => 1,
                    'image_hash' => $download['hash'],
                ));
            }
        }

        $active_hashes = array();
        $order_id = 0;
        foreach ($images as $image) {
            $active_hashes[] = $image['strImageHash'];

            // update the order
            $this->db->query('UPDATE property_images SET order_id=' . $order_id . ' WHERE property=' . $internalpropid . ' AND image_hash="' . $image['strImageHash'] . '"');

            // update the caption
            $this->db->query('UPDATE property_images SET caption="' . mysql_real_escape_string($image['strImageName']) . '" WHERE property=' . $internalpropid . ' AND image_hash="' . $image['strImageHash'] . '"');

            $order_id++;
        }

        $sql='UPDATE property_images SET is_active=0 WHERE property=' . $internalpropid . ' AND (image_hash IS NULL ';
        if ( !empty ( $active_hashes ) ) {
                $sql.= 'OR image_hash NOT IN ("' . implode('","', $active_hashes) . '")';
        }
        $sql.= ' ) ';
             
        // log the update property_images queries
        $this->logger->log($sql);

        // Deactivate all images not in V12
        $this->db->query($sql);
    }

    /*
     * Save the bedding stuff
     * @param object $propertydesc
     * @param array $amenitylist
     * @param int $internalpropid
     */
    private function saveBedding($propertydesc, $beddinglist, $internalpropid) {
        if(count($beddinglist))
        {

            $inserts = array();
            foreach ($beddinglist as $row) {
                $row= array(
                    '\''.addslashes($row['bedroomtypeid']).'\'',
                    '\''.addslashes($internalpropid).'\'',
                    '\''.addslashes($row['bedtypeid']).'\'',
                    '\''.addslashes($row['beds']).'\'',
                );
                $inserts[] = $row;
            }


            $insertstring = '';

            foreach ($inserts as $row) {
                $insertstring.= '(' . implode(',', $row) . '),';
            }

            $insertstring = trim($insertstring, ',');

            if(count($inserts)) {

                $sql='DELETE FROM bedroomtypes_properties where properties_id='.$internalpropid;
                $deleted = $this->db->Query($sql);

                $sql='insert into bedroomtypes_properties(bedroomtypes_id, properties_id, bedtypes_id, beds) values '.$insertstring;
                $this->db->Query($sql);
            }

        }
    }

    /*
     * Save amenities
     * @param object $propertydesc
     * @param array $amenitylist
     * @param int $internalpropid
     * @return void
     */
    private function saveAmenities($propertydesc, $amenitylist, $internalpropid) {
        if(count($amenitylist) && $internalpropid>0)
        {

            $sql='DELETE FROM amenities_properties where properties_id='.$internalpropid;
            echo $sql;
            $deleted = $this->db->Query($sql);

            $sql='UPDATE properties SET amenities="' . implode(',', $propertydesc->selectedamenities) . '" WHERE id="' . $internalpropid . '"';
            echo $sql;
            $this->db->query($sql);

            $sql='INSERT INTO amenities_properties (properties_id, amenities_id) values '.implode(',',$amenitylist);
            $this->db->Query($sql);

        }
    }


    /*
     * Parse amenities
     * @param object $propertydesc
     * @param string $propertyid
     * @param int $internalpropid
     * @return void
     */
    private function parseAmenities($propertydesc, $propertyid, $internalpropid, $amenitylist) {

        if ( count($propertydesc->selectedamenities) > 0 ) {
            $amenitylist = array();
			$pool = false;
            foreach($propertydesc->selectedamenities as $a)
			{
				$amenitylist[]='('.$internalpropid.','.$a.')';

				// Checks to see that $a is not equal to the "Has Pool" amenity, by default it should never equal "Has Pool"
				// Also checks to see if $a matches one of the requested pool amenity ids,
				// If it does, it adds an amenity id for Has Pool to the end of the amenity array
				if ($a != 191 && in_array($a, array(29, 57, 115, 123, 125, 129, 177, 189)))
				{
					$pool = true;
				}
			}
			if ($pool == true)
			{
				$a = 191;
				$amenitylist[]='('.$internalpropid.','.$a.')';
			}
        }

        return $amenitylist;
    }

    /*
     * Return a property desc if stored
     * @param string $property_id
     * @return mixed
     */
    public function getpropertydesc($property_id) {
        if (!empty($this->fullpropertylist) ) {
            foreach ($this->fullpropertylist as $unit_num => $prop ) {
                if ( $unit_num == $property_id ) {
                    return $this->fullpropertylist[$unit_num]['propertydesc'];
                }
            }
        }

        return false;
    }

    /*
     * Extract the quantity out of a text string
     * Example "1 Queen and Bed Safe", the quantity would be 1
     * @param string $text
     * @return int
     */
    private function getQuantity($text) {
        $pattern = '/^(\d+)\s(\w+)/';

        preg_match($pattern, $text, $matches);

        return !empty($matches) ? $matches[1] : 0;
    }

    /*
     * Strip the quantity
     * @param string $text
     * @return string
     */
    private function stripQuantity($text) {
        $pattern = '/^(\d+)(.*)/';

        preg_match($pattern, $text, $matches);

        return !empty($matches) ? trim($matches[2]) : $text;

    }

    /*
     * Fix the ordering post full import
     * @return void
     */
    private function fixOrdering() {
        $start = 10;
        $this->db->query('UPDATE bedroomtypes SET orderid = ' . ($start+1) . ' WHERE name="Bedding 1"');
        $this->db->query('UPDATE bedroomtypes SET orderid = ' . ($start+2) . ' WHERE name="Bedding 2"');
        $this->db->query('UPDATE bedroomtypes SET orderid = ' . ($start+3) . ' WHERE name="Bedding 3"');
        $this->db->query('UPDATE bedroomtypes SET orderid = ' . ($start+4) . ' WHERE name="Bedding 4"');
        $this->db->query('UPDATE bedroomtypes SET orderid = ' . ($start+5) . ' WHERE name="Bedding 5"');
        $this->db->query('UPDATE bedroomtypes SET orderid = ' . ($start+6) . ' WHERE name="Bedding 6"');
        $this->db->query('UPDATE bedroomtypes SET orderid = ' . ($start+7) . ' WHERE name="Bedding 7"');
        $this->db->query('UPDATE bedroomtypes SET orderid = ' . ($start+8) . ' WHERE name="Bedding 8"');
        $this->db->query('UPDATE bedroomtypes SET orderid = ' . ($start+9) . ' WHERE name="Loft"');
        $this->db->query('UPDATE bedroomtypes SET orderid = ' . ($start+10) . ' WHERE name="Landing"');
        $this->db->query('UPDATE bedroomtypes SET orderid = ' . ($start+11) . ' WHERE name="Extra Bedding"');
    }

    /*
     * Simple update for the order_id which strictly relies on the file_name being NUMBER.jpg
     * Dont have time for an elegant solution
     * @return void
     */
    private function reorderImages() {
        $this->db->query('SELECT id, file_name
            FROM property_images
            WHERE image_hash IS NOT NULL 
            AND order_id = 0
            ');

        $sql = 'UPDATE `property_images` SET order_id = CASE';

        $found = false;

        $ids = array();

        while ( $temp = $this->db->fetchassoc() ) {
            $number = $this->extractNumber($temp['file_name']);
            if ( $number ) {
                $ids[] = $temp['id'];
                $sql.= ' WHEN id = ' . $temp['id'] . ' THEN ' . $number . PHP_EOL;
                $found = true;
            }
        }

        $sql.= '

        ELSE order_id END

        WHERE image_hash IS NOT NULL AND id IN ( ' . implode(',', $ids) . ');';

        // only execute if theres an actual row to update
        if ( $found ) {
            $this->db->query($sql);
        }

    }

    /*
     * Extract number from filename
     * @param string $filename
     * @return int
     */
    private function extractNumber($filename) {
        $pattern = '/(\d+)\.(?:jpg|JPG)/i';

        preg_match($pattern, $filename, $matches);

        if ( !empty ( $matches ) ) {
            return $matches[1];
        }

        return 0;
    }
}
