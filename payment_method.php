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
require_once(dirname(__FILE__) . '/util.php');

ensureLoggedIn();

include_once(dirname(__FILE__) . '/includes/header.php');
include_once(dirname(__FILE__) . '/includes/nav.php');
?>

<div class="container">
<?php

$account = loadAccount();


$pm_created = null;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
  $paymentMethodData = new Killbill_PaymentMethod();
  $paymentMethodData->accountId = $account->accountId;
  $paymentMethodData->isDefault = true;
  $paymentMethodData->pluginName = 'killbill-coinbase';

  $propApiKey = new Killbill_PaymentMethodProperties();
  $propApiKey->key = 'apiKey';
  $propApiKey->value = $_POST['btcApiKey'];
  $propApiKey->isUpdatable = false;

  $paymentMethodData->pluginInfo = new Killbill_PaymentMethodPluginDetailAttributes();
  $paymentMethodData->pluginInfo->properties = array($propApiKey);

  $paymentMethod = $paymentMethodData->create("web-user", "PHP_TEST", "Test for the demo");
  if ($paymentMethod->paymentMethodId != null) {
      $pm_created = TRUE;
  } else {
      $pm_created = FALSE;
  }
} else {
    $paymentMethod = new Killbill_PaymentMethod();
}
?>


<div class="container">
<?php
if ($pm_created === FALSE) {
?>
<div class="alert alert-error">
  Your payment method count couldn't be created :(
</div>
<?php
} elseif ($pm_created === TRUE) {
// Redirect the user to his subscriptions page
//header('Location: /payment_method.php');
?>
<div class="alert alert-success">
  You payment method has been created! Your payment method id is <?php echo $paymentMethod->paymentMethodId; ?>
</div>
<?php
}
?>

 <form class="form-horizontal" method="post" action="payment_method.php">
   <fieldset>
             <legend>Enter your payment method:</legend>
            <div class="control-group">
                <label class="control-label" for="btcApiKey">BTC API Key</label>

                <div class="controls">
                    <input type="text" class="input-xlarge" id="btcApiKey" name="btcApiKey"
                           value="">
                    <p class="help-block">The API key you got from your hosted wallet provider</p>
                </div>
            </div>
   </fieldset>
     </form>
 </div>
 <!-- /container -->


<?php
include_once(dirname(__FILE__) . '/includes/footer.php');
?>
