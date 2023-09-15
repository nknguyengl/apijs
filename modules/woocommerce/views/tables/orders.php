<?php

defined('BASEPATH') or exit('No direct script access allowed');

$aColumns = [
    'order_id',
    'status',
    'total',
    'order_number',
];

$sIndexColumn = 'id';
$sTable       = db_prefix() . 'woocommerce_orders';
$join = [];
$where = [];

$custom_view = $this->ci->input->post('custom_view') ? $this->ci->input->post('custom_view') : '';
if ($custom_view) {
    if ($custom_view == 'pending') {
        $where[] = 'AND status = "pending"';
    }
    if ($custom_view == 'processing') {
        $where[] = 'AND status = "processing"';
    }
    if ($custom_view == 'on-hold') {
        $where[] = 'AND status = "on-hold"';
    }
    if ($custom_view == 'cancelled') {
        $where[] = 'AND status = "cancelled"';
    }
    if ($custom_view == 'failed') {
        $where[] = 'AND status = "failed"';
    }
    if ($custom_view == 'completed') {
        $where[] = 'AND status = "completed"';
    }
    if ($custom_view == 'refunded') {
        $where[] = 'AND status = "refunded"';
    }
}

$storeId = active_store_id();
$join[] = 'LEFT JOIN ' . db_prefix() . 'woocommerce_customers ON ' . db_prefix() . 'woocommerce_orders.customer_id = ' . db_prefix() . 'woocommerce_customers.woo_customer_id';
if(is_nan($storeId) || is_null($storeId)|| $storeId == '') $storeId = 00;
$where[] = 'AND '.$sTable .'.store_id = '.$storeId;

$result = data_tables_init($aColumns, $sIndexColumn, $sTable, $join, $where, ['currency',db_prefix() . 'woocommerce_orders.phone', 'date_created', 'invoice_id','address', db_prefix() .'woocommerce_customers.first_name', db_prefix() .'woocommerce_customers.last_name']);
$output  = $result['output'];
$rResult = $result['rResult'];

foreach ($rResult as $aRow) {
    $row = [];
    $content = '<div class="row-options"><a class="text-info " href="'.admin_url("woocommerce/order/".$aRow["order_id"]).'">'._l('view').'</a> &#124; <a class="order_update text-info " data-target="#updateModal" data-id="'.$aRow["order_id"].'" data-toggle="modal">'._l('update_status').'</a> &#124; <a class="order_delete text-danger " data-target="#deleteModal" data-id="'.$aRow["order_id"].'" data-toggle="modal">'._l('delete').' </a></div>';    
    $has_invoice = '<a href="' . admin_url() . 'invoices/invoice/' . $aRow['invoice_id'] . '">'._l('view_invoice').'</a>';
    $name_customer = !isset($aRow['first_name']) ? "Guest": $aRow['first_name'] .$aRow['last_name'];
    $row[] = '<span>' . $aRow['order_number'] . '</span> <br>'.$content .'</a>';
    $row[] =  '<span>' . $name_customer . '</span>';
    $row[] =  '<span "text-muted">' . $aRow['address'] . '</span>';
    $row[] =  '<span>' . $aRow['phone'] . '</span>';
    $row[] =  '<span>' . $aRow['status'] . '</span>';
    $row[] = '<span>' . $aRow['currency'] . ' ' .$aRow['total'] . '</span>';
    $row[] = _dt($aRow['date_created']) . '</span></a>';
    $row[] = isset($aRow['invoice']) ? $has_invoice :"";

    $output['aaData'][] = $row;
}

