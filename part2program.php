<?php

/*
* Assignment: Project #4, Part #2: K-Means Clustering
*
* Authors: Diego D'Gyves and Alex Buell
*
* Course: CSc 372
* Instructor: L. McCann
* TA(s): Tito Ferra and Josh Xiong
* Due Date: November 23, 2020
*
* Description: Implement K-Clustering algorithm according to spec in a new language (Php).
*
* Language: Php
*
* Ex. Packages: None.
*
* Deficiencies: None.
*
* PHP LANGUAGE STUDY: https://docs.google.com/document/d/1rb2q3LUCvGsNY121iZrpm8fKdRt_TUkKSnj6nsUQe-o/edit?usp=sharing
*/

/*
* parseFile -- parses a file passed from command line argument
* Params: array $arg
*/
function parseFile($arg){
	$i = 0;
	$data = array();
	
	// Read command line for filename
	if (sizeof($arg) != 2){
		echo "Please only include a file name.\n";
		die;
	}

	$filename = $arg[1];
	// Open file or return error message
	$myfile = fopen($filename, "r") or die("Unable to open file!");

	// Read one line until end-of-file
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

/*
* computeCentroids -- computes new centroids using datapoints from clusters
* Params:	array $clusters
*			int $k
* Returns:	array $newclusters
*/
function computeCentroids($clusters, $k){

	$newcentroids = array();

	// for each cluster
	for ($m = 0; $m < $k; $m++){
		$data = $clusters[$m];
		$sized = sizeof($data);

		// reindex array to avoid index errors
		$data = array_values($data);

		$totalx = 0;
		$totaly = 0;

		// for each datapoint in cluster
		for ($p = 0; $p < $sized; $p++){

			$datapoint = explode(" ", $data[$p]);
			$x = $datapoint[0];
			$y = $datapoint[1];
			
			$totalx = $totalx + $x;
			$totaly = $totaly + $y;
		}
		// multiply by inverse number of datapoints in cluster
		$newx = (1/$sized) * ($totalx);
		$newy = (1/$sized) * ($totaly);

		$newcentroids[$m] = $newx . " " . $newy;
	}

	return $newcentroids;
}

/*
* assignNearestCluster -- assigns new clusters according to closest distance from datapoints to centroids
* Params:	array $data
			array $centroids
			int $n
*			int $k
* Returns:	array $newclusters
*/
function assignNearestCluster($data, $centroids, $n, $k){
	$newclusters = array();
	
	// iterate through every data point
	for ($i = 0; $i < $n; $i++){

		$num = $data[$i];
		$numformat = explode(" ",$num);
		$x = $numformat[0];
		$y = $numformat[1];
		
		$prevdist = 10000000;

		// iterate through every centroid to find nearest one
		for ($j = 0; $j < $k; $j++){
			$u = explode(" ",$centroids[$j]);
			$ux = $u[0];
			$uy = $u[1];

			// calculate squared Euclidean distance to data point
			$dist = pow(($x - $ux),2) + pow(($y - $uy),2);

			// compute cluster membership
			if ($dist < $prevdist){
				$prevdist = $dist;
				$nearest = $j;
			}
		}
		$newclusters[$nearest][] = $num;
	}

	return $newclusters;
}

/*
* kClustering -- implements K-Means Clustering algorithm
* Params:	array $data
*/
function kClustering($data){
	$k = $data['k'];
	$n = $data['n'];
	unset($data['k']);
	unset($data['n']);

	$count = 0;
	$clusters = array();
	$centroids = array();

	// Select k data points and turn into cluster centroids
	for ($i = 0; $i < $k; $i++){
		$inner = array();
		$inner[$i] = $data[$i];
		$clusters[$i] = $inner;
		$centroids[$i] = $data[$i];
	}
	
	$prev = array();

	// While clusters are not stable (array not equal to prev iteration)
	while ($clusters !== $prev){
		$prev = $clusters;
		$centroids = computeCentroids($clusters, $k);
		$clusters = assignNearestCluster($data, $centroids, $n, $k);
		$count++;
	}

	echo "The final centroid locations are:\n\n";

	for ($c = 0; $c < sizeof($centroids); $c++){
		$u = explode(" ",$centroids[$c]);
		$ux = $u[0];
		$uy = $u[1];
		echo "   u(" . ($c+1) . ") = (" . round($ux, 3) . ", " . round($uy,3) . ")\n";
	}
	echo "\n" . $count . " iterations were required.";
}


// MAIN SCRIPT
$data = parseFile($argv);
kClustering($data);
?>