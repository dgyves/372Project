<?php

function parseFile(array $arg){
	// Read command line for filename
	if (sizeof($arg) != 2){
		echo "Please only include a file name.\n";
		die;
	}

	$i = 0;
	$data = array();

	$myfile = fopen("test.txt", "r") or die("Unable to open file!");
	// Output one line until end-of-file
	while(!feof($myfile)) {
		$str = fgets($myfile);
		
		if ($i == 0){
			$k = intval($str);
			$data['k'] = $k;
		} else if ($i == 1){
			$n = intval($str);
			$data['n'] = $n;
		} else {
			//$data = explode(" ",$str);
			$data[$i-2] = trim($str);
			//$x = $data[0] ;
			//$y = $data[1];
			///echo "x: " . $x . " ";
			//echo "y: " . $y . "\n";
		}
		$i++;
	}
	fclose($myfile);

	if($k > $n){
		die("Error: 'k' cannot be greater than 'n'.\n");
	}

	return $data;
}

function kClustering($data){
	$k = $data['k'];
	$n = $data['n'];
	unset($data['k']);
	unset($data['n']);
	echo "k: " . $k . "\n";
	echo "n: " . $n . "\n";
	var_dump($data);

	$count = 0;
	
}



$data = parseFile($argv);
kClustering($data);
?>