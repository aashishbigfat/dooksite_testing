<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DepartureDate;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Config;
use App\CCAvenuePayment;
use DB;
use DateTime;
use Mail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cookie;
use App\Mail\BookingConfirmationMail;

class BookingController extends Controller
{

   public function submitBooking(Request $request)
    { 
        if ($request->session()->has('FRONT_USER_LOGIN')) {
            // User is logged in
            $userId = $request->session()->get('FRONT_USER_ID');
            $customer = DB::table('agent_customer')->where('id', $userId)->first();

            // Use the logged-in user's data
            $customerData = [
                'first_name' => $customer->first_name,
                'last_name' => $customer->last_name,
                'email_id' => $customer->email_id,
                'phone' => $customer->phone,
                'password' => $customer->password, // Assuming password is already hashed
                "status" => 1,
                "email_verify" => 1,
                "rand_id" => $customer->rand_id,
                "created_at" => now(),
                "updated_at" => now()
            ];
        }else {
            $customerData = [
                'first_name' => '',
                'last_name' => '',
                'email_id' => '',
                'phone' => '',
                // 'password' => '', // Assuming password is already hashed
                "status" => 1,
                "email_verify" => 1,
                "rand_id" => '',
                "created_at" => now(),
                "updated_at" => now()
            ];
        }
        // dd($customerData);
        $username = env('AGENT_CONNECT_USERNAME');
        $password = env('AGENT_CONNECT_PASSWORD');

        $headerArray = [
            'Username: ' . $username,
            'Password: ' . $password,
        ];
        $baseUrl = 'https://agent.dookinternational.com/api';
        $url = $baseUrl . '/departure/group-departure';
        $method = 'GET';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headerArray);

        $result = curl_exec($ch);
        $data1 = json_decode($result);
        $departures = $data1->Result; 
        curl_close($ch);

        $validatedData = $request->validate([
            'pax' => 'required|numeric',
            'departure_date' => 'required',
        ]);

        $destinationTitle = $request->input('destination_title');
        $destinationImage = $request->input('destination_image');
        $numberOfNights = $request->input('no_of_nights');
        $numberOfDays = $request->input('no_of_days');
        $numberOfTravelers = $request->input('pax');
        $priceid = $request->input('priceId');
        $departure_dateid = $request->input('departure_dateid');
        $departure_date = $request->input('departure_date');

        $matchedDeparture = null;
        $matchedPrice = null;
        $singlePrice = null;

        foreach ($departures as $departure) {
            foreach ($departure->DepartureDateWithPrice as $datePrice) {
                if ($datePrice->DepartureId == $departure_dateid) {
                    $matchedDeparture = $datePrice;
                    break 2; 
                }
            }
        }

        if ($matchedDeparture) {
            foreach ($matchedDeparture->FareInfo as $priceDetail) {
                if ($priceDetail->Id == $priceid) {
                    $matchedPrice = $priceDetail;
                }
                if ($priceDetail->RoomShare == 'Single') {
                    $singlePrice = $priceDetail->Price->OfferedPrice;
                }
            }
        }

        $price = $matchedPrice ? $matchedPrice->Price->OfferedPrice : 0;
        $singleprice = $singlePrice ?? 0;

        $bookingData = [
            'destination_title' => $destinationTitle,
            'destination_image' => $destinationImage,
            'no_of_nights' => $numberOfNights,
            'no_of_days' => $numberOfDays,
            'pax' => $numberOfTravelers,
            'price' => $price,
            'singleprice' => $singleprice,
            'priceId' => $priceid,
            'departure_date' => $departure_date,
            'departure_dateid' => $departure_dateid,
            'singlePriceIdInput' => $request->input('singlePriceIdInput')
        ];

