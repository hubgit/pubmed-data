<?php

$dom = new DOMDocument;

foreach (range(1980, 2012) as $year) {
	$input = fopen("compress.zlib://ids/articles-$year.txt.gz", 'r');
	$output = fopen("lcompress.zlib://inks/articles-$year.txt.gz", 'w');

	while (($row = fgetcsv($input)) !== false) {
		list($id) = $row;

		$params = array('dbfrom' => 'pubmed', 'cmd' => 'prlinks', 'retmode' => 'ref', 'id' => $id);
		$url = 'http://eutils.ncbi.nlm.nih.gov/entrez/eutils/elink.fcgi?' . http_build_query($params);
		print $url . "\n";

		$headers = get_headers($url, 1);
		print_r($headers);

		$location = (array) $headers['Location'];
		array_unshift($location, $id);
		print_r($location);

		fputcsv($output, $location);
	}

	fclose($input);
	fclose($output);
}



