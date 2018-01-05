<?php

use ChurchCRM\dto\SystemConfig;
use ChurchCRM\dto\SystemURLs;

// Set the page title and include HTML header
$sPageTitle = gettext("Survey");
require(SystemURLs::getDocumentRoot(). "/Include/HeaderNotLoggedIn.php");
?>
<script src="<?= SystemURLs::getRootPath()?>/skin/external/survey-jquery/survey.jquery.min.js"></script>
<link rel="stylesheet" href="<?= SystemURLs::getRootPath()?>/skin/external/survey-jquery/survey.css" />
<div class="register-box" style="width: 600px;">
        <div class="register-logo">
            <?php
            $headerHTML = '<b>Church</b>CRM';
            $sHeader = SystemConfig::getValue("sHeader");
            $sChurchName = SystemConfig::getValue("sChurchName");
            $sSurveyName = $surveyDefinition->getName();
            if (!empty($sSurveyName)) {
              $headerHTML = $sSurveyName;
            }
            elseif (!empty($sHeader)) {
                $headerHTML = html_entity_decode($sHeader, ENT_QUOTES);
            } else if (!empty($sChurchName)) {
                $headerHTML = $sChurchName;
            }
            ?>
            <a href="#"><?= $headerHTML ?></a>
        </div>


        <div id='surveyContainer'></div>

    </div>
<script nonce="<?= SystemURLs::getCSPNonce() ?>">
    var surveyJSON = <?= $surveyDefinition->getDefinition() ?>;
$(document).ready(function() {
    //Survey.Survey.cssType = "bootstrap";
    var survey = new Survey.Model(surveyJSON);
    $("#surveyContainer").Survey({
        model:survey,
        onComplete:sendDataToServer
    });
  });
  
function sendDataToServer(survey) {
 
  
  toSend = JSON.stringify({
    responses: survey.data
  });
  console.log(toSend);
  $.ajax({
    method: "POST",
    path: window.CRM.root + "/external/survey/<?= $token->getToken()?>",
    dataType: 'json',
    contentType:  "application/json",
    data:toSend
  });
}
  </script>
<?php
// Add the page footer
require(SystemURLs::getDocumentRoot(). "/Include/FooterNotLoggedIn.php");
