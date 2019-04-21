<?php

namespace App\Form;

use App\Entity\Customer;
use App\Entity\OrderCustomer;
use App\Form\CustomerUserType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use App\Form\DataTransformer\FrenchToDateTimeTransformer;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class OrderCustomerType extends AbstractType
{
    private $transformer;
    
    public function __construct(FrenchToDateTimeTransformer $transformer)
    {
        $this->transformer = $transformer;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            
            ->add('dateOfVisit', TextType::class,   ["label" => "Date de visite",
                                                        'attr' => [
                                                            'placeholder' => "Veuillez saisir la date de votre visite",
                                                        ],
                                                        'required' => false
                                                    ])        
        
             ->add('numberOfTickets', IntegerType::class,   ['label' => "Nombre de billets",
                                                                'attr' => [
                                                                    'placeholder' => "Veuillez saisir le nombre de billets à commander"
                                                                ],
                                                                'required' => false
                                                            ])

            ->add('halfDay', CheckboxType::class,   ['label' => "Demi-journée (à partir de 14 heures)", 'required' => false])

            ->add('firstname', TextType::class,     ['label' => "Prénom",
                                                        'attr' => [
                                                            'placeholder' => "Veuillez saisir votre prénom"
                                                        ],
                                                        'required' => false
                                                    ])

            ->add('lastname', TextType::class,  ['label' => "Nom",
                                                    'attr' => [
                                                        'placeholder' => "Veuillez saisir votre nom"
                                                    ],
                                                    'required' => false
                                                ])

            ->add('email', TextType::class,     ['label' => "Email",
                                                    'attr' => [
                                                        'placeholder' => "Veuillez saisir votre email"
                                                    ],
                                                'required' => false

                                                ])
        ;
        
        $builder->get('dateOfVisit')->addModelTransformer($this->transformer);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => OrderCustomer::class,
        ]);
    }
}
