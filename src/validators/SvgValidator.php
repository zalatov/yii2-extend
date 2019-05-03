<?php

declare(strict_types=1);

namespace zalatov\yii2\extend\validators;

use yii\validators\Validator;

/**
 * Проверка на корректность SVG изображения.
 *
 * @author Залатов Александр <zalatov.ao@gmail.com>
 */
class SvgValidator extends Validator {
	/**
	 * {@inheritDoc}
	 *
	 * @author Залатов Александр <zalatov.ao@gmail.com>
	 */
	public function __construct($config = []) {
		$this->message = 'Атрибут {attribute} не является SVG изображением.';

		parent::__construct($config);
	}

	/**
	 * {@inheritDoc}
	 *
	 * @author Залатов Александр <zalatov.ao@gmail.com>
	 */
	protected function validateValue($value) {
		if (false === is_string($value)) {
			return [$this->message];
		}

		if (false === static::isSvg($value)) {
			return [$this->message];
		}

		return null;
	}

	/**
	 * Проверка, является ли строка SVG изображением.
	 * Метод позволяет делать проверку без создания экземпляра валидатора.
	 *
	 * @param string $string Строка для проверки
	 *
	 * @return bool
	 *
	 * @author Залатов Александр <zalatov.ao@gmail.com>
	 */
	public static function isSvg(string $string): bool {
		if ('' === $string) {
			return false;
		}

		$xml = @simplexml_load_string($string);

		return (false !== $xml && 'svg' === $xml->getName());
	}
}
