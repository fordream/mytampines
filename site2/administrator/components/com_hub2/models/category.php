<?php
/**
 * @version     $Id: $
 * @package     com_hub2
 * @copyright   (C) 2010 HyperLocalizer Pty Ltd. All rights reserved.
 * @license     HyperLocalizer proprietary.
 */

// no direct access
defined( '_JEXEC' ) or die( 'Restricted access' );
jximport( 'jxtended.database.query' );
require_once('categorymediarelations.php');
require_once('model.php');
require_once(dirname(__FILE__).DS.'..'.DS.'tables'.DS.
'categoryrootcontenttyperelations.php');
/**
 * Category model
 *
 */
class Hub2ModelCategory extends Hub2Model {

    /**
     * Overridden constructor
     *
     * @access  protected
     * @param   array   Configuration array
     */
    function __construct($config = array()) {
        $this->_name = 'category';
        parent::__construct($config);
    }

    private static function &getPropagator() {
        require_once(dirname(__FILE__).DS.'..'.DS.'services'.DS.
        'categoryPropagationService.php');
        static $instance = false;
        if (!$instance) {
            $instance = new Hub2ServiceCategoryPropagation();
        }
        return $instance;
    }
    function getItemsForType($type_id) {
        $filters        = JArrayHelper::fromObject( $this->getState() );
        $limit      = @$filters['limit'];
        $limitstart = @$filters['limitstart'];
        $qb          = $this->_getListQuery( $filters);
        $constraint = $this->_dataModel->getRootNodesContraintType($type_id);
        if ($constraint) {
            $qb->where($constraint);
        }
        $sql            = $qb->toString();
        $items         = $this->_getList( $sql, $limitstart,$limit);

        // set the total so that getPagination works
        $this->_total   = $this->_getListCount( $sql );

        return $items;
    }

    function processRequestVars($request, &$values) {
        $params         = JArrayHelper::getValue( $request['jxform'], 'params', array(), 'array' );
        if ($params) {
            $registry = new JRegistry();
            $registry->loadArray( $params );
            $values['params'] = $registry->toString();
        }

        $media         = JArrayHelper::getValue( $request, 'jxformmedia', array(), 'array' );
        if ($media) {
            $registry = new JRegistry();
            $registry->loadArray( $media );
            $values['media'] = $registry->toString();
        }

    }

