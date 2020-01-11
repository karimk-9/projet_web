<?php 

namespace App\Form\DataTransformer;

use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class FrToDateTimeTransformer implements DataTransformerInterface{
    public function transform($date){
        if ($date===null){
            return '';
        }
        return $date->format('d/m/Y');
    }

    public function reverseTransform($date_fr){
        if ($date_fr===null){
           // Exception
           throw new TransformationFailedException("Date non fournie");
           
        }
        $date= \DateTime::createFromFormat('d/m/Y', $date_fr);
        
        if ($date === false ){
            //Exception
            throw new TransformationFailedException("Mauvais format");
        }
        return $date;
    }
}