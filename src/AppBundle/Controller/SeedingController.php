<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Passenger;
use AppBundle\Entity\Trip;
use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class SeedingController extends Controller
{
    /**
     * @Route("seeding/passengers", name="seeding_passenger")
     */
    public function getSeedingPassengersAction(Request $request)
    {
        $em = $this->get('doctrine')->getManager();

        $passenger = new Passenger();
        $passenger->setTitle('Mr');
        $passenger->setFirstName('John');
        $passenger->setSurname('Doe');
        $passenger->setPassportId('A12345678');
        $passenger->setIsActive(1);

        // tells Doctrine you want to (eventually) save the Product (no queries yet)
        $em->persist($passenger);

        // actually executes the queries (i.e. the INSERT query)
        $em->flush();

        return new Response('Saved new record to passengers');
    }

    /**
     * @Route("seeding/trips", name="seeding_trips")
     */
    public function getSeedingTripsAction(Request $request)
    {
        $em = $this->get('doctrine')->getManager();

        $trip = new Trip();
        $trip->setPassengerId(1);
        $trip->setTripFrom('John');
        $trip->setTripTo('Doe');
        $trip->setDeparture('A12345678');
        $trip->setArrival('A45678');
        $trip->setIsActive(1);

        // tells Doctrine you want to (eventually) save the Product (no queries yet)
        $em->persist($trip);

        // actually executes the queries (i.e. the INSERT query)
        $em->flush();

        return new Response('Saved new record to trips');
    }
}
