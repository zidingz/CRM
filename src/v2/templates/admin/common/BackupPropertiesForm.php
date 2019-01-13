<form method="post" action="<?= sRootPath ?>/api/database/backup" id="BackupDatabase">
  <div class="form-group">
    <label><?= gettext('Select archive type') ?>:</label>
    <div class="form-check">
      <label for="BackupType2Check" class="form-check-label"><?= gettext('Database Only (.sql)') ?></label>
      <input id="BackupType2Check" type="radio" name="archiveType" value="2" class="form-check-input" >
    </div>
    <div class="form-check">
      <label for="BackupType3Check" class="form-check-label"><?= gettext('Database and Photos (.tar.gz)') ?></label>
      <input id="BackupType3Check" type="radio" name="archiveType" value="3" checked class="form-check-input">
    </div>
  </div>
  <div class="form-check">
      <label for="encryptBackup" class="form-check-label"><?= gettext('Encrypt backup file with a password?') ?></label>
      <input type="checkbox" id="encryptBackup" class="form-check-input" />
    </div>
  <div class="form-group" id="BackupPasswordEntry" style="display: none;">
    <label for="pw1"><?= gettext('Password') ?></label>:<input type="password" name="pw1" class="form-control">
    <label for="pw2"><?= gettext('Re-type Password') ?></label>:<input type="password" name="pw2" class="form-control">
  </div>
  <div class="form-group">
    <span id="passworderror" style="color: red"></span>
  </div>
  <div class="form-group">
    <input type="button" class="btn btn-primary" id="doBackup" <?= 'value="'.gettext('Generate Backup').'"' ?> class="form-control">
    <input type="button" class="btn btn-primary" id="doRemoteBackup" <?= 'value="'.gettext('Generate and Ship Backup to External Storage').'"' ?> class="form-control">
  </div>
</form>