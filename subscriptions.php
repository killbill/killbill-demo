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
$bundles = $account->getBundles();
?>

    <p>You currenty have <?php echo count($bundles); ?> instances</p>
    <table class="table table-condensed">
        <thead>
        <tr>
            <th>Instance identifier</th>
            <th>Product name</th>
            <th>Subscription start date</th>
            <th>Charted through date</th>
        </tr>
        </thead>
        <tbody>
        <?php
        if ($bundles != null) {
            foreach ($bundles as $bundle) {
                $j = 0;
                $subscriptions = $bundle->subscriptions;
                if ($subscriptions == null) {
                    continue;
                }

                foreach ($subscriptions as $subscription) {
                    echo '
            <tr>
                <td>' . $bundle->externalKey . '</td>
                <td>' . $subscription->productName . '</td>
                <td>' . $subscription->startDate . '</td>
                <td>' . $subscription->chargedThroughDate . '</td>
            </tr>';
                }
            }
        }
        ?>
        </tbody>
    </table>
</div>

<?php
include_once(dirname(__FILE__) . '/includes/footer.php');
?>
