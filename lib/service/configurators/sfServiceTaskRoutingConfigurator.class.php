<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * Class sfServiceTaskRoutingConfigurator
 *
 * @package    symfony
 * @subpackage service
 * @author     Ilya Sabelnikov <fruit.dev@gmail.com>
 * @version    SVN: $Id$
 */
class sfServiceTaskRoutingConfigurator
{
  /**
   * @var sfRoutingConfigHandler
   */
  protected $handler;

  /**
   * @var sfApplicationConfiguration
   */
  protected $configuration;

  /**
   * @var sfEventDispatcher
   */
  protected $dispatcher;

  /**
   * sfServiceTaskRoutingConfigurator constructor.
   *
   * @param sfApplicationConfiguration $configuration
   * @param sfRoutingConfigHandler     $handler
   * @param sfEventDispatcher          $dispatcher
   */
  public function __construct(
    sfApplicationConfiguration $configuration,
    sfRoutingConfigHandler $handler,
    sfEventDispatcher $dispatcher
  )
  {
    $this->configuration = $configuration;
    $this->handler       = $handler;
    $this->dispatcher    = $dispatcher;
  }

  /**
   * @param sfPatternRouting $routing
   */
  public function configure(sfPatternRouting $routing)
  {
    $routes = $this->handler->evaluate($this->configuration->getConfigPaths('config/routing.yml'));
    $routing->setRoutes($routes);

    $this->dispatcher->notify(new sfEvent($routing, 'routing.load_configuration'));
  }
}
