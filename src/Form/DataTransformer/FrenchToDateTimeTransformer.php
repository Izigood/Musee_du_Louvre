<?php

namespace App\Form\DataTransformer;

//use DateTimeZone;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Form\Exception\TransformationFailedException;


class FrenchToDateTimeTransformer implements DataTransformerInterface {

    public function transform($date)
    {
        if ($date === null)
        {
            return '';
        }
        
        return $date->format('d/m/Y');
    }

    public function reverseTransform($frenchDate)
    {
        if ($frenchDate === null)
        {
            throw new TransformationFailedException("Vous devez fournir une date !");
        }
        
        $date = \DateTime::createFromFormat('d/m/Y', $frenchDate, new \DateTimeZone('Europe/Paris'));

        if ($date === false)
        {
            throw new TransformationFailedException("Le format de la date n'est pas correct !");
        }

        return $date;
    }
}