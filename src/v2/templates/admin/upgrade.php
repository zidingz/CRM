<?php

use ChurchCRM\dto\SystemURLs;
use ChurchCRM\Service\AppIntegrityService;

require SystemURLs::getDocumentRoot() . '/Include/HeaderNotLoggedIn.php';
?>
<div class="col-lg-8 col-lg-offset-2" style="margin-top: 10px">
  <ul class="timeline">
    <li class="time-label">
        <span class="bg-red">
            <?= gettext('Upgrade ChurchCRM') ?>
        </span>
    </li>
    <?php
      if (AppIntegrityService::getIntegrityCheckStatus() == gettext("Failed")) {
          ?>
    <li>
      <i class="fa fa-bomb bg-red"></i>
      <div class="timeline-item" >
        <h3 class="timeline-header"><?= gettext('Warning: Signature mismatch') ?> <span id="status1"></span></h3>
        <div class="timeline-body" id="integrityCheckWarning">
          <p><?= gettext("Some ChurchCRM system files may have been modified since the last installation.")?><b><?= gettext("This upgrade will completely destroy any customizations made to the following files by reverting the files to the official version.")?></b></p>
          <p><?= gettext("If you wish to maintain your changes to these files, please take a manual backup of these files before proceeding with this upgrade, and then manually restore the files after the upgrade is complete.")?></p>
          <div>
              <p><?= gettext('Integrity Check Details:')?> <?=  AppIntegrityService::getIntegrityCheckMessage() ?></p>
                <?php
                  if (count(AppIntegrityService::getFilesFailingIntegrityCheck()) > 0) {
                      ?>
                    <p><?= gettext('Files failing integrity check') ?>:
                    <table class="display responsive no-wrap" width="100%" id="fileIntegrityCheckResultsTable">
                      <thead>
                      <td>FileName</td>
                      <td>Expected Hash</td>
                      <td>Actual Hash</td>
                    </thead>
                      <?php
                      foreach (AppIntegrityService::getFilesFailingIntegrityCheck() as $file) {
                          ?>
                    <tr>
                      <td><?= $file->filename ?></td>
                      <td><?= $file->expectedhash ?></td>
                      <td>
                          <?php
                          if ($file->status == 'File Missing') {
                              echo gettext('File Missing');
                          } else {
                              echo $file->actualhash;
                          } ?>
                      </td>
                    </tr>
                        <?php
                      } ?>
                    </table>
                    <?php
                  } ?>
            </div>
          <input type="button" class="btn btn-primary" id="acceptIntegrityCheckWarking" <?= 'value="'.gettext('I Understand').'"' ?>>
        </div>
      </div>
    </li>
    <?php
      }
    ?>
    <li>
      <i class="fa fa-database bg-blue"></i>
      <div class="timeline-item" >
        <h3 class="timeline-header"><?= gettext('Step 1: Backup Database') ?> <span id="status1"></span></h3>
        <div class="timeline-body" id="backupPhase" <?= AppIntegrityService::getIntegrityCheckStatus() == gettext("Failed") ? 'style="display:none"' : '' ?>>
          <p><?= gettext('Please create a database backup before beginning the upgrade process.')?></p>
          <?php echo $this->fetch('common/BackupPropertiesForm.php', $data); ?>
          <?php echo $this->fetch('common/BackupStatusForm.php', $data); ?>
        </div>
      </div>
    </li>
    <li>
      <i class="fa fa-cloud-download bg-blue"></i>
      <div class="timeline-item" >
        <h3 class="timeline-header"><?= gettext('Step 2: Fetch Update Package on Server') ?> <span id="status2"></span></h3>
        <div class="timeline-body" id="fetchPhase" <?= $_GET['expertmode'] ? '':'style="display: none"' ?>>
          <p><?= gettext('Fetch the latest files from the ChurchCRM GitHub release page')?></p>
          <input type="button" class="btn btn-primary" id="fetchUpdate" <?= 'value="'.gettext('Fetch Update Files').'"' ?> >
        </div>
      </div>
    </li>
    <li>
      <i class="fa fa-cogs bg-blue"></i>
      <div class="timeline-item" >
        <h3 class="timeline-header"><?= gettext('Step 3: Apply Update Package on Server') ?> <span id="status3"></span></h3>
        <div class="timeline-body" id="updatePhase" <?= $_GET['expertmode'] ? '':'style="display: none"' ?>>
          <p><?= gettext('Extract the upgrade archive, and apply the new files')?></p>
          <h4><?= gettext('Release Notes') ?></h4>
          <pre id="releaseNotes"></pre>
          <ul>
            <li><?= gettext('File Name:')?> <span id="updateFileName"> </span></li>
            <li><?= gettext('Full Path:')?> <span id="updateFullPath"> </span></li>
            <li><?= gettext('SHA1:')?> <span id="updateSHA1"> </span></li>
          </ul>
          <br/>
          <input type="button" class="btn btn-warning" id="applyUpdate" value="<?= gettext('Upgrade System') ?>">
        </div>
      </div>
    </li>
    <li>
      <i class="fa fa-sign-in bg-blue"></i>
      <div class="timeline-item" >
        <h3 class="timeline-header"><?= gettext('Step 4: Login') ?></h3>
        <div class="timeline-body" id="finalPhase" <?= $_GET['expertmode'] ? '':'style="display: none"' ?>>
          <a href="Logoff.php" class="btn btn-primary"><?= gettext('Login to Upgraded System') ?> </a>
        </div>
      </div>
    </li>
  </ul>
