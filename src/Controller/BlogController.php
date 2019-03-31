<?php

declare(strict_types=1);

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/blog")
 */
class BlogController extends AbstractController
{
    private const POSTS = [
        [
            'id' => 1,
            'slug' => 'hello-world',
            'title' => 'Hello World'
        ],
        [
            'id' => 2,
            'slug' => 'another-post',
            'title' => 'Second Example'
        ],
        [
            'id' => 3,
            'slug' => 'last-example',
            'title' => 'This is the last  one'
        ]
    ];

    /**
     * @Route("/list/{page}", name="blog_list", requirements={"id"="\d+"}, defaults={"page": 1})
     */
    public function list($page)
    {
        return new JsonResponse(
            [
                'page' => $page,
                'data' => array_map(
                    function ($item) {
                        return $this->generateUrl('blog_post', ['id' => $item['id']]);
                    }, self::POSTS
                )
            ]
        );
    }

    /**
     * @Route("/{id}", name="blog_post", requirements={"id"="\d+"})
     */
    public
    function post($id)
    {
        return new JsonResponse(
            self::POSTS[array_search($id, array_column(self::POSTS, 'id'))]
        );
    }

    /**
     * @Route("/{slug}", name="blog_slug")
     */
    public
    function postBySlug($slug)
    {
        return new JsonResponse(
            self::POSTS[array_search($slug, array_column(self::POSTS, 'slug'))]
        );
    }
}
