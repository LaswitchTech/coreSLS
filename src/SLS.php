<?php

// Declaring namespace
namespace LaswitchTech\coreSLS;

// Import additionnal class into the global namespace
use LaswitchTech\coreConfigurator\Configurator;
use LaswitchTech\coreLogger\Logger;
use Exception;

// Import additionnal coreSLS modules into the global namespace
use LaswitchTech\coreSLS\License;
use LaswitchTech\coreSLS\Product;

class SLS {

	// core Modules
	private $Configurator;
    private $Logger;

    /**
     * Constructor
     */
    public function __construct(){

        // Initialize Configurator
        $this->Configurator = new Configurator(['sls']);

        // Initiate Logger
        $this->Logger = new Logger('sls');
    }

    /**
     * Create license or product
     *
     * @param string $type
     * @param array $product
     * @return array
     */
    public function create($type = 'license', $product = null){
        try {

            // Check if type is empty
            if (empty($type)) {
                $type = 'license';
            }

            // Initialize object
            $object = null;

            // Check if type is license
            if(strtolower($type) === 'license'){

                // Create object
                $object = new License();
            } else {

                // Check if type is product
                if(strtolower($type) === 'product'){

                    // Check if product is empty
                    if (empty($product)) {
                        return ['status' => 'invalid', 'message' => 'Missing parameters'];
                    }

                    // Create object
                    $object = new Product($product);
                } else {

                    // Return invalid
                    return ['status' => 'invalid', 'message' => 'Invalid type'];
                }
            }

            // Check if object is empty
            if (empty($object)) {
                return ['status' => 'invalid', 'message' => 'Object could not be created'];
            }

            // Check status
            if($object->create()){

                // Return valid
                return ['status' => 'valid', 'message' => 'License is created', 'license' => $object->get()];
            } else {

                // Return invalid
                return ['status' => 'invalid', 'message' => 'License could not be created'];
            }
        } catch (Exception $e) {

            // Log error
            $this->Logger->error('Error in create method: ' . $e->getMessage());

            // Return false
            return ['status' => 'invalid', 'message' => 'Exception occurred'];
        }
    }

    /**
     * Validate license
     *
     * @param string $license
     * @param string $product
     * @param string $user
     * @return array
     */
    public function validate($license, $product = null, $user = null){
        try {

            // Check if license, product or user is empty
            if (empty($license)) {
                return ['status' => 'invalid', 'message' => 'Missing parameters'];
            }

            // Create object
            $object = new License($license);

            // Check status
            if($object->validate($product, $user)){

                // Return valid
                return ['status' => 'valid', 'message' => 'License is valid'];
            } else {

                // Return invalid
                return ['status' => 'invalid', 'message' => 'License could not be validated'];
            }
        } catch (Exception $e) {

            // Log error
            $this->Logger->error('Error in validate method: ' . $e->getMessage());

            // Return false
            return ['status' => 'invalid', 'message' => 'Exception occurred'];
        }
    }
}
