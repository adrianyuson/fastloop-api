<?php

namespace App\Controller;

// header("Access-Control-Allow-Origin: *");
// header('Access-Control-Allow-Methods: GET, POST');
// header("Access-Control-Allow-Headers: X-Requested-With");

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\SecretKey;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use DateTime;
use DateTimeZone;
use DateInterval;

class SecretKeyController extends AbstractController
{
    private function generateHexString($seed, $length) 
    {
        if($length > 12) {
            return substr(sha1($seed), 0, $length);
        }
        return substr(md5($seed), 0, $length);
    }

    /**
     * @Route("/refresh-secret-keys", name="refresh_secret_keys")
     */
    public function refreshSecretKeys()
    {
        $entityManager = $this->getDoctrine()->getManager();
        $secretKeys = $entityManager->getRepository(SecretKey::class)->findAll();

        if (!$secretKeys) {
            throw $this->createNotFoundException(
                'List is empty'
            );
        }
        $jsonResponse = [];
        foreach($secretKeys as $entry) {
            $newExpiry = $entry->getExpiry();
            $newExpiry->add(new DateInterval('PT24H'));
            $entry->setExpiry($newExpiry);
            array_push($jsonResponse, $entry->getExpiry());
        }

        $entityManager->flush();
        $entityManager->clear();
        
        return new Response('Refreshed all secret keys!');
    }

    /**
     * @Route("/get-all-secret-keys", name="get_all_secret_keys")
     */
    public function getAllSecretKeys()
    {
        $repository = $this->getDoctrine()->getRepository('App:SecretKey');
        $data = $repository->findAll();
        $jsonResponse = [];
        foreach($data as $entry) {
            array_push($jsonResponse, 
                    [
                        $entry->getId(),
                        $entry->getName(), 
                        $entry->getDescription(),
                        $entry->getExpiry(),
                        substr($entry->getSecret(), 0, 4) . 
                        '************************' .
                        substr($entry->getSecret(), 27, 4)
                    ]
            );
        }
        return $this->json($jsonResponse);
    }

    /**
     * @Route("/create-secret-key", name="create_secret_key")
     */
    public function createSecretKey()
    {
        $expiry = new DateTime();
        $timezone = new DateTimeZone('America/Los_Angeles');
        $expiry->setTimeZone($timezone);
        $expiry->add(new DateInterval('PT24H'));

        $jsonResponse = 
            [
                $this->generateHexString(time(), 12),
                '---',
                $expiry,
                $this->generateHexString(time(), 36)
            ];
        return $this->json($jsonResponse);
    }

    /**
     * @Route("/insert-secret-key", name="insert_secret_key")
     */
    public function insertSecretKey(Request $request)
    {
        $request = Request::createFromGlobals();
        $parameters = json_decode($request->getContent(), true);
        $entityManager = $this->getDoctrine()->getManager();

        $secretKey = new SecretKey();
        $secretKey->setName($parameters['name']);
        $secretKey->setDescription($parameters['description']);
        $secretKey->setExpiry(new DateTime($parameters['expiry']['date']));
        $secretKey->setSecret($parameters['secret']);

        // tell Doctrine you want to (eventually) save the SecretKey (no queries yet)
        $entityManager->persist($secretKey);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();
        
        return new Response($secretKey->getName() . 'successfully added to the database');
    }
    
    /**
     * @Route("/secret-key/{name}", name="secret_key_show")
     */
    public function show($name)
    {
        $secretKey = $this->getDoctrine()
            ->getRepository(SecretKey::class)
            ->findBy($name);

        // if (!$secretKey) {
        //     throw $this->createNotFoundException(
        //         'No key found.... ' . $name
        //     );
        // }

        // $repository = $this->getDoctrine()->getRepository(SecretKey::class);

        // $secretKey = $repository->findBy($name);

        if (!$secretKey) {
            throw $this->createNotFoundException(
                'No key found.... ' . $name
            );
        }

        return new Response('Check out this great secretKey: '.$secretKey->getName());
    }
}
