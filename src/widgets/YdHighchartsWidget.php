<?php

/**
 * YdHighchartsWidget class file.
 *
 * @author Milo Schuman <miloschuman@gmail.com>
 * @link http://yii-highcharts.googlecode.com/
 * @license http://www.opensource.org/licenses/mit-license.php MIT License
 * @version 0.3
 */

/**
 * HighchartsWidget encapsulates the {@link http://www.highcharts.com/ Highcharts}
 * charting library's Chart object.
 *
 * To use this widget, you may insert the following code in a view:
 * <pre>
 * $this->Widget('ext.highcharts.HighchartsWidget', array(
 *    'options'=>array(
 *       'title' => array('text' => 'Fruit Consumption'),
 *       'xAxis' => array(
 *          'categories' => array('Apples', 'Bananas', 'Oranges')
 *       ),
 *       'yAxis' => array(
 *          'title' => array('text' => 'Fruit eaten')
 *       ),
 *       'series' => array(
 *          array('name' => 'Jane', 'data' => array(1, 0, 4)),
 *          array('name' => 'John', 'data' => array(5, 7, 3))
 *       )
 *    )
 * ));
 * </pre>
 *
 * By configuring the {@link $options} property, you may specify the options
 * that need to be passed to the Highcharts JavaScript object. Please refer to
 * the demo gallery and documentation on the {@link http://www.highcharts.com/
 * Highcharts website} for possible options.
 *
 * Alternatively, you can use a valid JSON string in place of an associative
 * array to specify options:
 *
 * <pre>
 * $this->Widget('ext.highcharts.HighchartsWidget', array(
 *    'options'=>'{
 *       "title": { "text": "Fruit Consumption" },
 *       "xAxis": {
 *          "categories": ["Apples", "Bananas", "Oranges"]
 *       },
 *       "yAxis": {
 *          "title": { "text": "Fruit eaten" }
 *       },
 *       "series": [
 *          { "name": "Jane", "data": [1, 0, 4] },
 *          { "name": "John", "data": [5, 7,3] }
 *       ]
 *    }'
 * ));
 * </pre>
 *
 * Note: You must provide a valid JSON string (e.g. double quotes) when using
 * the second option. You can quickly validate your JSON string online using
 * {@link http://jsonlint.com/ JSONLint}.
 *
 * Note: You do not need to specify the <code>chart->renderTo</code> option as
 * is shown in many of the examples on the Highcharts website. This value is
 * automatically populated with the id of the widget's container element. If you
 * wish to use a different container, feel free to specify a custom value.
 */
class YdHighchartsWidget extends CWidget
{

    public $options = array();
    public $htmlOptions = array();

    /**
     * Renders the widget.
     */
    public function run()
    {
        $id = $this->getId();
        $this->htmlOptions['id'] = $id;

        echo CHtml::openTag('div', $this->htmlOptions);
        echo CHtml::closeTag('div');

        // check if options parameter is a json string
        if (is_string($this->options)) {
            if (!$this->options = CJSON::decode($this->options))
                throw new CException('The options parameter is not valid JSON.');
            // TO DO translate exception message
        }

        // merge options with default values
        $defaultOptions = array('chart' => array('renderTo' => $id), 'exporting' => array('enabled' => true));
        $this->options = CMap::mergeArray($defaultOptions, $this->options);
        $jsOptions = $this->encode($this->options);
        $this->registerScripts(__CLASS__ . '#' . $id, "var chart = new Highcharts.Chart($jsOptions);");
    }

    /**
     * Publishes and registers the necessary script files.
     *
     * @param $id string the id of the script to be inserted into the page
     * @param $embeddedScript string the embedded script to be inserted into the page
     */
    protected function registerScripts($id, $embeddedScript)
    {

        $basePath = vp() . DS . 'highcharts';
        $baseUrl = Yii::app()->assetManager->publish($basePath, true, 1, assetCopy());
        $scriptFile = YII_DEBUG ? '/highcharts.src.js' : '/highcharts.js';

        $cs = Yii::app()->clientScript;
        $cs->registerCoreScript('jquery');
        $cs->registerScriptFile($baseUrl . $scriptFile);

        // register exporting module if enabled via the 'exporting' option
        if ($this->options['exporting']['enabled']) {
            $scriptFile = YII_DEBUG ? 'exporting.src.js' : 'exporting.js';
            $cs->registerScriptFile($baseUrl . '/modules/' . $scriptFile);
        }
        $cs->registerScript($id, $embeddedScript);
    }


    function encode($input = array(), $funcs = array(), $level = 0)
    {
        foreach ($input as $key => $value) {
            if (is_array($value)) {
                $ret = $this->encode($value, $funcs, 1);
                $input[$key] = $ret[0];
                $funcs = $ret[1];
            }
            else {
                if (substr($value, 0, 9) == 'function(') {
                    $func_key = "#" . uniqid() . "#";
                    $funcs[$func_key] = $value;
                    $input[$key] = $func_key;
                }
            }
        }
        if ($level == 1) {
            return array($input, $funcs);
        }
        else {
            $input_json = json_encode($input);
            foreach ($funcs as $key => $value) {
                $input_json = str_replace('"' . $key . '"', $value, $input_json);
            }
            return $input_json;
        }
    }

}