<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_articles_upcoming
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;

use Joomla\CMS\Helper\ModuleHelper;
use Joomla\Module\ArticlesUpcoming\Site\Helper\ArticlesUpcomingHelper;


// Include the related items functions only once
//JLoader::register('ModArticlesUpcomingHelper', __DIR__ . '/helper.php');
/*
$cacheparams = new stdClass;
$cacheparams->cachemode = 'safeuri';
$cacheparams->class = 'ArticlesUpcomingHelper';
$cacheparams->method = 'getList';
$cacheparams->methodparams = $params;
$cacheparams->modeparams = array('id' => 'int', 'Itemid' => 'int');

$list = ModuleHelper::moduleCache($module, $params, $cacheparams);*/

$list       = ArticlesUpcomingHelper::getList($params);
$list['articleList'] = isset($list['articleList']) ? $list['articleList'] : array();

if (!count($list['articleList']))
{
	return;
}

$moduleclass_sfx = htmlspecialchars($params->get('moduleclass_sfx'), ENT_COMPAT, 'UTF-8');
$showDate        = $params->get('showDate', 0);

require ModuleHelper::getLayoutPath('mod_articles_upcoming', $params->get('layout', 'default'));