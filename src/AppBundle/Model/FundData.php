<?php

namespace AppBundle\Model;

use AppBundle\Doctrine\ArraySerializableInterface;
use DateTimeInterface;
use InvalidArgumentException;
use Symfony\Component\Serializer\Annotation\Groups;

class FundData implements ArraySerializableInterface
{
    /**
     * @var DateTimeInterface
     * @Groups({"fund_details"})
     */
    private $date;

    /**
     * @var float
     * @Groups({"fund_details"})
     */
    private $price;

    /**
     * @param DateTimeInterface $date
     * @param float             $price
     *
     * @throws InvalidArgumentException
     */
    public function __construct(DateTimeInterface $date, $price)
    {
        if (!is_float($price)) {
            throw new InvalidArgumentException('Price has to be float. %s passed', $price);
        }

        $this->date = $date;
        $this->price = $price;
    }

    /**
     * @return DateTimeInterface
     */
    public function getDate()
    {
        return $this->date;
    }

    /**
     * @return float
     */
    public function getPrice()
    {
        return $this->price;
    }

    /**
     * @param array $data
     *
     * @return FundData
     */
    public function fromArray(array $data)
    {
        $this->date = (new \DateTimeImmutable())->setTimestamp($data['date']);
        $this->price = $data['price'];

        return $this;
    }

    /**
     * @return array
     */
    public function toArray()
    {
        return [
            'date' => $this->getDate()->getTimestamp(),
            'price' => $this->getPrice(),
        ];
    }
}
