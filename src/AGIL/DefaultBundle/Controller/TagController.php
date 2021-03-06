<?php

namespace AGIL\DefaultBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class TagController extends Controller {

	public function testFuncAction() {

		$this->get('agil_default.tags');

		return $this->render('AGILDefaultBundle:Default:tags.html.twig');
	}

	/**
	 * @param Request $request Le préfixe
	 * @return string Ensemble de résultat au format JSON
	 * Récupère une liste de tags dont le préfixe est $char et la renvoie au format JSON
	 */
	public function searchAction(Request $request) {
		if (!$request->isXmlHttpRequest()) {
			throw $this->createNotFoundException('Ressource indisponible');
		}

		// On récupère la valeur envoyée par la requête
		$prefix = $request->request->get('prefix');

		// Récupération du tableau de AgilTag
		$tagsList = $this
			->getDoctrine()
			->getManager()
			->getRepository('AGILDefaultBundle:AgilTag')
			->getTagsList($prefix);

		$jsonTagsList = Array();
		foreach ($tagsList as $tag) {
			$jsonTagsList[] = $tag->getTagName();
		}
		// Retourne le tableau encodé en JSON
		return new JsonResponse($jsonTagsList);
	}

	/**
	 * @param Request $request Le nom du tag
	 * @return Response Pour notifier la fin de l'execution
	 * Récupère un tag dans la base de données pour le supprimer
	 */
	public function removeAction(Request $request) {
		if (!$request->isXmlHttpRequest()) {
			throw $this->createNotFoundException('Ressource indisponible');
		}

		$tagName = $request->request->get('tagName');

		$em = $this->getDoctrine()->getManager();

		$tag = $em->getRepository('AGILDefaultBundle:AgilTag')
			// On peut utiliser le OneBy car chaque tag est unique
			// méthode magique
			->findOneByTagName($tagName);

		if (null !== $tag) {
			$em->remove($tag);
			$em->flush();
		}

		return new Response();
	}
}