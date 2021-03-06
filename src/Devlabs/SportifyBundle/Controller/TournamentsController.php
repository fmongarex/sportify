<?php

namespace Devlabs\SportifyBundle\Controller;

use Devlabs\SportifyBundle\Entity\Score;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/**
 * Class TournamentsController
 * @package Devlabs\SportifyBundle\Controller
 */
class TournamentsController extends Controller
{
    /**
     * @Route("/tournaments", name="tournaments_index")
     */
    public function indexAction(Request $request)
    {
        // if user is not logged in, redirect to login page
        if (!is_object($user = $this->getUser())) {
            return $this->redirectToRoute('fos_user_security_login');
        }

        // Get an instance of the Entity Manager
        $em = $this->getDoctrine()->getManager();

        // get the tournaments helper service
        $tournamentsHelper = $this->container->get('app.tournaments.helper');

        // get all and joined tournaments lists
        $tournaments = $em->getRepository('DevlabsSportifyBundle:Tournament')
            ->findAll();
        $tournamentsJoined = $em->getRepository('DevlabsSportifyBundle:Tournament')
            ->getJoined($user);

        $forms = array();

        if ($tournaments) {
            // creating a form with JOIN/LEAVE button for each tournament
            foreach ($tournaments as $tournament) {
                // get the input data for building the tournament form
                $formInputData = $tournamentsHelper->getFormInputData($tournament, $tournamentsJoined);

                // create the tournament form and handle it
                $form = $tournamentsHelper->createForm($formInputData);
                $form->handleRequest($request);

                // iterate the forms and and if form is submitted, then execute code for the join/leave tournament
                if ($form->isSubmitted() && $form->isValid()) {
                    $tournamentsHelper->actionOnFormSubmit($form, $user);

                    // clear the submitted POST data and reload the page
                    return $this->redirectToRoute('tournaments_index');
                }

                // create view for each tournament's form
                $forms[$tournament->getId()] = $form->createView();
            }
        }

        // get user standings and set them as global Twig var
        $this->get('app.twig.helper')->setUserScores($user);

        // rendering the view and returning the response
        return $this->render(
            'Tournaments/index.html.twig',
            array(
                'tournaments' => $tournaments,
                'forms' => $forms
            )
        );
    }
}
