<?php
use ChurchCRM\dto\SystemConfig;
use ChurchCRM\dto\SystemURLs;
require SystemURLs::getDocumentRoot() . '/Include/SimpleConfig.php';
//Set the page title
$sPageTitle = gettext("Survey Responses List");
include SystemURLs::getDocumentRoot() . '/Include/Header.php';
/**
 * @var $sessionUser \ChurchCRM\User
 */
$sessionUser = $_SESSION['user'];

?>

<div class="box">
  <div class="box-body">
      <table id="surveyResponses" class="table table-striped table-bordered data-table" cellspacing="0" width="100%">
          <thead>
          <tr>
              <th><?= gettext('Response') ?></th>
              <th><?= gettext('Survey Definition Name') ?></th>
          </tr>
          </thead>
          <tbody>

      </table>
  </div>
</div>


<script src="<?= SystemURLs::getRootPath() ?>/skin/js/Surveys.js"></script>
<script nonce="<?= SystemURLs::getCSPNonce() ?>">
  $(document).ready(function(){
    window.CRM.surveys.ResponsesDataTable = $("#surveyResponses").DataTable({
      "language": {
        "url": window.CRM.plugin.dataTable.language.url
      },
      responsive: true,
      ajax: {
        url: window.CRM.root + "/api/surveys/responses",
        dataSrc: "SurveyResponses"
      },
      columns: [
        {
          width: 'auto',
          title: 'SurveyResponseId',
          data: 'SurveyResponseId',
          render: function (data, type, full, meta) {
            return "<a href='"+window.CRM.root+"/surveys/responses/"+data+"/edit'>"+data+": "+full.DateSubmitted+"</a>"
          }
        },
        {
          width: 'auto',
          title: 'Survey Definition Name',
          data: 'survey_definitionsname',
          render: function (data, type, full, meta) {
            return "<a href='"+window.CRM.root+"/surveys/definitions/"+full.survey_definitionssurvey_definition_id+"'>"+full.survey_definitionsname+"</a>"
          }
        }
      ]
    });
    
    $("#addNewSurveyDefinition").click(function() {
      var surveyDefinitionName = $("#surveyDefinitionName").val();
      window.CRM.surveys.new(surveyDefinitionName).done(function() {
        window.CRM.surveys.DefinitionsDataTable.ajax.reload();
      })
    });
    
  });
</script>


<?php include SystemURLs::getDocumentRoot() . '/Include/Footer.php'; ?>