<?php

/**
 * YdGeneratePropertiesAction allows you to update model files to contain correct phpdoc definitions regarding fields,
 * relations and behaviors.
 *
 * YdGeneratePropertiesAction can be added as an action to any controller:
 * <pre>
 * class ToolController extends YdWebController
 * {
 *     public function actions()
 *     {
 *         return array(
 *             'generateProperties' => array(
 *                 'class' => 'dressing.actions.YdGeneratePropertiesAction',
 *             ),
 *         );
 *     }
 * }
 * </pre>
 *
 * @property YdWebController $controller
 *
 * @author Brett O'Donnell <cornernote@gmail.com>
 * @author Zain Ul abidin <zainengineer@gmail.com>
 * @copyright 2013 Mr PHP
 * @link https://github.com/cornernote/yii-dressing
 * @license http://www.gnu.org/copyleft/gpl.html
 *
 * @package dressing.actions
 */
class YdGeneratePropertiesAction extends CAction
{

    /**
     * @var
     */
    public $modelName;

    /**
     * @var CActiveRecord
     */
    public $model;

    /**
     * Runs the action.
     * This method displays the view requested by the user.
     * @throws CHttpException if the modelName is invalid
     */
    public function run()
    {
        // try get the model name
        $this->modelName = YdHelper::getSubmittedField('modelName');

        // show a list
        if (!$this->modelName) {
            $this->renderModelList();
            return;
        }

        // load the model
        $this->model = CActiveRecord::model($this->modelName);
        if (!$this->model) {
            throw new CHttpException(strtr(Yii::t('dressing', 'No CActiveRecord Class with name :modelName was not found.'), array(':modelName' => $this->modelName)));
        }

        // render the properties
        $this->renderModelProperties();
    }

    /**
     *
     */
    public function renderModelList()
    {
        ob_start();
        $this->controller->widget('bootstrap.widgets.TbMenu', array(
            'type' => 'pills', // '', 'tabs', 'pills' (or 'list')
            'stacked' => true, // whether this is a stacked menu
            'items' => $this->getModelList(),
        ));
        $contents = ob_get_clean();
        $this->controller->renderText($contents);
    }

    /**
     * @return array
     */
    public function getModelList()
    {
        $pathList = CFileHelper::findFiles(Yii::getPathOfAlias("application.models"), array('fileTypes' => array('php')));
        $modelList = array();
        foreach ($pathList as $path) {
            $modelName = basename($path, '.php');
            if (strpos($modelName, '.') !== false) {
                echo "<br/> there is dot in modelName [$modelName] probably a version conflict file <br/>\r\n";
                continue;
            }
            $model = new $modelName;
            if ($model && is_subclass_of($model, 'CActiveRecord')) {
                $modelList[] = array('label' => $modelName, 'url' => array('/tool/generateProperties', 'modelName' => $modelName));
            }
        }
        return $modelList;
    }

    /**
     *
     */
    public function renderModelProperties()
    {
        $begin = " * --- BEGIN GenerateProperties ---";
        $end = " * --- END GenerateProperties ---";
        $contents = $begin . "\n" . implode("\n", $this->getModelProperties()) . "\n" . $end;

        $message = '';
        $fileName = Yii::getPathOfAlias("application.models") . '/' . $this->modelName . '.php';
        if (!file_exists($fileName)) {
            $fileName = Yii::getPathOfAlias("application.models.cre") . '/' . $this->modelName . '.php';
        }
        if (file_exists($fileName)) {
            $fileContents = file_get_contents($fileName);
            $fileContents = strtr($fileContents, array(
                ' * --- BEGIN AutoGenerated by tool/generateProperties ---' => $begin,
                ' * --- END AutoGenerated by tool/generateProperties ---' => $end,
            ));
            $firstPos = strpos($fileContents, $begin);
            $lastPos = strpos($fileContents, $end);
            if ($firstPos && $lastPos && ($lastPos > $firstPos)) {
                $oldDoc = YdStringHelper::getBetweenString($fileContents, $begin, $end, false, false);
                if ($contents != $oldDoc) {
                    file_put_contents($fileName, str_replace($oldDoc, $contents, $fileContents));
                    $message = 'overwrote file: ' . realpath($fileName);
                }
                else {
                    $message = 'contents matches file: ' . realpath($fileName);
                }
            }
        }
        $this->controller->breadcrumbs = array(
            Yii::t('dressing', 'Generate Properties') => array('/tool/generateProperties'),
            Yii::t('dressing', 'Model') . ' ' . $this->modelName,
        );
        $this->controller->renderText($message . '<pre>' . $contents . '</pre>');
    }

