<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Symfony\Component\Ldap\Adapter\ExtLdap;

use Symfony\Component\Ldap\Adapter\CollectionInterface;
use Symfony\Component\Ldap\Entry;

/**
 * @author Kevin Schuurmans <kevin.schuurmans@freshheads.com>
 */
interface PaginatorInterface
{
    /**
     * Executes a paginated query and returns the list of Ldap entries per page.
     *
     * @return CollectionInterface|Entry[]
     */
    public function execute();

    /**
     * Returns the amount of successfully executed paginated queries
     *
     * @return integer
     */
    public function getCurrentPage();
}
