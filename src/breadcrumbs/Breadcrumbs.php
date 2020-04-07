<?php

declare(strict_types=1);

namespace zalatov\yii2\extend\breadcrumbs;

use yii\base\Controller;
use yii\base\InvalidConfigException;

/**
 * Компонент для удобной работы с breadcrumbs контроллера.
 *
 * Какие преимущества и удобства:
 * - Дерево breadcrumbs задаётся в одном месте, а не в каждом экшене.
 * - Автоматическая генерация массива breadcrumbs для отдачи в виджет
 * - Удобство в работе IDE и autocomplete.
 *
 * @author Залатов Александр <zalatov.ao@gmail.com>
 */
class Breadcrumbs {
	/**
	 * Контроллер, к которому привязаны breadcrumbs.
	 * Указание необходимо, чтобы генерировать ссылки относительно конкретного контроллера.
	 *
	 * @var Controller
	 */
	private $controller;

	/**
	 * Список breadcrumbs'ов в виде вложенного дерева.
	 *
	 * @var Breadcrumb[]
	 */
	private $breadcrumbs;

	/**
	 * @param Controller   $controller  Контроллер, к которому привязаны breadcrumbs
	 * @param Breadcrumb[] $breadcrumbs Дерево breadcrumb'ов
	 *
	 * @author Залатов Александр <zalatov.ao@gmail.com>
	 */
	public function __construct(Controller $controller, array $breadcrumbs) {
		$this->controller  = $controller;
		$this->breadcrumbs = $breadcrumbs;
	}

	/**
	 * Получение заголовка страницы исходя из последнего breadcrumb'а в цепочке.
	 *
	 * @return string
	 *
	 * @author Залатов Александр <zalatov.ao@gmail.com>
	 */
	public function getViewTitle(): string {
		$breadcrumbs = $this->asArray();
		$last        = end($breadcrumbs);

		return (string)$last['label'];
	}

	/**
	 * Получение связанного breadcrumb'а по идентификатору экшена.
	 *
	 * @param string $actionId Идентификатор экшена
	 *
	 * @return Breadcrumb
	 *
	 * @throws InvalidConfigException Если не удалось найти
	 *
	 * @author Залатов Александр <zalatov.ao@gmail.com>
	 */
	public function get(string $actionId): Breadcrumb {
		return $this->_get($actionId, $this->breadcrumbs);
	}

	/**
	 * Внутренний метод!
	 * Получение связанного breadcrumb'а по идентификатору экшена.
	 *
	 * @param string            $actionId    Идентификатор экшена
	 * @param Breadcrumb[]|null $breadcrumbs Список breadcrumbs, по которым будет осуществлён рекурсивный поиск
	 *
	 * @return Breadcrumb
	 *
	 * @throws InvalidConfigException Если не удалось найти
	 *
	 * @author Залатов Александр <zalatov.ao@gmail.com>
	 */
	private function _get(string $actionId, array $breadcrumbs): Breadcrumb {
		foreach ($breadcrumbs as $bActionId => $breadcrumb) {
			if ($actionId === $bActionId) {
				return $breadcrumb;
			}

			$child = $this->_get($actionId, $breadcrumb->getChilds());
			if (null !== $child) {
				return $child;
			}
		}

		throw new InvalidConfigException;
	}

	/**
	 * Генерация пути breadcrumbs для текущего экшена.
	 *
	 * @return array В виде массива для передачи, например, в виджет отрисовки.
	 *
	 * @author Залатов Александр <zalatov.ao@gmail.com>
	 */
	public function asArray(): array {
		return $this->_asArray($this->breadcrumbs);
	}

	/**
	 * Внутренний метод!
	 * Генерация пути breadcrumbs для текущего экшена.
	 *
	 * @param Breadcrumb[] $breadcrumbs Список элементов, по которым пройтись для рекурсивного поиска нужных breadcrumbs
	 *
	 * @return array В виде массива для передачи, например, в виджет отрисовки.
	 *
	 * @author Залатов Александр <zalatov.ao@gmail.com>
	 */
	private function _asArray(array $breadcrumbs): array {
		$result = [];

		foreach ($breadcrumbs as $actionId => $breadcrumb) {
			if ($this->controller->action->id === $actionId) {
				$result[] = [
					'label' => $breadcrumb->getLabel(),
				];
			}
			elseif (0 !== count($breadcrumb->getChilds())) {
				$subResult = $this->_asArray($breadcrumb->getChilds());
				if (0 !== count($subResult)) {
					$url = $breadcrumb->getUrl();

					if (null === $url) {
						$url    = $breadcrumb->getUrlParams();
						$url[0] = $actionId;
					}

					$result[] = [
						'label' => $breadcrumb->getLabel(),
						'url'   => $url,
					];

					$result = array_merge($result, $subResult);
				}
			}
		}

		return $result;
	}
}
