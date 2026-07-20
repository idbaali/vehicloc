<?php

namespace App\Form;

use App\Entity\Voiture;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VoitureType extends AbstractType
{
    public function buildForm(
        FormBuilderInterface $builder,
        array $options
    ): void {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom de la voiture',
                'attr' => [
                    'placeholder' => 'Exemple : Peugeot 208',
                ],
            ])

            ->add('description', TextareaType::class, [
                'label' => 'Description',
                'attr' => [
                    'placeholder' => 'Décrivez la voiture',
                    'rows' => 6,
                ],
            ])

            ->add('dailyPrice', MoneyType::class, [
                'label' => 'Prix par jour',
                'currency' => 'EUR',
                'attr' => [
                    'min' => 0.01,
                    'step' => 0.01,
                ],
            ])

            ->add('monthlyPrice', MoneyType::class, [
                'label' => 'Prix par mois',
                'currency' => 'EUR',
                'attr' => [
                    'min' => 0.01,
                    'step' => 0.01,
                ],
            ])

            ->add('places', IntegerType::class, [
                'label' => 'Nombre de places',
                'attr' => [
                    'min' => 1,
                    'max' => 9,
                ],
            ])

            ->add('manual', CheckboxType::class, [
                'label' => 'Boîte manuelle',
                'required' => false,
            ])

            ->add('submit', SubmitType::class, [
                'label' => 'Ajouter la voiture',
                'attr' => [
                    'class' => 'btn-add',
                ],
            ]);
    }

    public function configureOptions(
        OptionsResolver $resolver
    ): void {
        $resolver->setDefaults([
            'data_class' => Voiture::class,
        ]);
    }
}