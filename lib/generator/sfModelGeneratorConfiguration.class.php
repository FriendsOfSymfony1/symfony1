<?php

/*
 * This file is part of the Symfony1 package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

abstract class sfModelGeneratorConfiguration
{
    /** @var sfModelGeneratorConfigurationField[][][] */
    protected $configuration = [];

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->compile();
    }

    abstract public function getActionsDefault();

    abstract public function getFormActions();

    abstract public function getNewActions();

    abstract public function getEditActions();

    abstract public function getListObjectActions();

    abstract public function getListActions();

    abstract public function getListBatchActions();

    abstract public function getListParams();

    abstract public function getListLayout();

    abstract public function getListTitle();

    abstract public function getEditTitle();

    abstract public function getNewTitle();

    abstract public function getFilterDisplay();

    abstract public function getFormDisplay();

    abstract public function getNewDisplay();

    abstract public function getEditDisplay();

    abstract public function getListDisplay();

    abstract public function getFieldsDefault();

    abstract public function getFieldsList();

    abstract public function getFieldsFilter();

    abstract public function getFieldsForm();

    abstract public function getFieldsEdit();

    abstract public function getFieldsNew();

    abstract public function getFormClass();

    abstract public function hasFilterForm();

    abstract public function getFilterFormClass();

    /**
     * @param string        $context
     * @param string[]|null $fields
     *
     * @return array|sfModelGeneratorConfigurationField[]
     */
    public function getContextConfiguration($context, $fields = null)
    {
        if (!isset($this->configuration[$context])) {
            throw new \InvalidArgumentException(sprintf('The context "%s" does not exist.', $context));
        }

        if (null === $fields) {
            return $this->configuration[$context];
        }

        $f = [];
        foreach ($fields as $field) {
            $f[$field] = $this->configuration[$context]['fields'][$field];
        }

        return $f;
    }

    public function getFieldConfiguration($context, $field)
    {
        if (!isset($this->configuration[$context])) {
            throw new \InvalidArgumentException(sprintf('The context "%s" does not exist.', $context));
        }

        if (!isset($this->configuration[$context]['fields'][$field])) {
            throw new \InvalidArgumentException(sprintf('Field "%s" does not exist.', $field));
        }

        return $this->configuration[$context]['fields'][$field];
    }

    /**
     * Gets the configuration for a given field.
     *
     * @param string $key     The configuration key (title.list.name for example)
     * @param mixed  $default The default value if none has been defined
     * @param bool   $escaped Whether to escape single quote (false by default)
     *
     * @return mixed The configuration value
     */
    public function getValue($key, $default = null, $escaped = false)
    {
        if (preg_match('/^(?P<context>[^\.]+)\.fields\.(?P<field>[^\.]+)\.(?P<key>.+)$/', $key, $matches)) {
            $v = $this->getFieldConfiguration($matches['context'], $matches['field'])->getConfig($matches['key'], $default);
        } elseif (preg_match('/^(?P<context>[^\.]+)\.(?P<key>.+)$/', $key, $matches)) {
            $v = \sfModelGeneratorConfiguration::getFieldConfigValue($this->getContextConfiguration($matches['context']), $matches['key'], $default);
        } else {
            throw new \InvalidArgumentException(sprintf('Configuration key "%s" is invalid.', $key));
        }

        return $escaped ? str_replace("'", "\\'", $v) : $v;
    }

    /**
     * Gets the fields that represents the filters.
     *
     * If no filter.display parameter is passed in the configuration,
     * all the fields from the form are returned (dynamically).
     *
     * @param \sfForm $form The form with the fields
     *
     * @return array
     */
    public function getFormFilterFields(\sfForm $form)
    {
        $config = $this->getConfig();

        if ($this->getFilterDisplay()) {
            $fields = [];
            foreach ($this->getFilterDisplay() as $name) {
                list($name, $flag) = \sfModelGeneratorConfigurationField::splitFieldWithFlag($name);
                if (!isset($this->configuration['filter']['fields'][$name])) {
                    $this->configuration['filter']['fields'][$name] = new \sfModelGeneratorConfigurationField($name, array_merge(
                        isset($config['default'][$name]) ? $config['default'][$name] : [],
                        isset($config['filter'][$name]) ? $config['filter'][$name] : [],
                        ['is_real' => false, 'type' => 'Text', 'flag' => $flag]
                    ));
                }
                $field = $this->configuration['filter']['fields'][$name];
                $field->setFlag($flag);
                $fields[$name] = $field;
            }

            return $fields;
        }

        $fields = [];
        foreach ($form->getWidgetSchema()->getPositions() as $name) {
            $fields[$name] = new \sfModelGeneratorConfigurationField($name, array_merge(
                ['type' => 'Text'],
                isset($config['default'][$name]) ? $config['default'][$name] : [],
                isset($config['filter'][$name]) ? $config['filter'][$name] : [],
                ['is_real' => false]
            ));
        }

        return $fields;
    }

    /**
     * Gets the fields that represents the form.
     *
     * If no form.display parameter is passed in the configuration,
     * all the fields from the form are returned (dynamically).
     *
     * @param \sfForm $form    The form with the fields
     * @param string  $context The display context
     *
     * @return array
     */
    public function getFormFields(\sfForm $form, $context)
    {
        $config = $this->getConfig();

        $method = sprintf('get%sDisplay', ucfirst($context));
        if (!$fieldsets = $this->{$method}()) {
            $fieldsets = $this->getFormDisplay();
        }

        if ($fieldsets) {
            $fields = [];

            // with fieldsets?
            if (!is_array(reset($fieldsets))) {
                $fieldsets = ['NONE' => $fieldsets];
            }

            foreach ($fieldsets as $fieldset => $names) {
                if (!$names) {
                    continue;
                }

                $fields[$fieldset] = [];

                foreach ($names as $name) {
                    list($name, $flag) = \sfModelGeneratorConfigurationField::splitFieldWithFlag($name);
                    if (!isset($this->configuration[$context]['fields'][$name])) {
                        $this->configuration[$context]['fields'][$name] = new \sfModelGeneratorConfigurationField($name, array_merge(
                            isset($config['default'][$name]) ? $config['default'][$name] : [],
                            isset($config['form'][$name]) ? $config['form'][$name] : [],
                            isset($config[$context][$name]) ? $config[$context][$name] : [],
                            ['is_real' => false, 'type' => 'Text', 'flag' => $flag]
                        ));
                    }

                    $field = $this->configuration[$context]['fields'][$name];
                    $field->setFlag($flag);
                    $fields[$fieldset][$name] = $field;
                }
            }

            return $fields;
        }

        $fields = [];
        foreach ($form->getWidgetSchema()->getPositions() as $name) {
            $fields[$name] = new \sfModelGeneratorConfigurationField($name, array_merge(
                ['type' => 'Text'],
                isset($config['default'][$name]) ? $config['default'][$name] : [],
                isset($config['form'][$name]) ? $config['form'][$name] : [],
                isset($config[$context][$name]) ? $config[$context][$name] : [],
                ['is_real' => false]
            ));
        }

        return ['NONE' => $fields];
    }

    /**
     * Gets the value for a given key.
     *
     * @param array  $config  The configuration
     * @param string $key     The key name
     * @param mixed  $default The default value
     *
     * @return mixed The key value
     */
    public static function getFieldConfigValue($config, $key, $default = null)
    {
        $ref = &$config;
        $parts = explode('.', $key);
        $count = count($parts);
        for ($i = 0; $i < $count; ++$i) {
            $partKey = $parts[$i];
            if (!isset($ref[$partKey])) {
                return $default;
            }

            if ($count == $i + 1) {
                return $ref[$partKey];
            }

            $ref = &$ref[$partKey];
        }

        return $default;
    }

    public function getCredentials($action)
    {
        if (str_starts_with($action, '_')) {
            $action = substr($action, 1);
        }

        return isset($this->configuration['credentials'][$action]) ? $this->configuration['credentials'][$action] : [];
    }

    public function getPager($model)
    {
        // TODO: Probably `getPagerClass()` method should be abstract here. As well as `getPagerMaxPerPage`
        $class = $this->getPagerClass();

        return new $class($model, $this->getPagerMaxPerPage());
    }

    /**
     * Gets a new form object.
     *
     * @param array       $options An array of options to merge with the options returned by getFormOptions()
     * @param \mixed|null $object
     *
     * @return \sfForm
     */
    public function getForm($object = null, $options = [])
    {
        $class = $this->getFormClass();

        return new $class($object, array_merge($this->getFormOptions(), $options));
    }

    public function getFormOptions()
    {
        return [];
    }

    public function getFilterForm($filters)
    {
        $class = $this->getFilterFormClass();

        return new $class($filters, $this->getFilterFormOptions());
    }

    public function getFilterFormOptions()
    {
        return [];
    }

    public function getFilterDefaults()
    {
        return [];
    }

    protected function compile()
    {
        $config = $this->getConfig();

        // inheritance rules:
        // new|edit < form < default
        // list < default
        // filter < default
        $this->configuration = [
            'list' => [
                'fields' => [],
                'layout' => $this->getListLayout(),
                'title' => $this->getListTitle(),
                'actions' => $this->getListActions(),
                'object_actions' => $this->getListObjectActions(),
                'params' => $this->getListParams(),
            ],
            'filter' => [
                'fields' => [],
            ],
            'form' => [
                'fields' => [],
            ],
            'new' => [
                'fields' => [],
                'title' => $this->getNewTitle(),
                'actions' => $this->getNewActions() ?: $this->getFormActions(),
            ],
            'edit' => [
                'fields' => [],
                'title' => $this->getEditTitle(),
                'actions' => $this->getEditActions() ?: $this->getFormActions(),
            ],
        ];

        foreach (array_keys($config['default']) as $field) {
            $formConfig = array_merge($config['default'][$field], isset($config['form'][$field]) ? $config['form'][$field] : []);

            $this->configuration['list']['fields'][$field] = new \sfModelGeneratorConfigurationField($field, array_merge(['label' => \sfInflector::humanize(\sfInflector::underscore($field))], $config['default'][$field], isset($config['list'][$field]) ? $config['list'][$field] : []));
            $this->configuration['filter']['fields'][$field] = new \sfModelGeneratorConfigurationField($field, array_merge($config['default'][$field], isset($config['filter'][$field]) ? $config['filter'][$field] : []));
            $this->configuration['new']['fields'][$field] = new \sfModelGeneratorConfigurationField($field, array_merge($formConfig, isset($config['new'][$field]) ? $config['new'][$field] : []));
            $this->configuration['edit']['fields'][$field] = new \sfModelGeneratorConfigurationField($field, array_merge($formConfig, isset($config['edit'][$field]) ? $config['edit'][$field] : []));
        }

        // "virtual" fields for list
        foreach ($this->getListDisplay() as $field) {
            list($field, $flag) = \sfModelGeneratorConfigurationField::splitFieldWithFlag($field);

            $this->configuration['list']['fields'][$field] = new \sfModelGeneratorConfigurationField($field, array_merge(
                ['type' => 'Text', 'label' => \sfInflector::humanize(\sfInflector::underscore($field))],
                isset($config['default'][$field]) ? $config['default'][$field] : [],
                isset($config['list'][$field]) ? $config['list'][$field] : [],
                ['flag' => $flag]
            ));
        }

        // form actions
        foreach (['edit', 'new'] as $context) {
            foreach ($this->configuration[$context]['actions'] as $action => $parameters) {
                $this->configuration[$context]['actions'][$action] = $this->fixActionParameters($action, $parameters);
            }
        }

        // list actions
        foreach ($this->configuration['list']['actions'] as $action => $parameters) {
            $this->configuration['list']['actions'][$action] = $this->fixActionParameters($action, $parameters);
        }

        // list batch actions
        $this->configuration['list']['batch_actions'] = [];
        foreach ($this->getListBatchActions() as $action => $parameters) {
            $parameters = $this->fixActionParameters($action, $parameters);

            $action = 'batch'.ucfirst(str_starts_with($action, '_') ? substr($action, 1) : $action);

            $this->configuration['list']['batch_actions'][$action] = $parameters;
        }

        // list object actions
        foreach ($this->configuration['list']['object_actions'] as $action => $parameters) {
            $this->configuration['list']['object_actions'][$action] = $this->fixActionParameters($action, $parameters);
        }

        // list field configuration
        $this->configuration['list']['display'] = [];
        foreach ($this->getListDisplay() as $name) {
            list($name, $flag) = \sfModelGeneratorConfigurationField::splitFieldWithFlag($name);
            if (!isset($this->configuration['list']['fields'][$name])) {
                throw new \InvalidArgumentException(sprintf('The field "%s" does not exist.', $name));
            }
            $field = $this->configuration['list']['fields'][$name];
            $field->setFlag($flag);
            $this->configuration['list']['display'][$name] = $field;
        }

        // parse the %%..%% variables, remove flags and add default fields where
        // necessary (fixes #7578)
        $this->parseVariables('list', 'params');
        $this->parseVariables('edit', 'title');
        $this->parseVariables('list', 'title');
        $this->parseVariables('new', 'title');

        // action credentials
        $this->configuration['credentials'] = [
            'list' => [],
            'new' => [],
            'create' => [],
            'edit' => [],
            'update' => [],
            'delete' => [],
        ];
        foreach ($this->getActionsDefault() as $action => $params) {
            if (str_starts_with($action, '_')) {
                $action = substr($action, 1);
            }

            $this->configuration['credentials'][$action] = isset($params['credentials']) ? $params['credentials'] : [];
            $this->configuration['credentials']['batch'.ucfirst($action)] = isset($params['credentials']) ? $params['credentials'] : [];
        }
        $this->configuration['credentials']['create'] = $this->configuration['credentials']['new'];
        $this->configuration['credentials']['update'] = $this->configuration['credentials']['edit'];
    }

    protected function parseVariables($context, $key)
    {
        preg_match_all('/%%([^%]+)%%/', $this->configuration[$context][$key], $matches, PREG_PATTERN_ORDER);
        foreach ($matches[1] as $name) {
            list($name, $flag) = \sfModelGeneratorConfigurationField::splitFieldWithFlag($name);
            if (!isset($this->configuration[$context]['fields'][$name])) {
                $this->configuration[$context]['fields'][$name] = new \sfModelGeneratorConfigurationField($name, array_merge(
                    ['type' => 'Text', 'label' => \sfInflector::humanize(\sfInflector::underscore($name))],
                    isset($config['default'][$name]) ? $config['default'][$name] : [],
                    isset($config[$context][$name]) ? $config[$context][$name] : [],
                    ['flag' => $flag]
                ));
            } else {
                $this->configuration[$context]['fields'][$name]->setFlag($flag);
            }

            $this->configuration[$context][$key] = str_replace('%%'.$flag.$name.'%%', '%%'.$name.'%%', $this->configuration[$context][$key]);
        }
    }

    protected function mapFieldName(\sfModelGeneratorConfigurationField $field)
    {
        return $field->getName();
    }

    protected function fixActionParameters($action, $parameters)
    {
        if (null === $parameters) {
            $parameters = [];
        }

        if (!isset($parameters['params'])) {
            $parameters['params'] = [];
        }

        if ('_delete' == $action && !isset($parameters['confirm'])) {
            $parameters['confirm'] = 'Are you sure?';
        }

        $parameters['class_suffix'] = strtolower('_' == $action[0] ? substr($action, 1) : $action);

        // merge with defaults
        $defaults = $this->getActionsDefault();
        if (isset($defaults[$action])) {
            $parameters = array_merge($defaults[$action], $parameters);
        }

        if (isset($parameters['label'])) {
            $label = $parameters['label'];
        } elseif ('_' != $action[0]) {
            $label = $action;
        } else {
            $label = '_list' == $action ? 'Back to list' : substr($action, 1);
        }

        $parameters['label'] = \sfInflector::humanize($label);

        return $parameters;
    }

    protected function getConfig()
    {
        return [
            'default' => $this->getFieldsDefault(),
            'list' => $this->getFieldsList(),
            'filter' => $this->getFieldsFilter(),
            'form' => $this->getFieldsForm(),
            'new' => $this->getFieldsNew(),
            'edit' => $this->getFieldsEdit(),
        ];
    }
}
