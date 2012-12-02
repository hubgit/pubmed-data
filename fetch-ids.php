<?php

$limit = 10000;
$dom = new DOMDocument;

foreach (range(1980, 2012) as $year) {
	$output = fopen("compress.zlib://ids/articles-$year.txt.gz", 'w');

	$offset = 0;

	do {
		$params = array('db' => 'pubmed', 'term' => $year . '[DP]', 'retmode' => 'xml', 'retstart' => $offset, 'retmax' => $limit);
		$url = 'http://eutils.ncbi.nlm.nih.gov/entrez/eutils/esearch.fcgi?' . http_build_query($params);

		print "$url\n";
		$dom->load($url);
		$xpath = new DOMXPath($dom);

		$nodes = $xpath->query('IdList/Id');
		if (!$nodes->length) {
			print "No nodes\n";
			break;
		}

		foreach ($nodes as $node) {
			fwrite($output, $node->textContent . "\n");
		}

		$total = $xpath->query('Count')->item(0)->nodeValue;
		$offset += $limit;

		print "Offset: $offset\tTotal: $total\n";
	} while ($offset <= $total);

	fclose($output);
}



