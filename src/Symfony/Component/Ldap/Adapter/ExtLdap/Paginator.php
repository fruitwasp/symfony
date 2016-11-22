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

use Symfony\Component\Ldap\Adapter\AbstractPaginator;
use Symfony\Component\Ldap\Entry;
use Symfony\Component\Ldap\Exception\LdapException;

/**
 * @author Kevin Schuurmans <kevin.schuurmans@freshheads.com>
 */
class Paginator extends AbstractPaginator
{
    /** @var Connection */
    private $connection;

    /** @var Query */
    private $query;

    /** @var string */
    private $cookie;

    /** @var integer */
    private $currentPage;

    public function __construct(Connection $connection, Query $query, array $options = [])
    {
        parent::__construct($options);

        $this->connection = $connection;
        $this->query = $query;
        $this->cookie = '';
        $this->currentPage = 0;
    }

    /**
     * {@inheritdoc}
     */
    public function execute()
    {
        // If the connection is not bound, then we try an anonymous bind.
        if (!$this->connection->isBound()) {
            $this->connection->bind();
        }

        if ($this->cookie === null || $this->cookie === '') {
            throw new LdapException('Could not retrieve any more LDAP entry pages');
        }

        $success = ldap_control_paged_result(
            $this->connection->getResource(),
            $this->options['maxItems'],
            $this->options['isCritical'],
            $this->cookie
        );

        if ($success === false) {
            throw new LdapException('Could not send LDAP pagination control');
        }

        /** @var Collection */
        $collection = $this->query->execute();
        $this->currentPage++;

        $success = ldap_control_paged_result_response(
            $this->connection->getResource(),
            $this->query->getResource(),
            $this->cookie
        );

        if ($success === false) {
            throw new LdapException('Could not retrieve LDAP pagination cookie');
        }

        return $collection;
    }

    /**
     * {@inheritdoc}
     */
    public function getCurrentPage()
    {
        return $this->currentPage;
    }
}
