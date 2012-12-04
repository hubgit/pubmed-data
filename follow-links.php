<?php

$input = fopen('data/links.csv', 'r');
$output = fopen('data/followed-links.csv', 'w');

while (($row = fgetcsv($input)) !== false) {
	print_r($row);

	list($id, $url) = $row;

	$headers = get_headers($url, 1);
	print_r($headers);

	$location = (array) $headers['Location'];
	array_unshift($location, $id, $url);
	print_r($location);

	fputcsv($output, $location);
}

fclose($input);
fclose($output);