    /**
     * @return array
     */
    public function getModelProperties()
    {
        $properties = array();

        Yii::app()->db->getSchema()->refresh();
        $this->model->refreshMetaData();
        //$this->model->refresh(); // caused an error on many_to_many tables

        // intro
        $properties[] = " *";
        $properties[] = " * This is the model class for table '" . $this->model->tableName() . "'";
        $properties[] = " *";

        // table
        $properties[] = " * @method {$this->modelName} model() static model(string \$className = NULL)";
        $properties[] = " * @method {$this->modelName} with() with()";
        $properties[] = " * @method {$this->modelName} find() find(\$condition, array \$params = array())";
        $properties[] = " * @method {$this->modelName}[] findAll() findAll(\$condition = '', array \$params = array())";
        $properties[] = " * @method {$this->modelName} findByPk() findByPk(\$pk, \$condition = '', array \$params = array())";
        $properties[] = " * @method {$this->modelName}[] findAllByPk() findAllByPk(\$pk, \$condition = '', array \$params = array())";
        $properties[] = " * @method {$this->modelName} findByAttributes() findByAttributes(array \$attributes, \$condition = '', array \$params = array())";
        $properties[] = " * @method {$this->modelName}[] findAllByAttributes() findAllByAttributes(array \$attributes, \$condition = '', array \$params = array())";
        $properties[] = " * @method {$this->modelName} findBySql() findBySql(\$sql, array \$params = array())";
        $properties[] = " * @method {$this->modelName}[] findAllBySql() findAllBySql(\$sql, array \$params = array())";
        $properties[] = " *";

        // behaviors
        $behaviors = $this->model->behaviors();
        $inheritedMethods = array();
        foreach (get_class_methods('CActiveRecordBehavior') as $methodName) {
            $inheritedMethods[$methodName] = $methodName;
        }
        $reflection = new ReflectionClass ($this->modelName);
        $selfMethods = CHtml::listData($reflection->getMethods(), 'name', 'name');
        foreach ($behaviors as $behavior) {
            $className = $behavior;
            if (is_array($behavior)) {
                $className = $behavior['class'];
            }
            $className = explode('.', $className);
            $className = $className[count($className) - 1];
            $methods = get_class_methods($className);
            $header = false;
            foreach ($methods as $methodName) {
                if (isset($inheritedMethods[$methodName]) || isset($selfMethods[$methodName])) {
                    continue;
                }
                if (!$header) {
                    $properties[] = " * Methods from behavior " . $className;
                    $header = true;
                }

                $methodReturn = $this->getTypeFromDocComment($className, $methodName, 'return');
                $paramTypes = $this->getDocComment($className, $methodName, 'param');
                $methodReturn = $methodReturn ? current($methodReturn) . ' ' : '';
                $property = " * @method $methodReturn$methodName() $methodName(";
                $r = new ReflectionMethod($className, $methodName);
                $params = $r->getParameters();
                $separator = '';
                foreach ($params as $param) {
                    //$param is an instance of ReflectionParameter
                    /* @var $param ReflectionParameter */
                    $type = current($paramTypes);
                    $filterType = '';
                    if ($type && strpos($type, '$')) {
                        $typeString = YdStringHelper::getBetweenString($type, false, '$');
                        $typeString = trim($typeString);
                        $filterType = $this->filterDocType($typeString);
                        $filterType = $filterType ? trim($filterType) . ' ' : '';
                    }
                    next($paramTypes);
                    $property .= $separator . $filterType . '$' . $param->getName();
                    if ($param->isOptional()) {
                        $property .= ' = ';
                        $property .= strtr(str_replace("\n", '', var_export($param->getDefaultValue(), true)), array(
                            'array (' => 'array(',
                        ));
                    }
                    $separator = ', ';
                }
                $property .= ")";
                $properties[] = $property;

            }
            if ($header) {
                $properties[] = ' *';
            }
        }

        // relations
        $relations = $this->model->relations();
        if ($relations) {
            $properties[] = ' * Properties from relation';
            foreach ($relations as $relationName => $relation) {
                if (in_array($relation[0], array('CBelongsToRelation', 'CHasOneRelation'))) {
                    $properties[] = ' * @property ' . $relation[1] . ' $' . $relationName;
                }
                elseif (in_array($relation[0], array('CHasManyRelation', 'CManyManyRelation'))) {
                    $properties[] = ' * @property ' . $relation[1] . '[] $' . $relationName;
                }
                elseif (in_array($relation[0], array('CStatRelation'))) {
                    $properties[] = ' * @property integer $' . $relationName;
                }
                else {
                    $properties[] = ' * @property unknown $' . $relationName;
                }

            }
            $properties[] = ' *';
        }

        // table fields
        $properties[] = ' * Properties from table fields';
        foreach ($this->model->tableSchema->columns as $column) {
            $type = $column->type;
            if (($column->dbType == 'datetime') || ($column->dbType == 'date')) {
                $type = 'string'; // $column->dbType;
            }
            if (strpos($column->dbType, 'decimal') !== false) {
                $type = 'number';
            }
            $properties[] = ' * @property ' . $type . ' $' . $column->name;
        }

        $properties[] = ' *';

        // all done...
        return $properties;
    }

