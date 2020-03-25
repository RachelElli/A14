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

        $UDI = $_POST['DeviceIdentifier'];
        //print($DI);

        //example DI from GS1:  (01)51022222233336(11)141231(17)150707(10)A213B1(21)1234

        $UDI_URL= urlencode($UDI);

        //echo $UDI; 

        $apiCall='https://accessgudid.nlm.nih.gov/api/v2/parse_udi.json?udi=' . $UDI_URL; 
        //echo $apiCall;
        
        
        $response = file_get_contents($apiCall);
        echo $response . nl2br("\n \n");

 
        
        //echo gettype($response);

        //var_dump(json_decode($response));
        $Parsed = json_decode($response, true);
        //echo $Parsed . nl2br("\n \n");

        echo $Parsed["udi"] . nl2br("\n \n");
        echo $Parsed["expirationDate"] . nl2br("\n \n");

        $IssuingAgency=$Parsed["issuingAgency"];
        echo $IssuingAgency . nl2br("\n \n");


        $DI=$Parsed["di"];
        echo $DI . nl2br("\n \n");


        $SerialNo=$Parsed["serialNumber"];
        echo $SerialNo . nl2br("\n \n");

        $ExpDate=$Parsed["expirationDate"];
        echo $ExpDate . nl2br("\n \n");

        $ManufactureDate=$Parsed["manufacturingDate"];
        echo $ManufactureDate . nl2br("\n \n");

        $LotNo=$Parsed["lotNumber"];
        echo $LotNot . nl2br("\n \n");




        $DevicePage= "<a href=\"https://accessgudid.nlm.nih.gov/devices/" . $Parsed["di"] . "\">Visit Access GUDID Device Information Page</a>";



        //Okay, now let's use the other API, Device lookup to query for information about the specific device.


        //Reset DI to something real here.
        //$DI="08717648200274";
        $DI="00814008024049";
        $apiCall='https://accessgudid.nlm.nih.gov/api/v2/devices/lookup.json?di=' . $DI; 
        echo $apiCall;
        
        
        $response = file_get_contents($apiCall);
        echo $response . nl2br("\n \n");

 
        
        //echo gettype($response);

        var_dump(json_decode($response));
        $LookedUp = json_decode($response, true);
        echo $LookedUp . nl2br("\n \n");

        echo $LookedUp["gudid"] . nl2br("\n \n");
        $gudid=$LookedUp["gudid"];

        echo $gudid["device"] . nl2br("\n \n");
        $device=$gudid["device"];


        echo $device["deviceDescription"] . nl2br("\n \n");
        $deviceDescription=$device["deviceDescription"];

        echo $device["brandName"] . nl2br("\n \n");
        $brandName=$device["brandName"];

        echo $device["companyName"] . nl2br("\n \n");
        $companyName=$device["companyName"];        

        echo $device["catalogNumber"] . nl2br("\n \n");
        $catalogNumber=$device["catalogNumber"];


        echo $device["singleUse"] . nl2br("\n \n");
        $singleUse=$device["singleUse"];

        echo $device["MRISafetyStatus"] . nl2br("\n \n");
        $MRISafetyStatus=$device["MRISafetyStatus"];        

        //has latex, latex allergy safe?
        $LabeledContainsNRL=$device["labeledContainsNRL"];//natural rubber latex or dry natural rubber
        $LabeledNotMadeWithNRL=$device["labeledNoNRL"];


        echo $device["rx"] . nl2br("\n \n");
        $RequiresPrescription=$device["rx"];  

        echo $device["otc"] . nl2br("\n \n");
        $OverTheCounter=$device["otc"];


        //Now let's pull out the contact info for the device
        $contacts=$device["contacts"];
        echo $contacts;
        echo $contacts["customerContact"];
        $cContacts=$contacts["customerContact"];
        echo $cContacts;
        echo $cContacts[0];
        $cContacts1=$cContacts[0];
        echo $cContacts1["phone"] . nl2br("\n \n");

        $phone=$cContacts1["phone"];
        $phoneExtension=$cContacts1["phoneExtension"];
        $email=$cContacts1["email"];


        //Now let's get the full product name and description
        $gmdnTerms=$device["gmdnTerms"];
        $gmdn=$gmdnTerms["gmdn"];
        $gmdn0=$gmdn[0];

        $gmdnPTName=$gmdn0["gmdnPTName"];
        $gmdnPTDefinition=$gmdn0["gmdnPTDefinition"];

        echo $gmdnPTDefinition . nl2br("\n \n");



        //Okay, now let's collect data on environmental conditions.
        $environmentalConditions=$device["environmentalConditions"];

        //array
        $storageConditions=$environmentalConditions["storageHandling"];


        //Next let's make a note of sterilization
        $sterilization=$device["sterilization"];

        //true false
        $isSterile=$sterilization["deviceSterile"];
        $sterilizeBeforeUse=$sterilization["sterilizationPriorToUse"];
        $steriliationMethodTypes=$sterilization["methodTypes"];
        $sterilizationMethod=$steriliationMethodTypes["sterilizationMethod"];


        echo $isSterile;
        echo $sterilizeBeforeUse;
        echo $sterilizationMethod[0];



    ?>

    


</head>

<body>

    <FORM NAME ="form1" METHOD ="POST" ACTION = "ImplantNewDevice.php">


        <INPUT TYPE = "Text" VALUE ="(01)00208851107345(11)141231(17)150707(10)A213B1(21)1234" NAME = "DeviceIdentifier">
        <INPUT TYPE = "Submit" Name = "Submit1" VALUE = "Parse ID">

    </FORM>
 
</body>

</html>
