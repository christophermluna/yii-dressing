<?php
/**
 * @var $this AccountController
 * @var $user YdUser
 *
 * @author Brett O'Donnell <cornernote@gmail.com>
 * @author Zain Ul abidin <zainengineer@gmail.com>
 * @copyright 2013 Brett O'Donnell <cornernote@gmail.com>, Zain Ul abidin <zainengineer@gmail.com>
 * @link https://github.com/cornernote/yii-dressing
 * @license http://www.gnu.org/copyleft/gpl.html
 */
$this->pageTitle = $this->pageHeading = Yii::t('dressing', 'Set Password');

$this->breadcrumbs[] = Yii::t('dressing', 'Set Password');

/** @var YdActiveForm $form */
$form = $this->beginWidget('dressing.widgets.YdActiveForm', array(
    'id' => 'password-form',
    //'enableAjaxValidation' => true,
    'type' => 'horizontal',
));

echo $form->beginModalWrap();
echo $form->errorSummary($user);

echo $form->passwordFieldRow($user, 'password');
echo $form->passwordFieldRow($user, 'confirm_password');

echo $form->endModalWrap();
echo '<div class="' . $form->getSubmitRowClass() . '">';
$this->widget('bootstrap.widgets.TbButton', array(
    'buttonType' => 'submit',
    'type' => 'primary',
    'icon' => 'ok white',
    'label' => Yii::t('dressing', 'Save'),
));
echo '</div>';
$this->endWidget();