<?php

namespace app\controllers;

use Exception;
use Yii;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\Controller;

class CallController extends Controller
{
    private const SID = "AC468958608cd6496d2215636fcc2b4a33";
    private const TOKEN = "6105192f2fe9f425c0a60112e753e53f";

    public function actionMake($to)
    {
        if(\Yii::$app->request->isAjax){
            // My number: (424) 377-1271
            // safe code: j49f5YVURunFSosp8v5cqUB1xLxF_DXt1uKulSy2

            $client = new \Twilio\Rest\Client(self::SID, self::TOKEN);

            // Read TwiML at this URL when a call connects (hold music)
            $call = $client->calls->create(
                $to, // Call this number
                '+14243771271', // From a valid Twilio number
                [
                    'url' => 'https://twimlets.com/holdmusic?Bucket=com.twilio.music.ambient'
                ]
            );

            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return Json::encode([
                'data' => 'Запрос принят!'
            ]);
        }
    }

    public function actionConnect()
    {
        if(\Yii::$app->request->isAjax){
            // Set URL for outbound call - this should be your public server URL
            $host = parse_url(Url::base(true), PHP_URL_HOST);
            $encodedSalesPhone = urlencode(str_replace(' ','', '+79248775690'));
            // Create authenticated REST client using account credentials in
            // <project root dir>/.env.php
            $client = new \Twilio\Rest\Client(self::SID, self::TOKEN);

            try {
                $client->calls->create(
                    '+79142736836', // The visitor's phone number
                    '+14243771271', // A Twilio number in your account
                    array(
                        "url" => "http://$host/outbound/$encodedSalesPhone"
                    )
                );
            } catch (Exception $e) {
                // Failed calls will throw
                return $e;
            }

            // return a JSON response
            Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
            return Json::encode([
                'message' => 'Call incoming!'
            ]);
        }
    }
}
