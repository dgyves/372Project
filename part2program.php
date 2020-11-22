<?php

function parseFile(array $arg){
	$i = 0;
	$data = array();
	
	// Read command line for filename
	if (sizeof($arg) != 2){
		echo "Please only include a file name.\n";
		die;
	}

	$filename = $arg[1];
	$myfile = fopen($filename, "r") or die("Unable to open file!");

	// Output one line until end-of-file
	while(!feof($myfile)) {
		$line = fgets($myfile);
		
		// first line is value of k
		if ($i == 0){
			$k = intval($line);
			$data['k'] = $k;

		// second line is value of n
		} else if ($i == 1){
			$n = intval($line);
			$data['n'] = $n;

		// rest of file is data points
		} else {
			$data[$i-2] = trim($line);
		}

		$i++;
	}
	fclose($myfile);

	// check for error case k > n
	if($k > $n){
		die("Error: 'k' cannot be greater than 'n'.\n");
	}

	return $data;
}

function computeCentroids($clusters, $k){

	$newcentroids = array();

	// for each cluster
	for ($m = 0; $m < $k; $m++){
		$data = $clusters[$m];
		$sized = sizeof($data);

		// reindex array to avoid index errors
		$data = array_values($data);

		//var_dump($data);
		//echo sizeof($data) . "\n";

		$totalx = 0;
		$totaly = 0;

		// for each datapoint in cluster
		for ($p = 0; $p < $sized; $p++){

			//array_values($data);
			$datapoint = explode(" ", $data[$p]);
			//array_values($datapoint);
			//var_dump($datapoint);

			$x = $datapoint[0];
			$y = $datapoint[1];

			//echo "\t x: " . $x ." y: " . $y . "\n";

			$totalx = $totalx + $x;
			$totaly = $totaly + $y;

			$datapoint = 0;
		}

		//echo $newx ." <- new total x, new total y -> " . $newy . "\n";

		$newx = (1/$sized) * ($totalx);
		$newy = (1/$sized) * ($totaly);

		//echo $newx ." <- new x, new y -> " . $newy . "\n\n";
		
		$newcentroids[$m] = $newx . " " . $newy;
	}

	//echo "new centroids are: ";
	//var_dump($newcentroids);
	return $newcentroids;
}

function assignNearestCluster($data, $clusters, $centroids, $n, $k){

	$newclusters = array();
	$data = array_values($data);
	
	// iterate through every data point
	for ($i = 0; $i < $n; $i++){

		$num = $data[$i];
		$numformat = explode(" ",$num);
		
		//echo "num: " . $num . "\n";

		$x = $numformat[0];
		$y = $numformat[1];
		
		//echo "x: " . $x . " ";
		//echo "y: " . $y . "\n";

		$nearest = -1;
		$prevdist = 10000000;

		for ($j = 0; $j < $k; $j++){
			$u = explode(" ",$centroids[$j]);
			$ux = $u[0];
			$uy = $u[1];
			//echo "\t cluster C" . $j . " :";
			//echo "\tux: " . $ux . " ";
			//echo "\tuy: " . $uy . "\n";

			$dist = (($x - $ux) ** 2) + (($y - $uy) ** 2);
			//echo "\t\td: " . $dist . "\n";

			// compute cluster membership
			if ($dist < $prevdist){
				$prevdist = $dist;
				$nearest = $j;
			}
		}

		$newclusters[$nearest][] = $num;

		//echo "nearest cluster: " . $nearest . "\n\n\n";

	}

	//var_dump($newclusters);

	return $newclusters;
}

function kClustering($data){
	$k = $data['k'];
	$n = $data['n'];
	unset($data['k']);
	unset($data['n']);
	echo "k: " . $k . "\n";
	echo "n: " . $n . "\n";
	//var_dump($data);

	$count = 1;
	$clusters = array();
	$centroids = array();

	// Select k data points and turn into cluster centroids
	for ($i = 0; $i < $k; $i++){
		$inner = array();
		$inner[$i] = trim($data[$i], "\n");
		$clusters[$i] = $inner;
		$centroids[$i] = $data[$i];
	}
	
	//var_dump($clusters);
	$prev = array();

	// While clusters are not stable (array not equal to prev iteration)
	while ($clusters !== $prev && $count != 6){
		$prev = $clusters;

		echo "-------------- iteration " . $count . " --------------\n";

		$centroids = computeCentroids($clusters, $k);

		$clusters = assignNearestCluster($data, $clusters, $centroids, $n, $k);

		$count++;
	}

	var_dump($centroids);
	echo "The final centroid locations are:\n";

	for ($c = 0; $c < sizeof($centroids); $c++){
		echo "   u(" . $c . ") = (" . number_format($centroids[$c][0], 3, '.', ',') . ", " . $centroids[$c][1] . ")\n";
	}

	echo $count . " iterations were required.";

}


// MAIN SCRIPT
$data = parseFile($argv);
kClustering($data);
?>