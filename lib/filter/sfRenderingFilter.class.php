<?php

/*
 * This file is part of the symfony package.
 * (c) 2004-2006 Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

/**
 * sfRenderingFilter is the last filter registered for each filter chain. This
 * filter does the rendering.
 *
 * @author     Fabien Potencier <fabien.potencier@symfony-project.com>
 */
class sfRenderingFilter extends sfFilter
{
    /**
     * Executes this filter.
     *
     * @param sfFilterChain $filterChain the filter chain
     *
     * @throws sfInitializeException If an error occurs during view initialization
     * @throws sfViewException       If an error occurs while executing the view
     */
    public function execute($filterChain)
    {
        // execute next filter
        $filterChain->execute();

        // get response object
        $response = $this->context->getResponse();

        // hack to rethrow sfForm and|or sfFormField __toString() exceptions (see sfForm and sfFormField)
        if (sfForm::hasToStringException()) {
            throw sfForm::getToStringException();
        }
        if (sfFormField::hasToStringException()) {
            throw sfFormField::getToStringException();
        }

        // send headers + content
        if (sfView::RENDER_VAR != $this->context->getController()->getRenderMode()) {
            $response->send();
        }
    }
}
