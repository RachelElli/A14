<?php
/**
 * Patient selector screen.
 *
 * @package       OpenEMR
 * @author        Rachel Ellison <ellison.rachel.e@gmail.com>
 * @copyright (C) 2020 Rachel Ellison <ellison.rachel.e@gmail.com>
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

if($pid != 'Not Set'){
    $sql = "SELECT `title` FROM `lists` WHERE `pid` = ? AND `type` = 'surgery'";

    //Use me if you're using the user selected subtype by placing it as a question mark.
    //$result = sqlStatement($sql, array($user_selected_subtype));

    // //Get pretty medical problem subtype list to display
    // $result = sqlStatement($sql, array($pid));
    // $display_row = array();
    // while ($row = sqlFetchArray($result)) {
    //     $display_row[] = "<td>" .  $row['title'] . "</td>";
    // }//while




    //Now let's get all the other relevant patient data and use PHP to put it in a nice displayable format to call from html later.
    //Get list of patient IDs of patients with medical issue selected in address bar.  Note that the subtype is broken at the moment so we have to go by title.  Which can catch when someone types the disease instead of selecting from dropdown.
    $sql="SELECT `id`, `title`, `date`,`begdate`, `enddate`, `occurrence`, `severity_al`, `extrainfo`, `destination`,`classification`,`referredby`, `comments` FROM `lists` WHERE `pid` = ? AND `type` = 'surgery'";
    $result = sqlStatement($sql, array($pid));
    while ($row = sqlFetchArray($result)) {
        $list_surgery_attributes_link[] = "<td>" . "<a href= \"GUDID.php?pid=1&Surgery1=" . $row['id'] . "\"> ". $row['id'] . "</a>" ."</td>";
        $list_surgery_attributes_issueID[] = "<td>" . $row['id'] . "</td>";
        $list_surgery_attributes_title[] = "<td>" . $row['title'] . "</td>";
        $list_surgery_attributes_date[] = "<td>" . $row['date'] . "</td>";
        $list_surgery_attributes_begdate[] = "<td>" . $row['begdate'] . "</td>";
        $list_surgery_attributes_enddate[] = "<td>" . $row['enddate'] . "</td>";
        $list_surgery_attributes_occurence[] = "<td>" . $row['occurence'] . "</td>";
        $list_surgery_attributes_severity[] = "<td>" . $row['severity_al'] . "</td>";
        $list_surgery_attributes_extra[] = "<td>" . $row['extrainfo'] . "</td>";
        $list_surgery_attributes_destination[] = "<td>" .  $row['destination'] . "</td>";
        $list_surgery_attributes_classification[] = "<td>" .  $row['classification'] . "</td>";
        $list_surgery_attributes_referredby[] = "<td>" . $row['referredby'] . "</td>";
        $list_surgery_attributes_comments[] = "<td>" . $row['comments'] . "</td>";



    }




}//Is set PID if




/*
//check if medical issue within subtype is set.
 if(isset($_REQUEST['procedure'])){
    $procedure =  $_REQUEST['procedure'];
    
 }else{
    $procedure = 'Not Set';

 }
 */

 //end PHP header
?> 

<!DOCTYPE html>
<html>
<head>
    <title>Display Patient Surgeries</title>
    <?php
    // Auto pulls in needed dependencies jquery, bootstrap, theme etc.
    Header::setupHeader(['datetime-picker']);
    ?>

    <?PHP

        

    ?>

    


</head>

<body>

    <!-- <FORM NAME ="form1" METHOD ="POST" ACTION = "GUDID.php">


        <INPUT TYPE = "Text" VALUE ="(01)51022222233336(11)141231(17)150707(10)A213B1(21)1234" NAME = "DeviceIdentifier">
        <INPUT TYPE = "Submit" Name = "Submit1" VALUE = "Parse ID">

    </FORM> -->

    <!-- <div class="form-group">
            <select class="form-control" id="subtype_select">
                <option>Default Search</option>

                <?php
                foreach ($display_row as $item) {
                    echo "<option>" . $item . "</option>\n";
                }
                ?>
            </select>

    </div>
  -->

    <table class="table">
            <thead>
                <tr>
                <th scope="col">Link</th>               
                <th scope="col">Issue ID</th>               
                <th scope="col">Title</th>
                <th scope="col">Date</th>
                <th scope="col">Begin Date</th>
                <th scope="col">End Date</th>
                <th scope="col">Occurence</th>
                <th scope="col">Severity</th>
                <th scope="col">Extra Info</th>
                <th scope="col">Destination</th>
                <th scope="col">Classification</th>
                <th scope="col">Referred By</th>
                <th scope="col">Comments</th>
                </tr>
            </thead>
            <tbody>
                <tc>


                
                <?php
                    echo "<tr>";
                    for($b=0;$b<count($list_surgery_attributes_date);$b++){
                        //echo $display_row[$b];
                        echo $list_surgery_attributes_link[$b];
                        echo $list_surgery_attributes_issueID[$b];
                        echo $list_surgery_attributes_title[$b];
                        echo $list_surgery_attributes_date[$b];
                        echo $list_surgery_attributes_begdate[$b];
                        echo $list_surgery_attributes_enddate[$b];
                        echo $list_surgery_attributes_occurence[$b];
                        echo $list_surgery_attributes_severity[$b];
                        echo $list_surgery_attributes_extra[$b];
                        echo $list_surgery_attributes_destination[$b];
                        echo $list_surgery_attributes_classification[$b];
                        echo $list_surgery_attributes_referredby[$b];
                        echo $list_surgery_attributes_comments[$b];


                        echo "</tr>";
                    }
                    
                ?>
                
         
            </tbody>
        </table>


</body>

</html>
