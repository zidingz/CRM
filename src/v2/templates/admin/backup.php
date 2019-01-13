<?php

use ChurchCRM\dto\SystemConfig;
use ChurchCRM\dto\SystemURLs;
use ChurchCRM\Service\AppIntegrityService;
use ChurchCRM\Service\SystemService;

//Set the page title
include SystemURLs::getDocumentRoot() . '/Include/Header.php';
?>
<div class="box">
    <div class="box-header">
        <h3 class="box-title"><?= gettext('This tool will assist you in manually backing up the ChurchCRM database.') ?></h3>
    </div>
    <div class="box-body">
        <ul>
        <li><?= gettext('You should make a manual backup at least once a week unless you already have a regular backup procedule for your systems.') ?></li><br>
        <li><?= gettext('After you download the backup file, you should make two copies. Put one of them in a fire-proof safe on-site and the other in a safe location off-site.') ?></li><br>
        <li><?= gettext('If you are concerned about confidentiality of data stored in the ChurchCRM database, you should encrypt the backup data if it will be stored somewhere potentially accessible to others') ?></li><br>
        </ul>
        <?php echo $this->fetch('common/BackupPropertiesForm.php', $data); ?>
    </div>
</div>
<?php echo $this->fetch('common/BackupStatusForm.php', $data); ?>

<script src="<?= SystemURLs::getRootPath() ?>/skin/js/BackupDatabase.js"></script>

<?php include SystemURLs::getDocumentRoot() . '/Include/Footer.php'; ?>
