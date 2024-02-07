<?php

/*
 * This file is part of the Symfony1 package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once __DIR__.'/../../bootstrap/unit.php';

class FormFormatterStub extends \sfWidgetFormSchemaFormatter
{
    public function __construct()
    {
    }

    public function translate($subject, $parameters = [])
    {
        return sprintf('translation[%s]', $subject);
    }
}

class WidgetFormStub extends \sfWidget
{
    public function __construct()
    {
    }

    public function render($name, $value = null, $attributes = [], $errors = [])
    {
        return sprintf('##%s##', __CLASS__);
    }
}

$t = new \lime_test(1);

$dom = new \DOMDocument('1.0', 'utf-8');
$dom->validateOnParse = true;

// ->render()
$t->diag('->render()');

$ws = new \sfWidgetFormSchema();
$ws->addFormFormatter('stub', new \FormFormatterStub());
$ws->setFormFormatterName('stub');
$w = new \sfWidgetFormFilterDate(['from_date' => new \WidgetFormStub(), 'to_date' => new \WidgetFormStub()]);
$w->setParent($ws);
$dom->loadHTML($w->render('foo'));
$css = new \sfDomCssSelector($dom);
$t->is($css->matchSingle('label[for="foo_is_empty"]')->getValue(), 'translation[is empty]', '->render() translates the empty_label option');
