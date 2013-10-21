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

<?php
function extract_phase_price($prices, $currency)
{
    if (empty($prices)) {
        return 0;
    } else {
        foreach ($prices as $p) {
            if ($p->currency == $currency) {
                return $p->value;
            }
        }
        // Meaning this is not defined for that currency
        return -1;
    }
}

?>

<div class="container">
    <form class="form-horizontal" method="post" action="purchase.php">
        <table class="table table-condensed">
            <thead>
            <tr>
                <th>Instance type</th>
                <th>Plan</th>
                <th>Description</th>
                <th>Quantity</th>
            </tr>
            </thead>
            <tbody>
            <?php
            $account = loadAccount();
            $currency = $account->currency;
            $catalog = loadCatalog();

            $i = 0;
            foreach ($catalog->getBaseProducts() as $product) {
                $j = 0;
                foreach ($product->plans as $plan) {
                    $i += 1;

                    $description = '';
                    $j = 0;
                    foreach ($plan->phases as $phase) {
                        if ($j > 0) {
                            $description .= ' then ';
                        }
                        $description .= $phase->type . ' phase at <strong>' . extract_phase_price($phase->prices, $currency) . ' ' . $currency . '</strong>';
                        $j++;
                    }

                    echo '
            <tr>
                <td>' . $product->name . '
                    <input id="product_' . $i . '" name="product_' . $i . '" type="hidden" value="' . $product->name . '">
                    <input id="category_' . $i . '" name="category_' . $i . '" type="hidden" value="' . $product->type . '">
                </td>
                <td>' . $plan->name . '</td>
                <td>' . $description . '</td>
                <td><input id="quantity_' . $i . '" name="quantity_' . $i . '" type="text" class="input-mini" value="0"></td>
            </tr>';
                }
            }
            echo '
            <tr>
                <td></td>
                <td></td>
                <td><input id="nb_plans" name="nb_plans" type="hidden" value="' . $i . '"></td>
                <td><input type="submit" class="btn btn-primary" value="Purchase"></td>
            </tr>';
            ?>
            </tbody>
        </table>
    </form>
</div>

<?php
include_once(dirname(__FILE__) . '/includes/footer.php');
?>
