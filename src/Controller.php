<?php

// Declaring namespace
namespace LaswitchTech\coreSLS;

// Import additionnal class into the global namespace
use LaswitchTech\coreBase\BaseController;
use LaswitchTech\coreSLS\Model;

class Controller extends BaseController {

	// coreSLS Properties
    private $Model;

    /**
     * Constructor
     */
	public function __construct($Auth){

        // Namespace: /sls

		// Set the controller Authentication Policy
		$this->Public = true; // Set to false to require authentication

		// Set the controller Authorization Policy
		$this->Permission = false; // Set to true to require a permission for the namespace used.
		$this->Level = 1; // Set the permission level required

        // Initialize model
        $this->Model = new Model();

		// Call the parent constructor
		parent::__construct($Auth);
	}

    /**
     * List licenses
     *
     * @return Array
     */
    public function licensesRouterAction(){

        // Namespace: /sls/licenses

        // Return the licenses
        return $this->Model->getLicenses();
    }

    /**
     * List licenses
     *
     * @output JSON
     */
    public function licensesAPIAction(){

        // Namespace: /sls/licenses

        // Send the output
        $this->output(
            $this->Model->getLicenses(),
            array('Content-Type: application/json', 'HTTP/1.1 200 OK')
        );
    }

    /**
     * List products
     *
     * @return Array
     */
    public function productsRouterAction(){

        // Namespace: /sls/products

        // Return the products
        return $this->Model->getProducts();
    }

    /**
     * List products
     *
     * @output JSON
     */
    public function productsAPIAction(){

        // Namespace: /sls/products

        // Send the output
        $this->output(
            $this->Model->getProducts(),
            array('Content-Type: application/json', 'HTTP/1.1 200 OK')
        );
    }

    /**
     * Create License/product
     *
     * @return Array
     */
    public function createRouterAction(){

        // Namespace: /sls/create

        // Initialize object
        $object = [];

        // Retrieve Parameters
        $object['type'] = isset($_GET['type']) ? $_GET['type'] : '';

        $object['terms'] = ['perpetual', 'subscription', 'trial'];
        $object['durations'] = ['day', 'week', 'month', 'year'];
        $object['products'] = $this->Model->getProducts();

        // Check request method
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Check for required parameters
            switch($object['type']){
                case 'product':
                    foreach(['name', 'description', 'term', 'duration'] as $param){
                        if (!isset($_POST[$param]) || empty($_POST[$param])) {
                            $object['status'] = 'invalid';
                            $object['message'] = 'Missing required parameters: ' . $param;
                            break;
                        }
                    }
                    $result = $this->Model->new($object['type'], $_POST['name']);
                    $object['status'] = $result['status'];
                    $object['message'] = $result['message'];
                    if(isset($result['product'])){
                        $this->Model->update("UPDATE `products` SET `description` = ?, `term` = ?, `duration` = ? WHERE `id` = ?", [$_POST['description'], $_POST['term'], $_POST['duration'], $result['product']]);
                        $_POST = [];
                    }
                    break;
                case 'license':
                    foreach(['product', 'term', 'duration'] as $param){
                        if (!isset($_POST[$param]) || empty($_POST[$param])) {
                            $object['status'] = 'invalid';
                            $object['message'] = 'Missing required parameters: ' . $param;
                            break;
                        }
                    }
                    $result = $this->Model->new($object['type'], $_POST['product']);
                    $object['status'] = $result['status'];
                    $object['message'] = $result['message'];
                    if(isset($result['license'])){
                        $this->Model->update("UPDATE `licenses` SET `product` = ?, `term` = ?, `duration` = ? WHERE `license` = ?", [$_POST['product'], $_POST['term'], $_POST['duration'], $result['license']]);
                        if(isset($_POST['user']) && !empty($_POST['user'])){
                            $this->Model->update("UPDATE `licenses` SET `user` = ? WHERE `license` = ?", [$_POST['user'], $result['license']]);
                        }
                        $_POST = [];
                    }
                    break;
                default:
                    $object['status'] = 'invalid';
                    $object['message'] = 'Invalid type';
                    break;
            }
        }

        // Return
        return $object;
    }

    /**
     * Validate license
     *
     * @output JSON
     */
    public function validateAPIAction(){

        // Namespace: /sls/validate

        try {

            // Check the request method
            $license = $this->getParams('REQUEST', 'license');
            $product = $this->getParams('REQUEST', 'product');
            $user = $this->getParams('REQUEST', 'user');

            // Check if license is empty
            if (empty($license)) {
                throw new Exception("Missing parameters [license]");
            }

            // Check if running as server or client
            if($this->configurator->get('SLS', 'url')){

                // Running as client

                // Get license from the database
                $url = $this->configurator->get('SLS', 'url') . '?license=' . $license . '&product=' . $product . '&user=' . $user;

                // Get response from the server
                $response = file_get_contents($url);

                // Decode response
                $data = json_decode($response, true);

                // Send the output
                $this->output(
                    $data,
                    array('Content-Type: application/json', 'HTTP/1.1 200 OK')
                );
            } else {

                // Running as server

                // Send the output
                $this->output(
                    $this->Model->validate($license, $product, $user),
                    array('Content-Type: application/json', 'HTTP/1.1 200 OK')
                );
            }
        } catch (Exception $e) {

            // Log error
            $this->logger->error('Error in check method: ' . $e->getMessage());

			// Send the output
			$this->output(
				'Error in check method: ' . $e->getMessage(),
				array('Content-Type: application/json', 'HTTP/1.1 500')
			);
        }
    }
}
