<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\httpclient\Client;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $date = Yii::$app->request->post('date', date('Y-m-d'));
        $client = new Client();
        $response = $client->createRequest()
            ->setMethod('POST')
            ->setHeaders([
                'Authorization' => 'Bearer weather-secret-token',
                'content-type' => 'application/json'
            ])
            ->setFormat(Client::FORMAT_RAW_URLENCODED)
            ->setUrl('http://wt-history.webmore.top/api')
            ->setContent('{"jsonrpc": "2.0", "method": "weather.get-by-date", "params": {"date": "'.$date.'"}, "id": 1}')
            ->send();
        $result = json_decode($response->content);

        $responseLastDays = $client->createRequest()
            ->setMethod('POST')
            ->setHeaders([
                'Authorization' => 'Bearer weather-secret-token',
                'content-type' => 'application/json'
            ])
            ->setFormat(Client::FORMAT_RAW_URLENCODED)
            ->setUrl('http://wt-history.webmore.top/api')
            ->setContent('{"jsonrpc": "2.0", "method": "weather.get-history", "params": {"lastDays": 30}, "id": 1}')
            ->send();
        $lastDays = json_decode($responseLastDays->content);

        return $this->render('index', [
            'date' => $date,
            'data' => $result,
            'lastDays' => $lastDays
        ]);
    }

}
