<?php

namespace AGIL\ForumBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{

    private $client = null;

    /**
     * "Constructeur" qui initialise le client
     */
    public function setUp()
    {
        $this->client = static::createClient();
    }

    /**
     * Test : Accéder à l'accueil du forum
     */
    public function testForumHomepage()
    {
        $crawler = $this->client->request('GET', '/forum/');
        $crawler = $this->client->followRedirect();

        $form = $crawler->selectButton('_submit')->form(array(
            '_username'  => 'superadmin@superadmin.fr',
            '_password'  => 'superAdmin',
        ));

        $this->client->submit($form);
        $crawler = $this->client->followRedirect();

        $this->assertContains('Accueil Forum', $this->client->getResponse()->getContent());
    }

    /**
     * Test : Créer un sujet - Répondre au sujet - Editer une réponse - Supprimer le sujet
     *
     * Pré-condition : testForumHomepage()
     */
    public function testForum()
    {
        // Accéder au forum
        $this->testForumHomepage();

        /**
         * Créer un sujet
         */
        $crawler = $this->client->request('GET', '/forum/categories/1/page');

        $link = $crawler
            ->filter('a:contains("Nouveau Sujet")') // Cherche tous les liens contenant Nouveau Sujet
            ->eq(0) // Selectionne le premier trouvé
            ->link()
        ;
        $crawler = $this->client->click($link);

        $form = $crawler->selectButton('forum_add_first_answer[Ajouter]')->form();

        $form['forum_add_first_answer[subject][forumSubjectTitle]'] = "Vos retours sur PHP 7";
        $form['forum_add_first_answer[subject][forumSubjectDescription]'] = "Description";
        $form['forum_add_first_answer[subject][tags]'] = "Web PHP";
        $form['forum_add_first_answer[forumAnswerText]'] = "Salut les développeurs Web! J'aimerai avoir vos avis
        sur la version 7 de PHP, si vous êtes satisfait ou non.";

        $crawler = $this->client->submit($form);
        $crawler = $this->client->followRedirect();

        $this->assertContains('Le sujet a bien été créé', $this->client->getResponse()->getContent());

        /**
         * Répondre dans le sujet
         */
        $form = $crawler->selectButton('forum_add_answer[Ajouter]')->form();
        $form['forum_add_answer[forumAnswerText]'] = "Ma réponse au sujet";

        $crawler = $this->client->submit($form);
        $crawler = $this->client->followRedirect();

        $this->assertContains('Ma réponse au sujet', $this->client->getResponse()->getContent());


        /**
         * Editer son deuxième message
         */
        $link = $crawler
            ->filter('#editAnswerLink') // Cherche tous les liens contenant l'id editAnswerLink
            ->eq(1) // Selectionne le deuxième trouvé
            ->link()
        ;
        $crawler = $this->client->click($link);

        $form = $crawler->selectButton('forum_edit_answer[Modifier]')->form();

        $form['forum_edit_answer[forumAnswerText]'] = "J'ai modifié ma réponse au sujet";

        $crawler = $this->client->submit($form);
        $crawler = $this->client->followRedirect();

        $this->assertContains('La réponse a bien été modifiée', $this->client->getResponse()->getContent());


        /**
         * Supprimer le sujet créé
         */
        $crawler = $this->client->request('GET', '/forum/categories/1/page');

        $link = $crawler
            ->filter('#deleteSubjectLink') // Cherche tous les liens contenant l'id deleteSubjectLink
            ->eq(0) // Selectionne le premier trouvé
            ->link()
        ;

        $crawler = $this->client->click($link);

        $form = $crawler->selectButton('forum_delete_subject[Supprimer]')->form();
        $crawler = $this->client->submit($form);
        $crawler = $this->client->followRedirect();

        $this->assertContains('Le sujet a bien été supprimé.', $this->client->getResponse()->getContent());

    }

}
