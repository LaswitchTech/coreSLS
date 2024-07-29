<?php

// Declaring namespace
namespace LaswitchTech\coreSLS;

// Import additionnal class into the global namespace
use LaswitchTech\coreConfigurator\Configurator;
use LaswitchTech\coreDatabase\Database;
use LaswitchTech\coreLogger\Logger;
use Exception;
use DateTime;

class License {

    // Constants
    const digits = '0123456789';
    const lower = 'abcdefghijklmnopqrstuvwxyz';
    const upper = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';
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
    protected $license = null;
    protected $product = null;
    protected $user = null;
    protected $term = null;
    protected $duration = null;
    protected $expire = null;

    /**
     * Constructor
     */
    public function __construct($license = null){

        // Initialize Configurator
        $this->Configurator = new Configurator(['sls']);

        // Initiate Logger
        $this->Logger = new Logger('sls');

        // Initiate Database
        $this->Database = new Database();

        // Initialize properties
        $this->license = $license;
    }

    /**
     * Generate random string
     *
     * @param int $length
     * @return string
     */
    protected function generate($length = 25){

        // Generate random string
        $characters = self::digits . self::upper;
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }

        // Add '-' every 5 characters
        $randomString = substr(chunk_split($randomString, 5, '-'), 0, -1);

        // Return random string
        return $randomString;
    }

    /**
     * Load license
     *
     * @return boolean
     */
    protected function load(){

        // check if loaded
        if($this->id){
            return true;
        }

        // Load license
        $license = $this->Database->select("SELECT * FROM `licenses` WHERE `license` = ?", [$this->license]);

        // Log license
        $this->Logger->debug($license);

        // Return false if license is not found
        if (count($license) == 0) {
            return false;
        }

        // Set properties
        $this->id = $license[0]['id'];
        $this->license = $license[0]['license'];
        $this->product = $license[0]['product'];
        $this->user = $license[0]['user'];
        $this->term = $license[0]['term'];
        $this->duration = $license[0]['duration'];
        $this->expire = $license[0]['expire'];

        // Return true if license is found
        return true;
    }

    /**
     * Save license
     *
     * @return boolean
     */
    protected function save(){
        try{

            // Check if license is new
            if($this->id){

                // Update license
                $this->Database->update("UPDATE `licenses` SET `term` = ?, `duration` = ?, `expire` = ?, `license` = ?, `product` = ?, `user` = ? WHERE `id` = ?", [$this->term, $this->duration, $this->expire, $this->license, $this->product, $this->user, $this->id]);
            } else {

                // Insert license
                $this->id = $this->Database->insert("INSERT INTO `licenses` (`license`, `product`, `user`, `term`, `duration`, `expire`) VALUES (?, ?, ?, ?, ?, ?)", [$this->license, $this->product, $this->user, $this->term, $this->duration, $this->expire]);
            }

            if($this->id){

                // Return license
                return $this->license;
            }

            // Create Exception
            throw new Exception('Error in save method: License could not be saved');
        } catch (Exception $e) {

            // Log error
            $this->Logger->error('Error in save method: ' . $e->getMessage());

            // Return false
            return false;
        }
    }

    /**
     * Validate license
     *
     * @param string $product
     * @param string $user
     * @return boolean
     */
    public function validate($product, $user){
        try {

            // Load license
            $this->load();

            // Check if already exists
            if($this->id == null){
                return false;
            }

            // Check if product and user match
            if($this->product != $product || $this->user != $user){
                return false;
            }

            // Return true if license is valid
            switch ($this->term) {
                case 'perpetual':
                    return true;
                case 'subscription':
                    return strtotime($this->expire) >= time();
                default:
                    return false;
            }
        } catch (Exception $e) {

            // Log error
            $this->Logger->error('Error in validate method: ' . $e->getMessage());

            // Return false
            return false;
        }
    }

    /**
     * Create license
     *
     * @param string $term
     * @param string $duration
     * @return boolean
     */
    public function create($term = self::term, $duration = self::duration){

        // Load license
        $this->load();

        // Check if already exists
        if($this->id){
            return false;
        }

        // Initialise DateTime
        $date = new DateTime();

        // Generate license
        $this->license = $this->generate();

        // Set properties
        $this->term = $term;
        $this->duration = $duration;

        // Set expire
        switch($this->term){
            case 'trial':
            case 'subscription':
                switch($this->duration){
                    case 'day':
                    case 'week':
                    case 'month':
                    case 'year':
                        $date->setTimestamp(time());
                        $date->modify('+1 '.$this->duration);
                        $this->expire = $date->getTimestamp();
                        break;
                }
                break;
            case 'perpetual':
                $this->expire = time();
                break;
            default:
                $this->expire = time();
                break;
        }

        // Save license
        return $this->save();
    }

    /**
     * Renew license
     *
     * @return boolean
     */
    public function renew(){

        // Load license
        $this->load();

        // Check if already exists
        if($this->id == null){
            return false;
        }

        // Initialise DateTime
        $date = new DateTime();

        // Set expire
        switch($this->term){
            case 'trial':
            case 'subscription':
                switch($this->duration){
                    case 'day':
                    case 'week':
                    case 'month':
                    case 'year':
                        $date->setTimestamp($this->expire);
                        $date->modify('+1 '.$this->duration);
                        $this->expire = $date->getTimestamp();
                        break;
                    default:
                        $this->expire = time();
                }
                break;
            case 'perpetual':
                $this->expire = time();
                break;
            default:
                $this->expire = time();
                break;
        }

        // Save license
        return $this->save();
    }

    /**
     * Get license
     *
     * @return string
     */
    public function get(){

        // Return license
        return $this->license;
    }
}
