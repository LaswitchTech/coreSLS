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

        // Set object properties
        $object['terms'] = ['perpetual', 'subscription', 'trial'];
        $object['durations'] = ['day', 'week', 'month', 'year'];
        $object['products'] = $this->Model->getProducts();

        // Check request method
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Check for required parameters
            $break = false;
            switch($object['type']){
                case 'product':
                    foreach(['name', 'description', 'term'] as $param){
                        if (!isset($_POST[$param]) || empty($_POST[$param])) {
                            $object['status'] = 'invalid';
                            $object['message'] = 'Missing required parameters: ' . $param;
                            $break = true;
                            break;
                        }
                    }
                    if (in_array($_POST['term'],['subscription','trial']) && (!isset($_POST['duration']) || empty($_POST['duration']))) {
                        $object['status'] = 'invalid';
                        $object['message'] = 'Missing required parameters: duration';
                        $break = true;
                    }
                    if($break){
                        break;
                    }
                    $result = $this->Model->new($object['type'], $_POST['name']);
                    $object['status'] = $result['status'];
                    $object['message'] = $result['message'];
                    if(isset($result['product'])){
                        $product = ['id' => $result['product'], 'name' => $_POST['name'], 'description' => $_POST['description'], 'term' => $_POST['term'], 'duration' => $_POST['duration']];
                        $this->Model->edit("products",$product);
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
                    foreach($object['products'] as $product){
                        if($product['name'] === $_POST['product']){
                            $product = $product['id'];
                            break;
                        }
                    }
                    if(isset($product) && !empty($product)){
                        $result = $this->Model->new($object['type'], $product);
                    } else {
                        $result = $this->Model->new($object['type']);
                    }
                    $object['status'] = $result['status'];
                    $object['message'] = $result['message'];
                    if(isset($result['license'])){
                        $license = ['id' => $result['license'], 'term' => $_POST['term'], 'duration' => $_POST['duration']];
                        if(isset($_POST['product']) && !empty($_POST['product'])){
                            $license['product'] = $_POST['product'];
                        }
                        if(isset($_POST['user']) && !empty($_POST['user'])){
                            $license['user'] = $_POST['user'];
                        }
                        $this->Model->edit("licenses",$license);
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
     * Edit License/product
     *
     * @return Array
     */
    public function editRouterAction(){

        // Namespace: /sls/edit

        // Initialize object
        $object = [];

        // Retrieve Parameters
        $object['license'] = isset($_GET['license']) ? $_GET['license'] : '';
        $object['product'] = isset($_GET['product']) ? $_GET['product'] : '';
        if($object['license']){
            $object['type'] = 'license';
        } elseif($object['product']){
            $object['type'] = 'product';
        } else {
            $object['type'] = '';
        }

        // Set object properties
        $object['terms'] = ['perpetual', 'subscription', 'trial'];
        $object['durations'] = ['day', 'week', 'month', 'year'];
        if($object['type'] === 'license'){
            $object['products'] = $this->Model->getProducts();
            $object['license'] = $this->Model->getLicense($object['license']);
        }
        if($object['type'] === 'product'){
            $object['product'] = $this->Model->getProduct($object['product']);
        }

        // Check request method
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Check for required parameters
            switch($object['type']){
                case 'product':
                    $product = $object['product'];
                    foreach(['name', 'description', 'term', 'duration'] as $param){
                        if (!isset($_POST[$param]) || empty($_POST[$param])) {
                            $object['status'] = 'invalid';
                            $object['message'] = 'Missing required parameters: ' . $param;
                            break;
                        } else {
                            $product[$param] = $_POST[$param];
                        }
                    }
                    $this->Model->edit("products",$product);
                    $_POST = [];
                    $object['status'] = 'valid';
                    $object['message'] = 'Product['.$product['name'].'] was updated!';
                    $object['product'] = $product;
                    break;
                case 'license':
                    $license = $object['license'];
                    foreach(['product', 'term', 'duration'] as $param){
                        if (!isset($_POST[$param]) || empty($_POST[$param])) {
                            $object['status'] = 'invalid';
                            $object['message'] = 'Missing required parameters: ' . $param;
                            break;
                        } else {
                            $license[$param] = $_POST[$param];
                        }
                    }
                    if(isset($_POST['user']) && !empty($_POST['user'])){
                        $license['user'] = $_POST['user'];
                    }
                    $this->Model->edit("licenses",$license);
                    $_POST = [];
                    $object['status'] = 'valid';
                    $object['message'] = 'License['.$license['license'].'] was updated!';
                    $object['license'] = $license;
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
     * Delete License/product
     *
     * @return Array
     */
    public function deleteRouterAction(){

        // Namespace: /sls/delete

        // Initialize object
        $object = [];

        // Retrieve Parameters
        $object['license'] = isset($_GET['license']) ? $_GET['license'] : '';
        $object['product'] = isset($_GET['product']) ? $_GET['product'] : '';
        if($object['license']){
            $object['type'] = 'license';
        } elseif($object['product']){
            $object['type'] = 'product';
        } else {
            $object['type'] = '';
        }

        // Set object properties
        $object['terms'] = ['perpetual', 'subscription', 'trial'];
        $object['durations'] = ['day', 'week', 'month', 'year'];
        if($object['type'] === 'license'){
            $object['products'] = $this->Model->getProducts();
            $object['license'] = $this->Model->getLicense($object['license']);
        }
        if($object['type'] === 'product'){
            $object['product'] = $this->Model->getProduct($object['product']);
        }

        // Check request method
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Check for required parameters
            switch($object['type']){
                case 'product':
                    if(empty($object['product']['name'])){
                        $object['status'] = 'invalid';
                        $object['message'] = 'Could not find product!';
                        break;
                    }
                    if($this->Model->deleteProduct($object['product']['name'])){
                        $object['status'] = 'valid';
                        $object['message'] = 'Product['.$object['product']['name'].'] was deleted!';
                    } else {
                        $object['status'] = 'invalid';
                        $object['message'] = 'Product['.$object['product']['name'].'] could not be deleted!';
                    }
                    break;
                case 'license':
                    if(empty($object['license']['license'])){
                        $object['status'] = 'invalid';
                        $object['message'] = 'Could not find license!';
                        break;
                    }
                    if($this->Model->deleteLicense($object['license']['license'])){
                        $object['status'] = 'valid';
                        $object['message'] = 'License['.$object['license']['license'].'] was deleted!';
                    } else {
                        $object['status'] = 'invalid';
                        $object['message'] = 'License['.$object['license']['license'].'] could not be deleted!';
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
     * Renew License
     *
     * @return Array
     */
    public function renewRouterAction(){

        // Namespace: /sls/renew

        // Initialize object
        $object = [];

        // Retrieve Parameters
        $object['license'] = isset($_GET['license']) ? $_GET['license'] : '';
        $object['license'] = $this->Model->getLicense($object['license']);

        // Set object properties
        $object['products'] = $this->Model->getProducts();
        $object['terms'] = ['perpetual', 'subscription', 'trial'];
        $object['durations'] = ['day', 'week', 'month', 'year'];

        // Check request method
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {

            // Check for required parameters
            $result = $this->Model->renew($object['license']['license']);
            $object['status'] = $result['status'];
            $object['message'] = $result['message'];
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
