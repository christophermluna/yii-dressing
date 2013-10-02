<?php
/**
 * @var $this RoleController
 * @var $role YdRole
 *
 * @author Brett O'Donnell <cornernote@gmail.com>
 * @author Zain Ul abidin <zainengineer@gmail.com>
 * @copyright 2013 Brett O'Donnell <cornernote@gmail.com>, Zain Ul abidin <zainengineer@gmail.com>
 * @link https://github.com/cornernote/yii-dressing
 * @license http://www.gnu.org/copyleft/gpl.html
 */

echo '<div class="view">';

echo '<b>' . CHtml::encode($data->getAttributeLabel('id')) . ':</b>';
echo CHtml::link(CHtml::encode($data->id), array('view', 'id' => $data->id)) . '<br />';

echo '<b>' . CHtml::encode($data->getAttributeLabel('name')) . ':</b>';
echo CHtml::encode($data->name) . '<br />';

echo '</div>';
