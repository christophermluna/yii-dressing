<?php
/**
 * @var $this MenuController
 * @var $menu YdMenu
 *
 * @author Brett O'Donnell <cornernote@gmail.com>
 * @author Zain Ul abidin <zainengineer@gmail.com>
 * @copyright 2013 Brett O'Donnell <cornernote@gmail.com>, Zain Ul abidin <zainengineer@gmail.com>
 * @link https://github.com/cornernote/yii-dressing
 * @license http://www.gnu.org/copyleft/gpl.html
 */

$this->pageTitle = $this->pageHeading = $menu->getName() . ' - ' . $this->getName() . ' ' . Yii::t('dressing', 'Update');

$this->breadcrumbs[Yii::t('dressing', 'Tools')] = array('/tool/index');
$this->breadcrumbs[Yii::t('dressing', 'Menus')] = Yii::app()->user->getState('index.menu', array('/menu/index'));
$this->breadcrumbs[$menu->getName()] = $menu->getLink();
$this->breadcrumbs[] = Yii::t('dressing', 'Update');

$this->renderPartial('dressing.views.lookup._menu', array(
    'menu' => $menu,
));
$this->renderPartial('dressing.views.lookup._form', array(
    'menu' => $menu,
));
