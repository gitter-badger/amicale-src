<?php

namespace AGIL\HallBundle\Repository;

use AGIL\HallBundle\Controller\DefaultController;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;
use Symfony\Component\Validator\Constraints\DateTime;

/**
 * AgilEventRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AgilEventRepository extends EntityRepository
{
    public function getEventDataStartEnd($start, $end) {

        $end = new \DateTime($end);
        $end = $end->format('Y-m-d h:m:s');

        $start = new \DateTime($start);
        $start = $start->format('Y-m-d h:m:s');

        $query = $this->_em->createQueryBuilder();

        $query
            ->select('es.eventTitle',
                'es.eventDate',
                'es.eventPostDate',
                'es.eventDateEnd',
                'es.eventText',
                'es.eventId'
            )
            ->from('AGIL\HallBundle\Entity\AgilEvent', 'es')
            ->where('es.eventDate BETWEEN :startDate and :endDate')
            ->where('es.eventDateEnd BETWEEN :startDate and :endDate')
            ->setParameter('startDate', $start)
            ->setParameter('endDate', $end);


        return $query->getQuery()->getResult();
    }

    /**
     * Permet d'obtenir le nombre d'événements.
     *
     * @return mixed
     */
    public function getCountEvents(){
        $query = $this->_em->createQueryBuilder();
        $query->select('COUNT(event.eventId) as cnt')
            ->from('AGIL\HallBundle\Entity\AgilEvent','event');
        ;

        return $query->getQuery()->getSingleScalarResult();
    }

    public function getEventsByPage($page=1, $maxPerPage=DefaultController::MAX_EVENTS){
        $query = $this->_em->createQueryBuilder();

        $query->select('event')
            ->from('AGIL\HallBundle\Entity\AgilEvent','event')
            ->orderBy('event.eventPostDate','desc')
        ;

        $query->setFirstResult(($page-1) * $maxPerPage)
            ->setMaxResults($maxPerPage)->getQuery();

        return new Paginator($query);
    }

    /**
     * Permet de retourner les événement triés
     */
    public function getEventsByStartDateDesc() {

        return $this->findBy(array(), array('forumCategoryName' => 'ASC'));
//        $query = $this->_em->createQueryBuilder();
//
//        $query
//            ->select(
//                'es.eventTitle',
//                'es.eventDate',
//                'es.eventPostDate',
//                'es.eventDateEnd',
//                'es.eventText',
//                'es.eventId'
//            )
//            ->from('AGIL\HallBundle\Entity\AgilEvent', 'es')
//            ->orderBy('es.eventDate', 'ASC')
//        ;
    }
}
