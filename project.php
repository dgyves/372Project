<?php
/*
* Assignment: Project #4, Part #3: NBA Awards and Stats
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
			   Also allows user to browse stats acquired through the free NBA stats
			   https://www.balldontlie.io API.
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
* parseFile -- parses a file depending on user choice and calculates award winner through stat comparison
* Params: $category - String determining which award to calculate
*/
function parseFile($category){
	$myfile = "MVP.csv";	//default
	
	// Open file or return error message
	if($category == "DPOY"){
		$myfile = fopen("DPOY.csv", "r") or die("Unable to open file!");
	} elseif($category == "MVP"){
		$myfile = fopen("MVP.csv", "r") or die("Unable to open file!");
	} elseif ($category == "ROY"){

		$myfile = fopen("ROY.csv", "r") or die("Unable to open file!");
	}
	
	$i = 0;

	// Read one line until end-of-file
	while(!feof($myfile)) {
		
		$data = fgetcsv($myfile);

		// first line is the list of different categories
		if ($i == 0){
			$categories = $data;

		// rest of file is player data
		} else {
			$players[] = $data;
		}
		//var_dump($data);
		$i++;
	}
	fclose($myfile);
	echo "\nCategories:\n";
	for ($i = 2; $i < count($categories); $i++){
		echo $categories[$i]."\n";
	}
	echo "\nNominated Players:\n";
	for ($i = 0; $i < count($players); $i++){
		echo $players[$i][0]."\t".$players[$i][1]."\n";
	}
	$results = comp($players);
	for($i = 0; $i < count($players); $i++){
		if ($results[$players[$i][0]] == max(array_values($results))){
			echo"\n\nThe winner of the 2019-2020 NBA " . $category . " is: ";
			echo $players[$i][0] . " from the " . $players[$i][1]."\n";
			
		}
	}
}

/**
* Calculate a winner for NBA awards by comparing all players in every category
* params: $players - Player mappings with stats as values
* returns: $results - returns winner of award
*/
function comp($players){
	for($i = 0; $i < count($players); $i++){
		$results[$players[$i][0]] = 0;
	}
	for ($i = 0; $i < count($players);$i++){
		for ($j = $i+1; $j < count($players); $j++){
			$winner = compare($players[$i], $players[$j]);
			if($winner[0] > $winner[1]){
				$results[$players[$i][0]] += 1;
			}else{
				$results[$players[$i+1][0]] += 1;
			}
		}
	}
	return $results;
}
	
		
/**
* Returns array containing categories won between two players
* params: $player1, $player2 - arrays containing stats in respective categories 
* returns: $playerComp - array with number of winning categories between players
*/
function compare ($player1, $player2){
	$playerComp[0] = 0;
	$playerComp[1] = 0;

	for($i = 2; $i < count($player1); $i++){
		
		if($player1[$i] > $player2[$i]){
			//Player 1 Won
			$playerComp[0] += 1;
		}
		elseif($player1[$i] == $player2[$i]){
			//Both Won!
			$playerComp[0] += 1;
			$playerComp[1] += 1;

		}else{
			//Player 2 Won!
			$playerComp[1] += 1;
		}
	}
	//print_r($playerComp);
	return $playerComp;
}

/**
* Acquire team names using API through PHP's included Client URL methods and JSON Decoding
* params: n/a
* returns: $result->data - array object containing team data
*/
function getTeams(){
	$curl = curl_init();
	$url = "https://www.balldontlie.io/api/v1/teams";
	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	$result = curl_exec($curl);

	// Decode object ()
	$result = json_decode($result);

	curl_close($curl);
	return $result->data;
}

/**
* Acquire player names using API through PHP's included Client URL methods and JSON Decoding
* params: $page - integer indicating which page of players to show
* returns: $result->data - array object containing player data
*/
function getPlayers($page){
	$curl = curl_init();
	$url = "https://www.balldontlie.io/api/v1/players?per_page=10&page=".$page;

	curl_setopt($curl, CURLOPT_URL, $url);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
	$result = curl_exec($curl);

	// Decode object ()
	$result = json_decode($result);

	curl_close($curl);
	return $result->data;
}

/**
* Simple string script that dictates what information to display to user
* params: n/a
* returns: n/a
*/
function mainMenu(){
	echo "Welcome to the NBA Menu!";
	echo "\nHere are our options:";
	echo "\n\n1. NBA Awards (Enter awards)";
	echo "\n\n2. NBA Teams (Enter teams)";
	echo "\n\n3. NBA Players (Enter players)\n\n";

	$option = readline("Please enter one of the options above or 'exit' to end program: ");
	return $option;

}

// MAIN SCRIPT
while($option != "exit"){
	$option = mainMenu();
	if($option == "awards"){
		$command = "none";
		while($command != "exit"){
			echo "\n\nWelcome to the 2019-2020 NBA Awards. The following awards are:";
			echo"\n1. Most Valuable Player (Enter MVP)\n2. Defensive Player of the Year (Enter DPOY)";
			echo "\n3. Rookie of the year (Enter ROY)\n\n";
	
			$category = readline("Please select an award: ");
			$categories = [];
			$players = [];
			parseFile($category, $categories, $players);
			echo "Would you like to select another reward?";
			$command = readline("\nEnter 'exit' to return to the main"
			. " menu or any other key to continue: ");

			if ($command == "exit"){
				$option = "menu";
			}
		}
		
	// display players
	}elseif ($option == "players"){
		//Check for season averages
		//https://www.balldontlie.io/api/v1/season_averages
		//?season=2018 //specific season
		//"https://www.balldontlie.io/api/v1/season_averages?player_ids[]=237" //Lebron Averages
	
		$command = "none";
		$page = 1;
		while ($command != "exit"){
			echo "\n\nDisplaying Players (Page ".$page." out of 327):\n";
	
			$all_players = getPlayers($page);
			
			// display current page of players
			for ($i = 0; $i < count($all_players)-1; $i++){
				echo"\t".$all_players[$i]->first_name." ".$all_players[$i]->last_name."\n";
			}
	
			echo "Press < or > to change page.\n";
			echo "Enter specific page between 1 and 327.\n";
			echo "Type exit to change exit catalogue and return to main menu.\n";
	
			$command = readline("Command: ");
	
			if ($command == "<" && $page != 1){
				$page--;
			} elseif ($command == ">" && $page != 327){
				$page++;
			} elseif ($command == "<" && $page == 1){
				$page = 327;
			} elseif ($command == ">" && $page == 327){
				$page = 1;
			} elseif ($command == "exit"){
				$option = "menu";
			} else {
				if (intval($command) >= 1 && intval($command) <= 327){
					$page = intval($command);
				} else {
					echo "Error: Page number out of range!\n\n";
					continue;
				}
			}	
			echo "\n";
		}

	// display teams
	}elseif ($option == "teams"){
		$command = "none";
		while ($command != "exit"){
			echo "\nAll current NBA teams:\n\n";
			$all_teams = getTeams();

			foreach($all_teams as $team){
				echo $team->id . ". " . $team->full_name . " (" . $team->abbreviation;
				echo "):" . $team->division . " Division\n";
			}

			echo "\nType 'exit' to change exit list of teams and return to main menu.\n";
	
			$command = readline("Command: ");

			if($command == "exit"){
				$option = "menu";
			}

		}

	}

}
?>