        return view('frontend.book_now', compact('bookingData','customerData'));
    }

    public function bookNowPage(Request $request)
    {
        $bookingData = $request->session()->get('bookingData');

        return view('book_now', ['bookingData' => $bookingData]);
    }

    public function place_Order(Request $request)
    {

        $rand_id = rand(111111111, 999999999);
    
        if ($request->session()->has('FRONT_USER_LOGIN')) {
            $customerId = $request->session()->get('FRONT_USER_ID');
            $customerData = DB::table('agent_customer')->where('id', $customerId)->first();
        } else {
            $validator = Validator::make($request->all(), [
                'email_id' => 'required|email|unique:agent_customer,email_id'
            ]);
    
            if ($validator->fails()) {
                return response()->json(['status' => 'error', 'msg' => 'The email has already been taken']);
            }
    
            $customerData = [
                'first_name' => $request->input('first_name'),
                'last_name' => $request->input('last_name'),
                'email_id' => $request->input('email_id'),
                'phone' => $request->input('phone'),
                'password' => Hash::make($rand_id),
                'status' => 1,
                'email_verify' => 1,
                'rand_id' => $rand_id,
                'created_at' => now(),
                'updated_at' => now()
            ];
    
            $customerId = DB::table('agent_customer')->insertGetId($customerData);
    
            $request->session()->put('FRONT_USER_LOGIN', true);
            $request->session()->put('FRONT_USER_ID', $customerId);
            $request->session()->put('FRONT_USER_NAME', $request->input('first_name'));
    
            $data = ['name' => $request->input('first_name'), 'password' => $rand_id];
            Mail::send('front/password_send', $data, function ($message) use ($request) {
                $message->to($request->input('email_id'));
                $message->subject('New Password');
            });
        }
    
        $bookingData = $request->session()->get('bookingData');
        $pax = $request->input('pax');
        $pricingDetail = $this->preparePricingDetail($request, $pax, $customerId);
    
        $requestData = $this->prepareRequestData($request, $pricingDetail);
  
    
        // if (!$responseData) {
        //     return response()->json(['status' => 'error', 'msg' => 'External API request failed.']);
        // }
    
        $order = $this->createOrder($request, $customerId);

        return view('payment', [
            'orderid' => $order['order_id'],
            'customerId' => $customerId,
            'bookingData' => $bookingData,
            'order' => $order,
            'customerData' => $customerData // Add this line to pass customer data to the view
        ]);
        // return view('payment', compact('orderid', 'customerId', 'bookingData', 'order','customerData'));
    }

    private function preparePricingDetail(Request $request, $pax, $customerId)
    {
        $pricingDetail = [];
        for ($i = 0; $i < $pax; $i++) {
            $travelerData = [
                'customer_id' => $customerId,
                'title' => $request->input('title')[$i],
                'first_name' => $request->input('name')[$i],
                'last_name' => $request->input('last_name1')[$i],
                'gender' => $request->input('gender')[$i],
                'pan' => $request->input('pan')[$i],
                'passport_no' => $request->input('passport_no')[$i],
                'issue_date' => $request->input('issue_date')[$i],
                'expiry_date' => $request->input('expiry_date')[$i],
            ];

            DB::table('travel')->insert($travelerData);

            $useSinglePriceId = ($i == 0 && $pax % 2 == 1);
            $priceId = $useSinglePriceId ? $request->input('singlepriceid') : $request->input('priceId');

            $passengerData = [
                'Title' => $travelerData['title'],
                'FirstName' => $travelerData['first_name'],
                'LastName' => $travelerData['last_name'],
                'Gender' => $travelerData['gender'],
                'PanCard' => $travelerData['pan'],
                'PassportNumber' => $travelerData['passport_no'],
                'PassportIssueDate' => $travelerData['issue_date'],
                'PassportExpireDate' => $travelerData['expiry_date']
            ];

            $found = false;
            foreach ($pricingDetail as &$detail) {
                if ($detail['PriceId'] === $priceId) {
                    $detail['Passenger'][] = $passengerData;
                    $found = true;
                    break;
                }
            }
            if (!$found) {
                $pricingDetail[] = [
                    'PriceId' => $priceId,
                    'Passenger' => [$passengerData]
                ];
            }
        }

        return $pricingDetail;
    }

    private function prepareRequestData(Request $request, $pricingDetail)
    {
        return [
            'UserIp' => $request->ip(),
            'DepartureId' => $request->input('departure_dateid'),
            'BookingSource' => 'Customer',
            'BookingType' => 'Payment',
            'PaymentType' => 'Full',
            'PricingDetail' => $pricingDetail
        ];
    }

    // private function callExternalApi($requestData)
    // {
    //     // dd($requestData);
    //     $username = env('API_USERNAME');
    //     $password = env('API_PASSWORD');
    //     $baseUrl = 'https://agent.dookinternational.com/api';
    //     $searchUrl = $baseUrl . '/departure/idbook';

    //     $ch = curl_init();
    //     curl_setopt($ch, CURLOPT_URL, $searchUrl);
    //     curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //     curl_setopt($ch, CURLOPT_HTTPHEADER, [
    //         'Username: ' . $username,
    //         'Password: ' . $password,
    //     ]);
    //     curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($requestData));

    //     $response = curl_exec($ch);
    //     if ($response === false) {
    //         curl_close($ch);
    //         return false;
    //     }
    //     // dd($response);
    //     $responseData = json_decode($response);
    //     curl_close($ch);

    //     return $responseData ? $responseData->Result : false;
    // }

    private function createOrder(Request $request, $customerId)
    {
        $orderid = uniqid();
        $order = [
            'order_id' => $orderid,
            'customer_id' => $customerId,
            'destination_title' => $request->input('destination_title'),
            'departure_date' => $request->input('departure_date'),
            'priceId' => $request->input('singlePriceId'),
            'departure_dateid' => $request->input('departure_dateid'),
            'price' => $request->input('price'),
            'booking_status' => '',
            'invoice_amount' => '',
            'invoice_number' => '',
            'booking_id' => '',
            'confirmation_no' => '',
        ];

        DB::table('booking')->insert($order);

        return $order;
    }
    public function handleResponse(Request $request)
    {
        DB::table('payment_logs')->insert([
            'response_data' => json_encode($request->all()),
            'created_at' => now(),
            // Add other fields as needed
        ]);   
        
        return redirect()->route('thankyou');
    }

    public function purchaseSubscription(Request $request)
    {
      
         // Make sure all necessary session data is set
        $sessionUserId = $request->session()->get('FRONT_USER_ID');
        $sessionUserName = $request->session()->get('FRONT_USER_NAME');
        // dd($sessionUserId);
        $input = $request->all();
        // $input['amount'] = $input['amount'];
        // $input['order_id'] = "123XSDDD456";
        // $input['currency'] = "INR";
        $input['redirect_url'] = route('frontend.cc-response');
        $input['cancel_url'] = route('frontend.cc-response');
        // $input['language'] = "EN";
        // $input['merchant_id'] = "177375";

        $merchant_data = "";

        $working_key = '2D41D6EA81C0698782456E7FA46F738F'; 
        $access_code = 'AVPX79FG16BA18XPAB'; 

        $input['merchant_param1'] = "some-custom-inputs"; 
        $input['merchant_param2'] = "some-custom-inputs";
        $input['merchant_param3'] = "some-custom-inputs"; 
        $input['merchant_param4'] = "some-custom-inputs";
        $input['merchant_param5'] = "some-custom-inputs"; 
        foreach ($input as $key => $value) {
            $merchant_data .= $key . '=' . $value . '&';
        }

        $encrypted_data = $this->encryptCC($merchant_data, $working_key);
        $url = 'https://secure.ccavenue.com/transaction/transaction.do?command=initiateTransaction&encRequest=' . $encrypted_data . '&access_code=' . $access_code;

        $order = $this->ccResponse($request);
        return redirect($url);
    }
    public function ccResponse(Request $request)
    {
        $sessionUserId = $request->session()->get('FRONT_USER_ID');
        $sessionUserName = $request->session()->get('FRONT_USER_NAME');

        // if (!$sessionUserId) {
        //     dd('Session user ID is not set.');
        // }

        try {
            $workingKey = '77BA2B53665AC5B22FCE771055C3CF44'; 
            $encResponse = $_POST["encResp"];

          
            $rcvdString = $this->decryptCC($encResponse, $workingKey);

           
            $decryptValues = explode('&', $rcvdString);
            $dataSize = sizeof($decryptValues);

            $responseData = [];

            foreach ($decryptValues as $value) {
                list($key, $value) = explode('=', $value);
                $responseData[$key] = $value;
            }

            $orderStatus = $responseData['order_status'];
            $orderId = $responseData['order_id'];
            $email = $responseData['billing_email'];
            $billing_name = $responseData['billing_name'];

           
            if($order_status==="Success")
            {
                
               DB::table('booking')->where('order_id', $orderId)->update(['payment' => 1]);
                $order = DB::table('booking')->where('order_id', $orderId)->first();
             
                $orderid = DB::table('agent_customer')->where('id', $order->customer_id)->first();
 
                $request->session()->put('FRONT_USER_LOGIN', true);
                $request->session()->put('FRONT_USER_ID', $sessionUserId);
                $request->session()->put('FRONT_USER_NAME',$responseData['billing_name']);  
                 
            
                $mail_sent = Mail::send('emails.booking_confirmation', ['orderId' => $orderId, 'billing_name' => $responseData['billing_name']], function($mail) use ($email, $orderId,$billing_name) {
                     
                    $mail->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
                    $mail->to($email);
                    $mail->bcc('anubhavi.sharma@dooktravels.com');
                    $mail->subject('Booking Confirmation - Order ID: ' . $orderId);
                });

                return redirect()->route('thankyou');
            }
            else if($order_status==="Aborted")
            {
                  return view('frontend.common.failure');
            
            }
            else if($order_status==="Failure")
            {
                DB::table('booking')->where('order_id', $orderId)->update(['payment' => 1]);
                $order = DB::table('booking')->where('order_id', $orderId)->first();
             
                $orderid = DB::table('agent_customer')->where('id', $order->customer_id)->first();
 
                $request->session()->put('FRONT_USER_LOGIN', true);
                $request->session()->put('FRONT_USER_ID', $sessionUserId);
                $request->session()->put('FRONT_USER_NAME',$responseData['billing_name']);  
                 
            
                $mail_sent = Mail::send('emails.booking_confirmation', ['orderId' => $orderId, 'billing_name' => $responseData['billing_name']], function($mail) use ($email, $orderId,$billing_name) {
                     
                    $mail->from(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
                    $mail->to($email);
                    $mail->bcc('anubhavi.sharma@dooktravels.com');
                    $mail->subject('Booking Confirmation - Order ID: ' . $orderId);
                });

               return view('frontend.common.failure');
            }
            else
            {
                return view('frontend.common.failure');
            
            }

            
            return response()->json(['status' => 'success', 'message' => 'Payment response processed successfully']);
            } catch (\Exception $e) {
              

               return view('frontend.common.failure');
            }
    }
public function encryptCC($plainText, $key)
    {
        $key = $this->hextobin(md5($key));
        $initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
        $openMode = openssl_encrypt($plainText, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $initVector);
        $encryptedText = bin2hex($openMode);
        return $encryptedText;
    }

    public function decryptCC($encryptedText, $key)
    {
        $key = $this->hextobin(md5($key));
        $initVector = pack("C*", 0x00, 0x01, 0x02, 0x03, 0x04, 0x05, 0x06, 0x07, 0x08, 0x09, 0x0a, 0x0b, 0x0c, 0x0d, 0x0e, 0x0f);
        $encryptedText = $this->hextobin($encryptedText);
        $decryptedText = openssl_decrypt($encryptedText, 'AES-128-CBC', $key, OPENSSL_RAW_DATA, $initVector);
        return $decryptedText;
    }

    public function pkcs5_padCC($plainText, $blockSize)
    {
        $pad = $blockSize - (strlen($plainText) % $blockSize);
        return $plainText . str_repeat(chr($pad), $pad);
    }

    public function hextobin($hexString)
    {
        $length = strlen($hexString);
        $binString = "";
        $count = 0;
        while ($count < $length) {
            $subString = substr($hexString, $count, 2);
            $packedString = pack("H*", $subString);
            if ($count == 0) {
                $binString = $packedString;
            } else {
                $binString .= $packedString;
            }

            $count += 2;
        }
        return $binString;
    }
     public function getUserTempId(){
        if(!session()->has('USER_TEMP_ID')){
            $rand=rand(111111111,999999999);
            session()->put('USER_TEMP_ID',$rand);
            return $rand;
        }else{
            return session()->get('USER_TEMP_ID');
        }
    }

     public function login_process(Request $request)
    {
      
        $result=DB::table('agent_customer')  
            ->where(['email_id'=>$request->str_login_email])
            ->get(); 

        if(isset($result[0])){

            $db_pwd= Hash::check($request->str_login_password, $result[0]->password);
            $status=$result[0]->status;
            $email_verify=$result[0]->email_verify;

            if($email_verify==0){
                return response()->json(['status'=>"error",'msg'=>'Please verify your email id']); 
            }
            if($status==0){
                return response()->json(['status'=>"error",'msg'=>'Your account has been deactivated']); 
            }

            if(Hash::check($request->str_login_password, $result[0]->password)){

                if($request->rememberme===null){
                    setcookie('login_email',$request->str_login_email,100);
                    setcookie('login_pwd',$request->str_login_password,100);
                }else{
                   setcookie('login_email',$request->str_login_email,time()+60*60*24*100);
                   setcookie('login_pwd',$request->str_login_password,time()+60*60*24*100);
                }

                $request->session()->put('FRONT_USER_LOGIN',true);
                $request->session()->put('FRONT_USER_ID',$result[0]->id);
                $request->session()->put('FRONT_USER_NAME',$result[0]->first_name);
                $status="success";
                $msg="";

                $getUserTempId= $this->getUserTempId();
                DB::table('booking')  
                    ->where(['customer_id'=>$getUserTempId,'data_type'=>'Not-Reg'])
                    ->update(['customer_id'=>$result[0]->id,'data_type'=>'Reg']);
                
            }else{
                $status="error";
                $msg="Please enter valid password";
            }
        }else{
            $status="error";
            $msg="Please enter valid email id";
        }
       return response()->json(['status'=>$status,'msg'=>$msg]); 
       //$request->password
    }
    public function forgot_password(Request $request)
    {
        
        $result=DB::table('agent_customer')  
            ->where(['email_id'=>$request->str_forgot_email])
            ->get(); 

        $rand_id=rand(111111111,999999999);
        if(isset($result[0])){

            DB::table('agent_customer')  
                ->where(['email_id'=>$request->str_forgot_email])
                ->update(['is_forgot_password'=>1,'rand_id'=>$rand_id]);

            $data=['name'=>$result[0]->first_name,'rand_id'=>$rand_id];
            $user['to']=$request->str_forgot_email;
            Mail::send('frontend.forgot_email',$data,function($mail) use ($user){
                $mail->to($user['to']);
                $mail->subject("Forgot Password");
            });
            return response()->json(['status'=>'success','msg'=>'Please check your email for password']); 
        }else{
            return response()->json(['status'=>'error','msg'=>'Email id not registered']); 
        }
    }


    public function forgot_password_change(Request $request,$id)
    {
        $result=DB::table('agent_customer')  
            ->where(['rand_id'=>$id])
            ->where(['is_forgot_password'=>1])
            ->get(); 

        if(isset($result[0])){
            $request->session()->put('FORGOT_PASSWORD_USER_ID',$result[0]->id);
        
            return view('frontend.forgot_password_change');
        }else{
            return redirect('/');
        }
    }

    public function forgot_password_change_process(Request $request)
    {
        DB::table('agent_customer')  
            ->where(['id'=>$request->session()->get('FORGOT_PASSWORD_USER_ID')])
            ->update(
                [
                    'is_forgot_password'=>0,
                    'password' => Hash::make($request->password),
                    'rand_id'=>''
                ]
            ); 
        return response()->json(['status'=>'success','msg'=>'Password changed']);     
    }
    public function registration(Request $request)
    {
        // dd($request);
        if($request->session()->has('FRONT_USER_LOGIN')!=null){
            return redirect('/');
        }
        
        $result=[];
        return view('frontend.registration',$result);
    }
    
    public function registration_process(Request $request)
    {
            $valid=Validator::make($request->all(),[
            "name"=>'required',
            "email"=>'required|email|unique:agent_customer,email_id',
            "password"=>'required',
            "mobile"=>'required|numeric|digits:10',
       ]);

       if(!$valid->passes()){
            return response()->json(['status'=>'error','error'=>$valid->errors()->toArray()]);
          
       }else{
            $rand_id=rand(111111111,999999999);
            $arr=[
                "first_name"=>$request->name,
                "email_id"=>$request->email,
                "password"=>Hash::make($request->password),
                "phone"=>$request->mobile,
                "status"=>1,
                "email_verify"=>1,
                "rand_id"=>$rand_id,
                "created_at"=>date('Y-m-d h:i:s'),
                "updated_at"=>date('Y-m-d h:i:s')
            ];
            $query=DB::table('agent_customer')->insert($arr);
             if($query){

                 $data=['first_name'=>$request->name,'rand_id'=>$rand_id];

                 $user['to']=$request->email;
                 Mail::send('mail-templates.email_verification',$data,function($mail) use ($user){
                    $mail->to($user['to']);
                     $mail->subject('Email Id Verification');
                 });

                 return response()->json(['status'=>'success','msg'=>"Registration successfully. Please check your email id for verification"]);
               // return response()->json(['status'=>'success','msg'=>"Registration successfully. You can login in now."]);

             }

       }
    }

    public function email_verification(Request $request,$id)
    {
        $result=DB::table('agent_customer')  
            ->where(['rand_id'=>$id])
            ->where(['email_verify'=>0])
            ->get(); 

        if(isset($result[0])){
            DB::table('agent_customer')  
            ->where(['id'=>$result[0]->id])
            ->update(['email_verify'=>1,'rand_id'=>'']);
        return view('frontend.verification');
        }else{
            return redirect('/');
        }
    }
    public function order(Request $request)
    {
       $result['orders'] = DB::table('booking')
        // ->select('booking.*', 'orders_status.orders_status')
        ->where('booking.customer_id', $request->session()->get('FRONT_USER_ID'))
        ->get();
        // dd($request);
        return view('frontend.order',$result);
    }
    public function order_detail(Request $request, $id)
    {
        $keyword = $request->keyword;
         $username = env('API_USERNAME');
        $password = env('API_PASSWORD');

        $headerArray = [
            'Username: ' . $username,
            'Password: ' . $password,
        ];
        $baseUrl = 'https://agent.dookinternational.com/api';
        $url = $baseUrl . '/departure/booking-detail?bookingid=' . $id;
        $method = 'GET';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headerArray);

        if (isset($bodyArray)) {
            curl_setopt($ch, CURLOPT_POSTFIELDS, $bodyArray);
        }

        $result = curl_exec($ch);
        $data1 = json_decode($result, true); // Convert JSON to associative array
        curl_close($ch);

        if (isset($data1['Result'])) {
            $departures = $data1['Result'];
        } else {
            $departures = [];
        }

        // Ensure $departures is an array
        if (!is_array($departures)) {
            $departures = (array) $departures;
        }

        // Parse the date strings into DateTime objects
        $travelDate = DateTime::createFromFormat('d-M-Y', $departures['TravelDate']);
        $returnDate = DateTime::createFromFormat('d-M-Y', $departures['ReturnDate']);

        // Check if the dates were parsed successfully
        if ($travelDate && $returnDate) {
            // Calculate the difference between the two dates
            $interval = $travelDate->diff($returnDate);
            // Extract the duration in days
            $duration = $interval->days;
            // Add the duration to the departure array
            $departures['Duration'] = $duration;
        } else {
            // Handle error if dates are not valid
            $departures['Duration'] = null;
        }

      

        return view('frontend.order_detail',compact('departures', $departures['Duration']));
    }

    
}
