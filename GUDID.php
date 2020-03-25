<?php
/**
 * Patient selector screen.
 *
 * @package       OpenEMR
 * @author        Brady Miller <brady.g.miller@gmail.com>
 * @copyright (C) 2017 Brady Miller <brady.g.miller@gmail.com>
 * @link          http://www.open-emr.org
 * @license       https://github.com/openemr/openemr/blob/master/LICENSE GNU General Public License 3
 */


 //This allows you to access/run your code without being logged into OpenEMR.
 //Good for testing and development but should be set to true before releasing into the public codebase.
$ignoreAuth = true;


/**Globals.php is located in the openemr/interface folder.
 *  It contains some important code and you'll need to give the path to the file in the parenthesis and double quotes below.
 *  The ../ means go up one folder.  Since we used it 3 times that tells the code to look 3 folders up for globals.php
 *  If we made a sub folder in the current folder holding this code and moved it there, you'd need another ../ if you moved this code up a folder
 *  you'd need one fewer ../ 
 */
require_once("../../../globals.php");

//This tells the code to use the OpenEMR header with the menu options.
use OpenEMR\Core\Header;


//If your code calls another php script or redirects there you'll want to uncomment out the below and send a token in the command line.
//A token can only be used once.  This is used to keep OpenEMR data safe from a cross site scripting attack.
/*
if (!empty($_REQUEST)) {
    if (!verifyCsrfToken($_REQUEST["csrf_token_form"])) {
        csrfNotVerified();
    }
}
*/

//Collect data from page launched by.
//$fstart is the variable name.
//It checks to see if it's set.  If yes, it sets it to the value on the right.

if(isset($_REQUEST['pid'])){
    $pid =  $_REQUEST['pid'];

}else{
    $pid = 'Not Set';
}






//check if medical issue within subtype is set.
 if(isset($_REQUEST['procedure'])){
    $procedure =  $_REQUEST['procedure'];
    
 }else{
    $procedure = 'Not Set';

 }

 //end PHP header
?> 

<!DOCTYPE html>
<html>
<head>
    <title>Implantable Device Parser</title>
    <?php
    // Auto pulls in needed dependencies jquery, bootstrap, theme etc.
    Header::setupHeader(['datetime-picker']);
    ?>

    <?PHP

        $DI = $_POST['DeviceIdentifier'];
        //print($DI);

        //example DI from GS1:  (01)51022222233336(11)141231(17)150707(10)A213B1(21)1234

        $UDI= urlencode($DI);

        //echo $UDI; 

        $apiCall='https://accessgudid.nlm.nih.gov/api/v2/parse_udi.json?udi=' . $UDI; 
        //echo $apiCall;
        
        
        $response = file_get_contents($apiCall);
        echo $response;
        
        //echo gettype($response);

        //var_dump(json_decode($response));
        $Parsed = json_decode($response, true);

        echo $Parsed["udi"];
        echo $Parsed["expirationDate"];

        echo gettype($Parsed["di"]);

        $DevicePage= "<a href=\"https://accessgudid.nlm.nih.gov/devices/" . $Parsed["di"] . "\">Visit Access GUDID Device Information Page</a>";



        
        echo $DevicePage;

        //$jsonData = stripslashes(html_entity_decode($jsonData));

        //$k=json_decode($jsonData,true);

        //print_r($k);


    ?>

    


</head>

<body>

    <FORM NAME ="form1" METHOD ="POST" ACTION = "GUDID.php">


        <INPUT TYPE = "Text" VALUE ="(01)51022222233336(11)141231(17)150707(10)A213B1(21)1234" NAME = "DeviceIdentifier">
        <INPUT TYPE = "Submit" Name = "Submit1" VALUE = "Parse ID">

    </FORM>
 
</body>

</html>
