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

include_once(dirname(__FILE__) . '/includes/client.php');

ensureLoggedIn();

date_default_timezone_set('UTC');

function iso8601($time = false) {
    if (!$time) $time = time();
    return date("Y-m-d", $time);
}

$account = new Killbill_Account();
$account->accountId = $_SESSION['accountId'];
$account = $account->get($tenantHeaders);

for ($i = 1; $i <= $_POST['nb_plans']; $i++) {
    $quantity = intval($_POST['quantity_' . $i]);
    if ($quantity <= 0) {
        continue;
    }

    for ($j = 1; $j <= $quantity; $j++) {
        $externalBundleId = uniqid();

        // Associate a subscription
        $subscriptionData = new Killbill_Subscription();
        $subscriptionData->accountId =  $account->accountId;
        $subscriptionData->externalKey = $externalBundleId;
        $subscriptionData->startDate = iso8601();
        $subscriptionData->productName = $_POST['product_' . $i];
        $subscriptionData->productCategory = $_POST['category_' . $i];
        $subscriptionData->billingPeriod = "MONTHLY";
        $subscriptionData->priceList = "DEFAULT";

        $subscription = $subscriptionData->createAndWait(true, "pierre", "PHP_TEST", "Test for " . $externalBundleId, $tenantHeaders);
    }
}

// Redirect the user to his subscriptions page
header('Location: subscriptions.php');
?>