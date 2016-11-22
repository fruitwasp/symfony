<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Ldap\Adapter;

use Symfony\Component\Ldap\Adapter\ExtLdap\PaginatorInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * @author Kevin Schuurmans <kevin.schuurmans@freshheads.com>
 */
abstract class AbstractPaginator implements PaginatorInterface
{
    /** @var Options */
    protected $options;

    public function __construct(array $options = [])
    {
        $optionsResolver = new OptionsResolver();

        $this->configureOptions($optionsResolver);

        /** @var Options */
        $this->options = $optionsResolver->resolve($optionsResolver);
    }

    /**
     * @param OptionsResolver $optionsResolver
     */
    protected function configureOptions(OptionsResolver $optionsResolver)
    {
        $optionsResolver->setDefaults([
            'maxItems' => 20,
            'isCritical' => false
        ]);

        $optionsResolver->setAllowedTypes('maxItems', 'numeric');
        $optionsResolver->setAllowedTypes('isCritical', 'bool');
    }
}
