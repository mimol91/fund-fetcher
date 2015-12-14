<?php

namespace AppBundle\Doctrine;

interface ArraySerializableInterface
{
    /**
     * @param array $data
     *
     * @return mixed
     */
    public function fromArray(array $data);
    /**
     * @return array
     */
    public function toArray();
}
