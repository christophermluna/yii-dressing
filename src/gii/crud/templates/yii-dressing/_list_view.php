<?php
/**
 * The following variables are available in this template:
 * @var $this CrudCode
 *
 * @author Brett O'Donnell <cornernote@gmail.com>
 * @author Zain Ul abidin <zainengineer@gmail.com>
 * @copyright 2013 Brett O'Donnell <cornernote@gmail.com>, Zain Ul abidin <zainengineer@gmail.com>
 * @link https://github.com/cornernote/yii-dressing
 * @license http://www.gnu.org/copyleft/gpl.html
 */

echo "<?php\n";
echo "/**\n";
echo " * @var \$this " . $this->controllerClass . "\n";
echo " * @var \$" . lcfirst($this->modelClass) . " " . $this->modelClass . "\n";
echo " */\n";
echo "\n";
echo "echo '<div class=\"view\">';\n";
echo "\n";
echo "echo '<b>'.CHtml::encode(\$data->getAttributeLabel('{$this->tableSchema->primaryKey}')).':</b>';\n";
echo "echo CHtml::link(CHtml::encode(\$data->{$this->tableSchema->primaryKey}), array('view', 'id'=>\$data->{$this->tableSchema->primaryKey})).'<br />';\n";
echo "\n";
$count = 0;
foreach ($this->tableSchema->columns as $column) {
    if ($column->isPrimaryKey)
        continue;
    echo "echo '<b>' . CHtml::encode(\$data->getAttributeLabel('{$column->name}')) . ':</b>';\n";
    echo "echo CHtml::encode(\$data->{$column->name}) . '<br />';\n";
    echo "\n";
}
echo "echo '</div>';\n";
