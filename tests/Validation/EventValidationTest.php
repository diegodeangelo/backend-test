<?php

namespace App\Tests\Validation;

use App\Entity\Event;
use App\Tests\Validation\ValidationTest;


class EventValidationTest extends ValidationTest
{
	public function setEntity(): Event
	{
		$datetime = new \Datetime();
		$datetime->setDate(2020, 1, 15);
		$datetime->setTime(8, 30, 0);

		$event = new Event();

		$event->setName("Evento Teste")
			  ->setDescription("Lorem ipsum dolor sit amet, consectetur adipiscing elit. Mauris fringilla sit amet ligula vitae fringilla. Sed lacinia sit amet arcu sed blandit. Mauris sed consequat est. Vivamus luctus, dui at egestas tincidunt, magna eros tristique est, sed facilisis nibh lacus sed urna. Integer laoreet, metus vitae pretium faucibus, ligula neque accumsan nisl, ut finibus diam dolor id sem. Sed sed ante tempor, scelerisque nulla id, efficitur nibh. Donec pulvinar dolor nec leo porttitor, vitae varius lectus sodales. Ut suscipit blandit tortor, dapibus dapibus dui vehicula at. Nullam in dui id ligula faucibus rutrum. Proin placerat, nulla eget faucibus egestas, massa nulla blandit nibh, nec consectetur tortor nulla vitae arcu. Quisque at enim vitae dui lobortis maximus. Curabitur tincidunt vehicula ullamcorper. Ut rutrum tellus id ipsum pharetra, sed commodo sem vulputate.")
			  ->setDate($datetime->format("Y-m-d"))
			  ->setTime($datetime->format("H:i:s"))
			  ->setStatus(Event::STATUS_ACTIVE);
		
		return $event;
	}
}