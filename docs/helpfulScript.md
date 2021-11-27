<?php
        // 1. go to (a site like) https://sandbox.onlinephpfunctions.com/
        // 2. paste this script in
        // 3. fill in the $before and $after arrays
        // 4. run it to get your estimations of removed and added

$before = [
    # add countries here
];

$after = [
    # add countries here
];

$removed = array_diff($before, $after);
$added = array_diff($after, $before);

echo PHP_EOL;
echo 'REMOVED' . PHP_EOL;
echo '-------------------------' . PHP_EOL;
foreach($removed as $country){
    echo $country . PHP_EOL;
}

echo PHP_EOL;
echo 'ADDED' . PHP_EOL;
echo '-------------------------' . PHP_EOL;
foreach($added as $country){
    echo $country . PHP_EOL;
}