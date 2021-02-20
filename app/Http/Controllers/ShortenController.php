<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use DB;

class ShortenController extends Controller
{
    //
    public function saveURL(Request $request){
        $inputted_url = $request->url;
        $long_url = trim($inputted_url);

        if(!empty($long_url) && preg_match('|^https?://|', $long_url))
        {
            //check if the URL is valid
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $long_url);
            curl_setopt($ch,  CURLOPT_RETURNTRANSFER, TRUE);
            $response = curl_exec($ch);
            $response_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            if($response_status == '404')
            {
                $shorten_url = '0'; // Not a valid URL -- 404
            }else{
                $chars = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
                $length = 16;               
                $shorten_url = substr(str_shuffle(str_repeat($chars, 5)), 0, $length);
                DB::table('urls')
                    ->insert([
                        'inputted_url' => $inputted_url,
                        'shorten_url' => $shorten_url
                    ]);
            }            
        } else{
            // incorrect format url -- without http:// or https://
            $shorten_url = '-1';
        }

        return response()->json(['result'=>$shorten_url]);
    }

    public function getURL(Request $request){
        $url = DB::table('urls')->where('shorten_url', '=', $request->url)->get('inputted_url');
        $inputted_url = $url[0]->inputted_url;
        return response()->json(['result'=> $inputted_url ]);
    }
}
