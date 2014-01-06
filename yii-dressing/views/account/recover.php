<?php
/**
 * @var $this YdAccountController
 * @var $user YdAccountRecover
 * @var $recaptcha string
 *
 * @author Brett O'Donnell <cornernote@gmail.com>
 * @author Zain Ul abidin <zainengineer@gmail.com>
 * @copyright 2013 Mr PHP
 * @link https://github.com/cornernote/yii-dressing
 * @license BSD-3-Clause https://raw.github.com/cornernote/yii-dressing/master/license.txt
 */

$this->pageTitle = Yii::t('dressing', 'Recover Password');


/** @var YdActiveForm $form */
$form = $this->beginWidget('dressing.widgets.YdActiveForm', array(
    'id' => 'recover-form',
));
echo $form->beginModalWrap();
echo $form->errorSummary($user);

echo $form->textFieldControlGroup($user, 'username_or_email');
if ($recaptcha) {
    echo CHtml::activeLabel($user, 'recaptcha');
    $this->widget('dressing.widgets.YdReCaptchaInput', array('model' => $user));
    echo CHtml::error($user, 'recaptcha');
}
echo $form->endModalWrap();
echo $form->getSubmitButtonRow(Yii::t('app', 'Recover Password'));
$this->endWidget();
