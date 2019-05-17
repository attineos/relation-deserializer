<?php

namespace App\Controller;

use App\Entity\Book;
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
 * @Route("/books")
 */
class BookController extends BaseController
{
    /**
     * @Route("", name="book_get_all", methods={"GET"})
     */
    public function getBooks(SerializerInterface $serializer, EntityManagerInterface $entityManager)
    {

        $books = $entityManager->getRepository(Book::class)->findAll();

        $json = $serializer->serialize($books, 'json', ['groups' => 'book']);

        return new Response($json, 200, [
            'Content-Type' => 'application/json',
        ]);
    }


    /**
     * @Route("/{id}", name="book_get", methods={"GET"})
     */
    public function getBook(Book $book, SerializerInterface $serializer)
    {

        $json = $serializer->serialize($book, 'json', ['groups' => 'book']);

        return new Response($json, 200, [
            'Content-Type' => 'application/json',
        ]);
    }

    /**
     * @Route("", name="book_create", methods={"POST"}, defaults={"_format"="json"})
     */
    public function createBook(Request $request, SerializerInterface $serializer, EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $data = $request->getContent();

        $newObject  =  $serializer->deserialize($data, Book::class, 'json', ['groups' => 'book']);

        /** @var ConstraintViolationList $errors */
        $errors = $validator->validate($newObject, null ,['groups' => 'book_validation']);

        if (count($errors) > 0) {

            return new Response($this->generateJsonErrors($errors), 400);
        }


        $entityManager->persist($newObject);
        $entityManager->flush();

        $json = $serializer->serialize($newObject, 'json', ['groups' => 'book']);

        return new Response($json, 200, [
            'Content-Type' => 'application/json',
        ]);
    }

    /**
     * @Route("/{id}", name="book_update", methods={"PUT"})
     */
    public function updateBook(Book $book, Request $request, SerializerInterface $serializer, ValidatorInterface $validator, EntityManagerInterface $entityManager)
    {
        $data = $request->getContent();

        $serializer->deserialize($data, Book::class, 'json',  ['object_to_populate' => $book, 'groups' => 'book']);

        /** @var ConstraintViolationList $errors */
        $errors = $validator->validate($book, null ,['groups' => 'book_validation']);

        if (count($errors) > 0) {

            return new Response($this->generateJsonErrors($errors), 400);
        }

        $entityManager->flush();

        $json = $serializer->serialize($book, 'json', ['groups' => 'book']);

        return new Response($json, 200, [
            'Content-Type' => 'application/json',
        ]);
    }

}
