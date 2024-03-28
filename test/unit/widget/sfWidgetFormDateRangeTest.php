<?php

/*
 * This file is part of the symfony package.
 * (c) Fabien Potencier <fabien.potencier@symfony-project.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

require_once __DIR__.'/../../bootstrap/unit.php';

class FormFormatterMock extends sfWidgetFormSchemaFormatter
{
    public $translateSubjects = [];

    public function __construct()
    {
    }

    public function translate($subject, $parameters = [])
    {
        $this->translateSubjects[] = $subject;

        return sprintf('translation[%s]', $subject);
    }
}

class WidgetFormStub extends sfWidget
{
    public function __construct()
    {
    }

    public function render($name, $value = null, $attributes = [], $errors = [])
    {
        return sprintf('##%s##', __CLASS__);
    }
}

$t = new lime_test(2);

// ->render()
$t->diag('->render()');

$ws = new sfWidgetFormSchema();
$ws->addFormFormatter('stub', $formatter = new FormFormatterMock());
$ws->setFormFormatterName('stub');
$w = new sfWidgetFormDateRange(['from_date' => new WidgetFormStub(), 'to_date' => new WidgetFormStub()]);
$w->setParent($ws);
$t->is($w->render('foo'), 'translation[from ##WidgetFormStub## to ##WidgetFormStub##]', '->render() remplaces %from_date% and %to_date%');
$t->is($formatter->translateSubjects, ['from %from_date% to %to_date%'], '->render() translates the template option');
