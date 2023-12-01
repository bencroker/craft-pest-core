<?php

namespace markhuot\craftpest\modules\test\controllers;

use craft\web\Controller;

class TestController extends Controller
{
    public function actionTestableWebResponse()
    {
        return $this->asJson(['foo' => 'bar']);
    }

    public function actionTestableWebAction()
    {
        $this->requirePostRequest();

        return $this->asJson(['foo' => 'bar']);
    }
}
