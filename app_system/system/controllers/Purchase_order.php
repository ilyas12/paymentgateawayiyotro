<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Purchase_order extends AR_Controller
{
    var $user_id;
    var $purchase_order_model;
    var $purchase_order_detail_model;
    var $supplier_model;
    var $inventory_model;
    var $inventory_log_model;
    var $purchase_order_status_model;
    public function __construct()
    {
        parent::__construct();
        $this->load->model("master_model");
        $this->purchase_order_model         = new master_model('purchase_order');
        $this->purchase_order_detail_model  = new master_model('purchase_order_detail');
        $this->supplier_model               = new master_model('supplier');
        $this->inventory_model              = new master_model('inventory');
        $this->inventory_log_model          = new master_model('inventory_log');
        $this->purchase_order_status_model  = new master_model('purchase_order_status');

        // $this->is_login('admin');
        $user = $this->session->userdata('adminData');
        $this->user_id = $user['id']; 
    }

    public function index()
    {
        $temp_data = $this->temporary_data;
        $data['title'] = "Purchase Order List";
        $data['add_new_link'] = site_url('purchase_order_list/add');
        $data['po'] = self::datatables();
        $this->template->loadView('admin/purchaseOrderList', $data, "admin");
    }

    public function datatables(){
        $data = $this->db->select('po.*, s.name, ps.status')
                ->from('purchase_order po')
                ->join('supplier s','s.id = po.supplier_id','left')
                ->join('purchase_order_status ps','ps.id = po.po_status','left')
                ->where('po.deleted_at',NULL)
                ->get()->result();
        return $data;
        // echo json_encode($data);
    }

    public function add()
    {
        $data['title'] = "Add New Purchase Order";
        $data['supplier_data'] = $this->supplier_model->all();
        $data['inventory_data'] = $this->inventory_model->all();
        $data['action_url'] = site_url('purchase_order_list/store');
        $data['supplier']= $this->supplier_model->all();
        $data['po_status'] = $this->purchase_order_status_model->all();
        $data['po_details'] = array();
        $this->template->loadView('admin/purchaseOrderForm', $data, "admin");

    }   

    public function store(){
        $crud_data = $this->input_data;

        $po = array(
            "purchase_order_no"=> $crud_data['purchase_order_no'],
            "supplier_id"=> $crud_data['supplier_id'],
            "po_date"=> date('Y-m-d',strtotime($crud_data['po_date'])),
            "amount"=> $crud_data['amount'],
            "balance"=> $crud_data['balance'],
            "po_status"=> $crud_data['po_status'],
            "gst"=> $crud_data['gst'],
            );

        // STORE TO TABLE PURCHASE ORDER
        $id = $this->purchase_order_model->add($po);
        // $id =1;
        $po_detail = array();
        foreach ($crud_data['inventory_id'] as $key => $value) {
            if($value != ""){

            $po_detail[$key] = array(
                                "purchase_order_id"=>$id,
                                "inventory_id"=>$value,
                                "qty"=>$crud_data['qty'][$key],
                                "price"=>$crud_data['price'][$key],
                                "total"=>$crud_data['total'][$key],
                                );
            // STORE TO TABLE PURCHASE ORDER DETAIL
            $this->purchase_order_detail_model->add($po_detail[$key]);

            // GET LAST INVENTORY QTY
            $inv = $this->inventory_model->find($value);

            // SET DATA TO BEGIN UPDATING INVENTORY TABLE
            $inventory = array(
                        "qty"=> $inv['qty'] + $crud_data['qty'][$key]
                        );

            // SET DATA TO BEGIN INSERTING INVENTORY LOG TABLE
            $inventory_log = array(
                        "inventory_id"=>$value,
                        "inventory_name"=>$inv['name'],
                        "starting_qty"=>$inv['qty'],
                        "ending_qty"=> $inv['qty'] + $crud_data['qty'][$key],
                        "IN"=> $crud_data['qty'][$key],
                        "OUT"=> 0,
                        "user_id"=> $this->user_id,
                        "remark"=> "Adjust from purchase order"
                        );

            // UPDATING INVENTORY TABLE
            $this->inventory_model->update($value,$inventory);

            // INSERT INTO INVENTORY LOG FROM INVENTORY TABLE
            $this->inventory_log_model->add($inventory_log);
            // echo json_encode($po_detail[$key]);
            }
        }

        redirect('purchase_order_list');
    }

    function edit($id){
        $data['title'] = "Edit Purchase Order";
        $data['supplier_data'] = $this->supplier_model->all();
        $data['inventory_data'] = $this->inventory_model->all();
        $data['action_url'] = site_url('purchase_order_list/update/'.$id);
        $data['supplier']= $this->supplier_model->all();
        $data['po_status'] = $this->purchase_order_status_model->all();
        $data['datas'] = $this->purchase_order_model->find($id);
        $data['po_details'] = $this->purchase_order_detail_model->all(" AND purchase_order_id = '".$data['datas']['id']."' ");
        $this->template->loadView('admin/purchaseOrderForm', $data, "admin");
        // echo json_encode($data['datas']);
    }

    function update($id){
        $crud_data = $this->input_data;
        $po = array(
            "purchase_order_no"=> $crud_data['purchase_order_no'],
            "supplier_id"=> $crud_data['supplier_id'],
            "po_date"=> date('Y-m-d',strtotime($crud_data['po_date'])),
            "amount"=> $crud_data['amount'],
            "balance"=> $crud_data['balance'],
            "po_status"=> $crud_data['po_status'],
            "gst"=> $crud_data['gst'],
            );

        // STORE TO TABLE PURCHASE ORDER
        $this->purchase_order_model->update($id, $po);

        $po_detail = array();
        foreach ($crud_data['inventory_id'] as $key => $value) {
            if($value != ""){

                $po_detail[$key] = array(
                                    "purchase_order_id"=>$id,
                                    "inventory_id"=>$value,
                                    "qty"=>$crud_data['qty'][$key],
                                    "price"=>$crud_data['price'][$key],
                                    "total"=>$crud_data['total'][$key],
                                    );

                $getid = $this->purchase_order_detail_model->all(' AND purchase_order_id = "'.$id.'" AND inventory_id = "'.$value.'" LIMIT 1');
                if($getid != null){
                    // UPDATE PURCHASE ORDER DETAIL TABLE
                    $this->purchase_order_detail_model->update($getid[0]['id'],$po_detail[$key]);

                    // GET LAST INVENTORY QTY
                    $inv = $this->inventory_model->find($value);

                    // IF INVENTORY QTY FROM PO BEFORE UPDATING IS MORE THAN NEW ONE THEN DECREASE INVENTORY QTY FROM INVENTORY TABLE
                    // AFTER THAT ADJUST CALCULATION WITH QTY FROM NEW DATA QTY FROM PO
                    $decrease_inv = $inv['qty'] - $getid[0]['qty'];
                    $adjustment = $decrease_inv + $crud_data['qty'][$key];
                    $inventory = array("qty"=>$adjustment);

                    // UPDATING INVENTORY TABLE
                    $this->inventory_model->update($value,$inventory);

                    // INSERT INVENTORY LOG
                    $inventory_log = array(
                                "inventory_id"=>$value,
                                "inventory_name"=>$inv['name'],
                                "starting_qty"=>$inv['qty'],
                                "ending_qty"=> $adjustment,
                                "IN"=> abs($getid[0]['qty'] - $crud_data['qty'][$key]),
                                "OUT"=> 0,
                                "user_id" => $this->user_id,
                                "remark"=> "Updating Purchase Order"
                                );

                    $this->inventory_log_model->add($inventory_log);
                }
                else{
                    // IF THERE'S FAILED TO UPDATE THEN STORE TO TABLE PURCHASE ORDER DETAIL
                    $this->purchase_order_detail_model->add($po_detail[$key]);

                    // GET LAST INVENTORY QTY
                    $inv = $this->inventory_model->find($value);

                    // SET DATA TO BEGIN UPDATING INVENTORY TABLE
                    $inventory = array(
                                "qty"=> $inv['qty'] + $crud_data['qty'][$key]
                                );

                    // SET DATA TO BEGIN INSERTING INVENTORY LOG TABLE
                    $inventory_log = array(
                                "inventory_id"=>$value,
                                "inventory_name"=>$inv['name'],
                                "starting_qty"=>$inv['qty'],
                                "ending_qty"=> $inv['qty'] + $crud_data['qty'][$key],
                                "IN"=> $crud_data['qty'][$key],
                                "OUT"=> 0,
                                "user_id" => $this->user_id,
                                "remark"=> "Adjust from purchase order"
                                );

                    // UPDATING INVENTORY TABLE
                    $this->inventory_model->update($value,$inventory);

                    // INSERT INTO INVENTORY LOG FROM INVENTORY TABLE
                    $this->inventory_log_model->add($inventory_log);


                }
            }
        }
        redirect('purchase_order_list');
    }

    public function softDelete($id){
        $db_data = $this->purchase_order_model->find($id);      
        $this->purchase_order_model->delete($id);
        redirect('purchase_order_list');
    }

}