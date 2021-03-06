<?php
/*
 * This file is part of the Qimnet update tracker Bundle.
 *
 * (c) Antoine Guigan <aguigan@qimnet.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace Qimnet\UpdateTrackerBundle\CacheManager;

/**
 * Collection of defined cache repositories
 */

interface CacheRepositoriesInterface
{
    /**
     * Adds a repository to the collection
     *
     * @param string                   $name
     * @param CacheRepositoryInterface $repository
     */
    public function addRepository($name, CacheRepositoryInterface $repository);

    /**
     * Returns the repository corresponding to the given name
     *
     * @param  string                   $name
     * @return CacheRepositoryInterface
     */
    public function getRepository($name=false);

}
