<?php
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

/*
 * route et controlleur de la home pour import csv
 */
$app->match('/', function(Request $request) use ($app){
    
    $msg = null;
    /*
     * creation du formulaire (quasiment vide juste pour mettre sur ecouter le bouton submit)
     */
    $form = $app['form.factory']->createBuilder(FormType::class)
            ->add('envoi', SubmitType::class, array(
                'label' => 'Insérer le CSV en base'
            ))
            ->getForm();
    
    $form->handleRequest($request);
    
    if($form->isSubmitted()){
        
        $msg = $app['services.tickets']->insertCsv();
        
    }
    
    return $app['twig']->render('index.twig', array(
        'form' => $form->createView(),
        'msg' => $msg
    ));
    
})->bind('home');

/*
 * route et controlleur des top 10
 */
$app->get('/top10', function() use ($app){
    
    $result = $app['dao.tickets']->getDataFactures();
    
    return $app['twig']->render('top10.twig', array(
        'result' => $result
    ));
    
})->bind('top10');


/*
 * route et controlleur du total des sms et appels
 */
$app->get('/appel-sms', function() use ($app){
    
    $resultSms = $app['dao.tickets']->getSms();
    $resultAppel = $app['dao.tickets']->getAppel();
    
    return $app['twig']->render('sms-appel.twig', array(
        'resultSms' => $resultSms,
        'resultAppel' => $resultAppel
    ));
    
})->bind('appel_sms');