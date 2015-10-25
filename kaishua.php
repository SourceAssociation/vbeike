<?php
switch($_POST['unit'])
{
	case 1:$unit_id=46;$unitSequence=0;break;
	case 2:$unit_id=47;$unitSequence=1;break;
	case 3:$unit_id=48;$unitSequence=2;break;
	case 4:$unit_id=49;$unitSequence=3;break;
	case 5:$unit_id=50;$unitSequence=4;break;
	case 6:$unit_id=51;$unitSequence=5;break;
	case 7:$unit_id=59;$unitSequence=6;break;
	case 8:$unit_id=60;$unitSequence=7;break;
	case 9:$unit_id=53;$unitSequence=8;break;
	case 10:$unit_id=54;$unitSequence=9;break;
	case 11:$unit_id=61;$unitSequence=10;break;
	case 12:$unit_id=55;$unitSequence=11;break;
	case 13:$unit_id=56;$unitSequence=12;break;
	case 14:$unit_id=57;$unitSequence=13;break;
	case 15:$unit_id=62;$unitSequence=14;break;
}
	$sec_id=1;
	for(;$sec_id<=600;$sec_id++)
		{
		echo "<script language='javascript' type='text/javascript'>";  
		echo "myWindow=window.open('http://222.28.61.102/HepStudent/Ajax/ScoreAjax.ashx?Math=0.3937742392634834&levelSequence=7&unitSequence=".$unitSequence."&sectionSequence=3&sectionName=Listening+Task+3&topictype=5&score=100&rolea=100&roleb=100&sectionID=".$sec_id."&unitid=".$unit_id."&passScore=25&timeLimited=00%3A10%3A00&levelID=7&duration=00%3A10%3A09&highWords=&lowWords=&pScore=%E8%89%AF&rScore=%E5%B7%AE&fScore=%E8%89%AF&sScore=%E5%B7%AE&tScore=%E8%89%AF');";  
		echo "</script>";  
		}

	$sec_id=1400;
	for(;$sec_id<=1600;$sec_id++)
		{
		echo "<script language='javascript' type='text/javascript'>";  
		echo "myWindow=window.open('http://222.28.61.102/HepStudent/Ajax/ScoreAjax.ashx?Math=0.3937742392634834&levelSequence=7&unitSequence=".$unitSequence."&sectionSequence=3&sectionName=Listening+Task+3&topictype=5&score=100&rolea=100&roleb=100&sectionID=".$sec_id."&unitid=".$unit_id."&passScore=25&timeLimited=00%3A10%3A00&levelID=7&duration=00%3A10%3A09&highWords=&lowWords=&pScore=%E8%89%AF&rScore=%E5%B7%AE&fScore=%E8%89%AF&sScore=%E5%B7%AE&tScore=%E8%89%AF');";  
		echo "</script>";  
		}
?>
<SCRIPT language=JavaScript>function CONFIRM(){setTimeout('window.location.href="./shua.php"',100);return " ";}document.writeln(CONFIRM())</SCRIPT>
