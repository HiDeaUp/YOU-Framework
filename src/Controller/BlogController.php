<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\BlogPost;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\Serializer;

/**
 * @Route("/blog")
 */
class BlogController extends AbstractController
{
    /**
     * @Route("/list/{page}", name="blog_list", requirements={"page"="\d+"}, defaults={"page": 1})
     */
    public function list($page, Request $request): JsonResponse
    {
        $limit = $request->get('limit', 10);
        $items = $this->getDoctrine()->getRepository(BlogPost::class)->findAll();

        return new JsonResponse(
            [
                'page' => $page,
                'limit' => $limit,
                'data' => array_map(
                    function (BlogPost $item) {
                        return $this->generateUrl('blog_slug', ['slug' => $item->getSlug()]);
                    }, $items
                )
            ]
        );
    }

    /**
     * @Route("/{id}", name="blog_post", requirements={"id"="\d+"})
     * @ParamConverter("post", class="App:BlogPost")
     */
    public function post(BlogPost $post): JsonResponse
    {
        return $this->json($post);
    }

    /**
     * @Route("/{slug}", name="blog_slug")
     * @ParamConverter("post", class="App:BlogPost", options={"mapping": {"slug": "author"}})
     */
    public function postBySlug(BlogPost $post): JsonResponse
    {
        return $this->json($post);
    }

    /**
     * @Route("/add", name="blog_add", methods={"POST"})
     */
    public function add(Request $request): JsonResponse
    {
        /** @var Serializer $serializer */
        $serializer = $this->get('serializer');

        $blogPost = $serializer->deserialize($request->getContent(), BlogPost::class, 'json');

        $em = $this->getDoctrine()->getManager();
        $em->persist($blogPost);
        $em->flush();

        return $this->json($blogPost);
    }
}
