<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Passenger;
use AppBundle\Entity\Trip;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $user = $this->get('security.context')->getToken()->getUser();

        $passengers = $this->getDoctrine()
                          ->getRepository(Passenger::class)
                          ->findBy(
                                array('is_active' => 1)
                            );
        $trips = $this->getDoctrine()
                          ->getRepository(Trip::class)
                          ->findBy(
                                array('is_active' => 1)
                            );

        $tripsWithDataUnserialized = array();
        for($i=0; $i<count($trips); $i++)
        {   
            $tripsWithDataUnserialized[] = array(
                'id' => $trips[$i]->getId(),
                'passengerIds' => unserialize($trips[$i]->getPassengerId()),
                'tripFrom' => $trips[$i]->getTripFrom(),
                'tripTo' => $trips[$i]->getTripTo(),
                'departure' => $trips[$i]->getDeparture(),
                'arrival' => $trips[$i]->getArrival(),
                'is_active' => $trips[$i]->getIsActive(),
                
            );
        }

        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', array(
            'userId'        => $user->getId(),
            'userName'      => $user->getUsername(),
            'userAddress'   => $user->getAddress(),
            'userCity'      => $user->getCity(),
            'userCountry'   => $user->getCountry(),
            'passengers'    => $passengers,
            'trips'         => $tripsWithDataUnserialized,
            'currentPage'   => 'homepage'
        ));
    }
    /**
     * @Route("/deleteTrip/{id}", name="deleteTrip")
     */
    public function delete(Request $Request, $id)
    {   
        $em = $this->get('doctrine')->getManager();
        $qb = $em->createQueryBuilder();
        $qb = $qb->delete('AppBundle:Trip', 't')
                ->where('t.id = :id')
                ->setParameter('id', $id)
                ->getQuery();
        $qb = $qb->execute();
        return $this->redirect($this->generateUrl('homepage'));
    }

    /**
     * @Route("/deletePassenger/{id}", name="deletePassenger")
     */
    public function deletePassenger(Request $Request, $id)
    {   
        $em = $this->get('doctrine')->getManager();
        $qb = $em->createQueryBuilder();
        $qb = $qb->delete('AppBundle:Passenger', 'p')
                ->where('p.id = :id')
                ->setParameter('id', $id)
                ->getQuery();
        $qb = $qb->execute();
        return $this->redirect($this->generateUrl('homepage'));
    }

    /**
     * @Route("/deleteAllPassenger", name="deleteAllPassenger")
     */
    public function deleteAllPassenger(Request $Request)
    {   
        $sql = "truncate passengers";
        $em = $this->getDoctrine()->getManager();
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();
        return $this->redirect($this->generateUrl('homepage'));
    }

    /**
     * @Route("/deleteAllTrips", name="deleteAllTrips")
     */
    public function deleteAllTrips(Request $Request)
    {   
        $sql = "truncate trips";
        $em = $this->getDoctrine()->getManager();
        $stmt = $em->getConnection()->prepare($sql);
        $stmt->execute();
        return $this->redirect($this->generateUrl('homepage'));
    }

    /**
     * @Route("/add_passenger", name="addPassengerPage")
     */
    public function addPassengerAction(Request $request)
    {
        return $this->render('default/add_passenger.html.twig', array('currentPage' => 'addPassengerPage'));
    }

    /**
     * @Route("/add_trip", name="addTripPage")
     */
    public function addTripAction(Request $request)
    {
        $userManager = $this->container->get('fos_user.user_manager');
        $users = $userManager->findUsers();
        // $passenger = new Passenger();
        $passengerRepository = $this->getDoctrine()->getRepository(Passenger::class);
        $users = $passengerRepository->findAll();
        return $this->render('default/add_trip.html.twig', array('users' => $users ,'currentPage' => 'addTripPage'));
    }

    /**
     * @Route("/update/user", name="updateUser")
     */
    public function updateUserAction(Request $request)
    {
        $user = Request::createFromGlobals();

        if ($user->request->has('submit')) {
            $username = $user->request->get('username');
            $address = $user->request->get('address');
            $city = $user->request->get('city');
            $country = $user->request->get('country');

            $qb = $this->getDoctrine()->getmanager();
            $qb = $qb->createQueryBuilder();
        
            $user = $this->get('security.context')->getToken()->getUser();
            $userId = $user->getId();
           
            $q = $qb->update('AppBundle:User', 'u')
                    ->set('u.username', $qb->expr()->literal($username))
                    ->set('u.address', $qb->expr()->literal($address))
                    ->set('u.city', $qb->expr()->literal($city))
                    ->set('u.country', $qb->expr()->literal($country))
                    ->where('u.id = ?1')
                    ->setParameter(1, $userId)
                    ->getQuery();
            $p = $q->execute();
        } else {
            $name = 'Not submitted yet';
            $p = false;
        }
        if($p)
            $data = 'success';
        else
            $data = 'error';

        return $this->redirect($this->generateUrl('homepage'));

    }

    /**
     * @Route("/add/passenger", name="addPassenger")
     */
    public function addPassenger(Request $request)
    {
        $passenger = Request::createFromGlobals();

        if ($passenger->request->has('submit')) {
            $title = $passenger->request->get('title');
            $first_name = $passenger->request->get('first_name');
            $surname = $passenger->request->get('surname');
            $passport_id = $passenger->request->get('passport_id');

            $qb = $this->getDoctrine()->getmanager();
            $qb = $qb->createQueryBuilder();
        
            $user = $this->get('security.context')->getToken()->getUser();
            $userId = $user->getId();
            
            $em = $this->get('doctrine')->getManager();

            $passenger = new Passenger();
            $passenger->setTitle($title);
            $passenger->setFirstName($first_name);
            $passenger->setSurname($surname);
            $passenger->setPassportId($passport_id);
            $passenger->setUserId($userId);
            $passenger->setIsActive(1);

            // tells Doctrine you want to (eventually) save the Product (no queries yet)
            $em->persist($passenger);

            // actually executes the queries (i.e. the INSERT query)
            $em->flush();

            $p = $em;
        } else {
            $name = 'Not submitted yet';
            $p = false;
        }
        if($p)
            $data = 'success';
        else
            $data = 'error';

        return $this->redirect($this->generateUrl('homepage'));

    }

    /**
     * @Route("/add/trip", name="addTrip")
     */
    public function addTrip(Request $request)
    {
        $trip = Request::createFromGlobals();

        if ($trip->request->has('submit')) {
            $tripFrom = $trip->request->get('tripFrom');
            $triptTo = $trip->request->get('tripTo');
            $departure = $trip->request->get('departure');
            $arrival = $trip->request->get('arrival');
            $passengers = serialize($trip->request->get('passengers'));
           
            $qb = $this->getDoctrine()->getmanager();
            $qb = $qb->createQueryBuilder();
        
            $user = $this->get('security.context')->getToken()->getUser();
            $userId = $user->getId();
            
            $em = $this->get('doctrine')->getManager();

            $trip = new Trip();
            $trip->setTripFrom($tripFrom);
            $trip->setTripTo($triptTo);
            $trip->setDeparture($departure);
            $trip->setArrival($arrival);
            $trip->setpassengerId($passengers);
            $trip->setIsActive(1);

            // tells Doctrine you want to (eventually) save the Product (no queries yet)
            $em->persist($trip);

            // actually executes the queries (i.e. the INSERT query)
            $em->flush();

            $p = $em;
        } else {
            $name = 'Not submitted yet';
            $p = false;
        }
        if($p)
            $data = 'success';
        else
            $data = 'error';

        return $this->redirect($this->generateUrl('homepage'));

    }


}
