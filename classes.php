<?php

class Customer
{
    public $id, $fname, $lname, $company_business, $email, $number, $address;

    function __construct($details)
    {
        $this->id = $details[0];
        $this->fname = $details[1];
        $this->lname = $details[2];
        $this->company_business = $details[3];
        $this->email = $details[4];
        $this->number = $details[5];
        $this->address = $details[6];
    }

    function insert_in_db()
    {
    }
}
class Vendor
{
    public $id, $fname, $lname, $company_business, $email, $number, $address;

    function __construct($details)
    {
        $this->id = $details[0];
        $this->fname = $details[1];
        $this->lname = $details[2];
        $this->company_business = $details[3];
        $this->email = $details[4];
        $this->number = $details[5];
        $this->address = $details[6];
    }
}
class Product
{
    public $id, $name, $price_per_box, $price_per_unit, $units_per_box, $packaging_size, $packaging_unit;

    function __construct($details)
    {
        $this->id = $details[0];
        $this->name = $details[1];
        $this->price_per_box = $details[2];
        $this->units_per_box = $details[3];
        $this->packaging_size = $details[4];
        $this->packaging_unit = $details[5];
    }
}

class Order
{
    public $id, $type, $person, $invoice_no, $products, $quantities, $products_details, $method, $status, $advanced_payment, $date, $delivery_date;

    function __construct($details)
    {
        $this->id = $details[0];
        $this->type = $details[1];
        $this->person = $details[2];
        $this->invoice_no = $details[3];
        $this->products = $details[4];
        $this->quantities = $details[5];
        $this->products_details = $details[6];
        $this->method = $details[7];
        $this->status = $details[8];
        $this->advanced_payment = $details[9];
        $this->date = $details[10];
        $this->delivery_date = $details[11];
    }
}
