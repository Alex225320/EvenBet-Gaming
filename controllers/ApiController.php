<?php


namespace app\controllers;


use app\models\Chef;
use app\models\OrderItems;
use app\models\Orders;
use yii\web\Controller;
use Yii;

class ApiController extends Controller
{
    public $ac_key = '';

    public function beforeAction($action)
    {
        if (isset($_SERVER['HTTP_AUTHORIZATION']) && $_SERVER['HTTP_AUTHORIZATION'] === $this->ac_key)
            return parent::beforeAction($action);
        else self::Response(403, 'Bad permissions');
    }

    public function actionCreateTicket() {
        $ticket = Orders::create();
        if ($ticket) return json_encode(['success' => true, 'orderID' => $ticket]);
        else self::Response(400, 'Something went wrong.');
    }

    public function actionAddItemToTicket() {
        $post = yii::$app->request->post();
        if (!$post || empty($post['orderID']) || empty($post['productID']) || empty($post['price']))
            self::Response(400, 'Bad data.');
        else {
            $item = OrderItems::create($post);
            if ($item) return json_encode(['success' => true]);
            else self::Response(400, 'Something went wrong.');
        }
    }

    public function actionReportChef() {
        $post = yii::$app->request->post();
        if (!$post || empty($post['start']) || !is_numeric($post['start']) || strlen($post['start']) < 10)
            self::Response(400, 'Bad data.');
        else {
            if (empty($post['end']) || !is_numeric($post['end']) || strlen($post['end']) < 10) $post['end'] = time();

            $arrChefs = Chef::getData();
            $arrReport = [];
            if ($arrChefs) {
                foreach ($arrChefs as $arrChef) {
                    $arrReport[$arrChef['name']] = OrderItems::ItemsWithChef($post['start'], $post['end'], $arrChef['id']);
                }

                arsort($arrReport);
            }

            return json_encode(['success' => true, 'array' => $arrReport]);
        }
    }

    public static function Response(int $code, string $message)
    {
        $response = Yii::$app->response;
        $response->statusCode = $code;
        $response->format = \yii\web\Response::FORMAT_JSON;
        $response->data = ['statusCode' => $code, 'message' => $message];
        return $response;
    }
}