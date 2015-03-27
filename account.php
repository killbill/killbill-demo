<?php
/*
 * Copyright 2011-2012 Ning, Inc.
 *
 * Ning licenses this file to you under the Apache License, version 2.0
 * (the "License"); you may not use this file except in compliance with the
 * License.  You may obtain a copy of the License at:
 *
 *    http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS, WITHOUT
 * WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.  See the
 * License for the specific language governing permissions and limitations
 * under the License.
 */

require_once(dirname(__FILE__) . '/killbill-client-php/lib/killbill.php');

include_once(dirname(__FILE__) . '/includes/client.php');

session_start();

$account_created = null;
$account_updated = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $accountData = new Killbill_Account();
    $accountData->name = $_POST['name'];
    $accountData->email = $_POST['email'];
    $accountData->currency = $_POST['currency'];
    $accountData->paymentMethodId = null;
    $accountData->address1 = $_POST['address1'];
    $accountData->address2 = $_POST['address2'];
    $accountData->state = $_POST['state'];
    $accountData->country = $_POST['country'];
    $accountData->phone = $_POST['phone'];
    $accountData->length = strlen($accountData->name);
    $accountData->timeZone = 'UTC'; //$_POST['timeZone'];

    if (!isset($_SESSION['accountId'])) {
        // Create the account
        // The external key is not used in this example
        $accountData->externalKey = uniqid();
        $account = $accountData->create("web-user", "PHP_TEST", "Test for the demo", $tenantHeaders);

        if ($account != null && $account->accountId != null) {
            $_SESSION['accountId'] = $account->accountId;
            $account_created = TRUE;
        } else {
            $account = new Killbill_Account();
            $account_created = FALSE;
        }
    } else {
        // Update the account
        $accountData->accountId = $_SESSION['accountId'];

        // TODO API needs to be fixed
        $currentAccount = $accountData->get($tenantHeaders);
        $accountData->externalKey = $currentAccount->externalKey;

        $account = $accountData->update("web-user", "PHP_TEST", "Test for the demo", $tenantHeaders);

        if ($account != null) {
            $account_updated = TRUE;
        } else {
            $account = new Killbill_Account();
            $account_updated = FALSE;
        }
    }
} else {
    $account = new Killbill_Account();

    if (isset($_SESSION['accountId'])) {
        $account->accountId = $_SESSION['accountId'];
        $account = $account->get($tenantHeaders);
    }
}

include_once(dirname(__FILE__) . '/includes/header.php');
include_once(dirname(__FILE__) . '/includes/nav.php');
?>

<div class="container">
<?php
if ($account_created === FALSE) {
    ?>
        <div class="alert alert-error">
            Your account couldn't be created :(
        </div>
    <?php

} elseif ($account_created === TRUE) {
    ?>
     <div class="alert alert-success">
      You account has been created! Your account id is <?php echo $account->accountId; ?>
      </div>
    <?php
    if ($account->currency === 'BTC') {
        header('Location: /payment_method.php');
    }
    ?>
    <?php

} elseif ($account_updated === FALSE) {
    ?>
        <div class="alert alert-error">
            Your account couldn't be updated :(
        </div>
    <?php

} elseif ($account_updated === TRUE) {
    ?>
        <div class="alert alert-success">
            You account has been updated!
        </div>
    <?php

}
?>
    <form class="form-horizontal" method="post" action="account.php">
        <fieldset>
            <legend>Your account in Killbill</legend>
        <?php if (isset($_SESSION['accountId'])) { ?>
            <div class="control-group">
                <label class="control-label">Account id</label>

                <div class="controls">
                    <span class="input-xlarge uneditable-input"><?php echo $account->accountId; ?></span>
                </div>
            </div>
        <?php } ?>

            <div class="control-group">
                <label class="control-label" for="name">Name</label>

                <div class="controls">
                    <input type="text" class="input-xlarge" id="name" name="name" value="<?php echo $account->name; ?>">

                    <p class="help-block">Your full name, e.g. Bob Smith</p>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="email">Email</label>

                <div class="controls">
                    <input type="text" class="input-xlarge" id="email" name="email"
                           value="<?php echo $account->email; ?>">

                    <p class="help-block">Primary email associated with your account, e.g. bob@company.com. This will
                        be used for invoice notifications</p>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="currency">Currency</label>

                <div class="controls">
                    <select id="currency" name="currency">
                        <option>BTC</option>
                        <option>USD</option>
                        <option>AUD</option>
                        <option>BRL</option>
                        <option>EUR</option>
                        <option>GBP</option>
                        <option>MXN</option>
                    </select>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="address1">Address</label>

                <div class="controls">
                    <input type="text" class="input-xlarge" id="address1" name="address1"
                           value="<?php echo $account->address1; ?>">

                    <p class="help-block">Primary address associated with your account, e.g. 202 Mission Street</p>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="address2">Address (ctd.)</label>

                <div class="controls">
                    <input type="text" class="input-xlarge" id="address2" name="address2"
                           value="<?php echo $account->address2; ?>">

                    <p class="help-block">Additional address information, e.g. Apt 12</p>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="state">State</label>

                <div class="controls">
                    <input type="text" class="input-xlarge" id="state" name="state"
                           value="<?php echo $account->state; ?>">

                    <p class="help-block">Your state, e.g. CA</p>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="country">Country</label>

                <div class="controls">
                    <input type="text" class="input-xlarge" id="country" name="country"
                           value="<?php echo $account->country; ?>">

                    <p class="help-block">Your country, e.g. USA</p>
                </div>
            </div>
            <div class="control-group">
                <label class="control-label" for="phone">Phone</label>

                <div class="controls">
                    <input type="text" class="input-xlarge" id="phone" name="phone"
                           value="<?php echo $account->phone; ?>">

                    <p class="help-block">Your primary phone number, e.g. 555-122-3491</p>
                </div>
            </div>
            <div class="form-actions">
                <input type="submit" class="btn btn-primary" value="<?php if ($account->accountId != null) {
                    echo 'Update';
                } else {
                    echo 'Create';
                }; ?>">
            </div>
        </fieldset>
    </form>
</div>
<!-- /container -->

<?php
include_once(dirname(__FILE__) . '/includes/footer.php');
?>
