<?php

declare(strict_types=1);

namespace Zentlix\User\Endpoint\Http\Web\Form\Admin\Group;

use Spiral\AdminPanel\Form\AbstractType;
use Spiral\AdminPanel\Form\Type\TextType;
use Spiral\Symfony\Form\Attribute\FormType;
use Spiral\Translator\TranslatorInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Zentlix\User\Domain\Group\DataTransferObject\Title;
use Zentlix\User\Domain\Locale\ReadModel\Repository\LocaleRepositoryInterface;

#[FormType]
final class GroupTitleForm extends AbstractType
{
    public function __construct(
        private readonly TranslatorInterface $translator,
        private readonly LocaleRepositoryInterface $repository
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, [$this, 'onPreSetData']);
    }

    public function onPreSetData(FormEvent $event): void
    {
        $locales = [];
        foreach ($this->repository->findActive() as $locale) {
            $locales[$locale->getId()] = $locale;
        }

        $title = $event->getData();
        $language = \sprintf(' [%s]', $locales[$title->getLocale()->toString()]->title);

        $event->getForm()->add('title', TextType::class, [
            'label' => $this->translator->trans('user.title') . $language,
            'label_html' => true,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Title::class]);
    }
}
