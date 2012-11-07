<?php
/**
* @package     Joomla.Plugin
* @subpackage  Fabrik.visualization.chart
* @copyright   Copyright (C) 2005 Fabrik. All rights reserved.
* @license     GNU General Public License version 2 or later; see LICENSE.txt
*/

// Check to ensure this file is included in Joomla!
defined('_JEXEC') or die();

jimport('joomla.application.component.view');

/**
* Fabrik Calendar HTML View
*
* @package     Joomla.Plugin
* @subpackage  Fabrik.visualization.chart
* @since       3.0
*/

class FabrikViewChart extends JViewLegacy
{

	/**
	* Execute and display a template script.
	*
	* @param   string  $tpl  The name of the template file to parse; automatically searches through the template paths.
	*
	* @return  mixed  A string if successful, otherwise a JError object.
	*/

	public function display($tpl = 'default')
	{
		$app = JFactory::getApplication();
		$input = $app->input;
		$srcs = FabrikHelperHTML::framework();
		$srcs[] = 'media/com_fabrik/js/listfilter.js';
		$srcs[] = 'media/com_fabrik/js/advanced-search.js';
		require_once COM_FABRIK_FRONTEND . '/helpers/html.php';
		$model = $this->getModel();
		$usersConfig = JComponentHelper::getParams('com_fabrik');
		$model->setId($input->getInt('id', $usersConfig->get('visualizationid', $input->getInt('visualizationid', 0))));
		$this->row = $model->getVisualization();
		if ($this->row->published == 0)
		{
			JError::raiseWarning(500, JText::_('JERROR_ALERTNOAUTHOR'));
			return '';
		}
		$calendar = $model->getRow();
		$this->requiredFiltersFound = $this->get('RequiredFiltersFound');
		if ($this->requiredFiltersFound)
		{
			$this->chart = $this->get('Chart');
		}
		else
		{
			$this->chart = '';
		}
		$params = $model->getParams();
		$this->params = $params;
		$viewName = $this->getName();
		$pluginManager = FabrikWorker::getPluginManager();
		$plugin = $pluginManager->getPlugIn('chart', 'visualization');
		$this->containerId = $this->get('ContainerId');
		$this->filters = $this->get('Filters');
		$this->showFilters = $input->getInt('showfilters', $params->get('show_filters')) === 1 ?  1 : 0;
		$this->filterFormURL = $this->get('FilterFormURL');

		$tpl = $params->get('chart_layout', $tpl);
		$tmplpath = JPATH_ROOT . '/plugins/fabrik_visualization/chart/views/chart/tmpl/' . $tpl;
		$this->_setPath('template', $tmplpath);
		FabrikHelperHTML::stylesheetFromPath('plugins/fabrik_visualization/chart/views/chart/tmpl/' . $tpl . '/template.css');

		// Assign something to Fabrik.blocks to ensure we can clear filters
		$ref = $model->getJSRenderContext();
		$js = "$ref = {};";
		$js .= "\n" . "Fabrik.addBlock('$ref', $ref);";
		$js .= $model->getFilterJs();
		FabrikHelperHTML::addScriptDeclaration($srcs, $js);
		echo parent::display();
	}

}
