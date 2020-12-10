<?php

namespace ByTIC\DataObjects\Behaviors\Timestampable;

use Nip\Records\AbstractModels\RecordManager;
use Nip\Records\EventManager\Events\Event;

/**
 * Trait TimestampableManagerTrait
 * @package ByTIC\DataObjects\Behaviors\Timestampable
 */
trait TimestampableManagerTrait
{
    use TimestampableTrait;

    public function bootTimestampableManagerTrait()
    {
        $this->hookTimestampableIntoLifecycle();
    }

    protected function hookTimestampableIntoLifecycle()
    {
        $updateCallback = function ($record, $manager, $type) {
            if (method_exists($manager, 'getTimestampAttributes')) {
                $attributes = $this->getTimestampAttributes($type);
            } else {
                $attributes = $type;
            }

            /** @var TimestampableTrait $record */
            $record->updatedTimestamps($attributes);
        };

        $events = ['creating' => 'create', 'updating' => 'update'];
        foreach ($events as $event => $type) {
            if (is_callable('static::' . $event) == false) {
                continue;
            }
            static::$event(
                function (Event $event) use ($updateCallback, $type) {
                    $record = $event->getRecord();
                    /** @var static|RecordManager $manager */
                    $manager = $event->getManager();
                    $updateCallback($record, $manager, $type);
                }
            );
        }
    }
}
