<?php

namespace App\Form;

use App\Entity\Residents;
use App\Validator\Constraints\DateRange;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\NotNull;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Validator\Constraints\GreaterThanOrEqual;
// use Symfony\Component\Validator\Constraints;


class otvType extends AbstractType
{
    /**
     * @return void
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('civility', ChoiceType::class, [
                'label' => 'Civilité',
                'required' => false,
                'attr' => ['class' => 'form-control'], 
                'choices' => [
                    'M.' => 'M.',
                    'Mme' => 'Mme',
                    'Autre' => 'Autre',
                ],
                'placeholder' => 'Choissisez une civilité',

            ])
            ->add('otherCivility', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'class' => 'form-control mt-2', 
                    'id' => 'otherCivility', 
                    'style' => 'display: none;',
                    'placeholder' => 'Ajouter une civilité',
                ]
            ])
            ->add('lastname', TextType::class, [
                'label' => 'Nom',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez saisir votre nom.'])
                ],
            ])
            ->add('firstname', TextType::class, [
                'label' => 'Prénom',
                'required' => true,
                'attr' => [
                    'class' => 'form-control'
                ],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez saisir votre prénom.'])
                ]
            ])
            ->add('district', ChoiceType::class, [
                'label' => 'Quartier',
                'choices' => [
                    'Belles Terres' => 'Belles Terres',
                    'Bourg-Centre Ville' => 'Bourg-Centre Ville',
                    'Briqueterie' => 'Briqueterie',
                    'Buisson/May-Four/Pellevoisin' => 'Buisson/May-Four/Pellevoisin',
                    'Croisé-Laroche/Rouges-Barres' => 'Croisé-Laroche/Rouges-Barres',
                    'Mairie / Hippodrome' => 'Mairie / Hippodrome',
                    'Plouich/Clemenceau/Calmette' => 'Plouich/Clemenceau/Calmette',
                    'Pont/Monplaisir' => 'Pont/Monplaisir',
                ],
                'required' => true,
                'placeholder' => 'Veuillez sélectionner un quartier',
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez choisir une option.'])
                ]
            ])
            ->add('street', TextType::class, [
                'label' => 'Rue',
                'required' => true,
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez saisir votre rue.'])
                ]
            ])
            ->add('streetNumber', TextType::class, [
                'label' => 'Numéro de rue',
                'required' => false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('additionalStreetNumber', TextType::class, [
                'label' => 'Complément du numéro de rue',
                'required' => false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('additionalAddressInfo', TextType::class, [
                'label' => 'Informations complémentaires sur l\'adresse',
                'required' => false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('mobilePhone', TelType::class, [
                'label' => 'Téléphone mobile',
                'required' => true,
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez saisir votre numéro de téléphone.']),
                    new Regex([
                        //Le pattern prends en compte différents format de numéro de téléphone français: 0123456789, 01 23 45 67, 89 01.23.45.67.89, 0123 45.67.89, 0033 123-456-789, +33-1.23.45.67.89, +33 - 123 456 789, +33(0) 123 456 789, +33 (0)123 45 67 89, +33 (0)1 2345-6789, +33(0) - 123456789
                        'pattern' => '/^(?:(?:\+|00)33[\s.-]{0,3}(?:\(0\)[\s.-]{0,3})?|0)[1-9](?:(?:[\s.-]?\d{2}){4}|\d{2}(?:[\s.-]?\d{3}){2})$/',
                        'message' => 'Veuillez entrer un numéro de téléphone mobile valide.'
                    ])
                ]
            ])
            ->add('landlinePhone', TelType::class, [
                'label' => 'Téléphone fixe',
                'required' => false,
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new Regex([
                        'pattern' => '/^(?:(?:\+|00)33[\s.-]{0,3}(?:\(0\)[\s.-]{0,3})?|0)[1-9](?:(?:[\s.-]?\d{2}){4}|\d{2}(?:[\s.-]?\d{3}){2})$/',
                        'message' => 'Veuillez entrer un numéro de téléphone mobile valide.'
                    ])
                ]
            ])
            ->add('email', EmailType::class, [
                'label' => 'Courriel',
                'required' => true,
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez saisir votre courriel.']),
                    new Email(['message' => 'Veuillez entrer une adresse email valide.']),
                ]
            ])
            ->add('authorization', CheckboxType::class, [
                'label' => 'Le Propriétaire autorise la Police Municipale à pénétrer sur sa propriété dès qu\'elle le jugera utile',
                'data' => true,
                'constraints' => new IsTrue([
                    'message' => 'Veuillez cocher cette case pour bénéficier de l\'Opération Tranquillité Vacances.',
                ]),
            ])
            ->add('houseType', ChoiceType::class, [
                'label' => 'Type de Logement',
                'choices' => [
                    'Maison Mitoyenne' => 'Maison Mitoyenne',
                    'Maison Individuelle' => 'Maison Individuelle',
                    'Maison Semi-mitoyenne' => 'Maison Semi-mitoyenne',
                    'Commerce' => 'Commerce'
                ],
                'required' => true,
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez sélectionner un type de logement'])
                ],
                'placeholder' => 'Veuillez sélectionner un type de logement',
                'attr' => ['class' => 'form-control']
            ])
            ->add('hasAlarm', ChoiceType::class, [
                'label' => 'Le logement possède-t-il une alarme ?',
                'choices' => [
                    'Oui' => true,
                    'Non' => false
                ],
                'expanded' => true,
                'multiple' => false,
                'required' => true,
                'attr' => ['class' => 'form-check form-check-inline'],
                'constraints' => [
                    new NotNull(['message' => 'Veuillez sélectionner une option.'])
                ],
            ])
            ->add('hasAlarmExt', ChoiceType::class, [
                'label' => 'Le logement possède-t-il une alarme extérieure ?',
                'choices' => [
                    'Oui' => true,
                    'Non' => false
                ],
                'expanded' => true,
                'multiple' => false,
                'required' => true,
                'attr' => ['class' => 'form-check form-check-inline'],
                'constraints' => [
                    new NotNull(['message' => 'Veuillez sélectionner une option.'])
                ],
            ])
            ->add('hasCamera', ChoiceType::class, [
                'label' => 'Le logement possède-t-il des caméras ?',
                'choices' => [
                    'Oui' => true,
                    'Non' => false
                ],
                'expanded' => true,
                'multiple' => false,
                'required' => true,
                'attr' => ['class' => 'form-check form-check-inline'],
                'constraints' => [
                    new NotNull(['message' => 'Veuillez sélectionner une option.'])
                ],
            ])
            ->add('hasAnimal', ChoiceType::class, [
                'label' => 'Y a-t-il des animaux dans le logement ?',
                'choices' => [
                    'Oui' => true,
                    'Non' => false
                ],
                'expanded' => true,
                'multiple' => false,
                'required' => true,
                'attr' => ['class' => 'form-check form-check-inline'],
                'constraints' => [
                    new NotNull(['message' => 'Veuillez sélectionner une option.'])
                ],
            ])
            ->add('blindsSchedule', TimeType::class, [
                'label' => 'Horaire de programmation automatique des volets',
                'required' => false,
                'input' => 'string',
                'attr' => ['class' => 'form-control']
            ])
            ->add('lightsSchedule', TimeType::class, [
                'label' => 'Horaire de programmation automatique des éclairages',
                'required' => false,
                'input' => 'string',
                'attr' => ['class' => 'form-control']
            ])
            ->add('car', TextType::class, [
                'label' => 'Immatriculation ou marque et couleur du véhicule devant le garage',
                'required' => false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('additionalInfo', TextareaType::class, [
                'label' => 'Informations supplémentaires',
                'required' => false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('authorizedPersons', TextType::class, [
                'label' => 'Nom et prénom des personnes susceptibles de passer dans l’habitation',
                'required' => false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('start_Date', DateType::class, [
                'label' => 'Date de Début',
                'required' => true,
                'attr' => ['class' => 'form-control', 'id' => 'start_Date'],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez sélectionner une date de départ.']),
                    // new GreaterThanOrEqual('today')
                ]
            ])

            ->add('end_Date', DateType::class, [
                'label' => 'Date de Fin',
                'required' => true,
                'attr' => ['class' => 'form-control', 'id' => 'end_Date'],
                'constraints' => [
                    new NotBlank(['message' => 'Veuillez saisir la date de retour.']),
                    // new DateRange(),
                ]
            ])

            ->add('emergency_civility_1', TextType::class, [
                'label' => 'Civilité',
                'required' => false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('emergency_lastname_1', TextType::class, [
                'label' => 'Nom',
                'required' => false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('emergency_firstname_1', TextType::class, [
                'label' => 'Prénom',
                'required' => false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('emergency_mobilePhone_1', TelType::class, [
                'label' => 'Téléphone mobile',
                'required' => false,
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new Regex([
                        'pattern' => '/^(0|\+33)[1-9]( *[0-9]{2}){4}$/',
                        'message' => 'Veuillez entrer un numéro de téléphone mobile valide.'
                    ])
                ]
            ])
            ->add('emergency_landlinePhone_1', TelType::class, [
                'label' => 'Téléphone fixe',
                'required' => false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('emergency_email_1', EmailType::class, [
                'label' => 'Courriel',
                'required' => false,
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new Regex([
                        'pattern' => '/^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/',
                        'message' => 'Veuillez entrer une adresse email valide.'
                    ])
                ]
            ])

            ->add('emergency_civility_2', TextType::class, [
                'label' => 'Civilité',
                'required' => false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('emergency_lastname_2', TextType::class, [
                'label' => 'Nom',
                'required' => false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('emergency_firstname_2', TextType::class, [
                'label' => 'Prénom',
                'required' => false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('emergency_mobilePhone_2', TelType::class, [
                'label' => 'Téléphone mobile',
                'required' => false,
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new Regex([
                        'pattern' => '/^(0|\+33)[1-9]( *[0-9]{2}){4}$/',
                        'message' => 'Veuillez entrer un numéro de téléphone mobile valide.'
                    ])
                ]
            ])
            ->add('emergency_landlinePhone_2', TelType::class, [
                'label' => 'Téléphone fixe',
                'required' => false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('emergency_email_2', EmailType::class, [
                'label' => 'Courriel',
                'required' => false,
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new Regex([
                        'pattern' => '/^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/',
                        'message' => 'Veuillez entrer une adresse email valide.'
                    ])
                ]
            ])

            ->add('emergency_civility_3', TextType::class, [
                'label' => 'Civilité',
                'required' => false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('emergency_lastname_3', TextType::class, [
                'label' => 'Nom',
                'required' => false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('emergency_firstname_3', TextType::class, [
                'label' => 'Prénom',
                'required' => false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('emergency_mobilePhone_3', TelType::class, [
                'label' => 'Téléphone mobile',
                'required' => false,
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new Regex([
                        'pattern' => '/^(0|\+33)[1-9]( *[0-9]{2}){4}$/',
                        'message' => 'Veuillez entrer un numéro de téléphone mobile valide.'
                    ])
                ]
            ])
            ->add('emergency_landlinePhone_3', TelType::class, [
                'label' => 'Téléphone fixe',
                'required' => false,
                'attr' => ['class' => 'form-control']
            ])
            ->add('emergency_email_3', EmailType::class, [
                'label' => 'Courriel',
                'required' => false,
                'attr' => ['class' => 'form-control'],
                'constraints' => [
                    new Regex([
                        'pattern' => '/^[\w-\.]+@([\w-]+\.)+[\w-]{2,4}$/',
                        'message' => 'Veuillez entrer une adresse email valide.'
                    ])
                ]
            ])

            ->add('file', FileType::class, [
                'label' => 'Fichier',
                'attr' => [
                    'class' => 'form-control',
                    'id' => 'file',
                    'accept' => '.pdf, .jpeg, .png, .gif, .svg'
                ],
                'constraints' => [
                    new File([
                        'maxSize' => '1024k',
                        'mimeTypes' => [
                            'application/pdf',
                            'application/x-pdf',
                            'image/jpeg',
                            'image/png',
                            'image/gif',
                            'image/svg+xml'
                        ],
                        'mimeTypesMessage' => 'Veuillez sélectionner un fichier PDF, JPEG, PNG, GIF ou SVG valide.'
                    ]),
                    new NotBlank(['message' => 'Veuillez sélectionner un fichier.'])
                ]
            ])
           ->add('rgpd', CheckboxType::class, [
                'label' => 'J\'accepte que mes données soient utilisées pour le traitement de ma demande conformément à la politique de confidentialité du site.',
                'data' => true,
                'constraints' => new IsTrue([
                    'message' => 'Veuillez cocher cette case pour bénéficier de l\'Opération Tranquillité Vacances.',
                ]), 
            ])
            ->add('latitude', HiddenType::class)
            ->add('longitude', HiddenType::class)
            ;
    }

    /**
     * @return void
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => true,
        ]);
    }

    /**
     * @return string
     */
    public function getBlockPrefix()
    {
        return '';
    }
}
