<?php


function print_this($data)
{
    if (!isset($data)) {
        $data = [];
    }
    foreach ($data as $value) {
        print_r($value);
        echo "<br><br>";
    }
}


function total_cost($order)
{
    $cost = 0;
    foreach ($order->quantities as $key => $quantities) {
        $cost += ($quantities * $order->products_details[$key][0]);
    }
    return $cost;
}
function money($num)
{
    $explrestunits = "";
    if (strlen($num) > 3) {
        $lastthree = substr($num, strlen($num) - 3, strlen($num));
        $restunits = substr($num, 0, strlen($num) - 3); // extracts the last three digits
        $restunits = (strlen($restunits) % 2 == 1) ? "0" . $restunits : $restunits; // explodes the remaining digits in 2's formats, adds a zero in the beginning to maintain the 2's grouping.
        $expunit = str_split($restunits, 2);
        for ($i = 0; $i < sizeof($expunit); $i++) {
            // creates each of the 2's group and adds a comma to the end
            if ($i == 0) {
                $explrestunits .= (int)$expunit[$i] . ","; // if is first value , convert into integer
            } else {
                $explrestunits .= $expunit[$i] . ",";
            }
        }
        $thecash = $explrestunits . $lastthree;
    } else {
        $thecash = $num;
    }
    $thecash = '&#8377;' . $thecash;
    return $thecash; // writes the final format where $currency is the currency symbol.
}


function moveElement(&$array, $a, $b)
{
    $out = array_splice($array, $a, 1);
    array_splice($array, $b, 0, $out);
}
