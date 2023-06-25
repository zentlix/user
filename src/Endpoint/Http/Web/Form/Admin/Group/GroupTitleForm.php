<?php

declare(strict_types=1);

namespace Zentlix\User\Endpoint\Http\Web\Form\Admin\Group;

use Spiral\AdminPanel\Form\Type\TextType;
use Spiral\Symfony\Form\Attribute\FormType;
use Spiral\Symfony\Form\AbstractType;
use Spiral\Translator\TranslatorInterface;
use Symfony\Component\Form\FormBuilderInterface;
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
        $locales = $this->repository->findActive();

        foreach ($locales as $locale) {
            $language = \count($locales) > 1 ? \sprintf(' [%s]', $locale->title) : '';

            $builder->add('title', TextType::class, [
                'label' => $this->translator->trans('user.title') . $language
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(['data_class' => Title::class]);
    }
}
