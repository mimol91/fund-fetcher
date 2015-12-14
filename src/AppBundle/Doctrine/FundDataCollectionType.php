<?php

namespace AppBundle\Doctrine;

use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\Type;
use InvalidArgumentException;

class FundDataCollectionType extends Type
{
    const TYPE_NAME = 'fund_data_collection';

    /**
     * {@inheritdoc}
     */
    public function getSQLDeclaration(array $fieldDeclaration, AbstractPlatform $platform)
    {
        return $platform->getClobTypeDeclarationSQL($fieldDeclaration);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return self::TYPE_NAME;
    }

    /**
     * {@inheritdoc}
     */
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (!$value instanceof FundDataCollection) {
            throw new InvalidArgumentException();
        }

        return $value->serialize();
    }
    /**
     * {@inheritdoc}
     */
    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if (null === $value) {
            return;
        }
        $value = (is_resource($value)) ? stream_get_contents($value) : $value;
        $fundDataCollection = new FundDataCollection();
        $fundDataCollection->unserialize($value);

        return $fundDataCollection;
    }
}
