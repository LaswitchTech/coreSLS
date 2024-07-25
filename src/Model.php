<?php

// Declaring namespace
namespace LaswitchTech\coreSLS;

//Import BaseModel class into the global namespace
use LaswitchTech\coreBase\BaseModel;
use LaswitchTech\coreSLS\SLS;

class Model extends BaseModel {

    // coreSLS Module
    private $SLS;

    /**
     * Constructor
     */
    public function __construct(){

        // Initialize SLS
        $this->SLS = new SLS();

        // Call the parent constructor
        parent::__construct();
    }

    /**
     * Get all licenses
     *
     * @param int $limit
     * @return array
     */
    public function getLicenses($limit = null) {
        if($limit){
            return $this->select("SELECT * FROM licenses ORDER BY id ASC LIMIT ?", [$limit]);
        } else {
            return $this->select("SELECT * FROM licenses ORDER BY id ASC");
        }
    }

    /**
     * Get license
     *
     * @param int $id
     * @return array
     */
    public function getLicense($id) {
        return $this->select("SELECT * FROM licenses WHERE id = ?", [$id]);
    }

    /**
     * Get all products
     *
     * @param int $limit
     * @return array
     */
    public function getProducts($limit = null) {
        if($limit){
            return $this->select("SELECT * FROM products ORDER BY id ASC LIMIT ?", [$limit]);
        } else {
            return $this->select("SELECT * FROM products ORDER BY id ASC");
        }
    }

    /**
     * Get product
     *
     * @param int $id
     * @return array
     */
    public function getProduct($id) {
        return $this->select("SELECT * FROM products WHERE id = ?", [$id]);
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
        return $this->SLS->validate($license, $product, $user);
    }

    /**
     * Create license or product
     *
     * @param string $type
     * @param array $product
     * @return array
     */
    public function new($type, $product = null){
        return $this->SLS->create($type, $product);
    }
}
