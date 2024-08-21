<?php
/**
 * @package     Joomla.Site
 * @subpackage  mod_articles_upcoming
 *
 * @copyright   Copyright (C) 2005 - 2020 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('_JEXEC') or die;
?>
    <!-- LIST of Upcoming Article -->
    
    <?php if (count($list['articleList'])) { ?>
    <h3 class="module-title "><span><?php echo JText::_('MOD_ARTICLES_UPCOMING_TITLE'); ?></span></h3>
    
    <ul class="relateditems<?php echo $moduleclass_sfx; ?> mod-list">
		<?php foreach ($list['articleList'] as $item) : ?>
        <li>
            <div class="media">
                <?php if ($showDate) { ?>
                <span>
                    <strong>
                        <?php echo JHtml::_('date', $item->publish_up, $params->get('show_date_format', JText::_('DATE_FORMAT_LC4'))); ?>
                    </strong>
                </span>
                <?php } ?>
                <div>
                    <?php echo $item->title; ?>
                </div>
            </div>
        </li>
        <?php endforeach; ?>
    </ul>
    
	<?php } ?>