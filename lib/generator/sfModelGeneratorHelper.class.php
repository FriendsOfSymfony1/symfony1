<?php

/*
 * This file is part of the Symfony1 package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

abstract class sfModelGeneratorHelper
{
    abstract public function getUrlForAction($action);

    public function linkToNew($params)
    {
        return '<li class="sf_admin_action_new">'.link_to(__($params['label'], [], 'sf_admin'), '@'.$this->getUrlForAction('new')).'</li>';
    }

    public function linkToEdit($object, $params)
    {
        return '<li class="sf_admin_action_edit">'.link_to(__($params['label'], [], 'sf_admin'), $this->getUrlForAction('edit'), $object).'</li>';
    }

    /**
     * @param mixed|Persistent $object
     * @param array            $params
     *
     * @return string
     */
    public function linkToDelete($object, $params)
    {
        if ($object->isNew()) {
            return '';
        }

        return '<li class="sf_admin_action_delete">'.link_to(__($params['label'], [], 'sf_admin'), $this->getUrlForAction('delete'), $object, ['method' => 'delete', 'confirm' => !empty($params['confirm']) ? __($params['confirm'], [], 'sf_admin') : $params['confirm']]).'</li>';
    }

    public function linkToList($params)
    {
        return '<li class="sf_admin_action_list">'.link_to(__($params['label'], [], 'sf_admin'), '@'.$this->getUrlForAction('list')).'</li>';
    }

    public function linkToSave($object, $params)
    {
        return '<li class="sf_admin_action_save"><input type="submit" value="'.__($params['label'], [], 'sf_admin').'" /></li>';
    }

    /**
     * @param mixed|Persistent $object
     * @param array            $params
     *
     * @return string
     */
    public function linkToSaveAndAdd($object, $params)
    {
        if (!$object->isNew()) {
            return '';
        }

        return '<li class="sf_admin_action_save_and_add"><input type="submit" value="'.__($params['label'], [], 'sf_admin').'" name="_save_and_add" /></li>';
    }
}
