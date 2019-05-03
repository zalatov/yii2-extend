<?php

declare(strict_types=1);

namespace zalatov\yii2\extend\breadcrumbs\example;

use yii\db\ActiveRecord;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use zalatov\yii2\extend\breadcrumbs\Breadcrumb;
use zalatov\yii2\extend\breadcrumbs\Breadcrumbs;

/**
 * Контроллер для примера использования компонента Breadcrumbs.
 *
 * @author Залатов Александр <zalatov.ao@gmail.com>
 */
class BreadcrumbExampleController extends Controller {
	/** @var Breadcrumbs */
	private $breadcrumbs;

	/**
	 * {@inheritDoc}
	 *
	 * @author Залатов Александр <zalatov.ao@gmail.com>
	 */
	public function init() {
		$this->breadcrumbs = new Breadcrumbs($this, [
			'index' => new Breadcrumb('Список записей', [
				'view' => new Breadcrumb('Просмотр записи'),
			]),
		]);

		parent::init();
	}

	/**
	 * @return string
	 *
	 * @author Залатов Александр <zalatov.ao@gmail.com>
	 */
	public function actionIndex(): string {
		$records = ActiveRecord::find()->all();

		return $this->render('index', ['records' => $records]);
	}

	/**
	 * @param string $id
	 *
	 * @return string
	 *
	 * @author Залатов Александр <zalatov.ao@gmail.com>
	 */
	public function actionView(string $id): string {
		$record = ActiveRecord::findOne($id);
		if (null === $record) {
			throw new NotFoundHttpException;
		}

		$this->breadcrumbs->get($this->action->id)
			->setUrlParams(['id' => $id])// Указываем дополнительные параметры к URL
			->setLabel($record->name)// Заменяем заголовок "Просмотр записи" на название конкретной записи
		;

		$this->view->title                 = $this->breadcrumbs->getViewTitle();
		$this->view->params['breadcrumbs'] = $this->breadcrumbs->asArray();

		return $this->render('view', ['record' => $record]);
	}
}
