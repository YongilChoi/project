<?php

namespace WP_SMS\Gateway;

class directsend extends \WP_SMS\Gateway
{
    public $wsdl_link = "https://directsend.co.kr/index.php/api_v2/sms_change_word";
    public $tariff = "https://directsend.co.kr";
    public $unitrial = true;
    public $unit;
    public $flash = "disable";
    public $isflash = false;
    public $callback_url;

    public function __construct()
    {
        parent::__construct();
        $this->validateNumber = "XXXXXXXX,YYYYYYYY";
        $this->has_key        = true;
        $this->bulk_send      = true;
        $this->help           = 'Please enter your API key and leave the API username & API password empty.';
    }

    public function SendSMS()
    {

        /**
         * Modify sender number
         *
         * @param string $this ->from sender number.
         * @since 3.4
         *
         */
        $this->from = apply_filters('sms_to_from', $this->from);
        /**
         * Modify Receiver number
         *
         * @param array $this ->to receiver number
         * @since 3.4
         *
         */
        $this->to = apply_filters('sms_to_to', $this->to);
        /**
         * Modify text message
         *
         * @param string $this ->msg text message.
         * @since 3.4
         *
         */
        $has_key = $this->has_key;
        // Get the credit.
        $credit = $this->GetCredit();  //Credit 체크 하나 ? 이건 wpsms 의 크래듯 인가? 

        $no_of_characters = $this->CountNumberOfCharacters();

        if ($no_of_characters > 480) {
            return new \WP_Error('account-credit', __('You have exceeded the max limit of 480 characters', 'sms-to'));
        }

        // Check gateway credit
        if (is_wp_error($credit)) {
            // Log the result
            $this->log($this->from, $this->msg, $this->to, $credit->get_error_message(), 'error');

            return $credit;
        }

        $this->msg = apply_filters('sms_to_msg', $this->msg);

        $bodyContent = array(
            'sender_id' => $this->from,
            'to'        => $this->to,
            'message'   => $this->msg,
        );

        if ((isset($this->options['gateway_smsto_callback_url']))) {
            $callback_url                = apply_filters('sms_to_callback', $this->options['gateway_smsto_callback_url']);
            $bodyContent['callback_url'] = 'https://' . $callback_url . '/wp-json/sms-to/get_post';
        }

        if (empty($has_key)) {
            return [
                'error'  => true,
                'reason' => 'Invalid Credentials',
                'data'   => null,
                'status' => 'FAILED'
            ];
        }

        if ($this->isflash == false) {
            $this->wsdl_link = "https://directsend.co.kr/index.php/api_v2/sms_change_word";
        } else
            if ($this->isflash == true) {
                $this->wsdl_link = "https://directsend.co.kr/index.php/api_v2/sms_change_word";
            }

        $opts = [
            CURLOPT_URL            => $this->wsdl_link,
            CURLOPT_POST           => true, 
            CURLOPT_POSTFIELDS     => $this->postvars, 
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING       => "",
            CURLOPT_MAXREDIRS      => 10,
            CURLOPT_TIMEOUT        => 15,
            CURLOPT_HTTP_VERSION   => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST  => 'POST',
            CURLOPT_HTTPHEADER     => [
                'authorization: Bearer ' . $has_key,
                'content-type: application/json',
            ],
        ];

        if ($bodyContent) {
            $opts[CURLOPT_POSTFIELDS] = json_encode($bodyContent);
        }

        
//---------------------------------------------------
//1. 초기화 yong 

        $curlSession = curl_init();  
//2. 설정         

$title = "최용일 노트북 발송";
$message = '[$NAME]님 알림 문자 입니다. 전화번호 : [$MOBILE] 비고1 : [$NOTE1] 비고2 : [$NOTE2] 비고3 : [$NOTE3] 비고4 : [$NOTE4] 비고5 : [$NOTE5] ';             //필수입력


//$sender = "0263008777";                    //필수입력
//$username = "sgis01";                //필수입력
//$key = "2jY4VeF3LC06JBq";           //필수입력  //n최용일 개인 KEY : 8uEP6VmkHQj3Ou 

$sender = "01097694876"; 
$username = "hurstchoi";                //필수입력
$key = "n8uEP6VmkHQj3Ou";  

//고유 API KEY : n8uEP6VmkHQj3Ou



/* Directsend 는 API 연동시에 id 와 키 값만 있으면 다른 패스워드 없음. */


// 예약발송 정보 추가
$sms_type = 'NORMAL'; // NORMAL - 즉시발송 / ONETIME - 1회예약 / WEEKLY - 매주정기예약 / MONTHLY - 매월정기예약
$start_reserve_time = date('Y-m-d H:i:s'); //  발송하고자 하는 시간(시,분단위까지만 가능) (동일한 예약 시간으로는 200회 이상 API 호출을 할 수 없습니다.)
$end_reserve_time = date('Y-m-d H:i:s'); //  발송이 끝나는 시간 1회 예약일 경우 $start_reserve_time = $end_reserve_time
// WEEKLY | MONTHLY 일 경우에 시작 시간부터 끝나는 시간까지 발송되는 횟수 Ex) type = WEEKLY, start_reserve_time = '2017-05-17 13:00:00', end_reserve_time = '2017-05-24 13:00:00' 이면 remained_count = 2 로 되어야 합니다.
$remained_count = 1;



$message = str_replace(' ', ' ', $message);  //유니코드 공백문자 치환


$postvars = '"title":"'.$title.'"';
$postvars = $postvars.', "message":"'.$message.'"';
$postvars = $postvars.', "sender":"'.$sender.'"';
$postvars = $postvars.', "username":"'.$username.'"';
$postvars = $postvars.', "receiver":'.$receiver.'';

$postvars = $postvars.', "key":"'.$key.'"';
$postvars = '{'.$postvars.'}';      //JSON 데이터

$headers = array("cache-control: no-cache","content-type: application/json; charset=utf-8");

curl_setopt($curlSession,CURLOPT_URL, $url);
curl_setopt($curlSession,CURLOPT_POST, true);
curl_setopt($curlSession,CURLOPT_POSTFIELDS, $postvars);
curl_setopt($curlSession,CURLOPT_RETURNTRANSFER, true);
curl_setopt($curlSession,CURLOPT_CONNECTTIMEOUT ,3);
curl_setopt($curlSession,CURLOPT_TIMEOUT, 60);
curl_setopt($curlSession, CURLOPT_HTTPHEADER, $headers);






       // curl_setopt_array($curlSession, $opts);

//-----------------------------------------------
//3. 실행 
        $response = curl_exec($curlSession);
//---------------------------------------------        
        
        $err      = curl_error($curlSession);

        $response = json_decode($response);
        $err      = json_decode($err);

        if ($err) {
            $response = [
                'error'  => true,
                'reason' => $err,
                'data'   => $bodyContent,
                'status' => 'FAILED'
            ];
            do_action('sms_to_send', $response);
            $this->log($this->from, $this->msg, $this->to, $response);

            return $response;
        }


        if ($response->success == "true") {
            // Log the result
            $this->log($this->from, $this->msg, $this->to, $response, 'PENDING');

            /**
             * Run hook after send sms.
             *
             * @param string $response result output.
             * @since 2.4
             *
             */
            do_action('sms_to_send', $response);

            return $response;
        } else {
            // Log the result
            $this->log($this->from, $this->msg, $this->to, $response->message, 'ERROR');
            return new \WP_Error('send-sms', $response->message);
        }
        curl_close($curlSession);
    }

    public function GetCredit()
    {
        // Check api
        if (!$this->has_key) {
            return new \WP_Error('account-credit', __('API not set', 'sms-to'));
        }

        /**
         * Send request
         */
        $response = wp_remote_get($this->tariff . 'api/balance?api_key=' . $this->has_key);

        /**
         * Make sure the request doesn't have the error
         */
        if (is_wp_error($response)) {
            return new \WP_Error('account-credit', $response->get_error_message());
        }

        $responseBody   = wp_remote_retrieve_body($response);
        $responseObject = json_decode($responseBody);

        /*
         * Response validity
         */
        if (wp_remote_retrieve_response_code($response) == '200') {

            if (isset($responseObject->balance)) {
                return round($responseObject->balance, 2);
            }

            return new \WP_Error('account-credit', $responseObject->message);

        } else {
            $errorResponse = isset($responseObject->message) ? $responseObject->message : $responseObject;
            return new \WP_Error('account-credit', $errorResponse);
        }
    }

    public function CountNumberOfCharacters()
    {
        $numberOfCharacters = strlen($this->msg);
        return $numberOfCharacters;
    }

}
