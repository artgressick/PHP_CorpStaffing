<?
	$BF = '';
	$NON_HTML_PAGE=1;
	include($BF.'_lib.php');
	include($BF.'components/add_functions.php');
	function cal_escape($string) {
		return(str_replace(array("\\", ',', ';', "\n", "\r"), array("\\\\", "\\,", "\\;", '\n', ''), $string));
	}
	
	header('Content-type: text/plain');
?>
BEGIN:VCALENDAR
VERSION:2.0
X-WR-CALNAME:<?=str_replace(' ','_',decode($PROJECT_NAME))?>_Schedule

PRODID:-//Apple Computer //<?=decode($PROJECT_NAME)?>//EN
X-WR-TIMEZONE:US/Pacific
CALSCALE:GREGORIAN
LAST-MODIFIED:<?=date('Ymd\Thms')?>Z

<?
	$q = db_query("SELECT chrCalendarQuery FROM CalendarQueries WHERE chrKEY='". $_REQUEST['k'] ."'","Getting Query",1);
		
	$results = db_query(decode($q['chrCalendarQuery']),"Getting Information");

	while($row = mysqli_fetch_assoc($results)) {
?>


BEGIN:VEVENT

DTSTAMP:<?=date('Ymd\Thms', strtotime('now'))?>Z

URL;VALUE=URI:<?=$PROJECT_ADDRESS?>

DTSTART;VALUE=DATE:<?=strftime('%Y%m%d', strtotime($row['dDate']))?>T<?=str_replace(':','',$row['tBegin'])?>

DTEND;VALUE=DATE:<?=strftime('%Y%m%d', strtotime($row['dDate']))?>T<?=str_replace(':','',$row['tEnd'])?>

SUMMARY:<?=$row['chrEvent']?>

DESCRIPTION:You are scheduled to work at:\nEvent: <?=decode($row['chrEvent'])?>\nLocation: <?=decode($row['chrLocation'])?>\nStation: <?=decode($row['chrStation'].' ('.$row['chrNumber'].')')?>\nDate: <?=strftime('%m/%d/%Y', strtotime($row['dDate']))?>\nTimes: <?=date('g:m a',strtotime($row['tBegin']))?> - <?=date('g:m a',strtotime($row['tEnd']))?>

UID:<?=str_replace(' ','_',decode($PROJECT_NAME)).$row['ID']?>

<?		if(false) { ?>

BEGIN:VALARM

ACTION:DISPLAY

X-WR-ALARMUID:ALARM<?=$row['ID']?>

DESCRIPTION:You are scheduled to work at:\nEvent: <?=decode($row['chrEvent'])?>\nLocation: <?=decode($row['chrLocation'])?>\nStation: <?=decode($row['chrStation'].' ('.$row['chrNumber'].')')?>\nDate: <?=strftime('%m/%d/%Y', strtotime($row['dDate']))?>\nTimes: <?=date('g:m a',strtotime($row['tBegin']))?> - <?=date('g:m a',strtotime($row['tEnd']))?>

TRIGGER:-PT15M

END:VALARM
<?		} ?>

END:VEVENT

<?		} ?>

END:VCALENDAR

<?
	$tmp = db_query("UPDATE CalendarQueries SET dtSynced=now() WHERE chrKEY='".$_REQUEST['k']."'","Updating Synced Record");
?>
