<?php

namespace AppBundle\Doctrine;

use Doctrine\Common\Collections\Collection;
use Serializable;

interface SerializableCollectionInterface extends Collection, Serializable
{
}
