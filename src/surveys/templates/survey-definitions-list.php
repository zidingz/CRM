<?php
use ChurchCRM\dto\SystemConfig;
use ChurchCRM\dto\SystemURLs;
require SystemURLs::getDocumentRoot() . '/Include/SimpleConfig.php';
//Set the page title
$sPageTitle = gettext("Survey Definitions List");
include SystemURLs::getDocumentRoot() . '/Include/Header.php';
/**
 * @var $sessionUser \ChurchCRM\User
 */
$sessionUser = $_SESSION['user'];

?>

<div class="box">
  <div class="box-body">
      <table id="surveyDefinitions" class="table table-striped table-bordered data-table" cellspacing="0" width="100%">
          <thead>
          <tr>
              <th><?= gettext('Survey Definition Name') ?></th>
              <th><?= gettext('Owner') ?></th>
              <th><?= gettext('Responses') ?></th>
          </tr>
          </thead>
          <tbody>

      </table>
    
      <form action="#" method="get" class="form">
          <label for="addNewSurveyDefinition"><?= gettext('Add New Survey Definition') ?> :</label>
          <input class="form-control newGroup" name="surveyDefinitionName" id="surveyDefinitionName" style="width:100%">
          <br>
          <button type="button" class="btn btn-primary" id="addNewSurveyDefinition"><?= gettext('Add New Survey Definition') ?></button>
      </form>
  </div>
</div>



<script nonce="<?= SystemURLs::getCSPNonce() ?>">
  $(document).ready(function(){
    window.CRM.surveyDefinitionsDataTable = $("#surveyDefinitions").DataTable({
      "language": {
        "url": window.CRM.plugin.dataTable.language.url
      },
      responsive: true,
      ajax: {
        url: window.CRM.root + "/api/surveys/definitions",
        dataSrc: "SurveyDefinitions"
      },
      columns: [
        {
          width: 'auto',
          title: 'Survey Definition Name',
          data: 'name',
          render: function (data, type, full, meta) {
            return "<a href='"+window.CRM.root+"/surveys/definitions/"+full.survey_definition_id+"/edit'>"+full.name+"</a>"
          }
        }
      ]
    });
  });
</script>


<?php include SystemURLs::getDocumentRoot() . '/Include/Footer.php'; ?>