<?php

namespace AGIL\DefaultBundle\Repository;

use AGIL\DefaultBundle\Entity\AgilTag;
use AGIL\ProfileBundle\Entity\AgilSkill;
use Doctrine\ORM\EntityRepository;
use InvalidArgumentException;

/**
 * AgilTagRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class AgilTagRepository extends EntityRepository
{
	/**
	 * @param $firstLetter String Le premier caractère du tag
	 * @return String Un texte en JSON avec la liste des tags correspondants
	 * Sert à trouver les tags dont la première lettre est $firstLetter
	 */
	function getTagsList($firstLetter) {
		// On créé la requête
		$request = $this->createQueryBuilder('t')->where('t.tagName LIKE :string')->setParameter('string', $firstLetter.'%');
		// On l'execute et on retourne le résultat
		/* /!\ C'est une liste d'objet de type AgilTag /!\ */
		return $request->getQuery()->getResult();
	}
}
