<?
abstract class Amimal
{
	public $id;
	public $productTipe;
	
	function __construct(ProductTipe $tipe) {
		$this->id = uniqid();
		$this->productTipe = $tipe;
	}
	
	function getProduct() {
		return rand($this->productTipe->min, $this->productTipe->max);
	}
	
	function getProductTipeName() {
		return $this->productTipe->getClassName();
	}
}

class Chicken extends Amimal
{

}

class Cow extends Amimal
{

}

abstract class ProductTipe
{
	public $min;
	public $max;
	static $form1 = 'штука';
	static $form2 = 'штуки';
	static $form3 = 'штук';
	
	function __construct($min, $max) {
		$this->min = $min;
		$this->max = $max;
	}
	
	static function format_by_count($count)
	{
		$count = abs($count) % 100;
		$lcount = $count % 10;
		if ($count >= 11 && $count <= 19) return(static::$form3);
		if ($lcount >= 2 && $lcount <= 4) return(static::$form2);
		if ($lcount == 1) return(static::$form1);
		return static::$form3;
	}
	
	function getClassName() {
		return static::class;
	}
}

class Egg extends ProductTipe
{
	static $form1 = 'яйцо';
	static $form2 = 'яйца';
	static $form3 = 'яиц';
}

class Milk extends ProductTipe
{
	static $form1 = 'литр молока';
	static $form2 = 'литра молока';
	static $form3 = 'литров молока';
}

class Farm
{
	private $animalArray = [];
	private $production = [];
	
	function addAnimal(string $animalTipe, ProductTipe $product, $quantity = 1)
	{
		for ($i = 1; $i <= $quantity; $i++) {
			$this->animalArray[] = new  $animalTipe($product);
		}
		
	}
	
	function getProduction() {
		if ($this->animalArray) {
			foreach ($this->animalArray as $value) {
				/* @var $value Amimal */
				if (array_key_exists($value->getProductTipeName(), $this->production)) {
					$this->production[$value->getProductTipeName()] += $value->getProduct();
				} else {
					$this->production[$value->getProductTipeName()] = $value->getProduct();
				}
			}
		}
	}

	function calculateProduction() {
		if ($this->production) {
			foreach ($this->production as $key => $value ) {
				/* @var $key ProductTipe */
				echo 'Собрано: ' . $value . ' ' . $key::format_by_count($value) . '</br>';
			}
		}
	}
}

$uncleBobFarm = new Farm();
$uncleBobFarm->addAnimal('Chicken', new Egg( 0, 1), 20);
$uncleBobFarm->addAnimal('Cow', new Milk( 8, 12), 10);
$uncleBobFarm->getProduction();
$uncleBobFarm->calculateProduction();