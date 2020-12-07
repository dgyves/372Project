
<?php
// First commit
/*
* parseFile -- parses a file passed from command line argument
* Params: array $arg
*/
function parseFile($arg){
	$i = 0;
	$data = array();
	
	// Ask user for what stats to calculate
	//if (sizeof($arg) != 2){
	//	echo "Please only include a file name.\n";
	//	die;
	//}

	$filename = $arg[1];
	// Open file or return error message
	//$myfile = fopen($filename+".csv", "r") or die("Unable to open file!");
	$myfile = fopen("DPOY.csv", "r") or die("Unable to open file!");

	// Read one line until end-of-file
	while(!feof($myfile)) {
		//$line = fgets($myfile);
		
		// print file name
		print_r(fgetcsv($myfile));

		$i++;
	}
	fclose($myfile);

	// check for error case k > n
	if($k > $n){
		die("Error: 'k' cannot be greater than 'n'.\n");
	}

	return $data;
}

// MAIN SCRIPT
$data = parseFile($argv);

?>
