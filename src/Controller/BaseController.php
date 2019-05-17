<?php


namespace App\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Validator\ConstraintViolation;
use Symfony\Component\Validator\ConstraintViolationList;

class BaseController extends AbstractController
{

    protected function generateJsonErrors(ConstraintViolationList $errors)
    {
        $jsonError = [];
        foreach ($errors as  $error){
            /** @var ConstraintViolation $error */
            array_push($jsonError, [
                'message' => $error->getMessage(),
                'path' => $error->getPropertyPath(),
            ]);
        }

        return json_encode($jsonError);

    }
}