    /**
     * @param $class
     * @param $method
     * @param string $tag
     * @return array|string
     */
    public function getDocComment($class, $method, $tag = '')
    {
        $reflection = new ReflectionMethod($class, $method);
        $comment = $reflection->getDocComment();
        if (!$tag) {
            return $comment;
        }

        $matches = array();
        preg_match_all("/" . $tag . " (.*)(\\r\\n|\\r|\\n)/U", $comment, $matches);

        $returns = array();
        foreach ($matches[1] as $match) {
            $match = explode(' ', $match);
            $type = $match[0];
            $name = isset($match[1]) ? $match[1] : '';
            if (strpos($type, '$') === 0) {
                $name_ = $name;
                $name = $type;
                $type = $name_;
            }
            if (strpos($name, '$') !== 0) {
                $name = '';
            }
            $returns[] = trim($type . ' ' . $name);
        }

        return $returns;
    }

    /**
     * @param $class
     * @param $method
     * @param $tag
     * @return array
     */
    public function getTypeFromDocComment($class, $method, $tag)
    {
        $types = $this->getDocComment($class, $method, $tag);
        $returnTypes = array();
        foreach ($types as $k => $type) {
            $filteredType = $this->filterDocType($type);
            if ($filteredType) {
                $returnTypes[$k] = trim($filteredType);
            }
        }
        return $returnTypes;

    }

    /**
     * @param $type
     * @return mixed|string
     */
    public function filterDocType($type)
    {
        $ignoreTypes = array('void', 'mixed', 'null');
        $replace = array(
            'bool' => 'boolean',
            'integer' => 'int',
        );
        $filteredType = '';
        if (strpos($type, '|') !== false) {
            $multiType = explode('|', $type);
            $multiTypeSafe = array();
            foreach ($multiType as $singleType) {
                if (!in_array($singleType, $ignoreTypes)) {
                    if (isset($replace[$singleType])) {
                        $singleType = $replace[$singleType];
                    }
                    $multiTypeSafe[] = $singleType;
                }
            }
            $filteredType = implode('|', $multiTypeSafe);
        }
        else {
            if (!in_array($type, $ignoreTypes)) {
                $filteredType = $type;
                if (isset($replace[$type])) {
                    $filteredType = $replace[$type];
                }
            }
        }
        if ($filteredType) {
            $filteredType = str_replace('-', ' ', $filteredType);
            $filteredType = trim($filteredType);
            if (strpos($type, ' ')) {
                $filteredType = YdStringHelper::getBetweenString($type, false, ' ');
            }
        }

        return $filteredType;

    }

}
