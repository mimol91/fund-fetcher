<?php

namespace AppBundle\Doctrine;

use Doctrine\Common\Collections\ArrayCollection;
use ReflectionClass;

abstract class AbstractSerializableCollection extends ArrayCollection implements SerializableCollectionInterface
{
    /**
     * @return string
     */
    abstract public function getElementClass();

    /**
     * {@inheritdoc}
     */
    public function serialize()
    {
        return serialize($this->map(function (ArraySerializableInterface $element) {
            return $element->toArray();
        })->toArray());
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize($serialized)
    {
        $this->clear();
        $collection = unserialize($serialized);

        foreach ($collection as $key => $element) {
            $object = (new ReflectionClass($this->getElementClass()))
                ->newInstanceWithoutConstructor()
                ->fromArray($element);

            $this->set($key, $object);
        }
    }
}
