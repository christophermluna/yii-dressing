<?php
/**
 * @var $this LookupController
 * @var $lookup YdLookup
 */

user()->setState('index.lookup', ru());
$this->pageTitle = $this->pageHeading = $this->getName() . ' ' . t('List');
$this->breadcrumbs = array($this->getName() . ' ' . t('List'));

$this->menu = YdMenu::getItemsFromMenu('System');

echo '<div class="spacer">';
$this->widget('bootstrap.widgets.TbButton', array(
    'label' => t('Create') . ' ' . $this->getName(),
    'url' => array('/lookup/create'),
    'type' => 'primary',
    'htmlOptions' => array('data-toggle' => 'modal-remote'),
));
echo ' ';
$this->widget('bootstrap.widgets.TbButton', array(
    'label' => t('Search'),
    'htmlOptions' => array('class' => 'search-button'),
    'toggle' => true,
));
if (user()->getState('index.lookup') != url('/lookup/index')) {
    echo ' ';
    $this->widget('bootstrap.widgets.TbButton', array(
        'label' => t('Reset Filters'),
        'url' => array('/lookup/index'),
    ));
}
echo '</div>';

// search
$this->renderPartial('dressing.views.lookup._search', array(
    'lookup' => $lookup,
));

// grid
$this->renderPartial('dressing.views.lookup._grid', array(
    'lookup' => $lookup,
));
