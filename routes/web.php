<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/', function () {
    return view('welcome');
});

/*
Route::get('/qr', function(Request $request) {
        
        $api = 'kMnq2I4gjnoD4161YO1ghz6hallRM388';

        $data = \DB::table('t_devices')
            ->leftjoin('t_servers', 't_devices.id_server', '=', 't_servers.id')
            ->where('dev_api', $api)
            ->first();

        $server = $data->server;
        $account = $data->account;
        $token = $data->token;

        $now    = strtotime(date("Y-m-d h:i:s"));
        $time   = strtotime(date($data->dev_expired))-$now;

        if( $data->id_server <= null ){
            return 'server not active';
        }elseif( $time <= 0 ){
            return 'expired';
        }else{
            $url = "https://$server.chat-api.com/instance$account/status?token=$token";
            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_URL, $url); 
            curl_setopt($ch, CURLOPT_HEADER, 0);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $response = curl_exec($ch);
            curl_close($ch);

            $hasil = json_decode($response);

            $status = $hasil->accountStatus;

            if($status=='got qr code'){
                $qrcode = $hasil->qrCode;

                $file = str_replace('data:image/png;base64,', '', $qrcode);
                $img = str_replace(' ', '+', $file);
                $datanya = base64_decode($img);
                $random = md5(uniqid(rand(), true));
                // $filename = date('ymdhis') . $random . ".png";
                $filename = md5($data->dev_id) . ".png";

                // UPLOAD FILE ON DESTINATION PATH1
                $destinationPath1 = 'public/img/qrcode/';
                $file1 = $destinationPath1 . $filename;
                $success = file_put_contents($file1, $datanya);

                
                // open file a image resource
                $img = Image::make($file1);

                // THEN RESIZE IT AND PLACE ON 3 DESTINATION
                $returnData = $file1;
                $image1  = Image::make(file_get_contents($returnData));
                $img->crop(380, 380, 40, 40)->save($file1);

                return 'data:image/png;base64,'.base64_encode(file_get_contents($file1));

            }else{
                return $status;
            }

        }

});
*/

/*

Route::get('/auth/{apikey}', function($apikey, Request $request) {
    
    $phone = '6287825642542';
    $text = 'Your Message';
    $apikey = $apikey; // kMnq2I4gjnoD4161YO1ghz6hallRM388

    $url = 'https://api.wanotif.id/v1/auth_status';

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($curl, CURLOPT_HTTPHEADER, [
        "Accept: application/json",
        "Authorization: $apikey",
        "Phone: 6287825642542",
        "Message: Mantap banget"
    ]);
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);

    if ($err) {
        echo "cURL Error #:" . $err;
       } else {
      echo $response;
    }
    
});

*/



