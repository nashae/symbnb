<?php

namespace App\Form;

use App\Entity\Ad;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AnnonceType extends AbstractType
{
    
    /**
     * configuration de base des champs
     *
     * @param string $label
     * @param string $placeholder
     * @param array $options
     * @return array
     */
    private function getConfiguration($label, $placeholder, $options = [])
    {
        return array_merge([
            'label' => $label,
            'attr' => [
                'placeholder' => $placeholder
            ]
        ], $options);
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', TextType::class, $this->getConfiguration('Titre', 'Titre de l\'annonce'))
            ->add('slug', TextType::class, $this->getConfiguration('Adresse Web', "Choisissez une adresse web pour votre annonce (facultatif)", ["required" => false]))
            ->add('coverImage', UrlType::class, $this->getConfiguration("URL de l'image principale", "choisissez l'image principale de votre annonce"))
            ->add('introduction', TextType::class, $this->getConfiguration('Introduction', 'Phrase d\'accroche de votre annonce'))
            ->add('content', TextareaType::class, $this->getConfiguration('Description', 'Décrivez ici en détail votre bien aux futurs visiteurs de votre annonce'))
            ->add('rooms', IntegerType::class, $this->getConfiguration("Nombre de chambres", "Le nombre de chambres disponibles"))
            ->add('price', MoneyType::class, $this->getConfiguration('Prix par nuit', 'Indiquez le prix auquel vous souhaitez louer votre bien'))
            ->add('images', CollectionType::class,[
                'entry_type' => ImageType::class,
                'allow_add' => true, //crée un champ data_prototype dans la div annonces_images,contient template du form(champ url et caption)l'attribut name des elements du form prototype est __name__
                'allow_delete' => true
                ]);                   
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Ad::class,
        ]);
    }
}
