<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use Image;
use DB;
use App\User;
use Illuminate\Support\Facades\Input;
use Response;
use Illuminate\Support\Facades\Schema;

class ApiController extends Controller
{

    public function authStatus(Request $request){
        
        header("Content-Type:application/json");
        $headers = apache_request_headers();
        $results = array();
        if(isset($headers['Apikey'])){
            $apikey = $headers['Apikey'];

            if($apikey==null){
                // return 'Apikey can not Null.';
                return response()->json(
                    [
                        'wanotif' => [
                            'status' => 'API Key Not Valid.'
                        ]
                    ], 400
                );
            }else{

                $data = DB::table('t_devices')
                ->leftjoin('t_servers', 't_devices.id_server', '=', 't_servers.id')
                ->select('t_devices.*', 't_servers.account', 't_servers.token', 't_servers.server', 't_servers.expired')
                ->where('dev_api', $apikey)
                ->first();

                if($data!=null){
                    if($data->id_server==null){
                        // return 'Server not active / ready.';
                        return response()->json(
                            [
                                'wanotif' => [
                                    'status' => 'Server not active / ready.'
                                ]
                            ], 400
                        );
                    }else{

                        $now    = strtotime(date("Y-m-d h:i:s"));
                        $time   = strtotime(date($data->dev_expired))-$now;

                        if( $time<=0 ){
                            // return 'Expired';
                            return response()->json(
                                [
                                    'wanotif' => [
                                        'status' => 'Account Expired.'
                                    ]
                                ], 400
                            );

                        }else{

                            $url = "https://$data->server.chat-api.com/instance$data->account/status?token=$data->token";
                            $ch = curl_init($url);
                            curl_setopt($ch, CURLOPT_URL, $url); 
                            curl_setopt($ch, CURLOPT_HEADER, 0);
                            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                            $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
                            $response = curl_exec($ch);
                            curl_close($ch);

                            
                            if($response!=null){
                                $hasil = json_decode($response);

                                if($hasil!=null){
                                    if (array_key_exists('accountStatus', $hasil)) {
                                        $status = $hasil->accountStatus;

                                        if($status=='got qr code'){
                                            // 1. get data QR Code
                                            $qrcode = $hasil->qrCode;

                                            $file = str_replace('data:image/png;base64,', '', $qrcode);
                                            $img = str_replace(' ', '+', $file);
                                            $datanya = base64_decode($img);
                                            $random = md5(uniqid(rand(), true));
                                            $filename = md5($data->dev_id) . ".png";

                                            // 2. UPLOAD FILE ON DESTINATION PATH Img/Qrcode
                                            $destinationPath1 = 'img/qrcode/';
                                            $file1 = $destinationPath1 . $filename;
                                            $success = file_put_contents($file1, $datanya);

                                            
                                            // 1. Open file an image resource from Img/Qrcode
                                            $img = Image::make($file1);

                                            // 2 .THEN RESIZE IT
                                            $returnData = $file1;
                                            $image1  = Image::make(file_get_contents($returnData));
                                            $img->crop(380, 380, 40, 40)->save($file1);

                                            // return 'data:image/png;base64,'.base64_encode(file_get_contents($file1));

                                            return response()->json(
                                                [
                                                    'wanotif' => [
                                                        'status' => 'scan',
                                                        'qrcode' => 'data:image/png;base64,'.base64_encode(file_get_contents($file1)),
                                                    ]
                                                ], 200
                                            );

                                        }else{
                                            // return $status;
                                            if($status=='authenticated'){
                                                $status = 'connected';
                                            }
                                            return response()->json(
                                                [
                                                    'wanotif' => [
                                                        'status' => $status
                                                    ]
                                                ], 200
                                            );
                                        }

                                    }else{
                                        // return 'Wrong API Token';
                                        return response()->json(
                                            [
                                                'wanotif' => [
                                                    'status' => 'Wrong API Token'
                                                ]
                                            ], 400
                                        );
                                    }
                                }else{
                                    // return 'Not Connect';
                                    return response()->json(
                                        [
                                            'wanotif' => [
                                                'status' => 'Server Not Connect.'
                                            ]
                                        ], 404
                                    );
                                }
                                
                            }else{
                                // return '404';
                                return response()->json(
                                    [
                                        'wanotif' => [
                                            'status' => 'Server Not Found.'
                                        ]
                                    ], 404
                                );
                            }

                        }
                    }
                }else{
                    // echo 'API Key not valid.';
                    return response()->json(
                        [
                            'wanotif' => [
                                'status' => 'API Key Not Valid.'
                            ]
                        ], 400
                    );
                }
                
            }
        }else{
            // echo 'Failed!';
            return response()->json(
                [
                    'wanotif' => [
                        'status' => 'Unauthenticated.'
                    ]
                ], 401
            );
        }
        
    }

