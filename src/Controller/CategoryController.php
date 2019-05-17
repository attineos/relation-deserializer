<?php

namespace App\Controller;

use App\Entity\Category;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/categories")
 */
class CategoryController extends BaseController
{
    /**
     * @Route("", name="category_get_all", methods={"GET"})
     */
    public function getCategories(SerializerInterface $serializer, EntityManagerInterface $entityManager)
    {

        $categories = $entityManager->getRepository(Category::class)->findAll();

        $json = $serializer->serialize($categories, 'json', ['groups' => 'category']);

        return new Response($json, 200, [
            'Content-Type' => 'application/json',
        ]);
    }


    /**
     * @Route("/{id}", name="category_get", methods={"GET"})
     */
    public function getCategory(Category $category, SerializerInterface $serializer)
    {

        $json = $serializer->serialize($category, 'json', ['groups' => 'category']);

        return new Response($json, 200, [
            'Content-Type' => 'application/json',
        ]);
    }

    /**
     * @Route("", name="category_create", methods={"POST"}, defaults={"_format"="json"})
     */
    public function createCategory(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $data = $request->getContent();

        $newObject = $serializer->deserialize($data, Category::class, 'json', ['groups' => 'category']);

        /** @var ConstraintViolationList $errors */
        $errors = $validator->validate($newObject, null ,['groups' => 'category_validation']);

        if (count($errors) > 0) {
            return new Response($this->generateJsonErrors($errors), 400);
        }

        $entityManager->persist($newObject);
        $entityManager->flush();

        $json = $serializer->serialize($newObject, 'json', ['groups' => 'category']);

        return new Response($json, 200, [
            'Content-Type' => 'application/json',
        ]);
    }

    /**
     * @Route("/{id}", name="category_update", methods={"PUT"})
     */
    public function updateCategory(Category $category, Request $request, SerializerInterface $serializer, ValidatorInterface $validator, EntityManagerInterface $entityManager)
    {
        $data = $request->getContent();

        $serializer->deserialize($data, Category::class, 'json',  ['object_to_populate' => $category, 'groups' => 'category']);

        /** @var ConstraintViolationList $errors */
        $errors = $validator->validate($category, null ,['groups' => 'category_validation']);

        if (count($errors) > 0) {
            return new Response($this->generateJsonErrors($errors), 400);
        }

        $entityManager->flush();

        $json = $serializer->serialize($category, 'json', ['groups' => 'category']);

        return new Response($json, 200, [
            'Content-Type' => 'application/json',
        ]);
    }
}
