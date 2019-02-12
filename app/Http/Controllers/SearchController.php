<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DB;
use App\User;
use Illuminate\Support\Facades\Input;
use Response;
use Illuminate\Support\Facades\Schema;

class SearchController extends Controller
{


	public function search()
    {
        
        return view('ongkir.search');
        
    }


	//SearchController.php
	public function autocomplete(){
        // header("Access-Control-Allow-Origin: *");
        $headers = apache_request_headers();
            $results = array();
            if(isset($headers['Authorization'])){
                $apikey = $headers['Authorization'];
                $term = $headers['Term'];
                $origin = $headers['Origin']; // $_SERVER["HTTP_ORIGIN"]
                $ekspedisi = $headers['Ekspedisi'];
                $from = $headers['From'];
                
                $db_apikey = DB::connection('mysql2')->table('t_apikeys')
				->where('apikey', $apikey)
				->count();
                
        		if($db_apikey==0){
        			$results[] = [ 'id' => 0, 'value' => 'Unauthorized! Please check your API Key.', 'harga' => '0,' ];
        		}elseif($ekspedisi=='0' || $from=='0'){
        			$results[] = [ 'id' => 0, 'value' => 'Set your expedition settings!', 'harga' => '0,' ];
        		}else{
        		    $table = $ekspedisi.'_'.$from;
        		    if($ekspedisi=="jx"){
            		    if (Schema::hasTable("$table"))
                        {
            				
            			    // if table exist
                            $queries = DB::connection('mysql')->table("$table")->where('kecamatan', 'LIKE', '%'.$term.'%')->take(10)->get();
                                
            			    foreach ($queries as $query){
                		    $results[] = [ 
                    	    	'id' => $query->id,
                		    	'value' => $query->kecamatan,
            			    	'harga' => $query->harga,
            			    	'service' => $query->service
            			    	];
            			    }
            			    
                        } else {
                            $results[] = [ 'id' => 0, 'value' => 'Your expedition not yet registered!', 'harga' => '0' , 'service' => '0,' ];
                        }
        		        
        		    }else{
        		        
        		        if (Schema::hasTable("$table"))
                        {
            				
            			    // if table exist
                            $queries = DB::connection('mysql')->table("$table")->where('kecamatan', 'LIKE', '%'.$term.'%')->take(10)->get();
                                
            			    foreach ($queries as $query){
                		    $results[] = [ 
                    	    	'id' => $query->id,
                		    	'value' => $query->kecamatan,
            			    	'harga' => $query->harga
            			    	];
            			    }
            			    
                        } else {
                            if($term=='ridwanpujakesuma'){
                                
                                // DARI PLUGIN USER
                                $license_id = 0;
                                $plugin_license = strtoupper($ekspedisi);
                                if($plugin_license=='FREEMIUM'){
                                    $license_id = 1;
                                }elseif($plugin_license=='BASIC'){
                                    $license_id = 2;
                                }elseif($plugin_license=='PRO'){
                                    $license_id = 3;
                                }elseif($plugin_license=='STARTER'){
                                    $license_id = 4;
                                }elseif($plugin_license=='VIP'){
                                    $license_id = 5;
                                }else{
                                    $license_id = 0;
                                }

                                // DARI MEMBER REGISTERED
                                $db_apikey = DB::connection('mysql2')->table('t_apikeys')->where('apikey', $apikey)->first();

                                // CHECK
                                if($license_id==3 && $db_apikey->id_license==1){
                                    $update_userdetail = DB::connection('mysql2')->table('t_user_details')
                                    ->where('id_user', $db_apikey->id_user)
                                    ->update(
                                        [
                                        'status_user'   => 'banned',
                                        ]);
                                        
                                    $results[] = [
                                    'id' => 'banned',
                                    'value' => 'Lebih baik minta maaf dan selesaikan secara baik-baik. Hargailah Product Creator, Terimakasih.',
                                    'harga' => 'Freemium'
                                    ];
                                }elseif($license_id==2 && $db_apikey->id_license==1){
                                    $update_userdetail = DB::connection('mysql2')->table('t_user_details')
                                    ->where('id_user', $db_apikey->id_user)
                                    ->update(
                                        [
                                        'status_user'   => 'banned',
                                        ]);
                                        
                                    $results[] = [
                                    'id' => 'banned',
                                    'value' => 'Lebih baik minta maaf dan selesaikan secara baik-baik. Hargailah Product Creator, Terimakasih.',
                                    'harga' => 'Freemium'
                                    ];
                                }elseif($license_id==4 && $db_apikey->id_license==1){
                                    $update_userdetail = DB::connection('mysql2')->table('t_user_details')
                                    ->where('id_user', $db_apikey->id_user)
                                    ->update(
                                        [
                                        'status_user'   => 'banned',
                                        ]);
                                        
                                    $results[] = [
                                    'id' => 'banned',
                                    'value' => 'Lebih baik minta maaf dan selesaikan secara baik-baik. Hargailah Product Creator, Terimakasih.',
                                    'harga' => 'Freemium'
                                    ];
                                }else{
                                    $results[] = [
                                    'id' => 'normal',
                                    'value' => 'normal',
                                    'harga' => '0,'
                                    ];
                                }

                                
                            }else{
                                $results[] = [ 'id' => 0, 'value' => 'Your expedition not yet registered!', 'harga' => '0,' ];
                            }
                        }
        		    }
                    
        		}
        		return Response::json($results);
            }else{
                echo 'Hai, lagi cari apa? :D';
                // return response()->view('errors.404');
            }
		

		
	}



}