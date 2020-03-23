<?php


$emailaddresses = array(
    // 'mroyer@palmettodunes.com',
    'amber.yothers@sabre.com',
    'bobby.anderson@sabre.com',
    'esheets@palmettodunes.com', 
    'meder.omuraliev@sabre.com',
    'esheets3025@gmail.com',
    'jdelsandro@palmettodunes.com',
    'kim.cantil@sabre.com'
);
//$emailaddresses = array('bobby.anderson@sabre.com');
$developeremail = 'noreply@sabre.com';
$fromname = 'Palmetto Dunes System';
$subject = 'PalmettoDunes Booking Rate Check: Properties that have not had their rates updated';


$dir = rtrim(dirname(dirname(__FILE__)), '/');
require_once($dir.'/conf/conf.php');

require_once('esite/config/conf.php');
require_once('esite/classes/GenericErrors.class.php');
require_once('esite/classes/Db.Class.php');
require_once('esite/classes/Mailer.Class.php');


$runstart = date('U');

$db = new Db();


$yesterday = time() - 86400; // Today minus 1 day
print $yesterday;


$sql = <<<EOT
SELECT 
      id, property_name, ows_id, date_created, lastraterefresh, lastrateerror, latestrate, lastvalidrate, dailyminrate, weeklyminrate, promotions_id
FROM 
      properties 
WHERE 
      is_deleted = 0 AND is_active = 1 AND (lastraterefresh <= FROM_UNIXTIME({$yesterday}) OR id NOT IN (SELECT DISTINCT properties_id FROM rates) OR lastrateerror = 1)
EOT;

// print $sql;
die();

$db->query($sql);

$message = array('"Property Name", "ID", "Last Rate Refresh", "Date Created", "OWS Error"');

if ($db->GetTotalRows() > 0)
{
	while (($row = $db->fetchAssoc()))
	{
		$message[] = '"' . addslashes($row['property_name']) . '", "' . addslashes($row['id']) . '", "' . addslashes($row['lastraterefresh']) . '", "' . addslashes($row['date_created']) . '", "'.$row['lastrateerror'].'"';
	}
}
if (count($message) > 1)
{
	$message = implode("\n", $message);
	echo $subject . "\n" . $message."\n";
	if (is_array($emailaddresses) && !empty($emailaddresses))
	{
		$mail = new Mailer(array_shift($emailaddresses), $developeremail, $fromname, $subject, $message, FALSE, FALSE);
		foreach ($emailaddresses as $emailaddress)
		{
			$mail->AddAddress($emailaddress);
		}
		$mail->sendMail();
	}
	else
	{
		echo "\nNO EMAIL ADDRESS SET FOR RATE CHECK\n";
	}
}
else
{
	echo "\nNo Rate Error\n";
}

echo 'Total runtime: ' . (date('U') - $runstart) . "\n";
