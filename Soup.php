<?php
require_once("SoupClasses.php");
try{
	$letters=filter_input(INPUT_GET, 'letters', FILTER_SANITIZE_STRING);
	$width=filter_input(INPUT_GET, 'width', FILTER_SANITIZE_NUMBER_INT);
	$height=filter_input(INPUT_GET, 'height', FILTER_SANITIZE_NUMBER_INT);
	$word=filter_input(INPUT_GET, 'word', FILTER_SANITIZE_STRING);
	$strategy=filter_input(INPUT_GET, 'strategy', FILTER_SANITIZE_STRING);
	try {
		$AlphabetSoup= new AlphabetSoupStrategy($strategy);
		$result=$AlphabetSoup->word_search($letters,$height,$width,$word);

		if($result===false){
			echo json_encode(array("status" => TRUE,"found"=>$result, "error"=>"Invalid Matrix size"));
		}else{
			echo json_encode(array("status" => TRUE,"found"=>$result));
		}
	} catch (Exception $e) {
			echo json_encode(array("status" => TRUE,"found"=>$result, "error"=>$e->getMessage()));
	}
}catch (Exception $e) {
		echo json_encode(array("status" => FALSE, "error"=>$e->getMessage()));
}


?>