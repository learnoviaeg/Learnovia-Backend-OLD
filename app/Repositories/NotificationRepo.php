<?php

namespace App\Repositories;
use GuzzleHttp\Client;

class NotificationRepo implements NotificationRepoInterface
{
    public function sendNotify($users,$reqNot)
    {
        // dd($reqNot);
        $data=[
            'users' => $users,
            'school_domain'=>substr(request()->getHost(),0,strpos(request()->getHost(),'api')),
            // 'school_domain'=>'test',
            // 'title'=> substr(request()->getHost(),0,strpos(request()->getHost(),'api')),
            'title'=> 'Learnovia',
            'body'=> $reqNot['message'],
            "item_type" => $reqNot['item_type'],
            "type" => $reqNot['type'],
            "item_id" => $reqNot['item_id'],
            'course_name' => $reqNot['course_name'],
            'lesson_id' => $reqNot['lesson_id'],
            'publish_date' => $reqNot['publish_date']
        ];

        // dd($data);

        // $clientt = new Client();
        // $res = $clientt->request('POST', 'http://ec2-100-26-60-206.compute-1.amazonaws.com/api/send/notifications', [
        //     'headers'   => [
        //         'username' => 'test',
        //         'password' => 'api_test_5eOiG7CTC',
        //     ],
        //     'form_params' => $data
        // ]);

        $curl = curl_init();

        curl_setopt_array($curl, array(
        CURLOPT_URL => 'http://ec2-100-26-60-206.compute-1.amazonaws.com/api/send/notifications',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_ENCODING => '',
        CURLOPT_MAXREDIRS => 10,
        CURLOPT_TIMEOUT => 0,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
        CURLOPT_CUSTOMREQUEST => 'POST',
        CURLOPT_POSTFIELDS =>json_encode($data),
        CURLOPT_HTTPHEADER => array(
                'username: test',
                'password: api_test_5eOiG7CTC',
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);
        curl_close($curl);

        return $response;
    }
}