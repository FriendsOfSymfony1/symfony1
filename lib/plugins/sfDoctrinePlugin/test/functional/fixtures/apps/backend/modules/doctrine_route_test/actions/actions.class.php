<?php

/*
 * This file is part of the Symfony1 package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * doctrine_route_test actions.
 *
 * @author     Your name here
 *
 * @version    SVN: $Id$
 */
class doctrine_route_testActions extends \sfActions
{
    public function executeIndex(\sfWebRequest $request)
    {
        try {
            $this->object = $this->getRoute()->getObjects();
        } catch (\Exception $e) {
            try {
                $this->object = $this->getRoute()->getObject();
            } catch (\Exception $e) {
                return \sfView::NONE;
            }
        }
    }
}