    public function sendMessage(Request $request){
        
        $input = $request->all();

        $apikey = $input['Apikey'];
        $phone = $input['Phone'];
        $message = $input['Message']; // $_SERVER["HTTP_ORIGIN"]
        
        if(isset($input['Apikey'])){
            if($apikey==null){
                // return 'Apikey can not Null.';
                return response()->json(
                    [
                        'wanotif' => [
                            'status' => 'API Key Not Valid.'
                        ]
                    ], 400
                );
            }else{

                $data = DB::table('t_devices')
                ->leftjoin('t_servers', 't_devices.id_server', '=', 't_servers.id')
                ->select('t_devices.*', 't_servers.account', 't_servers.token', 't_servers.server', 't_servers.expired')
                ->where('dev_api', $apikey)
                ->first();

                if($data!=null){
                    if($data->id_server==null){
                        // return 'Server not active / ready.';
                        return response()->json(
                            [
                                'wanotif' => [
                                    'status' => 'Server not active / ready.'
                                ]
                            ], 400
                        );
                    }else{

                        $now    = strtotime(date("Y-m-d h:i:s"));
                        $time   = strtotime(date($data->dev_expired))-$now;

                        if( $time<=0 ){
                            // return 'Expired';
                            return response()->json(
                                [
                                    'wanotif' => [
                                        'status' => 'Account Expired.'
                                    ]
                                ], 400
                            );

                        }else{
                            
                            // URL for request POST /message
                            $url = "https://$data->server.chat-api.com/instance$data->account/message?token=$data->token";

                            $curlHandle = curl_init();
                            curl_setopt($curlHandle, CURLOPT_URL, $url);
                            curl_setopt($curlHandle, CURLOPT_HEADER, 0);
                            curl_setopt($curlHandle, CURLOPT_RETURNTRANSFER, 1);
                            curl_setopt($curlHandle, CURLOPT_SSL_VERIFYHOST, 2);
                            curl_setopt($curlHandle, CURLOPT_SSL_VERIFYPEER, 0);
                            curl_setopt($curlHandle, CURLOPT_TIMEOUT,30);
                            curl_setopt($curlHandle, CURLOPT_POST, 1);
                            curl_setopt($curlHandle, CURLOPT_POSTFIELDS, array(
                                'phone' => $phone,
                                'body' => $message
                            ));
                            $response = json_decode(curl_exec($curlHandle), true);
                            curl_close($curlHandle);
                            
                            $status = $response['sent'];

                            if($response!=null){
                                if($status==true){
                                    // return 'success';
                                    return response()->json(
                                        [
                                            'wanotif' => [
                                                'status' => 'sent'
                                            ]
                                        ], 200
                                    );
                                }else{
                                    // return 'failed';
                                    return response()->json(
                                        [
                                            'wanotif' => [
                                                'status' => 'failed'
                                            ]
                                        ], 400
                                    );
                                }
                            }else{
                                // return '404';
                                return response()->json(
                                    [
                                        'wanotif' => [
                                            'status' => 'Server Not Found.'
                                        ]
                                    ], 404
                                );
                            }



                        }
                    }
                }else{
                    // echo 'API Key not valid.';
                    return response()->json(
                        [
                            'wanotif' => [
                                'status' => 'API Key Not Valid.'
                            ]
                        ], 400
                    );
                }
                
            }
        }else{
            // echo 'Failed!';
            return response()->json(
                [
                    'wanotif' => [
                        'status' => 'Unauthenticated.'
                    ]
                ], 401
            );
        }


          
    }



    public function sendtestMessage(Request $request){
        
        // header("Content-Type:application/json");
        $input = $request->all();

        $apikey = $input['Apikey'];
        $phone = $input['Phone'];
        $message = $input['Message']; // $_SERVER["HTTP_ORIGIN"]
        
        // $headers = apache_request_headers();
        if(isset($input['Apikey'])){
            if($apikey==null){
                // return 'Apikey can not Null.';
                return response()->json(
                    [
                        'wanotif' => [
                            'status' => 'API Key Not Valid.'
                        ]
                    ], 400
                );
            }else{

                $data = DB::table('t_devices')
                ->leftjoin('t_servers', 't_devices.id_server', '=', 't_servers.id')
                ->select('t_devices.*', 't_servers.account', 't_servers.token', 't_servers.server', 't_servers.expired')
                ->where('dev_api', $apikey)
                ->first();

                if($data!=null){
                    if($data->id_server==null){
                        // return 'Server not active / ready.';
                        return response()->json(
                            [
                                'wanotif' => [
                                    'status' => 'Server not active / ready.'
                                ]
                            ], 400
                        );
                    }else{

                        $now    = strtotime(date("Y-m-d h:i:s"));
                        $time   = strtotime(date($data->dev_expired))-$now;

                        if( $time<=0 ){
                            // return 'Expired';
                            return response()->json(
                                [
                                    'wanotif' => [
                                        'status' => 'Account Expired.'
                                    ]
                                ], 400
                            );

                        }else{
                            

                            return response()->json(
                                [
                                    'wanotif' => [
                                        'status' => 'sent'
                                    ]
                                ], 200
                            );


                        }
                    }
                }else{
                    // echo 'API Key not valid.';
                    return response()->json(
                        [
                            'wanotif' => [
                                'status' => 'API Key Not Valid.'
                            ]
                        ], 400
                    );
                }
                
            }
        }else{
            // echo 'Failed!';
            return response()->json(
                [
                    'wanotif' => [
                        'status' => 'Unauthenticated.'
                    ]
                ], 401
            );
        }
          
    }



}