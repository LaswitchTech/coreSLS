<?php

// Declaring namespace
namespace LaswitchTech\coreSLS;

// Import additionnal class into the global namespace
use LaswitchTech\coreConfigurator\Configurator;
use LaswitchTech\coreDatabase\Database;
use LaswitchTech\coreLogger\Logger;
use Exception;

class Product {

    // Constants
    const terms = ['perpetual', 'subscription', 'trial'];
    const term = 'perpetual';
    const durations = ['day', 'week', 'month', 'year'];
    const duration = 'month';

	// core Modules
	private $Configurator;
    private $Database;
    private $Logger;

    // coreSLS Properties
    protected $id = null;
    protected $product = null;
    protected $description = null;
    protected $term = null;
    protected $duration = null;

    /**
     * Constructor
     */
    public function __construct($product = null){

        // Initialize Configurator
        $this->Configurator = new Configurator(['sls']);

        // Initiate Logger
        $this->Logger = new Logger('sls');

        // Initiate Database
        $this->Database = new Database();

        // Initialize properties
        $this->product = $product;
    }

    /**
     * Load product
     *
     * @return boolean
     */
    protected function load(){

        // check if loaded
        if($this->id){
            return true;
        }

        // Load license
        $license = $this->Database->select("SELECT * FROM `products` WHERE `name` = ?", [$this->product]);

        // Log license
        $this->Logger->debug($license);

        // Return false if license is not found
        if (count($license) == 0) {
            return false;
        }

        // Set properties
        $this->id = $license[0]['id'];
        $this->product = $license[0]['name'];
        $this->description = $license[0]['description'];
        $this->term = $license[0]['term'];
        $this->duration = $license[0]['duration'];

        // Return true if license is found
        return true;
    }

    /**
     * Save product
     *
     * @return boolean
     */
    protected function save(){
        try{

            // Check if license is new
            if($this->id){

                // Update license
                $this->Database->update("UPDATE `products` SET `name` = ?, `description` = ?, `term` = ?, `duration` = ? WHERE `id` = ?", [$this->product, $this->description, $this->term, $this->duration, $this->id]);
            } else {

                // Insert license
                $this->id = $this->Database->insert("INSERT INTO `products` (`name`, `description`, `term`, `duration`) VALUES (?, ?, ?, ?)", [$this->product, $this->description, $this->term, $this->duration]);
            }

            // Return license
            return $this->product;
        } catch (Exception $e) {

            // Log error
            $this->Logger->error('Error in save method: ' . $e->getMessage());

            // Return false
            return false;
        }
    }

    /**
     * Create product
     *
     * @param string $description
     * @param string $term
     * @param string $duration
     * @return boolean
     */
    public function create($term = self::term, $duration = self::duration, $description = null){

        // Load license
        $this->load();

        // Check if already exists
        if($this->id){
            return false;
        }

        // Set properties
        $this->description = $description;
        $this->term = $term;
        $this->duration = $duration;

        // Save product
        return $this->save();
    }

    /**
     * Get product
     *
     * @return string
     */
    public function get(){

        // Return product id
        return $this->id;
    }
}
