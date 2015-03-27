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

include_once(dirname(__FILE__) . '/includes/header.php');
include_once(dirname(__FILE__) . '/includes/nav.php');
?>

<div class="container">
<?php
$account = loadAccount($tenantHeaders);

$invoices = $account->getInvoices(false, null, $tenantHeaders);

?>
    <table class="table table-condensed">
        <thead>
        <tr>
            <th>Invoice number</th>
            <th>Invoice date</th>
            <th>Amount</th>
            <th>Credit</th>
            <th>Balance</th>
            <th></th>
        </tr>
        </thead>
        <tbody>
        <?php
        if ($invoices != null) {
            foreach ($invoices as $invoice) {
                echo '
            <tr>
                <td>' . $invoice->invoiceNumber . '</td>
                <td>' . $invoice->invoiceDate . '</td>
                <td>' . $invoice->amount . ' ' . $account->currency . '</td>
                <td>' . $invoice->credit . ' ' . $account->currency . '</td>
                <td>' . $invoice->balance . ' ' . $account->currency . '</td>
                <td><a href="/invoice.php?id=' . $invoice->invoiceId . '" target="_blank">View</a></td>
            </tr>';
            }
        }
        ?>
        </tbody>
    </table>
</div>

<?php
include_once(dirname(__FILE__) . '/includes/footer.php');
?>
