<?php

namespace App\Form;

use App\Entity\Voiture;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class VoitureType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom du véhicule',
                'attr' => [
                    'placeholder' => 'Exemple : Peugeot 208',
                ],
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => [
                    'placeholder' => 'Décrivez le véhicule',
                    'rows' => 5,
                ],
            ])
            ->add('dailyPrice', MoneyType::class, [
                'label' => 'Prix par jour',
                'currency' => 'EUR',
            ])
            ->add('monthlyPrice', MoneyType::class, [
                'label' => 'Prix par mois',
                'currency' => 'EUR',
            ])
            ->add('places', IntegerType::class, [
                'label' => 'Nombre de places',
                'attr' => [
                    'min' => 1,
                ],
            ])
            ->add('motor', TextType::class, [
                'label' => 'Motorisation',
                'attr' => [
                    'placeholder' => 'Exemple : Essence, Diesel, Électrique',
                ],
            ])
            ->add('image', FileType::class, [
                'label' => 'Photo du véhicule',
                'mapped' => false,
                'required' => true,
                'constraints' => [
                    new File(
                        maxSize: '5M',
                        mimeTypes: [
                            'image/jpeg',
                            'image/png',
                            'image/webp',
                        ],
                        mimeTypesMessage: 'Veuillez choisir une image JPG, PNG ou WEBP.',
                    ),
                ],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Voiture::class,
        ]);
    }
}
