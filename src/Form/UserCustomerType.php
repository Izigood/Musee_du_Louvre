<?php

namespace App\Form;

use App\Entity\Customer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Form\DataTransformer\FrenchToDateTimeTransformer;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;

class UserCustomerType extends AbstractType
{

    public function __construct(FrenchToDateTimeTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //->add('firstname') //A supprimer
            //->add('lastname') //A supprimer
            ->add('country', TextType::class, ['label' => "Pays",
            'attr' => [
                'placeholder' => "Veuillez saisir votre pays"
            ],
            'required' => false
            ])
            ->add('dateOfBirthday', TextType::class, ["label" => "Date de naissance",
            'attr' => [
                'placeholder' => "Veuillez saisir votre date de naissance"
            ],
            'required' => false
            ])
            ->add('reducedPrice', CheckboxType::class, ['label' => "Tarif réduit", 'required' => false])
            ->add('ticketPrice', MoneyType::class, ['label' => "Prix du billet",
            'disabled' => true ,
            'attr' => [
             'placeholder' => "0,00 Euros" //A modifier
            ],
            'required' => false
         ])
        ;

        $builder->get('dateOfBirthday')->addModelTransformer($this->transformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Customer::class,
        ]);
    }
}
