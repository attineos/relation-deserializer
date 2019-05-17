<?php

namespace App\Controller;

use App\Entity\Author;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * @Route("/authors")
 */
class AuthorController extends BaseController
{
    /**
     * @Route("/", name="author_get_all", methods={"GET"})
     */
    public function getAuthors(SerializerInterface $serializer, EntityManagerInterface $entityManager)
    {
        $books = $entityManager->getRepository(Author::class)->findAll();

        $json = $serializer->serialize($books, 'json', ['groups' => 'author']);

        return new Response($json, 200, [
            'Content-Type' => 'application/json',
        ]);
    }


    /**
     * @Route("/{id}", name="author_get", methods={"GET"})
     */
    public function getAuthor(Author $author, SerializerInterface $serializer)
    {

        $json = $serializer->serialize($author, 'json', ['groups' => 'author']);

        return new Response($json, 200, [
            'Content-Type' => 'application/json',
        ]);
    }

    /**
     * @Route("", name="author_create", methods={"POST"}, defaults={"_format"="json"})
     */
    public function createAuthor(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $data = $request->getContent();

        $newObject  =  $serializer->deserialize($data, Author::class, 'json', ['groups' => 'author']);

        /** @var ConstraintViolationList $errors */
        $errors = $validator->validate($newObject, null ,['groups' => 'author_validation']);

        if (count($errors) > 0) {

            return new Response($this->generateJsonErrors($errors), 400);
        }


        $entityManager->persist($newObject);
        $entityManager->flush();

        $json = $serializer->serialize($newObject, 'json', ['groups' => 'author']);

        return new Response($json, 200, [
            'Content-Type' => 'application/json',
        ]);
    }

    /**
     * @Route("/{id}", name="author_update", methods={"PUT"})
     */
    public function updateAuthor(Author $author, Request $request, SerializerInterface $serializer, ValidatorInterface $validator, EntityManagerInterface $entityManager)
    {
        $data = $request->getContent();

        $serializer->deserialize($data, Author::class, 'json',  ['object_to_populate' => $author, 'groups' => 'author']);

        /** @var ConstraintViolationList $errors */
        $errors = $validator->validate($author, null ,['groups' => 'author_validation']);

        if (count($errors) > 0) {

            return new Response($this->generateJsonErrors($errors), 400);
        }

        $entityManager->flush();

        $json = $serializer->serialize($author, 'json', ['groups' => 'author']);

        return new Response($json, 200, [
            'Content-Type' => 'application/json',
        ]);
    }

}
