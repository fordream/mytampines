<?php
// based on com_content.php from XMAP
defined('_JEXEC') or die('Direct Access to this location is not allowed.');

require_once (JPATH_SITE . DS . 'components' . DS . 'com_hub2' . DS .
'hub2includepaths.php');
require_once (JPATH_SITE . DS . 'components' . DS . 'com_hub2' . DS .
'views'.DS.'helpers' . DS . 'route.php');
require_once (JPATH_SITE . DS . 'components' . DS . 'com_hub2' . DS .
'helpers' . DS . 'contentDisplayHelper.php');
require_once (JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_hub2' . DS .'models'.DS.
'postcode.php');
require_once (JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_hub2' . DS .'models'.DS.
'category.php');
require_once (JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_hub2' . DS .'models'.DS.
'tag.php');
require_once (JPATH_ADMINISTRATOR . DS . 'components' . DS . 'com_hub2' . DS .'models'.DS.
'topic.php');
require_once (JPATH_SITE . DS . 'components' . DS . 'com_hub2' . DS .'models'.DS.
'content.php');
require_once (JPATH_SITE . DS . 'components' . DS . 'com_hub2' . DS .'helpers'.DS.
'timezone.php');

class xmap_com_hub2 {
    /*
     * This function is called before a menu item is printed. We use it to set the
     * proper uniqueid for the item
     */
    function prepareMenuItem(&$node,&$params) {
        $link_query = parse_url($node->link);
        parse_str(html_entity_decode($link_query['query']), $link_vars);
        $view = JArrayHelper::getValue($link_vars, 'view', '');
        $id = intval(JArrayHelper::getValue($link_vars, 'id', 0));
        $layout = JArrayHelper::getValue($link_vars, 'layout', '');

        switch ($view) {
            case 'sitecategory':
                $node->uid = 'com_hub2c' . $id;
                $node->expandible = true;
                break;
            case 'sitetopic':
                $node->uid = 'com_hub2t' . $id;
                $node->expandible = true;
                break;
            case 'sitepostcode':
                $node->uid = 'com_hub2p' . $id;
                $node->expandible = true;
                break;
            case 'sitepostcodelist':
                $node->uid = 'com_hub2pl' . $id;
                $node->expandible = true;
                break;
            case 'sitetag':
                $node->uid = 'com_hub2ta' . $id;
                $node->expandible = true;
                break;
            case 'sitetaglist':
                $node->uid = 'com_hub2tal' . $id;
                $node->expandible = true;
                break;
            case 'sitearticle':
            case 'siteevent':
            case 'sitemediagroup':
            case 'sitepoll':
                $node->expandible = false;
                if ($id) {
                    $type = str_replace('site','',$view);
                    $type .= 's';
                    $db = & JFactory::getDBO();
                    $node->uid = 'com_hub2' . $type.$id;
                    $db = & JFactory::getDBO();
                    $db->setQuery("SELECT UNIX_TIMESTAMP(modified) as modified,
                    UNIX_TIMESTAMP(created) as created "
                    . "FROM #__hub2_{$type} WHERE head_id=" . $id);
                    $item = $db->loadObject();
                    if (!$item->modified) {
                        $item->modified = $item->created;
                    }
                    $node->modified = $item->modified;
                    $node->expandible = false;
                }
        }
    }

    /** return a node-tree */
    function getTree(&$xmap, &$parent, &$params) {
        $result = null;

        $link_query = parse_url($parent->link);
        parse_str(html_entity_decode($link_query['query']), $link_vars);
        $view = JArrayHelper::getValue($link_vars, 'view', '');
        $layout = JArrayHelper::getValue($link_vars, 'layout', '');
        $itemtype = JArrayHelper::getValue($link_vars, 'itemtype', '');
        $id = intval(JArrayHelper::getValue($link_vars, 'id', 0));

        $menu = & JSite::getMenu();
        $menuparams = $menu->getParams($parent->id);

        /** *
         * Parameters Initialitation
         * */
        //----- Set expand_categories param
        $expand_categories = JArrayHelper::getValue($params, 'expand_categories', 1);
        $expand_categories = ( $expand_categories == 1
        || ( $expand_categories == 2 && $xmap->view == 'xml')
        || ( $expand_categories == 3 && $xmap->view == 'html')
        || $xmap->view == 'navigator');
        $params['expand_categories'] = $expand_categories;

        //----- Set show_unauth param
        $show_unauth = JArrayHelper::getValue($params, 'show_unauth', 1);
        $show_unauth = ( $show_unauth == 1
        || ( $show_unauth == 2 && $xmap->view == 'xml')
        || ( $show_unauth == 3 && $xmap->view == 'html'));
        $params['show_unauth'] = $show_unauth;

        //----- Set cat_priority and cat_changefreq params
        $priority = JArrayHelper::getValue($params, 'cat_priority', $parent->priority);
        $changefreq = JArrayHelper::getValue($params, 'cat_changefreq', $parent->changefreq);
        if ($priority == '-1') {
            $priority = $parent->priority;
        }
        if ($changefreq == '-1') {
            $changefreq = $parent->changefreq;
        }
        $params['cat_priority'] = $priority;
        $params['cat_changefreq'] = $changefreq;

        //----- Set art_priority and art_changefreq params
        $priority = JArrayHelper::getValue($params, 'art_priority', $parent->priority);
        $changefreq = JArrayHelper::getValue($params, 'art_changefreq', $parent->changefreq);
        if ($priority == '-1') {
            $priority = $parent->priority;
        }
        if ($changefreq == '-1') {
            $changefreq = $parent->changefreq;
        }
        $params['art_priority'] = $priority;
        $params['art_changefreq'] = $changefreq;

        $params['keywords'] = JArrayHelper::getValue($params, 'keywords', 'metakey');
        $params['max_art'] = intval(JArrayHelper::getValue($params, 'max_art', 0));
        $params['articles_order'] = JArrayHelper::getValue($params, 'articles_order', 'menu');

        if ($xmap->isNews) {
            $params['show_unauth'] = 0;
        }

        switch ($view) {
            case 'sitecategory':
            case 'sitetopic':
            case 'sitepostcode':
            case 'sitetag':
                if ($params['expand_categories']) {
                    $modelName = str_replace('site','',$view);
                    if ($modelName == 'topic') {
                        $model = new Hub2ModelTopic();
                    } else if ($modelName == 'category') {
                        $model = new Hub2ModelCategory();
                    } else if ($modelName == 'tag') {
                        $model = new Hub2ModelTag();
                    } else {
                        $model = new Hub2ModelPostcode();
                    }
                    $model->setState('id',$id);
                    // get the topic model and the topic details
                    $category   = & $model->getItem();
                    $category->idslug = $model->getItemSlug($category);
                    $contentModel = new Hub2ModelContent($itemtype);
                    xmap_com_hub2::setContentModelState($contentModel,
                    $modelName,$category,$menuparams);
                    $contentModel->setState('limit',$params['max_art']);
                    $contentModel->setState('limitstart',0);
                    $items = $contentModel->getItems();
                    foreach ($items as &$item) {
                        $item->readmore_link = xmap_com_hub2::getItemURL(
                        $item,$itemtype,$modelName,$category,$parent->id);
                    }
                    xmap_com_hub2::showArticles($xmap,$parent,$params,$items);
                }
                break;
            case 'sitepostcodelist':
            case 'sitetaglist':
                break;
        }
        return true;
    }

