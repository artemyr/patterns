<pre>
class ProductFacade {
    private $products = [];
    public function __construct(string $file)
    {
        $this->file = $file;
        $this->compile();
    }
    private function compile() {
        $lines = getProductFileLines($this->file);
        foreach ($lines as $line) {
            $id = getIDFomLine($line);
            $name = getNameFromLine($line);
            $this->products[$id] = getProductObjectFromID($id, $name0);
        }
    }
    public function getProducts(): array
    {
        return $this->products;
    }
    public function getProduct(string $id): \Product
    {
        if (isset($this->products[$id])) {
            return $this->products[$id];
        }
        return null;
    }
}

$facade = new ProductFacade(__DIR__.'/test2.txt');
$object = $facade->getProducts('123');
