<?php

namespace backend\modules\common\controllers;

use Yii;
use common\helpers\ResultHelper;
use common\models\common\Log;
use common\enums\StatusEnum;
use common\models\base\SearchModel;
use backend\controllers\BaseController;

/**
 * Class LogController
 * @package backend\modules\common\controllers
 * @author jianyan74 <751393839@qq.com>
 */
class LogController extends BaseController
{
    /**
     * @return string
     * @throws \yii\web\NotFoundHttpException
     */
    public function actionIndex()
    {
        $searchModel = new SearchModel([
            'model' => Log::class,
            'scenario' => 'default',
            'partialMatchAttributes' => ['method', 'url'], // 模糊查询
            'defaultOrder' => [
                'id' => SORT_DESC,
            ],
            'pageSize' => $this->pageSize,
        ]);

        $dataProvider = $searchModel
            ->search(Yii::$app->request->queryParams);
        $dataProvider->query
            ->andWhere(['status' => StatusEnum::ENABLED])
            ->with(['member']);

        return $this->render($this->action->id, [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    /**
     * @param string $data
     * @return array|string
     */
    public function actionStat($type = '')
    {
        if (!empty($type)) {
            $data = Yii::$app->services->log->stat($type);

            return ResultHelper::json(200, '获取成功', $data);
        }

        return $this->renderAjax($this->action->id, [

        ]);
    }

    /**
     * @param string $data
     * @return array|string
     */
    public function actionFlowStat($type = '')
    {
        if (!empty($type)) {
            $data = Yii::$app->services->log->flowStat($type);

            return ResultHelper::json(200, '获取成功', $data);
        }

        return $this->renderAjax($this->action->id, [

        ]);
    }

    /**
     * 行为日志详情
     *
     * @param $id
     * @return string
     */
    public function actionView($id)
    {
        return $this->renderAjax($this->action->id, [
            'model' => Log::findOne($id),
        ]);
    }
}