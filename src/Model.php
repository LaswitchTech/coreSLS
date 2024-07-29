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

        // Initialize licenses
        $licenses = [];

        // Get Products
        $products = $this->getProducts();

        // Get licenses
        if($limit){
            $records = $this->select("SELECT * FROM `licenses` ORDER BY `id` ASC LIMIT ?", [$limit]);
        } else {
            $records = $this->select("SELECT * FROM `licenses` ORDER BY `id` ASC");
        }

        // Loop through records and set by id
        foreach($records as $record){
            $licenses[$record['id']] = $record;
            if($record['product']){
                if(isset($products[$record['product']])){
                    $licenses[$record['id']]['product'] = $products[$record['product']]['name'];
                } else {
                    $licenses[$record['id']]['product'] = '[Deleted]';
                }
            }
            if($record['expire']){
                $licenses[$record['id']]['expire'] = date('Y-m-d H:i:s', $record['expire']);
            }
        }

        // Return licenses
        return $licenses;
    }

    /**
     * Get license
     *
     * @param int $id
     * @return array
     */
    public function getLicense($license) {
        $license = $this->select("SELECT * FROM `licenses` WHERE `license` = ?", [$license]);
        return count($license) ? $license[0] : [];
    }

    /**
     * Delete license
     *
     * @param int $id
     * @return array
     */
    public function deleteLicense($license) {
        return $this->delete("DELETE FROM `licenses` WHERE `license` = ?", [$license]);
    }

    /**
     * Get all products
     *
     * @param int $limit
     * @return array
     */
    public function getProducts($limit = null) {

        // Initialize products
        $products = [];

        // Get products
        if($limit){
            $records = $this->select("SELECT * FROM `products` ORDER BY `id` ASC LIMIT ?", [$limit]);
        } else {
            $records = $this->select("SELECT * FROM `products` ORDER BY `id` ASC");
        }

        // Loop through records and set by id
        foreach($records as $record){
            $products[$record['id']] = $record;
        }

        // Return products
        return $products;
    }

    /**
     * Get product
     *
     * @param int $id
     * @return array
     */
    public function getProduct($name) {
        $product = $this->select("SELECT * FROM `products` WHERE `name` = ?", [$name]);
        return count($product) ? $product[0] : [];
    }

    /**
     * Delete product
     *
     * @param int $id
     * @return array
     */
    public function deleteProduct($name) {
        return $this->delete("DELETE FROM `products` WHERE `name` = ?", [$name]);
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
     * Renew license
     *
     * @param string $license
     * @return array
     */
    public function renew($license){
        return $this->SLS->renew($license);
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

    /**
     * Update license or product
     *
     * @param string $query
     * @param array $params
     * @return array
     */
    public function edit($table, $record){

        // Retrieve Primary Key
        $primary = $this->getPrimary($table);

        // Check if primary key is set
        if(!isset($record[$primary])){
            return false;
        }

        // Initialize query
        $query = "UPDATE `$table` SET ";

        // Initialize params
        $params = [];

        // Loop through record and set query
        foreach($record as $key => $value){
            $query .= "`$key` = ?, ";
            $params[] = $value;
        }

        // Remove last comma
        $query = rtrim($query, ', ');

        // Set where clause
        $query .= " WHERE `$primary` = ?";
        $params[] = $record[$primary];

        // Execute query
        return $this->update($query, $params);
    }
}
