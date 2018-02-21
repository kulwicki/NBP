<?php

$from = date('Y-m-d', strtotime('- 1 years'));
$to = date('Y-m-d');

$ch = curl_init('http://api.nbp.pl/api/cenyzlota/' . $from . '/' . $to . '/?format=json');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

$res = curl_exec($ch);
curl_close($ch);

$json = json_decode($res, true);

$courses = [];
$dates = [];
foreach ($json as $item) {
    $courses[] = $item['cena'];
    $dates[] = $item['data'];
}

$buy = '';
$sell = '';
$diff = 0;

foreach ($courses as $key => $course) {
    $min = $course;

    $afterCourses = array_slice($courses, $key + 1);
    if (count($afterCourses)) {
        $max = max($afterCourses);
        $maxKey = array_keys($afterCourses, $max)[0];

        if ($newDiff = ($max - $min) > $diff) {
            $diff = $newDiff;

            $buy = $dates[$key];
            $sell = $dates[$maxKey];
        }
    }
}

?>

<h4>Best time to buy</h4>
<?= $buy ?>

<h4>Best time to sell</h4>
<?= $sell ?>
