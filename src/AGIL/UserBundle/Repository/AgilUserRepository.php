<?php

namespace AGIL\UserBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * AgilUserRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AgilUserRepository extends EntityRepository
{

    /**
     * @param string $role
     *
     * @return array
     */
    public function findByRole($role)
    {
        $qb = $this->_em->createQueryBuilder()
            ->select('agil_user')
            ->from('AGILUserBundle:AgilUser','agil_user')
            ->where('agil_user.roles LIKE :roles')
            ->setParameter('roles', '%"' .$role.'"%')
            ->orderBy('agil_user.username', 'ASC')
        ;

        return $qb->getQuery()->getResult();
    }

    public function getCountUsers($page=1, $maxperpage=25)
    {
        $qb = $this->_em->createQueryBuilder()
            ->select('count(agil_user.id)')
            ->from('AGILUserBundle:AgilUser','agil_user')
            ->where('
            agil_user.roles NOT LIKE :roles AND
            agil_user.roles NOT LIKE :roles2 AND
            agil_user.roles NOT LIKE :roles3
            ')
            ->setParameter('roles', '%"ROLE_SUPER_ADMIN"%')
            ->setParameter('roles2', '%"ROLE_MODERATOR"%')
            ->setParameter('roles3', '%"ROLE_ADMIN"%')
        ;

        $users_count = $qb->getQuery()->getSingleScalarResult();

        return $users_count;
    }

    /**
     * Get the paginated list of published articles
     *
     * @param int $page
     * @param int $maxperpage
     * @return Paginator
     */
    public function getListUsers($page=1, $maxperpage=25)
    {
        $qb = $this->_em->createQueryBuilder()
            ->select('agil_user')
            ->from('AGILUserBundle:AgilUser','agil_user')
            ->where('
            agil_user.roles NOT LIKE :roles AND
            agil_user.roles NOT LIKE :roles2 AND
            agil_user.roles NOT LIKE :roles3
            ')
            ->setParameter('roles', '%"ROLE_SUPER_ADMIN"%')
            ->setParameter('roles2', '%"ROLE_MODERATOR"%')
            ->setParameter('roles3', '%"ROLE_ADMIN"%')
            ->orderBy('agil_user.username', 'ASC')
        ;

        $qb->getQuery()->setFirstResult(($page-1) * $maxperpage)
            ->setMaxResults($maxperpage);

        return new Paginator($qb);
    }

    /**
     * Get the paginated list of published articles
     *
     * @param int $page
     * @param int $maxperpage
     * @param string $role
     * @return Paginator
     */
    public function getList($page=1, $maxperpage=25, $role)
    {
        $qb = $this->_em->createQueryBuilder()
            ->select('agil_user')
            ->from('AGILUserBundle:AgilUser','agil_user')
            ->where('agil_user.roles LIKE :roles')
            ->setParameter('roles', '%"' .$role.'"%')
            ->orderBy('agil_user.username', 'ASC')
        ;

        $qb->getQuery()->setFirstResult(($page-1) * $maxperpage)
            ->setMaxResults($maxperpage);

        return new Paginator($qb);
    }
}
