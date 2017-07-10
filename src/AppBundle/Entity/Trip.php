<?php
namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="trips")
 */
class Trip
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $passenger_id;
    
    /**
     * @ORM\Column(type="string", length=100)
     */
    private $tripFrom;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $tripTo;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $departure;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $arrival;

    /**
     * @ORM\Column(type="integer")
     */
    private $is_active;


    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set passengerId
     *
     * @param string $passengerId
     *
     * @return Trip
     */
    public function setPassengerId($passengerId)
    {
        $this->passenger_id = $passengerId;

        return $this;
    }

    /**
     * Get passengerId
     *
     * @return string
     */
    public function getPassengerId()
    {
        return $this->passenger_id;
    }

    /**
     * Set from
     *
     * @param string $from
     *
     * @return Trip
     */
    public function setTripFrom($from)
    {
        $this->tripFrom = $from;

        return $this;
    }

    /**
     * Get tripFrom
     *
     * @return string
     */
    public function getTripFrom()
    {
        return $this->tripFrom;
    }

    /**
     * Set to
     *
     * @param string $to
     *
     * @return Trip
     */
    public function setTripTo($to)
    {
        $this->tripTo = $to;

        return $this;
    }

    /**
     * Get tripTo
     *
     * @return string
     */
    public function getTripTo()
    {
        return $this->tripTo;
    }

    /**
     * Set departure
     *
     * @param string $departure
     *
     * @return Trip
     */
    public function setDeparture($departure)
    {
        $this->departure = $departure;

        return $this;
    }

    /**
     * Get departure
     *
     * @return integer
     */
    public function getDeparture()
    {
        return $this->departure;
    }

    /**
     * Set arrival
     *
     * @param string $arrival
     *
     * @return Trip
     */
    public function setArrival($arrival)
    {
        $this->arrival = $arrival;

        return $this;
    }

    /**
     * Get arrival
     *
     * @return integer
     */
    public function getArrival()
    {
        return $this->arrival;
    }

    /**
     * Set isActive
     *
     * @param integer $isActive
     *
     * @return Trip
     */
    public function setIsActive($isActive)
    {
        $this->is_active = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return string
     */
    public function getIsActive()
    {
        return $this->is_active;
    }
}
