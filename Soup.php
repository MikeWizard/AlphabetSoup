<?php


class AlphabetSoupStrategy {
	/**
	 * Variable para guardar la instancia del patrón Strategy
	 * @var strategy
	 */
    private $strategy = NULL; 
	/**
	 * Constructor de clase
	 * Se encarga de instanciar la estrategia deseada
	 * @return void
	 */
    public function __construct($id) {
        switch ($id) {
            case "RecursiveWithLimiter": 
                $this->strategy = new AlphabetSoupManagerRecursiveWithLimiter();
            break;
            case "HalfRecursive":
                $this->strategy = new AlphabetSoupManagerHalfRecursive();
            break;
            case "Standard":
                $this->strategy = new AlphabetSoupManagerStandard();
            break;
            
        }
    }
	/**
	 * Búqueda de palabra
	 * Método para buscar una palabra en una configuracion dada.
	 * @param string $letters     	Letras que componen a la sopa de letras
     * @param int  $height 			Alto de la matriz
     * @param int  $width    		Ancho de la matriz
     * @param string $word       	Palabra a buscar     
	 * @return int|false
	 */
    public function word_search(string $letters,int $height,int $width, string $word){
    	return $this->strategy->word_search($letters,$height,$width,$word);
    }
}

interface AlphabetSoupStrategyInterface {
    public function word_search(string $letters,int $height,int $width, string $word);
}
class AlphabetSoupManager{
	/**
	 * Cargar Sopa
	 * Método para generar una matriz con una configuracion dada.
	 * @param string $letters     	Letras que componen a la sopa de letras
     * @param int  $height 			Alto de la matriz
     * @param int  $width    		Ancho de la matriz
	 * @return array|false
	 */
	protected function cargar_AlphabetSoup(string $letters,int $height,int $width){
		if (strlen($letters)==$height*$width){
			$s=array();
			for ($i=0;$i<$height;$i++){
				for ($j=0;$j<$width;$j++){
					$letra=substr($letters,($i)*($width)+$j,1);
					// echo ($i)*($width)+$j."/";
					$s[$i][$j]=$letra;
				}
			}
			// echo "<br>";
			return $s;
		}else{
			return false;
		}

	}
	/**
	 * Buscar en una celda
	 * Método para buscar en las 8 direcciones posibles usando una celda como origen.
	 * @param array $AlphabetSoup     	Letras que componen a la sopa de letras
     * @param int  $height 			Alto de la matriz
     * @param int  $width    		Ancho de la matriz
     * @param string $word       	Palabra a buscar     
	 * @return int
	 */
	protected function search_cell_for_appearances(array $AlphabetSoup, int $x, int $y, $word){
		$appearances=0;
		$ways= array();
		$lp=strlen($word)-1;//Con esto sabemos cuanto tiene que tener de espacio ejemplo: si la palabra es "sandia" buscamos 6 espacios
		for ($i=0;$i<$lp+1	;$i++){
			if (isset($AlphabetSoup[$x][$y+$lp])){
				$ways[0][$i]=$AlphabetSoup[$x][$y+$i];
			}
			if (isset($AlphabetSoup[$x+$lp][$y+$lp])){
				$ways[1][$i]=$AlphabetSoup[$x+$i][$y+$i];
			}
			if (isset($AlphabetSoup[$x+$lp][$y])){
				$ways[2][$i]=$AlphabetSoup[$x+$i][$y];
			}
			if (isset($AlphabetSoup[$x-$lp][$y-$lp])){
				$ways[3][$i]=$AlphabetSoup[$x-$i][$y-$i];
			}
			if (isset($AlphabetSoup[$x-$lp][$y])){
				$ways[4][$i]=$AlphabetSoup[$x-$i][$y];
			}
			if (isset($AlphabetSoup[$x+$lp][$y-$lp])){
				$ways[5][$i]=$AlphabetSoup[$x+$i][$y-$i];
			}
			if (isset($AlphabetSoup[$x-$lp][$y+$lp])){
				$ways[6][$i]=$AlphabetSoup[$x-$i][$y+$i];
			}
			if (isset($AlphabetSoup[$x][$y-$lp])){
				$ways[7][$i]=$AlphabetSoup[$x][$y-$i];
			}
		}
		foreach($ways as $k=>$way){
			$p=implode('', $way);
			if ($p==$word) {
				$appearances++;
			}
		}
		return $appearances;
	}
}
class AlphabetSoupManagerRecursiveWithLimiter extends AlphabetSoupManager implements AlphabetSoupStrategyInterface{
	private $horizontal_limiter=0;
	private function recursive_search(array $AlphabetSoup, int $x, int $y, $word){
		// echo "buscar recursiva [".$x."][".$y."]<hr>";
		$appearances=0;		
		if(isset($AlphabetSoup[$x+1+$this->horizontal_limiter][$y])){
			$appearances+=$this->recursive_search($AlphabetSoup,$x+1,$y,$word);
		}
		if(isset($AlphabetSoup[$x][$y+1])){
			$appearances+=$this->recursive_search($AlphabetSoup,$x,$y+1,$word);
		}
		$this->horizontal_limiter++;
		$appearances+=$this->search_cell_for_appearances($AlphabetSoup,$x,$y,$word);
		return $appearances;
	}
	public function word_search(string $letters,int $height,int $width, string $word){
		$this->horizontal_limiter=0;
		$AlphabetSoup=$this->cargar_AlphabetSoup($letters,$height,$width);
		if ($AlphabetSoup){
			return $this->recursive_search($AlphabetSoup,0,0,$word);
		}else{

		}
	}
	
}
class AlphabetSoupManagerHalfRecursive extends AlphabetSoupManager implements AlphabetSoupStrategyInterface{
	
