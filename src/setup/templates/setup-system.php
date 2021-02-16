<?php

use ChurchCRM\dto\SystemURLs;

$URL = 'http' . (isset($_SERVER['HTTPS']) ? 's' : '') . '://' . $_SERVER['HTTP_HOST'] . '/';

$sPageTitle = 'ChurchCRM – Setup';
require '../Include/HeaderNotLoggedIn.php';
?>
<script nonce="<?= SystemURLs::getCSPNonce() ?>">
    window.CRM = {
        root: "<?= SystemURLs::getRootPath() ?>"
    };
</script>
<style>
    .wizard .content > .body {
        width: 100%;
        height: auto;
        padding: 15px;
        position: relative;
    }

</style>
<h1 class="text-center">System Setup</h1>
<p/><br/>
<form id="setup-form">
    <div id="wizard">
        <h2>Church Info</h2>
        <section>
            <div class="form-group">
                <label for="churchName">Name</label>
                <input type="text" name="Name" id="churchName" class="form-control" required>
                <br/>
                <label for="address">Address</label>
                <input type="text" name="Address" id="address" class="form-control" required>
                <br/>
                <label for="city">City</label>
                <input type="text" name="City" id="city" class="form-control" required>
                <br/>
                <label for="state">State</label>
                <input type="text" name="State" id="state" class="form-control" required>
                <br/>
                <label for="country">Country</label>
                <input type="text" name="Country" id="country" class="form-control" required>
                <br/>
                <label for="churchPhone">Main Phone</label>
                <input type="text" name="Church Phone" id="churchPhone" class="form-control" required>
                <br/>
                <label for="churchEmail">Email</label>
                <input type="email" name="Church Email" id="churchEmail" class="form-control" required>
            </div>
        </section>

        <h2>Admin Info</h2>
        <section>
            <div class="form-group">
                <label for="firstName">First Name</label>
                <input type="text" name="firstName" id="firstName" class="form-control" required>
                <br/>
                <label for="lastName">Last Name</label>
                <input type="text" name="lastName" id="lastName" class="form-control" required>
                <br/>
                <label for="email">Email</label>
                <input type="email" id="email" class="form-control" required>
                <br/>
                <label for="phoneNumber">Phone Number</label>
                <input type="text" id="phoneNumber" class="form-control">

            </div>
        </section>


    </div>
</form>
<script src="<?= SystemURLs::getRootPath() ?>/skin/external/jquery.steps/jquery.steps.min.js"></script>
<script src="<?= SystemURLs::getRootPath() ?>/skin/external/jquery-validation/jquery.validate.min.js"></script>
<script src="<?= SystemURLs::getRootPath() ?>/skin/js/setup-system.js"></script>

<?php
require '../Include/FooterNotLoggedIn.php';
?>