    protected function getItemURL(&$item,$itemtype,$type,$category,$itemid,$xhtml=true) {
        if ($type == 'category') {
            $url = Hub2HelperRoute::getItemRoute($item->head_id,$item,
            $itemtype,array($category->idslug),0,0,0,$itemid,true,'',$xhtml);
            return $url;
        } else if ($type == 'tag') {
            $url = Hub2HelperRoute::getItemRoute($item->head_id,$item,
            $itemtype,0,0,array($category->idslug),0,$itemid,true,'',$xhtml);
            return $url;
        } else if ($type == 'postcode') {
            $url = Hub2HelperRoute::getItemRoute($item->head_id,$item,
            $itemtype,0,0,0,array($category->idslug),$itemid,true,'',$xhtml);
            return $url;
        } else { // topic
            $url = Hub2HelperRoute::getItemRoute($item->head_id,$item,
            $itemtype,0,array($category->idslug),0,0,$itemid,true,'',$xhtml);
            return $url;
        }
        return '';
    }

    protected function setContentModelState(&$model, $type, &$category, &$params) {
        $orderByPri = $params->get('orderby_pri','');
        $orderBySec = $params->get('orderby_sec','ordering:desc:category'); // default ordering
        if ($orderByPri == 'relation:desc') {
            // get the relationship field
            $field = $params->get('ordering_field','');
            if ($field !== '') {
                $orderByPri = $field;
                if (stripos($field,':') === false) {
                    $orderByPri = $field.':desc';
                }
            } else {
                $orderByPri = '';
            }
        }

        $orders = array();
        if ($orderByPri == '') {
            $orders[] = Hub2ContentDisplayHelper::createOrderingArray($orderBySec);
        } else {
            $orders[] = Hub2ContentDisplayHelper::createOrderingArray($orderByPri);
            $orders[] = Hub2ContentDisplayHelper::createOrderingArray($orderBySec);
        }
        // set states category and orderCol/orderDirn based on params, limit and limitstart
        $model->setState($type,$category->id);
        $model->setState('orders',$orders);
    }

    function showArticles(&$xmap, &$parent, $params, &$items) {
        static $urlBase;

        if (count($items) > 0) {
            $xmap->changeLevel(1);
            foreach ($items as $item) {
                // Ignore old items for news sitemap
                if ($xmap->isNews && $item->created < ($xmap->now - (2 * 86400))) {
                    continue;
                }
                $subnodes = array();
                $node = new stdclass();
                $node->id = $parent->id;
                $node->uid = $parent->uid . 'a' . $item->id;
                $node->browserNav = $parent->browserNav;
                $node->priority = $params['art_priority'];
                $node->changefreq = $params['art_changefreq'];
                $node->name = $item->title;
                $node->access = $item->access;
                $node->expandible = false;
                switch ($params['keywords']) {
                    case 'metakey':
                        $node->keywords = $item->metakey;
                }
                $node->newsItem = 1;

                // For the google news we should use te publication date instead
                // the last modification date. See
                $node->modified = (@$item->modified ?
                Hub2TimezoneHelper::getTimestamp($item->modified) :
                Hub2TimezoneHelper::getTimestamp($item->created));
                $node->link = $item->readmore_link;
                if ($xmap->printNode($node) && $node->expandible) {
                    $xmap->changeLevel(1);
                    foreach ($subnodes as $subnode) {
                        //var_dump($subnodes);
                        $subnode->id = $parent->id;
                        $subnode->browserNav = $parent->browserNav;
                        $subnode->priority = $params['art_priority'];
                        $subnode->changefreq = $params['art_changefreq'];
                        $subnode->access = $item->access;
                        $xmap->printNode($subnode);
                    }
                    $xmap->changeLevel(-1);
                }
            }
            $xmap->changeLevel(-1);
        }
        return true;
    }
}
