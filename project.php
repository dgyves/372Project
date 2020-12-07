<?php
/*
* Assignment: Project #4, Part #3: NBA Awards
*
* Authors: Diego D'Gyves and Alex Buell
*
* Course: CSc 372
* Instructor: L. McCann
* TA(s): Tito Ferra and Josh Xiong
* Due Date: December 7, 2020
*
* Description: Reads in CSV files containing the season leaders in multiple categories
               to determine an NBA Most Valuable Player(MVP), Defensive Player of the
               Year (DPOY), and Rookie of the Year (ROY).
*
* Language: Php
*
* Ex. Packages: None.
*
* Deficiencies: None.
*
* PHP LANGUAGE STUDY: https://docs.google.com/document/d/1rb2q3LUCvGsNY121iZrpm8fKdRt_TUkKSnj6nsUQe-o/edit?usp=sharing
*/



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
