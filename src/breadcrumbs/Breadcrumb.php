<?php

declare(strict_types=1);

namespace zalatov\yii2\extend\breadcrumbs;

/**
 * Модель, описывающая данные по каждому из breadcrumb'ов.
 * Можно было обойтись и массивами, но с классами проще и понятней, так как есть подсказки и проверки средствами IDE.
 *
 * @author Залатов Александр <zalatov.ao@gmail.com>
 */
class Breadcrumb {
	/** @var string Заголовок. */
	private $label;

	/** @var static[] Дочерние breadcrumbs. */
	private $childs = [];

	/** @var string|array|null URL адрес или маршрут (url-route). */
	private $url;

	/** @var array Дополнительные параметры для генерации URL адреса. */
	private $urlParams = [];

	/**
	 * @param string   $label  Заголовок
	 * @param static[] $childs Дочерние элементы
	 *
	 * @author Залатов Александр <zalatov.ao@gmail.com>
	 */
	public function __construct(string $label, array $childs = []) {
		$this->label  = $label;
		$this->childs = $childs;
	}

	/**
	 * Установка заголовка.
	 *
	 * @param string $label Текст заголовка
	 *
	 * @return static
	 *
	 * @author Залатов Александр <zalatov.ao@gmail.com>
	 */
	public function setLabel(string $label): self {
		$this->label = $label;

		return $this;
	}

	/**
	 * Получение текста заголовка.
	 *
	 * @return string
	 *
	 * @author Залатов Александр <zalatov.ao@gmail.com>
	 */
	public function getLabel(): string {
		return $this->label;
	}

	/**
	 * Установка списка дочерних элементов.
	 *
	 * @param static[] $childs Список дочерних элементов
	 *
	 * @return static
	 *
	 * @author Залатов Александр <zalatov.ao@gmail.com>
	 */
	public function setChilds(array $childs): self {
		$this->childs = $childs;

		return $this;
	}

	/**
	 * Получение списка дочерних элементов.
	 *
	 * @return static[]
	 *
	 * @author Залатов Александр <zalatov.ao@gmail.com>
	 */
	public function getChilds(): array {
		return $this->childs;
	}

	/**
	 * Указание URL адреса.
	 *
	 * @param string|array $url URL адрес или маршрут (url-route)
	 *
	 * @return static
	 *
	 * @author Залатов Александр <zalatov.ao@gmail.com>
	 */
	public function setUrl($url): self {
		$this->url = $url;

		return $this;
	}

	/**
	 * Получение URL адреса или маршрута (url-route).
	 *
	 * @return string|array|null
	 *
	 * @author Залатов Александр <zalatov.ao@gmail.com>
	 */
	public function getUrl() {
		return $this->url;
	}

	/**
	 * Установка дополнительных параметров для генерации URL адреса.
	 *
	 * @param array $params Параметры URL
	 *
	 * @return static
	 *
	 * @author Залатов Александр <zalatov.ao@gmail.com>
	 */
	public function setUrlParams(array $params): self {
		$this->urlParams = $params;

		return $this;
	}

	/**
	 * Получение дополнительных параметров для генерации URL адреса.
	 *
	 * @return array
	 *
	 * @author Залатов Александр <zalatov.ao@gmail.com>
	 */
	public function getUrlParams(): array {
		return $this->urlParams;
	}
}
