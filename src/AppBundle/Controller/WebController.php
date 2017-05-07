<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class WebController extends Controller
{
    /**
     * @Route("/", name="users_list")
     */
    public function listAction()
    {
        $users = $this->getDoctrine()->getRepository('AppBundle:User')->findAll();
        return $this->render('myweb/index.html.twig', array(
            'users' => $users
        ));
    }

    /**
     * @Route("/user/create", name="user_create")
     */
    public function createAction(Request $request)
    {
        $user = new User();

        $form = $this->createFormBuilder($user)
            ->add('username', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('password', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
//            ->add('dateCreate', DateTimeType::class, array('attr' => array('class' => 'formcontrol', 'style' => 'margin-bottom:15px')))
            ->add('save', SubmitType::class, array('label' => 'Create User', 'attr' => array('class' => 'btn btn-primary', 'style' => 'margin-bottom:15px')))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //Get Data
            $name = $form['username']->getData();
            $password = $form['password']->getData();
//            $dateCreate = $form['dateCreate']->getData();

            $now = new\DateTime('now');

            $user->setUsername($name);
            $user->setPassword($password);
            $user->setDateCreate($now);

            $em = $this->getDoctrine()->getManager();

            $em->persist($user);
            $em->flush();

            $this->addFlash(
                'notice',
                'User Added'
            );

            return $this->redirectToRoute('users_list');
        }
        return $this->render('myweb/create.html.twig', array(
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/user/edit/{id}", name="user_edit")
     */
    public function editAction($id, Request $request)
    {
        $user = $this->getDoctrine()->getRepository('AppBundle:User')->find($id);

        $user->setUsername($user->getUsername());
        $user->setPassword($user->getPassword());
        $user->setDateCreate($user->getDateCreate());

        $form = $this->createFormBuilder($user)
            ->add('username', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
            ->add('password', TextType::class, array('attr' => array('class' => 'form-control', 'style' => 'margin-bottom:15px')))
//            ->add('dateCreate', DateTimeType::class, array('attr' => array('class' => 'formcontrol', 'style' => 'margin-bottom:15px')))
            ->add('save', SubmitType::class, array('label' => 'Update User', 'attr' => array('class' => 'btn btn-primary', 'style' => 'margin-bottom:15px')))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //Get Data
            $name = $form['username']->getData();
            $password = $form['password']->getData();
//            $dateCreate = $form['dateCreate']->getData();

            $now = new\DateTime('now');

            $em = $this->getDoctrine()->getManager();
            $user = $em->getRepository('AppBundle:User')->find($id);

            $user->setUsername($name);
            $user->setPassword($password);
            $user->setDateCreate($now);

            $em->flush();

            $this->addFlash(
                'notice',
                'User Updated'
            );

            return $this->redirectToRoute('users_list');
        }


        return $this->render('myweb/edit.html.twig', array(
            'user' => $user,
            'form' => $form->createView()
        ));
    }

    /**
     * @Route("/user/details/{id}", name="user_details")
     */
    public function detailsAction($id)
    {
        $user = $this->getDoctrine()->getRepository('AppBundle:User')->find($id);
        return $this->render('myweb/details.html.twig', array(
            'user' => $user
        ));
    }

    /**
     * @Route("/user/delete/{id}", name="user_delete")
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $user = $em->getRepository('AppBundle:User')->find($id);

        $em->remove($user);
        $em->flush();
        $this->addFlash(
            'notice',
            'User Removed'
        );

        return $this->redirectToRoute('users_list');
    }

}
