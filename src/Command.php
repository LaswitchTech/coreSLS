<?php

// Declaring namespace
namespace LaswitchTech\coreSLS;

// Import additionnal class into the global namespace
use LaswitchTech\coreBase\BaseCommand;
use LaswitchTech\coreSLS\Model;
use Exception;

class Command extends BaseCommand {

	// coreSLS Properties
    private $Model;

    /**
     * Constructor
     */
	public function __construct($Auth){

        // Namespace: /sls

        // Initialize SLS
        $this->Model = new Model();

		// Call the parent constructor
		parent::__construct($Auth);
	}

    /**
     * Create license
     *
     * @output String
     */
    public function createAction($argv){

        // Namespace: /sls/create $type = null $product = null

        try{

            // Initialize variables
            $type = null;
            $product = null;

            // Check if type is provided
            if (isset($argv[0])) {
                $type = $argv[0];
            } else {
                throw new Exception("Missing parameters");
            }

            // Check if product is provided
            if (isset($argv[1])) {
                $product = $argv[1];
            }

            // Create the license
            $result = $this->Model->new($type, $product);

            // Return the result
            if($result['status'] == 'valid'){
                $this->success(json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
            } else {
                $this->error(json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
            }
        } catch (Exception $e) {

            // Return the error
            $this->error($e->getMessage());
        }
    }

    /**
     * List licenses
     *
     * @output String
     */
    public function licensesAction($argv){

        // Namespace: /sls/licenses $limit = null

        try{

            // Initialize variables
            $limit = null;

            // Check if limit is provided
            if (isset($argv[0])) {
                $limit = $argv[0];
            }

            // Get the licenses
            $result = $this->Model->getLicenses($limit);

            // Retrieve columns names and max length
            $columns = [];
            foreach($this->Model->query("SHOW COLUMNS FROM `licenses`") as $column){
                $columns[$column['Field']] = $this->Model->query("SELECT MAX(CHAR_LENGTH(".$column['Field'].")) AS max_length FROM `licenses`")[0]['max_length'];
                $columns[$column['Field']] = strlen($column['Field']) > $columns[$column['Field']] ? strlen($column['Field']) : $columns[$column['Field']];
            }
            $columns['expire'] = strlen("0000-00-00 00:00:00");

            // Output Header
            $this->output("|-" . str_pad('', $columns['id'], "-", STR_PAD_LEFT) . "-|-" . str_pad('', $columns['license'], "-", STR_PAD_RIGHT) . "-|-" . str_pad('', $columns['product'], "-", STR_PAD_RIGHT) . "-|-" . str_pad('', $columns['user'], "-", STR_PAD_RIGHT) . "-|-" . str_pad('', $columns['term'], "-", STR_PAD_RIGHT) . "-|-" . str_pad('', $columns['duration'], "-", STR_PAD_RIGHT) . "-|-" . str_pad('', $columns['expire'], "-", STR_PAD_RIGHT) . "-|");
            $this->output("| " . str_pad('ID', $columns['id'], " ", STR_PAD_LEFT) . " | " . str_pad('License', $columns['license'], " ", STR_PAD_RIGHT) . " | " . str_pad('Product', $columns['product'], " ", STR_PAD_RIGHT) . " | " . str_pad('User', $columns['user'], " ", STR_PAD_RIGHT) . " | " . str_pad('Term', $columns['term'], " ", STR_PAD_RIGHT) . " | " . str_pad('Duration', $columns['duration'], " ", STR_PAD_RIGHT) . " | " . str_pad('Expire', $columns['expire'], " ", STR_PAD_RIGHT) . " |");
            $this->output("|-" . str_pad('', $columns['id'], "-", STR_PAD_LEFT) . "-|-" . str_pad('', $columns['license'], "-", STR_PAD_RIGHT) . "-|-" . str_pad('', $columns['product'], "-", STR_PAD_RIGHT) . "-|-" . str_pad('', $columns['user'], "-", STR_PAD_RIGHT) . "-|-" . str_pad('', $columns['term'], "-", STR_PAD_RIGHT) . "-|-" . str_pad('', $columns['duration'], "-", STR_PAD_RIGHT) . "-|-" . str_pad('', $columns['expire'], "-", STR_PAD_RIGHT) . "-|");

            // Output list of licenses
            foreach($result as $id => $license){
                foreach($license as $key => $value){
                    $license[$key] = $value ? $value : 'N/A';
                }
                if($license['expire'] != 'N/A'){
                    $license['expire'] = date('Y-m-d H:i:s', $license['expire']);
                }
                $this->output("| " . str_pad($license['id'], $columns['id'], " ", STR_PAD_LEFT) . " | " . str_pad($license['license'], $columns['license'], " ", STR_PAD_RIGHT) . " | " . str_pad($license['product'], $columns['product'], " ", STR_PAD_RIGHT) . " | " . str_pad($license['user'], $columns['user'], " ", STR_PAD_RIGHT) . " | " . str_pad($license['term'], $columns['term'], " ", STR_PAD_RIGHT) . " | " . str_pad($license['duration'], $columns['duration'], " ", STR_PAD_RIGHT) . " | " . str_pad($license['expire'], $columns['expire'], " ", STR_PAD_RIGHT) . " |");
            }

            // Output Footer
            $this->output("|-" . str_pad('', $columns['id'], "-", STR_PAD_LEFT) . "-|-" . str_pad('', $columns['license'], "-", STR_PAD_RIGHT) . "-|-" . str_pad('', $columns['product'], "-", STR_PAD_RIGHT) . "-|-" . str_pad('', $columns['user'], "-", STR_PAD_RIGHT) . "-|-" . str_pad('', $columns['term'], "-", STR_PAD_RIGHT) . "-|-" . str_pad('', $columns['duration'], "-", STR_PAD_RIGHT) . "-|-" . str_pad('', $columns['expire'], "-", STR_PAD_RIGHT) . "-|");
        } catch (Exception $e) {

            // Return the error
            $this->error($e->getMessage());
        }
    }

    /**
     * List products
     *
     * @output String
     */
    public function productsAction($argv){

        // Namespace: /sls/products $limit = null

        try{

            // Initialize variables
            $limit = null;

            // Check if limit is provided
            if (isset($argv[0])) {
                $limit = $argv[0];
            }

            // Get the products
            $result = $this->Model->getProducts($limit);

            // Retrieve columns names and max length
            $columns = [];
            foreach($this->Model->query("SHOW COLUMNS FROM `products`") as $column){
                $columns[$column['Field']] = $this->Model->query("SELECT MAX(CHAR_LENGTH(".$column['Field'].")) AS max_length FROM `products`")[0]['max_length'];
                $columns[$column['Field']] = strlen($column['Field']) > $columns[$column['Field']] ? strlen($column['Field']) : $columns[$column['Field']];
            }

            // Output Header
            $this->output("|-" . str_pad('', $columns['id'], "-", STR_PAD_LEFT) . "-|-" . str_pad('', $columns['name'], "-", STR_PAD_RIGHT) . "-|-" . str_pad('', $columns['description'], "-", STR_PAD_RIGHT) . "-|-" . str_pad('', $columns['term'], "-", STR_PAD_RIGHT) . "-|-" . str_pad('', $columns['duration'], "-", STR_PAD_RIGHT) . "-|");
            $this->output("| " . str_pad('ID', $columns['id'], " ", STR_PAD_LEFT) . " | " . str_pad('Name', $columns['name'], " ", STR_PAD_RIGHT) . " | " . str_pad('Description', $columns['description'], " ", STR_PAD_RIGHT) . " | " . str_pad('Term', $columns['term'], " ", STR_PAD_RIGHT) . " | " . str_pad('Duration', $columns['duration'], " ", STR_PAD_RIGHT) . " |");
            $this->output("|-" . str_pad('', $columns['id'], "-", STR_PAD_LEFT) . "-|-" . str_pad('', $columns['name'], "-", STR_PAD_RIGHT) . "-|-" . str_pad('', $columns['description'], "-", STR_PAD_RIGHT) . "-|-" . str_pad('', $columns['term'], "-", STR_PAD_RIGHT) . "-|-" . str_pad('', $columns['duration'], "-", STR_PAD_RIGHT) . "-|");

            // Output list of products
            foreach($result as $id => $product){
                foreach($product as $key => $value){
                    $product[$key] = $value ? $value : 'N/A';
                }
                $this->output("| " . str_pad($product['id'], $columns['id'], " ", STR_PAD_LEFT) . " | " . str_pad($product['name'], $columns['name'], " ", STR_PAD_RIGHT) . " | " . str_pad($product['description'], $columns['description'], " ", STR_PAD_RIGHT) . " | " . str_pad($product['term'], $columns['term'], " ", STR_PAD_RIGHT) . " | " . str_pad($product['duration'], $columns['duration'], " ", STR_PAD_RIGHT) . " |");
            }

            // Output Footer
            $this->output("|-" . str_pad('', $columns['id'], "-", STR_PAD_LEFT) . "-|-" . str_pad('', $columns['name'], "-", STR_PAD_RIGHT) . "-|-" . str_pad('', $columns['description'], "-", STR_PAD_RIGHT) . "-|-" . str_pad('', $columns['term'], "-", STR_PAD_RIGHT) . "-|-" . str_pad('', $columns['duration'], "-", STR_PAD_RIGHT) . "-|");
        } catch (Exception $e) {

            // Return the error
            $this->error($e->getMessage());
        }
    }

    /**
     * Validate license
     *
     * @output String
     */
    public function validateAction($argv){

        // Namespace: /sls/validate $license $product = null $user = null

        try{

            // Initialize variables
            $license = null;
            $product = null;
            $user = null;

            // Check if license is provided
            if (empty($argv[0])) {
                throw new Exception("Missing parameters");
            }

            // Set the license
            $license = $argv[0];

            // Check if product is provided
            if (isset($argv[1])) {
                $product = $argv[1];
            }

            // Check if user is provided
            if (isset($argv[2])) {
                $user = $argv[2];
            }

            // Validate the license
            $result = $this->Model->validate($license, $product, $user);

            // Return the result
            if($result['status'] == 'valid'){

                // Return the result
                $this->success(json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
            } else {

                // Return the result
                $this->error(json_encode($result, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES));
            }
        } catch (Exception $e) {

            // Return the error
            $this->error($e->getMessage());
        }
    }
}
