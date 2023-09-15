<?php
defined('BASEPATH') or exit('No direct script access allowed');

use Automattic\WooCommerce\Client;
use Automattic\WooCommerce\HttpClient\HttpClientException;
use Illuminate\Support\Facades\Route;
use Twilio\Rest\Verify;

class Woocommerce_module
{
    private $url;
    private $key;
    private $secret;

    public function __construct()
    {
        $this->ci = &get_instance();
    }

    public function connect()
    {
        $options = ['wp_api' => true, 'version' => 'wc/v3', 'query_string_auth' => true, 'verify_ssl'=>false];
        $connect = new Client($this->url, $this->key, $this->secret, $options);
        return $connect;
    }

    public function test(){
        try {
          return $this->connect()->get('data/currencies/current');
        } catch (HttpClientException $e) {
          return $e->getMessage();
        }

    }

    public function orders()
    {
        $page = 1;
        $all_orders = [];
        $orders = null;
        do {
            try {
                $orders = $this->connect()->get('orders', array('per_page' => 100, 'page' => $page));
            } catch (HttpClientException $e) {
                $error = $e->getMessage();
                $orders  = $error;
            }
            if (!is_array($orders)) {
                break;
            }

            if (count($all_orders) >= 1500) {
                break;
            }

            $all_orders += $orders;
            $page++;
        } while (count($orders) > 0);

        if (!is_array($orders)) {
            return  $orders;
        } else {
            return  $all_orders;
        }
    }

    public function products()
    {
        $page = 1;
        $all_products = [];
        $products = null;
        do {
            try {
                $products = $this->connect()->get('products', array('per_page' => 100, 'page' => $page));
            } catch (HttpClientException $e) {
                $error = $e->getMessage();
                $products = $error;
            }
            if (!is_array($products)) {
                break;
            }
            $all_products += $products;
            $page++;

            if (count($all_products) >= 1500) {
                break;
            }
        } while (count($products) > 0);
        if (!is_array($products)) {
            return  $products;
        } else {
            return  $all_products;
        }
    }

    public function sales($query = ['period' => 'year'])
    {
        $sales = null;
        try {
            $sales = $this->connect()->get('reports/sales');
        } catch (HttpClientException $e) {
            $error = $e->getMessage();
            $sales = $error;
        }

        return  $sales;
    }

    public function customers()
    {
        $page = 1;
        $customers = null;
        $all_customers = [];
        do {
            try {
                $customers = $this->connect()->get('customers', array('per_page' => 100, 'page' => $page));
            } catch (HttpClientException $e) {
                $error = $e->getMessage();
                $customers = $error;
            }

            if (!is_array($customers)) {
                break;
            }

            $all_customers += $customers;
            $page++;

            if (count($all_customers) >= 1500) {
                break;
            }
        } while (count($customers) > 0);
        if (!is_array($customers)) {
            return  $customers;
        } else {
            return  $all_customers;
        }
    }

    public function customer($id)
    {
        $customer = null;
        try {
            $customer = $this->connect()->get('customers/' . $id);
        } catch (HttpClientException $e) {
            $error = $e->getMessage();
            $customer = $error;
        }
        return  $customer;
    }

    public function order($id)
    {
        $order = null;
        try {
            $order = $this->connect()->get('orders/' . $id);
        } catch (HttpClientException $e) {
            $error = $e->getMessage();
            $order = $error;
        }
        return  $order;
        
    }

    public function update_order($data)
    {
        $update = null;
        $id = $data['orderId'];
        $status = $data['status'];
        try {
            $update = $this->connect()->put('orders/' . $id, array('status' => $status));
            log_activity(' Product ' . $id . ' has been updated as ' . $status);
        } catch (HttpClientException $e) {
            $error = $e->getMessage();
            $update = $error;
        }
        return $update;
    }

    public function delete_order($data)
    {
        $update = null;
        $id = $data['orderid'];
        try {
            $update = $this->connect()->delete('orders/' . $id, ['force' => true]);
            log_activity('Order: ' . $id . ' was Successfully deleted');
        } catch (HttpClientException $e) {
            $error = $e->getMessage();
            $update = $error;
        }
        return $update;
    }

    public function cron($endpoint,$params)
    {
        try {
            $response = $this->connect()->get($endpoint, $params);
        } catch (HttpClientException $e) {
            $error = $e->getMessage();
            $response = $error;
        }   
        return $response;
    }

    public function cronReport($data)
    {
        try {
            $report = $this->connect()->get('reports/'.$data);
        } catch (HttpClientException $e) {
            $error = $e->getMessage();
            $report = $error;
        }   
        return $report;
    }

    public function delete($data,$scope)
    {
        $id = $data['productId'];
        $route = $scope.'/'. $id ;
        try {
            $update = $this->connect()->delete($route, ['force' => true]);
            log_activity('Product : ' . $id . ' was Successfully deleted');
        } catch (HttpClientException $e) {
            $error = $e->getMessage();
            $update = $error;
        }
        return $update;
    }

    public function product($id)
    {
        $product = null;
        try {
            $product = $this->connect()->get('products/' . $id);
        } catch (HttpClientException $e) {
            $error = $e->getMessage();
            $product = $error;
        }
        return  $product;
    }

    public function update($id,$data,$scope)
    {
        $product = null;
        try {
            $product = $this->connect()->put($scope.'s/'.$id,$data );
        } catch (HttpClientException $e) {
            $error = $e->getMessage();
            $product = $error;
        }
        return  $product;
    }

    public function set_store($store)
    {
        $this->url = $store->url;
        $this->key = $store->key;
        $this->secret = $store->secret;
    }
}
