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
    <table class="table table-condensed">
        <thead>
        <tr>
            <th>Instance name</th>
            <th>Description</th>
            <th>Price</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php
        $account = new Killbill_Account();
        $account->accountId = $_SESSION['accountId'];
        $account = $account->get();
        $bundles = $account->getBundles();
//        foreach ($catalog->getPlans() as $name => $plan) {
        foreach (array('mini', 'plus', 'pro') as $name => $plan) {
            echo '
            <tr>
                <td></td>
                <td></td>
                <td></td>
                <td><input type="text" class="input-mini" id="input1" value="0"></td>
            </tr>';
        }
        ?>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td><input type="submit" class="btn btn-primary" value="Purchase"></td>
        </tr>
        </tbody>
    </table>
</div>

<?php
include_once(dirname(__FILE__) . '/includes/footer.php');
?>
