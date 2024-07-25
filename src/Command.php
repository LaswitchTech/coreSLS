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
