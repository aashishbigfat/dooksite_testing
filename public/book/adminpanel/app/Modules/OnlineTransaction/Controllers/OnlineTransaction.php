<?php



namespace Modules\OnlineTransaction\Controllers;



use App\Modules\OnlineTransaction\Models\OnlineTransactionModel;

use App\Controllers\BaseController;

use CodeIgniter\Model;

use Modules\OnlineTransaction\Config\Validation;



require 'vendor/excel/autoload.php';



use PhpOffice\PhpSpreadsheet\Spreadsheet;

use PhpOffice\PhpSpreadsheet\Writer\Xlsx;



class OnlineTransaction extends BaseController

{
 
    protected $title; 
    protected $web_partner_id; 
    protected $user_id;   
    protected $web_partner_details;  
    protected $admin_comapny_detail;  


    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)

    {

        parent::initController($request, $response, $logger);

        $this->title = "Online Transactions";

        $this->web_partner_id = admin_cookie_data()['admin_user_details']['web_partner_id'];

        $this->web_partner_details = admin_cookie_data()['admin_user_details'];

        $this->admin_comapny_detail = admin_cookie_data()['admin_comapny_detail'];

        $this->user_id = admin_cookie_data()['admin_user_details']['id'];



        if (permission_access_error("OnlineTransaction", "OnlineTransaction_Module")) {

        }

    }



    public function index()

    {

        $OnlineTransactionModel = new OnlineTransactionModel();

        $getData = $this->request->getGET();

        if (isset($getData['key'])) {

            $searchData = array();

            $searchData = $this->request->getGet();

            $lists = $OnlineTransactionModel->search_data($this->request->getGet(),$this->web_partner_id);

        } else {

            $lists = $OnlineTransactionModel->online_transaction_list($this->web_partner_id);

            //  pr($lists);exit; 

        } 



        //  pr($lists);exit;



        if (isset($searchData['export_excel']) && $searchData['export_excel'] == 1) {

            OnlineTransaction::export_online_transaction_account_logs($lists);

        }

        $data = [

            'title' => $this->title,

            'list' => $lists,

            'view' => 'OnlineTransaction\Views\transaction-list',

            'pager' => $OnlineTransactionModel->pager,

            'search_bar_data' => $this->request->getGet(),

        ];

        return view('template/sidebar-layout', $data);

    }



    public function export_online_transaction_account_logs($online_trans_logs)

    { 

        if (permission_access("OnlineTransaction", "OnlineTransaction_download_excel")) {

            $fileName = 'Online-Transaction-Account-Logs' . "." . 'xlsx';

            $spreadsheet = new Spreadsheet();

            $sheet = $spreadsheet->getActiveSheet();

            $sheet->mergeCells('A1:J1');

            $sheet->getStyle('A1:J1')->getAlignment()->setHorizontal('center');

            $sheet->setCellValue('A1', 'Payment Gateway History');

            $sheet->getStyle("A1")->getFont()->setBold(true)->setName('Arial')->setSize(11);

            $sheet->getStyle("A2:J2")->getFont()->setBold(true)->setName('Arial')->setSize(11);

            $sheet->getStyle("A:J")->getFont()->setName('Arial')->setSize(11);

            $sheet->getColumnDimension('A')->setAutoSize(true);

            $sheet->getColumnDimension('B')->setAutoSize(true);

            $sheet->getColumnDimension('C')->setAutoSize(true);

            $sheet->getColumnDimension('D')->setAutoSize(true);

            $sheet->getColumnDimension('E')->setAutoSize(true);

            $sheet->getColumnDimension('F')->setAutoSize(true);

            $sheet->getColumnDimension('G')->setAutoSize(true);

            $sheet->getColumnDimension('H')->setAutoSize(true);

            $sheet->getColumnDimension('I')->setAutoSize(true);

            $sheet->getColumnDimension('J')->setAutoSize(true);



            $sheet->setCellValue('A2', 'S.No.');

            $sheet->setCellValue('B2', 'Payment ID');

            $sheet->setCellValue('C2', 'Company Name');

            $sheet->setCellValue('D2', 'Booking Ref No.');

            $sheet->setCellValue('E2', 'Transaction Type');

            $sheet->setCellValue('F2', 'Mode');

            $sheet->setCellValue('G2', 'Amount');

            $sheet->setCellValue('H2', 'Convenience Fee Amount');

            $sheet->setCellValue('I2', 'Response');

            $sheet->setCellValue('J2', 'Event Time');

            $rows = 3;

            foreach ($online_trans_logs as $key => $val) {

                $bookingrefno = '';

                if ($val['service'] == "flight") {

                    $bookingids =   explode(",", $val['booking_ref_no']);

                    if (!is_array($bookingids)) {

                        $bookingids = array($bookingids);

                    }

                    foreach ($bookingids as $bookingid) {

                        $bookingrefno .= $val['booking_prefix'] . $bookingid . " ";

                    }

                } else {

                    $bookingrefno = (!empty($val['booking_prefix'])) ? $val['booking_prefix'] . $val['booking_ref_no'] : '' . $val['booking_ref_no'];

                }

                $sheet->setCellValue('A' . $rows, $key + 1);

                $sheet->setCellValue('B' . $rows, $val['order_id']);

                $sheet->setCellValue('C' . $rows, $val['company_name']);

                $sheet->setCellValue('D' . $rows, $bookingrefno);

                $sheet->setCellValue('E' . $rows, ucwords(str_replace("_", " ", $val['service'])));

                $sheet->setCellValue('F' . $rows, $val['payment_mode']);

                $sheet->setCellValue('G' . $rows, $val['amount']);

                $sheet->setCellValue('H' . $rows, $val['convenience_fee']);

                $sheet->setCellValue('I' . $rows, $val['payment_status']);

                $sheet->setCellValue('J' . $rows, date_created_format($val['created']));

                $rows++;

            }

            ob_start();

            ob_end_clean();

            $writer = new Xlsx($spreadsheet);

            header("Content-Type: application/vnd.ms-excel");

            header('Content-Disposition: attachment; filename="' . basename($fileName) . '"');

            header('Expires: 0');

            $writer->save("php://output");

            exit;

        } else {

            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");

            return $this->response->setJSON($message);

        }

    }



    public function transaction_details()

    {

        $uri = $this->request->getUri();   
        $id =  dev_decode($uri->getSegment(3));
        $OnlineTransactionModel = new OnlineTransactionModel();

        $details = $OnlineTransactionModel->transaction_details($id,$this->web_partner_id);



        $data = [

            'title' => $this->title,

            'id' => $id,

            'details' => $details,

        ];



        $blog_details = view('Modules\OnlineTransaction\Views\transaction-details', $data);

        $data = array("StatusCode" => 0, "Message" => $blog_details, 'class' => 'success_popup');

        return $this->response->setJSON($data);

    }





    public function transaction_status_change()

    {

        if (permission_access("OnlineTransaction", "OnlineTransaction_status")) {

            $validate = new Validation();

            $rules = $this->validate($validate->transaction_status);

            if (!$rules) {

                $errors = $this->validator->getErrors();

                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));

                return $this->response->setJSON($data_validation);

            } else {

                $data = $this->request->getPost();

                $transaction_id = dev_decode($data['payment_id']);

                $OnlineTransactionModel = new OnlineTransactionModel();

                if ($data['payment_status'] == "Successful") {

                    $details = $OnlineTransactionModel->transaction_details($transaction_id,$this->web_partner_id);

                    if ($details['payment_source'] == 'Wl_b2c') {

                        $web_partner_id =  $details['web_partner_id'];

                        $topupAmount  =  round_value(($details['amount'] - $details['convenience_fee']));

                        $CustomerAccountLogData['web_partner_id'] = $web_partner_id;

                        $CustomerAccountLogData['user_id'] = $this->user_id;

                        $CustomerAccountLogData['created'] = create_date();

                        $CustomerAccountLogData['transaction_type'] = "credit";

                        $CustomerAccountLogData['action_type'] = "recharge";

                        $CustomerAccountLogData['payment_mode'] = $details['payment_mode'];

                        $CustomerAccountLogData['transaction_id'] = $details['transaction_id'];

                        $CustomerAccountLogData['role'] = 'super_admin';

                        $CustomerAccountLogData['remark'] =  $data['web_partner_remark'];

                        $CustomerAccountLogData['acc_ref_number'] = mt_rand(100000, 999999);



                        $tableName = 'customer_account_log';

                        $selectColumn = 'balance';

                        $whereCondition = ['web_partner_id' => $web_partner_id];

                        $orderBy = ['id', 'DESC'];

                        $limit = 1;



                        $available_balance = $OnlineTransactionModel->GetAvailableBalance($tableName, $selectColumn, $whereCondition, $orderBy, $limit,$this->web_partner_id);

                        if (!$available_balance) {

                            $available_balance['balance'] = 0;

                        }

                        $CustomerAccountLogData['balance'] = round_value(($available_balance['balance'] + $topupAmount));

                        $CustomerAccountLogData['credit'] = $topupAmount;

                        $added_data_id = $OnlineTransactionModel->InsertData('customer_account_log', $CustomerAccountLogData);

                        $updateData['acc_ref_number'] = reference_number($added_data_id);

                        $OnlineTransactionModel->updateData("customer_account_log", $updateData, array("id" => $added_data_id));

                    } else {

                        $web_partner_id =  $details['web_partner_id'];

                        $topupAmount  =  round_value(($details['amount'] - $details['convenience_fee']));

                        $AgentAccountLogData['web_partner_id'] = $web_partner_id;

                        $AgentAccountLogData['user_id'] = $this->user_id;

                        $AgentAccountLogData['created'] = create_date();

                        $AgentAccountLogData['transaction_type'] = "credit";

                        $AgentAccountLogData['action_type'] = "recharge";

                        $AgentAccountLogData['payment_mode'] = $details['payment_mode'];

                        $AgentAccountLogData['transaction_id'] = $details['transaction_id'];

                        $AgentAccountLogData['role'] = 'web_partner';

                        $AgentAccountLogData['remark'] =  $data['web_partner_remark'];

                        $AgentAccountLogData['acc_ref_number'] = mt_rand(100000, 999999);



                        $tableName = 'agent_account_log';

                        $selectColumn = 'balance';

                        $whereCondition = ['web_partner_id' => $web_partner_id];

                        $orderBy = ['id', 'DESC'];

                        $limit = 1;



                        $available_balance = $OnlineTransactionModel->GetAvailableBalance($tableName, $selectColumn, $whereCondition, $orderBy, $limit,$this->web_partner_id);



                        if (!$available_balance) {

                            $available_balance['balance'] = 0;

                        }

                        $AgentAccountLogData['balance'] = round_value(($available_balance['balance'] + $topupAmount));



                        $AgentAccountLogData['credit'] = $topupAmount;



                        $added_data_id = $OnlineTransactionModel->InsertData('agent_account_log', $AgentAccountLogData);

                        $updateData['acc_ref_number'] = reference_number($added_data_id);

                        $OnlineTransactionModel->updateData("agent_account_log", $updateData, array("id" => $added_data_id));

                    }

                }

                $store_data['payment_status'] = $data['payment_status'];

                $store_data['web_partner_remark'] = $data['web_partner_remark'];

                $store_data['web_partner_user_id'] = $this->user_id;



                $update = $OnlineTransactionModel->where("id", $transaction_id)->set($store_data)->update();

                if ($update) {

                    $message = array("StatusCode" => 0, "Message" => "Transaction status  successfully changed", "Class" => "success_popup", "Reload" => "true");

                } else {

                    $message = array("StatusCode" => 2, "Message" => "Transaction status not changed successfully", "Class" => "error_popup", "Reload" => "true");

                }

                $this->session->setFlashdata('Message', $message);

                return $this->response->setJSON($message);

            }

        } else {

            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");

            return $this->response->setJSON($message);

        }

    }

    public function transaction_status_remark_change()

    {

        if (permission_access("OnlineTransaction", "OnlineTransaction_remark")) {

            $validate = new Validation();

            $rules = $this->validate($validate->transaction_update_remark_status);

            if (!$rules) {

                $errors = $this->validator->getErrors();

                $data_validation = array("StatusCode" => 1, "ErrorMessage" => array_filter($errors));

                return $this->response->setJSON($data_validation);

            } else {

                $data = $this->request->getPost();

                // pr($data);exit;

                $transaction_id = dev_decode($data['payment_id']);

                $OnlineTransactionModel = new OnlineTransactionModel();

                $store_data['web_partner_remark'] = $data['web_partner_remark'];

                $store_data['web_partner_user_id'] = $this->user_id;

                $store_data['web_partner_id'] = $this->web_partner_id;

                $update = $OnlineTransactionModel->where("id", $transaction_id)->set($store_data)->update();



                if ($update) {

                    $message = array("StatusCode" => 0, "Message" => "Transaction status remark  successfully changed", "Class" => "success_popup", "Reload" => "true");

                } else {

                    $message = array("StatusCode" => 2, "Message" => "Transaction status remark not changed successfully", "Class" => "error_popup", "Reload" => "true");

                }

                $this->session->setFlashdata('Message', $message);

                return $this->response->setJSON($message);

            }

        } else {

            $message = array("StatusCode" => 2, "Message" => "Permission Denied", "Class" => "error_popup");

            return $this->response->setJSON($message);

        }

    }

}

