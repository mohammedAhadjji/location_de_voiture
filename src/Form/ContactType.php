<?php
namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;

class ContactType extends AbstractType
{
    private TranslatorInterface $translator;
    private RequestStack $requestStack;

    public function __construct(TranslatorInterface $translator, RequestStack $requestStack)
    {
        $this->translator = $translator;
        $this->requestStack = $requestStack;
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $session = $this->requestStack->getCurrentRequest()->getSession();
        $locale = $session->get('_locale', 'en');

        $builder
            ->add('visitor_name', TextType::class, [
                'label' => $this->translator->trans('contact.form.name', [], null, $locale),
                'attr' => ['class' => 'form-control'],
            ])
            ->add('visitor_email', EmailType::class, [
                'label' => $this->translator->trans('contact.form.email', [], null, $locale),
                'attr' => ['class' => 'form-control'],
            ])
            ->add('visitor_phone', TextType::class, [
                'label' => $this->translator->trans('contact.form.phone', [], null, $locale),
                'required' => false,
                'attr' => ['class' => 'form-control'],
            ])
            ->add('visitor_message', TextareaType::class, [
                'label' => $this->translator->trans('contact.form.message', [], null, $locale),
                'attr' => [
                    'class' => 'form-control h-200',
                    'cols' => 30,
                    'rows' => 10,
                ],
            ])
            ->add('send', SubmitType::class, [
                'label' => $this->translator->trans('contact.form.submit', [], null, $locale),
                'attr' => ['class' => 'btn btn-primary mt_10'],
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}