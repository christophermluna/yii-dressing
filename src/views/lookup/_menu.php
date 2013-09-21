<?php
/**
 * @var $this LookupController
 * @var $lookup YdLookup
 */

// index
if ($this->action->id == 'index') {
    $this->menu = YdMenu::getItemsFromMenu('System');
    return; // no more links
}

// create
if ($lookup->isNewRecord) {
    $this->menu[] = array(
        'label' => t('Create'),
        'url' => array('/lookup/create'),
    );
    return; // no more links
}

// view
$this->menu[] = array(
    'label' => t('View'),
    'url' => $lookup->getUrl(),
);

// others
foreach ($lookup->getDropdownLinkItems(true) as $linkItem) {
    $this->menu[] = $linkItem;
}
