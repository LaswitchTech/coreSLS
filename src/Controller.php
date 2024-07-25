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
        $this->Model = new SlsModel();

		// Call the parent constructor
		parent::__construct($Auth);
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

            // Get license from the database
            $url = $this->configurator->get('SLS', 'url') . '?license=' . $license . '&product=' . $product . '&user=' . $user;

            // Get response from the server
            $response = file_get_contents($url);

            // Decode response
            $data = json_decode($response, true);

            // Return status
            return $data['status'] === 'valid';
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
