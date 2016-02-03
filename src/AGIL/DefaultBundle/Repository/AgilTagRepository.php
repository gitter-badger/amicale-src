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
	 * Sert à trouver les tags dont la première lettre est $w
	 */
	function getTagsList($firstLetter) {
		// On créé la requête
		$request = $this->createQueryBuilder('t')->where('t.tagName LIKE :string')->setParameter('string', $firstLetter.'%');
		// On l'execute et on retourne le résultat
		/* /!\ C'est une liste d'objet de type AgilTag /!\ */
		return $request->getQuery()->getResult();
	}

	/**
	 * @param $tagName String Nom du tag à ajouter
	 * @param AgilSkill $skillCat (Optionnel) La catégorie à accrocher
	 * Permet d'insérer des tags dans la base de données
	 */
	function insertTag($tagName, AgilSkill $skillCat = null) {
		if (!$tagName) {
			throw new InvalidArgumentException('Un tag doit posséder au moins une lettre');
		}

		if (null === $this->findOneByTagName($tagName)) {
			$tag = new AgilTag();
			$tag->setTagName($tagName);
			$tag->setSkillCategory((null === $skillCat)? null : $skillCat);

			$this->getEntityManager()->persist($tag);
			$this->getEntityManager()->flush();
		}
	}
}