	private function recursive_search(array $AlphabetSoup, int $x, int $y, $word){
		$appearances=0;		
		if(isset($AlphabetSoup[$x+1][$y])){
			$appearances+=$this->recursive_search($AlphabetSoup,$x+1,$y,$word);
		}
		if(isset($AlphabetSoup[$x][$y+1])){
			$appearances+=$this->recursive_search_vertical($AlphabetSoup,$x,$y+1,$word);
		}
		$appearances+=$this->search_cell_for_appearances($AlphabetSoup,$x,$y,$word);
		return $appearances;
	}
	private function recursive_search_vertical(array $AlphabetSoup, int $x, int $y, $word){
		$appearances=0;		
		if(isset($AlphabetSoup[$x][$y+1])){
			$appearances+=$this->recursive_search_vertical($AlphabetSoup,$x,$y+1,$word);
		}
		$appearances+=$this->search_cell_for_appearances($AlphabetSoup,$x,$y,$word);
		return $appearances;
	}
	public function word_search(string $letters,int $height,int $width, string $word){
		$AlphabetSoup=$this->cargar_AlphabetSoup($letters,$height,$width);
		if ($AlphabetSoup){
			return $this->recursive_search($AlphabetSoup,0,0,$word);
		}
	}
}
class AlphabetSoupManagerStandard extends AlphabetSoupManager implements AlphabetSoupStrategyInterface{
	public function word_search(string $letters,int $height,int $width, string $word){
		$AlphabetSoup=$this->cargar_AlphabetSoup($letters,$height,$width);
		if ($AlphabetSoup){
			return $this->search($AlphabetSoup,$height,$width,$word);	
		}
	}
	private function search(array $AlphabetSoup,int $height,int $width, string $word){
		$appearances=0;
		for($x=0;$x<$width;$x++){
			for($y=0;$y<$height;$y++){
				$appearances+=$this->search_cell_for_appearances($AlphabetSoup,$x,$y,$word);
			}
		}
		return $appearances;
	}
}

// $AlphabetSoup= new AlphabetSoupStrategy("RecursiveWithLimiter");
// echo $AlphabetSoup->word_search("OIEIIXEXE",3,3,"OIE");
// echo "<hr>";
// echo $AlphabetSoup->word_search("EIOIEIOEIO",1,10,"OIE");
// echo "<hr>";
// echo $AlphabetSoup->word_search("EAEAEAIIIAEIOIEAIIIAEAEAE",5,5,"OIE");
// echo "<hr>";
// echo $AlphabetSoup->word_search("OXIOEXIIOXIEEX",7,2,"OIE");
// echo json_encode($AlphabetSoup->word_search("OIEIIXEXE",3,3,"OIE"));
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

?>