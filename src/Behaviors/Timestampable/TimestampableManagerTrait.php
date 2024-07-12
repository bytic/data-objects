<?php

declare(strict_types=1);

namespace ByTIC\DataObjects\Behaviors\Timestampable;

use Nip\Records\AbstractModels\RecordManager;
use Nip\Records\EventManager\Events\Event;

/**
 * Trait TimestampableManagerTrait.
 */
trait TimestampableManagerTrait
{
    use TimestampableTrait {
        usesTimestampsDefault as usesTimestampsDefaultTrait;
    }

    public function bootTimestampableManagerTrait()
    {
        if ($this->usesTimestamps()) {
            $this->hookTimestampableIntoLifecycle();
        }
    }

    /**
     * {@inheritDoc}
     */
    public function usesTimestampsDefault(): bool
    {
        if (method_exists($this, 'getModel')) {
            $recordClass = $this->getModel();
            $record = new $recordClass();

            if (method_exists($recordClass, 'usesTimestamps')) {
                return $record->usesTimestamps();
            }
        }

        return $this->usesTimestampsDefaultTrait();
    }

    protected function hookTimestampableIntoLifecycle()
    {
        $updateCallback = function ($record, $manager, $type) {
            if (method_exists($record, 'getTimestampAttributes')) {
                $attributes = $record->getTimestampAttributes($type);
            } elseif (method_exists($manager, 'getTimestampAttributes')) {
                $attributes = $this->getTimestampAttributes($type);
            } else {
                $attributes = [];
            }

            /** @var TimestampableTrait $record */
            $record->updatedTimestamps($attributes);
        };

        $events = ['creating' => 'create', 'updating' => 'update'];
        foreach ($events as $event => $type) {
            if (false == \is_callable(static::class. '::' . $event)) {
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