</div>
<script nonce="<?= SystemURLs::getCSPNonce() ?>">
$(document).ready(function() {
  
    $("#fileIntegrityCheckResultsTable").DataTable({
      responsive: true,
      paging:false,
      searching: false
    });

    $("#acceptIntegrityCheckWarking").click(function() {
      $("#integrityCheckWarning").slideUp();
      $("#backupPhase").show("slow");
    });
 

    $("#fetchUpdate").click(function(){
      $("#status2").html('<i class="fa fa-circle-o-notch fa-spin"></i>');
      window.CRM.APIRequest({
        type : 'GET',
        path  : 'systemupgrade/downloadlatestrelease',
      }).done(function(data){
        $("#status2").html('<i class="fa fa-check" style="color:green"></i>');
        window.CRM.updateFile=data;
        $("#updateFileName").text(data.fileName);
        $("#updateFullPath").text(data.fullPath);
        $("#releaseNotes").text(data.releaseNotes);
        $("#updateSHA1").text(data.sha1);
        $("#fetchPhase").slideUp();
        $("#updatePhase").show("slow");
      });
    });

    $("#applyUpdate").click(function(){
      $("#status3").html('<i class="fa fa-circle-o-notch fa-spin"></i>');
      window.CRM.APIRequest({
        method : 'POST',
        path : 'systemupgrade/doupgrade',
        data : JSON.stringify({
          fullPath: window.CRM.updateFile.fullPath,
          sha1: window.CRM.updateFile.sha1
         })
      }).done(function(data){
        $("#status3").html('<i class="fa fa-check" style="color:green"></i>');
        $("#updatePhase").slideUp();
        $("#finalPhase").show("slow");
      });
    });
 
    $(document).on("click","#downloadbutton",function(){
      $("#fetchPhase").show("slow");
      $("#backupPhase").slideUp();
      $("#status1").html('<i class="fa fa-check" style="color:green"></i>');
    });
});

</script>
<script src="<?= SystemURLs::getRootPath() ?>/skin/js/BackupDatabase.js"></script>
<script src="<?= SystemURLs::getRootPath() ?>/skin/js/CRMJSOM.js"></script>
<script src="<?= SystemURLs::getRootPath() ?>/skin/external/datatables/pdfmake.min.js"></script>
<script src="<?= SystemURLs::getRootPath() ?>/skin/external/datatables/vfs_fonts.js"></script>
<script src="<?= SystemURLs::getRootPath() ?>/skin/external/datatables/datatables.min.js"></script>

<?php include SystemURLs::getDocumentRoot() . '/Include/FooterNotLoggedIn.php'; ?>