    /**
     * Custom save method
     */
    function &save( &$values ) {
        $jApp = &JFactory::getApplication();

        $values['id']=$this->getState('id');

        $request        = $this->getState( 'request' );
        $sites        = JArrayHelper::getValue( $request, 'jxform_site', array(), 'array' );

        $this->processRequestVars($request,$values);

        if (empty($values['parent_id'])) {
            $values['parent_id'] = 0;
        }

        $contentTypes = JArrayHelper::getValue(
        $request,'jxform_content_types', array(), 'array' );
        if ($values['parent_id'] !== 0) {
            $contentTypes = array(); // discard mapping if any
        }

        // set ordering
        if ($values['id'] == 0) {
            // new item get proper order
            $values['ordering'] =
            $this->_dataModel->getNextOrderingForItemWithParent($values['parent_id']);
        } else {
            // old item check if we are changing parent
            $table = $this->getResource();
            $table->load($values['id']);
            if ($values['parent_id'] !== $table->parent_id && (int)$values['parent_id'] !== 0) {
                // if parent changed we need to check that the suggested sites is
                // compatible with the new parent
                $parent_sites = $this->_dataModel->getSiteIDsForCategory($values['parent_id']);
                foreach ($sites as $site) {
                    if (!in_array($site,$parent_sites)) {
                        // fail here
                        $result = JError::raiseWarning('ERROR_CODE',
                                "Cannot change the parent category
 since the suggested set of sites for this category
 are incompatible with the sites the new parent category is available on");
                        return $result;
                    }
                }
                // change ordering to be at the end of the category
                $values['ordering'] =
                $this->_dataModel->getNextOrderingForItemWithParent($values['parent_id']);
            }
        }

        $result = $this->_dataModel->save($values, $this->getResource());

        if (!JError::isError( $result)) {

            $obj = $this->getItemById($result);
            // update site/category mapping
            $insert_id=(int)$obj->id;

            // update content type mapping
            if (!$this->updateContentTypeMapping($insert_id,$contentTypes)) {
                JError::raiseNotice('1123',
                JText::_('ERROR Updating Content Type Mapping')
                );
            }

            // save the media
            $categoryMediaRelationModel = new Hub2ModelCategoryMediaRelations();
            $res = $categoryMediaRelationModel->updateMedia( $insert_id, $values['media']);
            if (!$res) {
                JError::raiseNotice('ERROR_CODE',
                JText::_($categoryMediaRelationModel->getError()));
            }

            // delete old connections first before saving new connections
            $old_sites = $this->_dataModel->getSiteIDsForCategory($insert_id);
            $child_sites = $this->_dataModel->getSiteIDsForChildren($insert_id);
            $parent_sites = $this->_dataModel->getSiteIDsForCategory($obj->parent_id);
            // first collect the sites this needs to be removed from
            // not directly removing to reduce the number of calls
            $removeFromSites = array();
            // need to delete from unselected sites
            foreach ($old_sites as $old_site) {
                if (!in_array($old_site,$sites)) {
                    // we now have an unselected site
                    // check if can remove since we cannot remove
                    // if a child category of this category is propagated to this site
                    if (in_array($old_site,$child_sites)) {
                        JError::raiseNotice('ERROR_CODE',
                        'Could not remove this category from site with ID '.
                        $old_site.' since some of its children are propogated to this site');
                    } else {
                        $removeFromSites[] = $old_site;
                    }
                }
            }
            if (!empty($removeFromSites)) {
                $errors = array();
                $siteModel = new Hub2ModelSite();
                $dsites = $siteModel->getDetails($removeFromSites);
                $dresult =  $this->getPropagator()->removeCategoryFromSites(
                $insert_id,$dsites,$errors);
                if (!$dresult) {
                    JError::raiseNotice('ERROR_CODE',
                    JText::_('Could not remove category from site(s)').
                    '<br />'.implode('<br />',$errors));
                }
                // remove mapping from error free sites
                foreach ($removeFromSites as $old_site) {
                    if (!array_key_exists($old_site,$errors)) {
                        $this->_dataModel->removeCategoryFromSite($insert_id,$old_site);
                    }
                }
            }
            // save suggested sites mapping
            foreach ($sites as $site) {
                // check if can add since cannot add if a parent category is not on site
                if ($obj->parent_id == 0 || in_array($site,$parent_sites)) {
                    $this->_dataModel->addCategoryToSite($insert_id,$site);
                } else {
                    JError::raiseNotice('ERROR_CODE',
                    'Could not add this category to site with ID '.
                    $site.' since its parent category is not available on this site');
                }
            }
        }

        return $result;
    }


    function getContentTypeMapping($id) {
        $db = &JFactory::getDBO();
        $table = New Hub2TableCategoryRootContentTypeRelations($db);
        return $table->getRelations($id);
    }

    function updateContentTypeMapping($id,$contentTypes=array()) {
        $db = &JFactory::getDBO();
        $table = New Hub2TableCategoryRootContentTypeRelations($db);
        $table->deleteRelations($id);
        return $table->addRelations($id,$contentTypes);
    }


    function canDelete($id) {
        $num = $this->_dataModel->getContentCount($id);
        // check if parent of someone
        $num2 = $this->_dataModel->getChildrenCount($id);
        // $sites = $this->_dataModel->getSitesForCategory($id);
        return ($num == 0 && $num2 ==0);
    }

    function delete($id) {
        $num = $this->_dataModel->getContentCount($id);
        if ($num > 0) {
            $result = JError::raiseWarning(500,"Cannot delete category with ID ".
            $id." since it has content associated with it.");
            return $result;
        }
        // check if parent of someone
        $num = $this->_dataModel->getChildrenCount($id);
        if ($num > 0) {
            $result = JError::raiseWarning(500,"Cannot delete category with ID ".
            $id." since it has sub-categories.");
            return $result;
        }

        // check if assigned to a site
        /*
        $sites = $this->_dataModel->getSitesForCategory($id);
        if (count($sites) > 0) {
        $result = JError::raiseWarning(500,"Cannot delete category with ID ".
        $id." since it has been propagated to sites. Remove the site mapping first.");
        return $result;
        }
        */
        // delete mapping with media
        $relations = new Hub2ModelCategoryMediaRelations();
        $success = $relations->deleteCategoryRelations($id);

        if ($success) {
            // delete from category root type mapping
            $this->updateContentTypeMapping($id);
        } else {
            $result = JError::raiseWarning(500,"Cannot delete category with ID ".
            $id." since its mapping to media could not be deleted.");
            return $result;
        }

        if ($success) {
            // delete from site category mapping
            require_once('sitecategoryrelations.php');
            $relations = new Hub2ModelSiteCategoryRelations();
            $success = $relations->deleteCategoryRelations($id);
        } else {
            $result = JError::raiseWarning(500,"Cannot delete category with ID ".
            $id." since its mapping to content types could not be deleted.");
            return $result;
        }

        if ($success) {
            // ok to delete
            return $this->_dataModel->delete($id);
        } else {
            $result = JError::raiseWarning(500,"Cannot delete category with ID ".
            $id." since its mapping to sites could not be deleted.");
            return $result;
        }
    }

    function validateData($values,&$errors) {
        $item = $this->getItem();
        $ret = $item->bind($values);
        if (!$ret) {
            JError::raiseError(500, $item->getError());
        }
        // mapping of required fields;
        $this->_validator->loadAndSetFieldValidateRules('category.validation.rule');

        $return = $this->_validator->validate($item,$errors);
        if ($return) {
            $db = &JFactory::getDBO();
            $constraints = array();
            $constraints[] = 'title='.$db->Quote($values['title']);
            $constraints[] = 'parent_id='.$db->Quote($values['parent_id']);
            $id = $this->getState('id',0);
            if ($id) {
                $constraints[] = 'id<>'.$id;
            }
            if ($this->_dataModel->getCountForConstraint($constraints)) {
                $errors[] = JText::_('Category with same title already exists.');
                $return = false;
            }
        }
        return $return;
    }

    function changeOrder($id, $order) {
        $table = $this->getResource();
        $table->load($id);
        $table->ordering = $order;
        return $table->store();
    }

    /**
     *
     * @return either a array or a JError object, array has the rows that have changed
     */
    function moveOrder($id,$dirn) {
        $table = $this->getResource();
        $table->load($id);
        $order = $table->ordering;
        // find the next or previous category as desired
        if ($dirn < 0) {
            $row = $this->_dataModel->getSiblingWithOrderLessThan($table->parent_id,$order);
        } else {
            $row = $this->_dataModel->getSiblingWithOrderGreaterThan($table->parent_id,$order);
        }
        if (isset($row)) {
            $return[] = (array('id'=>$row->id,'ordering'=>$order));
            $return[] = (array('id'=>$table->id,'ordering'=>$row->ordering));

            // if present then switch their orders
            if (!$this->changeOrder($table->id,$row->ordering)) {
                return JError::raiseWarning('500','Unable to change ordering');
            }
            if (!$this->changeOrder($row->id,$order)) {
                return JError::raiseWarning('500','Unable to change ordering');
            }
            // load the parent for reordering
            if ($table->parent_id ==0)  {
                $this->_dataModel->rebuild('hub2_categories',true);
            } else {
                $table->load($table->parent_id);
                $this->_dataModel->rebuild_tree('hub2_categories',$table->id,
                $table->lft,(int)$table->level,true);
            }
            // need to update the sites associated with the changed categories
            return $return;
        }
        return array();
    }

    function getSitesForCategory($id) {
        return $this->_dataModel->getSitesForCategory($id);
    }

    function rebuildOnExternalSave() {
        return $this->_dataModel->rebuildOnExternalSave();
    }

    /**
     *
     * @return an array of category objects indexed by the category id
     */
    function getChildren($id) {
        return $this->_dataModel->getChildren($id);
    }

    /**
     * return an array of ids, and title starting with the root
     *
     */
    function &getPath($id) {
        if ($id == 0) {
            return array();
        }
        $array = $this->_dataModel->getPath('hub2_categories',$id);
        $return = array();
        foreach ($array as &$obj) {
            $return[] = $this->getItemSlug($obj);
        }
        return $return;
    }

    function getItemSlug(&$category) {
        require_once(JPATH_SITE.DS.'components'.DS.'com_hub2'.DS.
        'helpers'.DS.'aliasHelper.php');
        if (empty($category->alias)) {
            $idslug = $category->id.':'.Hub2AliasHelper::buildAlias($category->title);
        } else {
            $idslug = $category->id.':'.$category->alias;
        }
        return $idslug;
    }

    /**
     * clean data for being saved in the DB
     * @param array $values
     */
    function cleanData(&$values) {
        $values['title'] = $this->cleanText($values['title']);
        $values['alias'] = $this->cleanText($values['alias']);
        $values['subtitle'] = $this->cleanText($values['subtitle']);
    }

    function isChild($childId,$parentId) {
        return $this->_dataModel->isChild('hub2_categories',$childId,$parentId);
    }

}
