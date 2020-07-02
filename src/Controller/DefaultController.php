<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    CONST data = [
        [
            'albumId' => "1",
            "id" => 1,
            "title" => "accusamus beatae ad facilis cum similique qui sunt",
            "description" => "It is a long established fact that a reader will be distracted by the readable content of a page when looking at its layout"
        ],
        [
            'albumId' => "2",
            "id" => 2,
            "title" => "accusamus beatae ad facilis cum similique qui sunt",
            "description" => "Many desktop publishing packages and web page editors now use Lorem Ipsum as their default model text"
        ],
        [
            'albumId' => "3",
            "id" => 3,
            "title" => "accusamus beatae ad facilis cum similique qui sunt",
            "description" => "There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form"
        ],
    ];

    /**
     * @Route("/api/public", name="public")
     * @return JsonResponse
     */
    public function publicAction()
    {
        return new JsonResponse(self::data);
    }

    // /**
    //  * @Route("/api/{id}", name="get-post-by-id")
    //  */
    // public function postById($id)
    // {
    //     return new JsonResponse(self::data[array_search($id, \array_column(self::data, 'id'))]);
    // }

    /**
     * @Route("/api/{page}", name="get-post-by-id")
     */
    public function postById($page, Request $request)
    {
        // console.log($request->get('name'));
        return new JsonResponse(
            [
                "page" => $page,
                "name" => $request->get('name')
            ],
        );
    }

    /**
     * @Route("/product", name="create_product")
     */
    public function createSecretKey(): Response
    {
        // you can fetch the EntityManager via $this->getDoctrine()
        // or you can add an argument to the action: createProduct(EntityManagerInterface $entityManager)
        $entityManager = $this->getDoctrine()->getManager();

        $product = new Product();
        $product->setName('Keyboard');
        $product->setPrice(1999);
        $product->setDescription('Ergonomic and stylish!');

        // tell Doctrine you want to (eventually) save the Product (no queries yet)
        $entityManager->persist($product);

        // actually executes the queries (i.e. the INSERT query)
        $entityManager->flush();

        return new Response('Saved new product with id '.$product->getId());
    }
}