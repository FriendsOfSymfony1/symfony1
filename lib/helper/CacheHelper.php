<?php

/*
 * This file is part of the symfony package.
 * (c) 2004-2006 Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * CacheHelper.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * @version    SVN: $Id$
 *
 * @param mixed $name
 * @param mixed $lifeTime
 */

/* Usage

<?php if (!cache('name')): ?>

... HTML ...

  <?php cache_save() ?>
<?php endif; ?>

*/
function cache($name, $lifeTime = 86400)
{
    if (!sfConfig::get('sf_cache')) {
        return null;
    }

    $cache = sfContext::getInstance()->getViewCacheManager();

    if (sfConfig::get('symfony.cache.started')) {
        throw new sfCacheException('Cache already started.');
    }

    $data = $cache->start($name, $lifeTime);

    if (null === $data) {
        sfConfig::set('symfony.cache.started', true);
        sfConfig::set('symfony.cache.current_name', $name);

        return false;
    }

    echo $data;

    return true;
}

function cache_save()
{
    if (!sfConfig::get('sf_cache')) {
        return null;
    }

    if (!sfConfig::get('symfony.cache.started')) {
        throw new sfCacheException('Cache not started.');
    }

    $data = sfContext::getInstance()->getViewCacheManager()->stop(sfConfig::get('symfony.cache.current_name', ''));

    sfConfig::set('symfony.cache.started', false);
    sfConfig::set('symfony.cache.current_name', null);

    echo $data;
}
