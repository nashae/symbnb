<?php
namespace App\Form\DataTransformer;

use DateTime;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class FrenchToDateTimeTransformer implements DataTransformerInterface
{
    public function transform($date)
    {
        if($date === null){
            return '';
        }
        return $date->format('d/m/Y');
    }

    public function reverseTransform($frenchDate)
    {
        if($frenchDate === null){
            throw new TransformationFailedException("vous devez fournir une date");
        }
        $date = DateTime::createFromFormat('d/m/Y', $frenchDate);
        if($date === false){
            throw new TransformationFailedException("format date incorrect");
        }
        return $date;
    }

}