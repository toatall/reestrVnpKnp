<?php
/*## TbDatePicker widget class
 *
 * @author: antonio ramirez <antonio@clevertech.biz>
 * @copyright Copyright &copy; Clevertech 2012-
 * @license [New BSD License](http://www.opensource.org/licenses/bsd-license.php)
 * @package YiiBooster bootstrap.widgets
 */
class TbDatePicker extends CInputWidget
{
	/**
	 * @var TbActiveForm when created via TbActiveForm.
	 * This attribute is set to the form that renders the widget
	 * @see TbActionForm->inputRow
	 */
	public $form;

	/**
	 * @var array the options for the Bootstrap JavaScript plugin.
	 */
	public $options = array();
    public $optionsMask = array();

	/**
	 * @var string[] the JavaScript event handlers.
	 */
	public $events = array();

	/**
	 *### .init()
	 *
	 * Initializes the widget.
	 */
	public function init()
	{
		$this->htmlOptions['type'] = 'text';
		$this->htmlOptions['autocomplete'] = 'off';

		if (!isset($this->options['language'])) {
			$this->options['language'] = substr(Yii::app()->getLanguage(), 0, 2);
		}
        
        if (!isset($this->options['autoclose'])) {
			$this->options['autoclose'] = true;
		}
                          		
        if (!isset($this->options['format'])) {
			$this->options['format'] = 'mm.dd.yyyy';
		}

		if (!isset($this->options['weekStart'])) {
			$this->options['weekStart'] = 0;
		} // Sunday
        
        if (!isset($this->options['todayBtn'])) {
			$this->options['todayBtn'] = 'linked';
		}

        
        
        // MASK
        if (!isset($this->optionsMask['use'])) {
			$this->optionsMask['use'] = false;                        
		}
        if (!isset($this->optionsMask['format'])) {
			$this->optionsMask['format'] = '00.00.0000';                        
		}
        if (!isset($this->optionsMask['placeHolder'])) {
			$this->optionsMask['placeHolder'] = '__.__.____';                        
		}
        
        
                  
	}

	/**
	 *### .run()
	 *
	 * Runs the widget.
	 */
	public function run()
	{
		list($name, $id) = $this->resolveNameID();

		if ($this->hasModel()) {
			if ($this->form) {
				echo $this->form->textField($this->model, $this->attribute, $this->htmlOptions);
			} else {
				echo CHtml::activeTextField($this->model, $this->attribute, $this->htmlOptions);
			}

		} else {
			echo CHtml::textField($name, $this->value, $this->htmlOptions);
		}

		$this->registerClientScript();
		$this->registerLanguageScript();
		$options = !empty($this->options) ? CJavaScript::encode($this->options) : '';
        
        $mask = '';
        if ($this->optionsMask['use'])
        {
            $mask = ".mask('".$this->optionsMask['format']
                ."', {placeholder: '".$this->optionsMask['placeHolder']."'});";            
        }
        
		ob_start();
		echo "jQuery('#{$id}').datepicker({$options})".$mask;
		foreach ($this->events as $event => $handler) {
			echo ".on('{$event}', " . CJavaScript::encode($handler) . ")";
		}
        
        
        
		Yii::app()->getClientScript()->registerScript(__CLASS__ . '#' . $this->getId(), ob_get_clean() . ';');

	}

	/**
	 *### .registerClientScript()
	 *
	 * Registers required client script for bootstrap datepicker. It is not used through bootstrap->registerPlugin
	 * in order to attach events if any
	 */
	public function registerClientScript()
	{
		//Yii::app()->bootstrap->registerPackage('datepicker');
	}

	public function registerLanguageScript()
	{
        Yii::app()->clientScript->registerScriptFile(
            Yii::app()->assetManager->publish(
                Yii::getPathOfAlias('bootstrap.assets').'/js/bootstrap-datepicker.js'
            )
        );
        Yii::app()->clientScript->registerScriptFile(
            Yii::app()->assetManager->publish(
                Yii::getPathOfAlias('bootstrap.assets').'/js/bootstrap-datepicker.ru.js'
            )
        );
        Yii::app()->clientScript->registerScriptFile(
            Yii::app()->assetManager->publish(
                Yii::getPathOfAlias('bootstrap.assets').'/js/jquery.mask.min.js'
            )
        );        
        Yii::app()->clientScript->registerCssFile(
            Yii::app()->assetManager->publish(
                Yii::getPathOfAlias('bootstrap.assets').'/css/bootstrap-datepicker.css'
            )
        );       
	}
}
