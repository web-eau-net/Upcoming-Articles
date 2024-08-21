<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_articles_upcoming
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

//defined('_JEXEC') or die;
//JLoader::register('ContentHelperRoute', JPATH_SITE . '/components/com_content/helpers/route.php');

namespace Joomla\Module\ArticlesUpcoming\Site\Helper;

\defined('_JEXEC') or die;

use Joomla\CMS\Access\Access;
use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Factory;
use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;
use Joomla\Component\Content\Site\Helper\RouteHelper;
use Joomla\CMS\Language\LanguageHelper;
use Joomla\CMS\Language\Multilanguage;
/**
 * Helper for mod_articles_upcoming
 *
 * @since  3.5.X
 */
 class ArticlesUpcomingHelper
{
	/**
	 * Get a list of Upcoming articles
	 *
	 * @param   \Joomla\Registry\Registry  &$params  module parameters
	 *
	 * @return  array
	 */
	public static function getList(&$params)
	{
		
		$db      = Factory::getDbo();
		$app     = Factory::getApplication();
		$user    = Factory::getUser();
		$groups  = implode(',', $user->getAuthorisedViewLevels());
		$date    = Factory::getDate();
		$maximum = (int) $params->get('maximum', 5);
		//

		// Get an instance of the generic articles model
		//JModelLegacy::addIncludePath(JPATH_SITE . '/components/com_content/models');
		
		 //$articles = JModelLegacy::getInstance('Articles', 'ContentModel', array('ignore_request' => true));

		 $articles = $app->bootComponent('com_content')
			->getMVCFactory()->createModel('Articles', 'Site', ['ignore_request' => true]);
		
		if ($articles === false)
		{
			Factory::getApplication()->enqueueMessage(JText::_('JERROR_AN_ERROR_HAS_OCCURRED'), 'error');

			return array();
		}

		$nullDate = $db->getNullDate();
		$now      = $date->toSql();
		$upcomingArticles  = array();
		$query    = $db->getQuery(true);
	
		$upcomingArticles['articleList'] = array();
		$catid = array();
			
		$catid = $params->get('catid');

		if (count($catid))
		{
			$catid = implode(', ',$params->get('catid'));
			// Select items based on the catid field
			$query->clear()
					->select('a.id as id, a.title as title, a.alias as alias, a.catid as catid, a.language as language, a.publish_up as publish_up')
					->from('#__content AS a LEFT JOIN #__categories AS c ON c.id = a.catid LEFT JOIN #__categories as parent ON parent.id = c.parent_id')
					->where('a.state != 2')
					->where('a.catid IN (' . $catid . ')')
					->where('a.access IN (' . $groups . ')')
					->where('c.published = 1')
					->where('c.access IN (' . $groups . ')');
			
			// Filter by Publish date
			$query->where('(a.publish_up > ' . $db->quote($now) . ')')
				->where('(a.publish_down = ' . $db->quote($nullDate) . 'OR a.publish_down is NULL OR a.publish_down >= ' . $db->quote($now) . ')');
				
			$query->order('a.publish_up');


			// Filter by language
			if (Multilanguage::isEnabled())
			{
				$query->where('a.language in (' . $db->quote(Factory::getLanguage()->getTag()) . ',' . $db->quote('*') . ')');

			}

				

			$db->setQuery($query, 0, $maximum);
			try
			{
				
				$articleIds = $db->loadObjectList();
			}
			catch (RuntimeException $e)
			{
				
				Factory::getApplication()->enqueueMessage(JText::_('JERROR_AN_ERROR_HAS_OCCURRED'), 'error');

				return array();
			}
			if (count($articleIds))
			{
				$upcomingArticles['articleList'] = $articleIds;
			}
			unset($articleIds);
		}
		return $upcomingArticles;
	}
}