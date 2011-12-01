<?php
/**
 * @version     $Id: $
 * @package     com_hub2
 * @copyright   (C) 2010 HyperLocalizer Pty Ltd. All rights reserved.
 * @license     HyperLocalizer proprietary.
 */
/*
 * Soap request handler
 */
require_once (JPATH_SITE . DS . 'components' . DS . 'com_hub2' . DS .'models'.DS.
'content.php');
require_once(JPATH_SITE . DS .'components' . DS . 'com_hub2' . DS . 'views' .
DS. 'helpers' . DS . 'route.php');
require_once (JPATH_SITE . DS . 'components' . DS . 'com_hub2' . DS .'tables'.DS.
'sitemisc.php');


class siteServer{

    /**
     * Authenticate input password against the keys
     * @param string $encPassword
     * @return string
     */
    private function authenticate($encPassword) {
        require_once(dirname(__FILE__).DS.'..'.DS.'models'.DS.'site.php');
        jimport('joomla.user.helper');
        $siteModel = new Hub2ModelSite();
        $keys = $siteModel->getMyKeys();
        if (!$keys) {
            return true; // keys have not been set as yet
        }
        $parts  = explode( ':', $encPassword );
        $crypt  = $parts[0];
        $salt   = @$parts[1];
        $testcrypt1 = JUserHelper::getCryptedPassword($keys['pkey1'], $salt);
        $testcrypt2 = JUserHelper::getCryptedPassword($keys['pkey2'], $salt);
        if ($testcrypt1 == $crypt || $testcrypt2 == $crypt) {
            return true;
        }
        return false;
    }

    /**
     * Method to update a tag on a site
     * @param string $itemSQL the replace portion of SQL command to create tag
     * @param string $encPassword1 pkey1 encrypted with a salt
     * @param string $encPassword2 pkey2 encrypted with a salt
     * @return SoapResponse
     */
    public function updateTag($itemSQL, $encPassword1, $encPassword2) {

        $return = new SoapResponse;
        if (!$this->authenticate($encPassword1) && !$this->authenticate($encPassword2)) {
            $return->success = false;
            $return->msg = "Password Error";
            return $return;
        }
        /*
         if (stripos(JURI::base(),'mysite2')) {
         $return->success = false;
         $return->msg = "Password Error";
         return $return;
         }
         if (stripos(JURI::base(),'mysite3')) {
         exit;
         }
         */
        $db = &JFactory::getDBO();
        $success = $db->Execute("REPLACE INTO #__hub2_tags ".$itemSQL);
        $return->success = ($success !== false);
        if (!$success) {
            $return->msg = $db->getErrorMsg();
        }
        return $return;
    }

    /**
     * Method to delete a tag from site
     * @param string $itemId the ID of the tag to delete
     * @param string $encPassword1 pkey1 encrypted with a salt
     * @param string $encPassword2 pkey2 encrypted with a salt
     * @return SoapResponse
     */
    function deleteTag($itemId, $encPassword1, $encPassword2) {

        $return = new SoapResponse;
        if (!$this->authenticate($encPassword1) && !$this->authenticate($encPassword2)) {
            $return->success = false;
            $return->msg = "Password Error";
            return $return;
        }
        /*
         if (stripos(JURI::base(),'mysite2')) {
         $return->success = false;
         $return->msg = "Password Error";
         return $return;
         }
         if (stripos(JURI::base(),'mysite3')) {
         exit;
         }
         */
        $db = &JFactory::getDBO();
        $db->setQuery("SELECT * FROM #__hub2_tags WHERE id=".$itemId);
        $db->query();

        if($db->getNumRows()>0) {
            $success = $db->Execute("DELETE FROM #__hub2_tags WHERE id=".$itemId);

            if (!$success) {
                $return->msg = $db->getErrorMsg();
                $return->success = false;
            } else {
                $return->success = true;
            }
        } else {
            $return->msg = "Tag does not exit on site.";
            $return->success = true;
        }

        return $return;
    }

    /**
     * Method to update/add a topic on site
     * @param string $itemSQL the replace portion of SQL command to create tag
     * @param string $encPassword1 pkey1 encrypted with a salt
     * @param string $encPassword2 pkey2 encrypted with a salt
     * @return SoapResponse
     */
    function updateTopic($itemSQL, $encPassword1, $encPassword2) {

        $return = new SoapResponse;
        if (!$this->authenticate($encPassword1) && !$this->authenticate($encPassword2)) {
            $return->success = false;
            $return->msg = "Password Error";
            return $return;
        }
        /*
         if (stripos(JURI::base(),'mysite2')) {
         $return->success = false;
         $return->msg = "Password Error";
         return $return;
         }
         if (stripos(JURI::base(),'mysite3')) {
         exit;
         }
         */
        $db = &JFactory::getDBO();
        $success = $db->Execute("REPLACE INTO #__hub2_topics ".$itemSQL);

        if ($success) {
            require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.
            'com_hub2'.DS.'models'.DS.'topic.php');
            $model = new Hub2ModelTopic();
            $model->rebuildOnExternalSave();
            $dispatcher = &JDispatcher::getInstance();
            $dispatcher->trigger('onMenuChange');
        }
        $return->success = ($success !== false);
        if (!$success) {
            $return->msg = $db->getErrorMsg();
        }
        return $return;
    }

    /**
     * Method to delete a topic on site
     * @param int $itemId the ID of topic to delete
     * @param string $encPassword1 pkey1 encrypted with a salt
     * @param string $encPassword2 pkey2 encrypted with a salt
     * @return SoapResponse
     */
    function deleteTopic($itemId, $encPassword1, $encPassword2) {

        $return = new SoapResponse;
        if (!$this->authenticate($encPassword1) && !$this->authenticate($encPassword2)) {
            $return->success = false;
            $return->msg = "Password Error";
            return $return;
        }
        /*
         if (stripos(JURI::base(),'mysite2')) {
         $return->success = false;
         $return->msg = "Password Error";
         return $return;
         }
         if (stripos(JURI::base(),'mysite3')) {
         exit;
         }
         */
        $db = &JFactory::getDBO();
        $db->setQuery("SELECT * FROM #__hub2_topics WHERE id=".$itemId);
        $db->query();

        if($db->getNumRows()>0) {
            $success = $db->Execute("DELETE FROM #__hub2_topics WHERE id=".$itemId);

            if (!$success) {
                $return->msg = $db->getErrorMsg();
                $return->success = false;
            } else {
                $return->success = true;
            }
        } else {
            $return->msg = "Topic does not exit on site.";
            $return->success = true;
        }

        return $return;
    }

    /**
     * Method to update/add a category on site
     * @param int[] $categoryIds array of IDs of the category(s) to add/update
     * @param string[] $itemSQLs the replace portion for adding/updating category
     * @param string $mediaSizes serialized MediaSizeStruct[][] with IDs of the related media
     * @param string $contentTypes serialized int[][] the content types the category(s) map to
     * @param string $encPassword1 pkey1 encrypted with a salt
     * @param string $encPassword2 pkey2 encrypted with a salt
     * @return SoapResponse
     */
    function updateCategory($categoryIds, $itemSQLs, $mediaSizes,
    $contentTypes, $encPassword1, $encPassword2) {
        $return = new SoapResponse;
        if (!$this->authenticate($encPassword1) && !$this->authenticate($encPassword2)) {
            $return->success = false;
            $return->msg = "Password Error";
            return $return;
        }

        $db = &JFactory::getDBO();

        $categoryIds = $this->getSOAPArray($categoryIds->intArray);
        $itemSQLs = $this->getSOAPArray($itemSQLs->stringArray);
        $mediaSizesArray = unserialize($mediaSizes);
        $contentTypesArray = unserialize($contentTypes);
        $count = count($categoryIds);
        $success = true;
        for ($i=0; ($i < $count) && $success; $i++) {
            $categoryId = $categoryIds[$i];
            $itemSQL = $itemSQLs[$i];
            $mediaSizes = $mediaSizesArray[$i];
            $contentTypes = $contentTypesArray[$i];
            $success = $db->Execute("REPLACE INTO #__hub2_categories ".$itemSQL);

            if ($success) {
                $success = $db->Execute(
                "DELETE FROM #__hub2_category_media_relations where category_id=".
                $categoryId);
            }

            foreach ($mediaSizes as $media) {
                if ($success) {
                    $sql = "REPLACE INTO #__hub2_category_media_relations ";
                    $sql .= "(category_id, media_id, size_description) VALUES (";
                    $sql .= $categoryId.",".$media->id.",".$db->Quote($media->size).")";
                    $success = $db->Execute( $sql );
                }
            }

            require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.
            'com_hub2'.DS.'models'.DS.'category.php');
            $model = new Hub2ModelCategory();
            $success = $model->updateContentTypeMapping($categoryId,$contentTypes);
        }

        // rebuild the tree
        if ($success) {
            $model->rebuildOnExternalSave();
            $dispatcher = &JDispatcher::getInstance();
            $dispatcher->trigger('onMenuChange');
        }
        $return->success = ($success !== false);
        if (!$success) {
            $return->msg = $db->getErrorMsg();
        }
        return $return;
    }

    /**
     * Method to delete a category on site
     * @param int $categoryId the ID of the category to delete
     * @param string $encPassword1 pkey1 encrypted with a salt
     * @param string $encPassword2 pkey2 encrypted with a salt
     * @return SoapResponse
     */
    function deleteCategory($categoryId, $encPassword1, $encPassword2) {

        $return = new SoapResponse;
        if (!$this->authenticate($encPassword1) && !$this->authenticate($encPassword2)) {
            $return->success = false;
            $return->msg = "Password Error";
            return $return;
        }
        $db = &JFactory::getDBO();
        $success = $db->Execute("DELETE FROM #__hub2_categories WHERE id=".$categoryId);
        if ($success) {
            $success = $db->Execute(
            "DELETE FROM #__hub2_category_media_relations WHERE category_id=".$categoryId);
            $success = $db->Execute(
            "DELETE FROM #__hub2_category_root_content_type_relations WHERE category_id=".
            $categoryId);
        }
        $return->success = ($success !== false);
        if (!$success) {
            $return->msg = $db->getErrorMsg();
        }
        return $return;
    }

    /**
     * Method to delete all categories on the site except the specified one
     * @param int[] exceptionIds an array of category IDs to save or null
     * @param string encPassword1 pkey1 encrypted with a salt
     * @param string encPassword2 pkey2 encrypted with a salt
     * @return SoapResponse
     */
    function deleteAllExceptCategory($exceptionIds, $encPassword1, $encPassword2) {

        $return = new SoapResponse;
        if (!$this->authenticate($encPassword1) && !$this->authenticate($encPassword2)) {
            $return->success = false;
            $return->msg = "Password Error";
            return $return;
        }
        $db = &JFactory::getDBO();
        $whereClause = '';
        $relationsWhereClause = '';

        $eids = $this->getSOAPArray(@$exceptionIds->intArray);
        if ($eids != null && count($eids) > 0) {
            $whereClause = 'WHERE id NOT IN ('.implode(',',$eids).')';
            $relationsWhereClause = 'WHERE category_id NOT IN ('.implode(',',$eids).')';
        }

        $success = $db->Execute("DELETE FROM #__hub2_categories ".$whereClause);
        if ($success) {
            $success = $db->Execute(
            "DELETE FROM #__hub2_category_media_relations ".$relationsWhereClause);
        }
        if ($success) {
            $success = $db->Execute(
            "DELETE FROM #__hub2_category_root_content_type_relations ".$relationsWhereClause);
        }
        $return->success = ($success !== false);
        if (!$success) {
            $return->msg = $db->getErrorMsg();
        }
        return $return;
    }


    /**
     * Method to update/add author on site
     * @param int $authorId the author id
     * @param string itemSQL the replace portion of SQL command to create author
     * @param MediaSizeStruct[] $mediaRelations the media attached to the author
     * @param string encPassword1 pkey1 encrypted with a salt
     * @param string encPassword2 pkey2 encrypted with a salt
     * @return SoapResponse
     */
    function updateAuthor($authorId, $itemSQL, $mediaRelations, $encPassword1, $encPassword2) {

        $return = new SoapResponse;
        if (!$this->authenticate($encPassword1) && !$this->authenticate($encPassword2)) {
            $return->success = false;
            $return->msg = "Password Error";
            return $return;
        }
        /*
         if (stripos(JURI::base(),'mysite2')) {
         $return->success = false;
         $return->msg = "Password Error";
         return $return;
         }
         if (stripos(JURI::base(),'mysite3')) {
         exit;
         }
         */
        $db = &JFactory::getDBO();
        $success = $db->Execute("REPLACE INTO #__hub2_authors ".$itemSQL);

        if ($success) {
            $success = $db->Execute(
                "DELETE FROM #__hub2_author_media_relations where author_id=".
            $authorId);
        }

        $mediaRelationsArray = $this->getSOAPArray(@$mediaRelations->MediaSizeStructArray);
        foreach ($mediaRelationsArray as $media) {
            if ($success) {
                $sql = "REPLACE INTO #__hub2_author_media_relations ";
                $sql .= "(author_id, media_id, size_description) VALUES (";
                $sql .= $authorId.",".$media->id.",".$db->Quote($media->size).")";
                $success = $db->Execute( $sql ) ;
            }
        }
        $return->success = ($success !== false);
        if (!$success) {
            $return->msg = $db->getErrorMsg();
        }
        return $return;
    }

    /**
     * Method to delete an author from site
     * @param int authorId the ID of the author to delete
     * @param string $encPassword1 pkey1 encrypted with a salt
     * @param string $encPassword2 pkey2 encrypted with a salt
     * @return SoapResponse
     */
    function deleteAuthor($authorId, $encPassword1, $encPassword2) {

        $return = new SoapResponse;
        if (!$this->authenticate($encPassword1) && !$this->authenticate($encPassword2)) {
            $return->success = false;
            $return->msg = "Password Error";
            return $return;
        }
        /*
         if (stripos(JURI::base(),'mysite2')) {
         $return->success = false;
         $return->msg = "Password Error";
         return $return;
         }
         if (stripos(JURI::base(),'mysite3')) {
         exit;
         }
         */
        $db = &JFactory::getDBO();
        $db->setQuery("SELECT * FROM #__hub2_authors WHERE id=".$authorId);
        $db->query();

        if($db->getNumRows()>0) {
            $success = $db->Execute("DELETE FROM #__hub2_authors WHERE id=".$authorId);
            if ($success) {
                $success = $db->Execute(
                "DELETE FROM #__hub2_author_media_relations WHERE author_id=".$authorId);
            }

            if (!$success) {
                $return->msg = $db->getErrorMsg();
                $return->success = false;
            } else {
                $return->success = true;
            }
        } else {
            $return->msg = "Author does not exit on site.";
            $return->success = true;
        }

        return $return;
    }

    /**
     * Method to update media on site
     * @param string[] mediaReplaceSQL the replace portion of SQL command to create media
     * @param string encPassword1 pkey1 encrypted with a salt
     * @param string encPassword2 pkey2 encrypted with a salt
     * @return SoapResponse
     */
    function updateMedia($mediaReplaceSQL, $encPassword1, $encPassword2) {

        $return = new SoapResponse;
        if (!$this->authenticate($encPassword1) && !$this->authenticate($encPassword2)) {
            $return->success = false;
            $return->msg = "Password Error";
            return $return;
        }

        $db = &JFactory::getDBO();
        $success = true;
        $mediaReplaceSQLArray = $this->getSOAPArray(@$mediaReplaceSQL->stringArray);
        if (count($mediaReplaceSQLArray) > 0) {
            foreach($mediaReplaceSQLArray as $replaceSQL) {
                if (!$db->Execute("REPLACE INTO #__hub2_site_media ".$replaceSQL)) {
                    $success = false;
                }
            }
        }
        $return->success = $success;
        if (!$success) {
            $return->msg = $db->getErrorMsg();
        }
        return $return;
    }

    /**
     * Method to update site details
     * @param int $siteId the ID of the site to update
     * @param string $itemSQL the replace portion of the SQL
     * @param int[] $relationsArray the IDs of the category
     * @param string $encPassword1 pkey1 encrypted with a salt
     * @param string $encPassword2 pkey2 encrypred with a salt
     * @return SoapResponse
     */
    function updateSite($siteId, $itemSQL, $relationsArray, $encPassword1, $encPassword2) {

        $return = new SoapResponse;
        if (!$this->authenticate($encPassword1) && !$this->authenticate($encPassword2)) {
            $return->success = false;
            $return->msg = "Password Error";
            return $return;
        }

        $db = &JFactory::getDBO();
        $success = $db->Execute("REPLACE INTO #__hub2_sites ".$itemSQL);

        if ($success) {
            $success = $db->Execute(
                "DELETE FROM #__hub2_sites_category_relations where site_id=".
            $siteId);
        }
        $relationsArrayArray = $this->getSOAPArray(@$relationsArray->intArray);
        if ($relationsArrayArray) {
            foreach ($relationsArrayArray as $id) {
                if ($success) {
                    $sql = "REPLACE INTO #__hub2_sites_category_relations ";
                    $sql .= "(site_id, category_id) VALUES (";
                    $sql .= $siteId.",".$id.")";
                    $success = $db->Execute( $sql );
                }
            }
        }
        if ($success) {
            $success = $db->Execute("delete from #__hub2_config");
        }
        if ($success) {
            $success = $db->Execute("insert into #__hub2_config (is_site,is_hub) VALUES (1,0)");
        }
        $return->success = ($success !== false);
        if (!$success) {
            $return->msg = $db->getErrorMsg();
        }
        return $return;
    }

    /**
     * Method to update/add a item on site
     * @param string $type the type of the content e.g. articles, events
     * @param int $typeId the ID of the type
     * @param int $itemId the ID of the item to add/update
     * @param int $headId the ID of the head version of the content
     * @param string $itemSQL the replace portion for adding/updating item
     * @param MediaSizeStruct[] $mediaRelations the IDs of the related media
     * @param string $mediaCaption caption for media
     * @param string $topicRelations the IDs of the related topic
     * @param string $tagRelations the IDs of the related tags
     * @param string $categoryRelations the IDs of the related category
     * @param string $postcodeRelations the IDs of the related postcode
     * @param string $multirelations the typeIDs and headIDs of the related content
     * @param string $encPassword1 pkey1 encrypted with a salt
     * @param string $encPassword2 pkey2 encrypted with a salt
     * @return SoapResponse
     */
    function updateItem($type, $typeId, $itemId, $headId, $itemSQL, $mediaRelations,
    $mediaCaption, $topicRelations, $tagRelations, $categoryRelations, $postcodeRelations,
    $multirelations, $encPassword1, $encPassword2) {

        $return = new SoapResponse;
        if (!$this->authenticate($encPassword1) && !$this->authenticate($encPassword2)) {
            $return->success = false;
            $return->msg = "Password Error";
            return $return;
        }
        /*
         if (stripos(JURI::base(),'mysite2')) {
         $return->success = false;
         $return->msg = "Password Error";
         return $return;
         }
         if (stripos(JURI::base(),'mysite3')) {
         exit;
         }
         */
        $db = &JFactory::getDBO();

        // get old hits first
        $db->setQuery("select hits from #__hub2_{$type} where head_id={$headId}");
        $hits = $db->loadResult();

        // delete old content
        $success = $db->Execute("delete from #__hub2_{$type} where head_id={$headId}");

        if ($success) {
            $success = $db->Execute("DELETE FROM #__hub2_item_media_relations where
            type_id={$typeId} AND item_id={$headId}") ;
        }
        if ($success) {
            $success = $db->Execute("DELETE FROM #__hub2_item_topic_relations where
            type_id={$typeId} AND item_id={$headId}") ;
        }
        if ($success) {
            $success = $db->Execute("DELETE FROM #__hub2_item_tag_relations where
            type_id={$typeId} AND item_id={$headId}") ;
        }
        if ($success) {
            $success = $db->Execute("DELETE FROM #__hub2_item_category_relations where
            type_id={$typeId} AND item_id={$headId}") ;
        }
        if ($success) {
            $success = $db->Execute("DELETE FROM #__hub2_item_postcode_relations where
            type_id={$typeId} AND item_id={$headId}") ;
        }

        if ($success) {
            $success = $db->Execute("DELETE FROM #__hub2_common_search WHERE
            type_id=".$typeId." AND head_id=".$headId);
        }

        $mediaRelationsArray = $this->getSOAPArray(@$mediaRelations->MediaSizeStructArray);
        foreach ($mediaRelationsArray as $media) {
            if ($success) {
                $sql = "REPLACE INTO #__hub2_item_media_relations ";
                $sql .= "(type_id,item_id, media_id, size_description,caption) VALUES (";
                $sql .= $typeId.",".$headId.",".$media->id.",".$db->Quote($media->size).
                            ",".$db->Quote($mediaCaption).")";
                $success = $db->Execute( $sql ) ;
            }
        }

        $topicRelations = unserialize($topicRelations);
        foreach ($topicRelations as $id) {
            if ($success) {
                $sql = "REPLACE INTO #__hub2_item_topic_relations ";
                $sql .= "(type_id,item_id, topic_id) VALUES (";
                $sql .= $typeId.",".$headId.",".$id.")";
                $success = $db->Execute( $sql ) ;
            }
        }
        $tagRelations = unserialize($tagRelations);
        foreach ($tagRelations as $id) {
            if ($success) {
                $sql = "REPLACE INTO #__hub2_item_tag_relations ";
                $sql .= "(type_id,item_id, tag_id) VALUES (";
                $sql .= $typeId.",".$headId.",".$id.")";
                $success = $db->Execute( $sql ) ;
            }
        }

        $categoryRelations = unserialize($categoryRelations);
        foreach ($categoryRelations as $cat_and_ordering) {
            if ($success) {
                $sql = "REPLACE INTO #__hub2_item_category_relations ";
                $sql .= "(type_id,item_id, category_id, ordering) VALUES (";
                $sql .= $typeId.",".$headId.",".$cat_and_ordering['id'].
                        ','.$cat_and_ordering['ordering'].")";
                $success = $db->Execute( $sql ) ;
            }
        }

        $postcodeRelations = unserialize($postcodeRelations);
        foreach ($postcodeRelations as $id) {
            if ($success) {
                $sql = "REPLACE INTO #__hub2_item_postcode_relations ";
                $sql .= "(type_id,item_id, postcode_id) VALUES (";
                $sql .= $typeId.",".$headId.",".$id.")";
                $success = $db->Execute( $sql ) ;
            }
        }
        require_once(JPATH_SITE.DS.'components'.DS.'com_hub2'.DS.'models'.DS.'contenttypes.php');
        $contentTypeModel = Hub2ModelContentTypes::getContentTypeInstance();
        $canExport = $contentTypeModel->canExportRelationsToSite($type);
        if ((int)$canExport == 1) {
            require_once(JPATH_SITE.DS.'components'.DS.'com_hub2'.DS.
            'tables'.DS.'itemmultirelations.php');
            $multiRelationsTable = new Hub2TableItemMultiRelations($db);

            if ($success) {
                $success = $multiRelationsTable->deleteRelations($typeId,$headId);
            }
            $multirelations = unserialize($multirelations);
            if ($success) {
                // Update item-multirelations mapping
                $success = $multiRelationsTable->updateMultiRelations($type, $headId,
                $multirelations);
            }
        }

        if ($success) {
            $success = $db->Execute("REPLACE INTO #__hub2_{$type} ".$itemSQL) ;
            if (!empty($hits)) {
                $db->Execute("UPDATE #__hub2_{$type} set hits={$hits} where head_id={$headId}");
            }
        }

        // inserting data into common_search table
        if ($success) {
            $commonsearch_sql = "INSERT INTO #__hub2_common_search
            (type_id, head_id, item_id, publish_up, publish_down, created, title, search_index)
            SELECT $typeId,head_id,id,publish_up,publish_down,created,title,search_index FROM
            #__hub2_{$type} WHERE head_id={$headId}";
            // error_log($commonsearch_sql);
            $success = $db->Execute($commonsearch_sql);
        }
        //error_log(print_r($commonsearch_sql,true));
        // end inserting data
        //exit;
        #edited by saumava on 27-06-2011 to manipulate
        #the social tokens are published or not(Start).
        $facebook_param = "";
        $twiter_params = "";
        if ($success) {
            $db->setQuery("select params, publish_up from #__hub2_{$type} where head_id={$headId}");
            $rows = $db->loadObject();
            $params = new JParameter($rows->params);
            $facebook_param = $params->get( 'published_on_facebok' );
            $twiter_params = $params->get( 'published_on_twiter' );
            //error_log($facebook_param . "\n" . $twiter_params . "\n");
            if ($facebook_param == "1" || $twiter_params == "1") {
                $m = new Hub2ModelContent($type);
                $item = $m->getItem($headId);
                $url = Hub2HelperRoute::getItemRoute($headId,$item,$type);
            }
            $published = 0;
            if($facebook_param == "1") {
                // if already published then delete from facebook first else
                // we will loose the handle stored in the table
                $sql = "INSERT INTO #__hub2_social_tokens_status ";
                $sql .= "(content_id, type_id, published, published_up, url, ";
                $sql .= "destination,feedback_data_from_social_engines) VALUES (";
                $sql .= $headId.",".$typeId.",".$published.",'";
                $sql .= $rows->publish_up."','".$url."','Facebook', '')";
                $sql .= " ON DUPLICATE KEY UPDATE published='0'";
                //error_log($sql);
                $success = $db->Execute( $sql ) ;
            }
            if($twiter_params == "1") {
                // if already published then delete from twiter first else
                // we will loose the handle stored in the table
                $sql = "INSERT INTO #__hub2_social_tokens_status ";
                $sql .= "(content_id, type_id, published, published_up, url,";
                $sql .= "destination,feedback_data_from_social_engines) VALUES (";
                $sql .= $headId.",".$typeId.",".$published.",'";
                $sql .= $rows->publish_up."', '".$url."','Twitter', '' )";
                $sql .= " ON DUPLICATE KEY UPDATE published='0'";
                $success = $db->Execute( $sql ) ;
            }
        }
        #End


        // put the item
        $return->success = ($success !== false);
        if (!$success) {
            $return->msg = $db->getErrorMsg();
        }
        return $return;
    }

    /**
     * Method to delete an item on site
     * @param string $type the type of item to delete
     * @param int $typeId the type_id of the item to delete
     * @param int $headId the head_id of the item to delete
     * @param string $encPassword1 pkey1 encrypted with a salt
     * @param string $encPassword2 pkey2 encrypted with a salt
     * @return SoapResponse
     */
    function deleteItem($type,$typeId,$headId, $encPassword1, $encPassword2) {

        $return = new SoapResponse;
        if (!$this->authenticate($encPassword1) && !$this->authenticate($encPassword2)) {
            $return->success = false;
            $return->msg = "Password Error";
            return $return;
        }
        /*
         if (stripos(JURI::base(),'mysite2')) {
         $return->success = false;
         $return->msg = "Password Error";
         return $return;
         }
         if (stripos(JURI::base(),'mysite3')) {
         exit;
         }
         */
        $db = &JFactory::getDBO();
        $success = $db->Execute("DELETE FROM #__hub2_{$type} WHERE head_id=".$headId);
        if ($success) {
            $success = $db->Execute(
            "DELETE FROM #__hub2_item_media_relations WHERE type_id=".
            $typeId." AND item_id={$headId}");
        }
        if ($success) {
            $success = $db->Execute(
            "DELETE FROM #__hub2_item_topic_relations WHERE type_id=".
            $typeId." AND item_id={$headId}");
        }
        if ($success) {
            $success = $db->Execute(
            "DELETE FROM #__hub2_item_tag_relations WHERE type_id=".
            $typeId." AND item_id={$headId}");
        }
        if ($success) {
            $success = $db->Execute(
            "DELETE FROM #__hub2_item_category_relations WHERE type_id=".
            $typeId." AND item_id={$headId}");
        }
        if ($success) {
            $success = $db->Execute(
            "DELETE FROM #__hub2_item_postcode_relations WHERE type_id=".
            $typeId." AND item_id={$headId}");
        }
        if ($success) {
            $sql = "SELECT name from #__hub2_content_types where enable_relation_in_edit=0";
            $db->setQuery($sql);
            $unrelated = $db->loadResultArray();
            if (!in_array($type,$unrelated)) {
                $success = $db->Execute(
                "DELETE FROM #__hub2_item_multirelations WHERE type_id_1=".
                $typeId." AND head_id_1={$headId}");
                $success = $db->Execute(
                "DELETE FROM #__hub2_item_multirelations WHERE type_id_2=".
                $typeId." AND head_id_2={$headId}");
            }
        }

        /* deleting row from common_search table */
        if ($success) {
            $success = $db->Execute(
            "DELETE FROM #__hub2_common_search WHERE type_id=".$typeId." AND head_id=".$headId);
        }

        /* BY Saumava */
        if ($success) {
            $update_query = "UPDATE #__hub2_social_tokens_status SET ";
            $update_query.= "published = '-1' WHERE type_id=".$typeId." AND content_id={$headId}";
            $success = $db->Execute($update_query);
        }

        $return->success = ($success !== false);
        if (!$success) {
            $return->msg = $db->getErrorMsg();
        } else {
            $dispatcher = &JDispatcher::getInstance();
            $dispatcher->trigger('onItemDelete',array($typeId,$headId));
        }

        return $return;
    }

    /**
     * Method to update comment on site
     * @param int $commentId the ID of the category to add/update
     * @param string $commentSQL the replace portion for adding/updating category
     * @param string $encPassword1 pkey1 encrypted with a salt
     * @param string $encPassword2 pkey2 encrypted with a salt
     * @return SoapResponse
     */
    function updateComment($commentId, $commentSQL, $encPassword1, $encPassword2) {

        $return = new SoapResponse;
        if (!$this->authenticate($encPassword1) && !$this->authenticate($encPassword2)) {
            $return->success = false;
            $return->msg = "Password Error";
            return $return;
        }
        /*
         if (stripos(JURI::base(),'mysite2')) {
         $return->success = false;
         $return->msg = "Password Error";
         return $return;
         }
         if (stripos(JURI::base(),'mysite3')) {
         exit;
         }
         */
        $db = &JFactory::getDBO();
        $success = $db->Execute("REPLACE INTO #__hub2_comments ".$commentSQL);

        $return->success = $success;
        if (!$success) {
            $return->msg = $db->getErrorMsg();
        }
        return $return;
    }

    /**
     * Method to delete a comment on site
     * @param int $categoryId the ID of the category to delete
     * @param string $encPassword1 pkey1 encrypted with a salt
     * @param string $encPassword2 pkey2 encrypted with a salt
     * @return SoapResponse
     */
    function deleteComment($commentId, $encPassword1, $encPassword2) {

        $return = new SoapResponse;
        if (!$this->authenticate($encPassword1) && !$this->authenticate($encPassword2)) {
            $return->success = false;
            $return->msg = "Password Error";
            return $return;
        }
        /*
         if (stripos(JURI::base(),'mysite2')) {
         $return->success = false;
         $return->msg = "Password Error";
         return $return;
         }
         if (stripos(JURI::base(),'mysite3')) {
         exit;
         }
         */
        $db = &JFactory::getDBO();
        $success = $db->Execute("DELETE FROM #__hub2_comments WHERE id=".$commentId);
        $return->success = ($success !== false);
        if (!$success) {
            $return->msg = $db->getErrorMsg();
        }
        return $return;
    }

    /**
     * Method to update a postcode on a site
     * @param string $itemSQL the replace portion of SQL command to create tag
     * @param string $encPassword1 pkey1 encrypted with a salt
     * @param string $encPassword2 pkey2 encrypted with a salt
     * @return SoapResponse
     */
    public function updatePostcode($itemSQL, $encPassword1, $encPassword2) {

        $return = new SoapResponse;
        if (!$this->authenticate($encPassword1) && !$this->authenticate($encPassword2)) {
            $return->success = false;
            $return->msg = "Password Error";
            return $return;
        }

        $db = &JFactory::getDBO();
        $success = $db->Execute("REPLACE INTO #__hub2_postcodes ".$itemSQL);
        $return->success = ($success !== false);
        if (!$success) {
            $return->msg = $db->getErrorMsg();
        }
        return $return;
    }

    /**
     * Method to delete all postcodes on the site except the specified one
     * @param int[] exceptionIds an array of postcode IDs to save or null
     * @param string encPassword1 pkey1 encrypted with a salt
     * @param string encPassword2 pkey2 encrypted with a salt
     * @return SoapResponse
     */
    function deleteAllExceptPostcode($exceptionIds, $encPassword1, $encPassword2) {

        $return = new SoapResponse;
        if (!$this->authenticate($encPassword1) && !$this->authenticate($encPassword2)) {
            $return->success = false;
            $return->msg = "Password Error";
            return $return;
        }
        $db = &JFactory::getDBO();
        $whereClause = '';

        $eids = $this->getSOAPArray(@$exceptionIds->intArray);
        if (count($eids) > 0) {
            $whereClause = 'WHERE id NOT IN ('.implode(',',$eids).')';
        }
        $success = $db->Execute("DELETE FROM #__hub2_postcodes ".$whereClause);
        $return->success = ($success !== false);
        if (!$success) {
            $return->msg = $db->getErrorMsg();
        }
        return $return;
    }

    /**
     * Method to delete a postcode from site
     * @param string $itemId the ID of the tag to delete
     * @param string $encPassword1 pkey1 encrypted with a salt
     * @param string $encPassword2 pkey2 encrypted with a salt
     * @return SoapResponse
     */
    function deletePostcode($itemId, $encPassword1, $encPassword2) {

        $return = new SoapResponse;
        if (!$this->authenticate($encPassword1) && !$this->authenticate($encPassword2)) {
            $return->success = false;
            $return->msg = "Password Error";
            return $return;
        }
        $db = &JFactory::getDBO();
        $success = $db->Execute("DELETE FROM #__hub2_postcodes WHERE id=".$itemId);
        $return->success = ($success !== false);
        if (!$success) {
            $return->msg = $db->getErrorMsg();
        }
        return $return;
    }

    /**
     * Method to update rss feed items on site
     * @param string $type
     * @param string $rssitemSQL the replace portion for adding/updating an item
     * @param string $encPassword1 pkey1 encrypted with a salt
     * @param string $encPassword2 pkey2 encrypted with a salt
     * @return SoapResponse
     */
    function updateRssItem($type, $rssitemSQL, $encPassword1, $encPassword2) {
        return $this->executeUpdate('#__hub2_' . $type, $rssitemSQL
        , $encPassword1, $encPassword2);
    }

    /**
     * Method to update rss feed items on site
     * @param string $type
     * @param string $fileLocation location of file on the Hub to pick up payload
     * @param string $encPassword1 pkey1 encrypted with a salt
     * @param string $encPassword2 pkey2 encrypted with a salt
     * @return SoapResponse
     */
    function updateRssItemLink($type, $fileLocation, $encPassword1, $encPassword2) {
        // Get URL of file from location
        require_once(dirname(__FILE__).DS.'..'.DS.'models'.DS.'site.php');
        $siteModel = new Hub2ModelSite();
        $site = $siteModel->getLocalSiteDetails();
        $hubUrl = $site['hub2_url'];
        // Get file content
        $fileUrl = $hubUrl . '/' . $fileLocation;
        $rssitemSQL = file_get_contents($fileUrl);
        if (!$rssitemSQL) {
            $response = new SoapResponse();
            $this->setError($response, JText::_('ERROR_CANNOT_READ_FILE') .
            ' ' . $fileUrl);
            return $response;
        }
        $response = $this->executeUpdate('#__hub2_' . $type, $rssitemSQL
        , $encPassword1, $encPassword2);
        return $response;
    }

    /**
     * Method to delete an item on site
     * @param string $type the type of item to delete. Use to determine table name
     * @param int $id the id of the item to delete
     * @param string $encPassword1 pkey1 encrypted with a salt
     * @param string $encPassword2 pkey2 encrypted with a salt
     * @return SoapResponse
     */
    function deleteRssItem($type, $id, $encPassword1, $encPassword2) {

        $return = new SoapResponse;
        if (!$this->authenticate($encPassword1) && !$this->authenticate($encPassword2)) {
            $return->success = false;
            $return->msg = "Password Error";
            return $return;
        }
        $db = &JFactory::getDBO();
        $success = $db->Execute("DELETE FROM #__hub2_{$type} WHERE id=".$id);

        $return->success = ($success !== false);
        if (!$success) {
            $return->msg = $db->getErrorMsg();
        }
        return $return;
    }

    /**
     * Method to delete all items in a rss feed
     * @param string $type the type of item to delete. Use to determine table name
     * @param string $feedId the id of the feed to delete
     * @param string $encPassword1 pkey1 encrypted with a salt
     * @param string $encPassword2 pkey2 encrypted with a salt
     * @return SoapResponse
     */
    function deleteRssFeed($type, $feedId, $encPassword1, $encPassword2) {
        return $this->executeDelete(
            '#__hub2_' . $type, 'rss_feed_id', $feedId, $encPassword1, $encPassword2);
    }

    /**
     * updates item ordering on site
     * @param string $type
     * @param string $serializedItemOrdering
     * @param string $encPassword1
     * @param string $encPassword2
     * @return SoapResponse
     */
    public function updateItemOrdering($type,$serializedItemOrdering,$encPassword1, $encPassword2) {
        $return = new SoapResponse;
        if (!$this->authenticate($encPassword1)
        && !$this->authenticate($encPassword2)) {
            $return->success = false;
            $return->msg = "Password Error";
            return $return;
        }
        $details = unserialize($serializedItemOrdering);
        $success = true;
        $db = &JFactory::getDBO();
        foreach ($details as $detail) {
            // check if the id is present in articles table
            // this check is done to ensure that the latest approved version of the item
            // is currently on the site. The latest approved versiob might be waiting on
            // publising via a site editor
            $sql = 'select count(id) from #__hub2_'.$type.' WHERE id='.$detail['id'];
            $db->setQuery($sql);
            $count = $db->loadResult();
            if ($count == 1) {
                $sql = 'UPDATE #__hub2_item_category_relations set ordering=' .
                $detail['ordering'] .
                ' WHERE item_id='.$detail['item_id'].' AND category_id='.$detail['category_id'];
                $res = $db->Execute($sql);
                if (!$res) {
                    $success = false;
                    break;
                }
            }
        }
        $return->success = $success;
        $return->msg = $db->getErrorMsg();
        return $return;
    }

    /**
     * updates item ordering on site
     * @param string $serializedOrdering
     * @param string $encPassword1
     * @param string $encPassword2
     * @return SoapResponse
     */
    public function updateCategoryOrdering($serializedOrdering,$encPassword1, $encPassword2) {
        $return = new SoapResponse;
        if (!$this->authenticate($encPassword1)
        && !$this->authenticate($encPassword2)) {
            $return->success = false;
            $return->msg = "Password Error";
            return $return;
        }
        $details = unserialize($serializedOrdering);
        $success = true;
        $db = &JFactory::getDBO();
        foreach ($details as $detail) {
            $sql = 'UPDATE #__hub2_categories set ordering=' .
            $detail['ordering'] .' WHERE id='.$detail['id'];
            $res = &$db->Execute($sql);
            if (!$res) {
                $success = false;
                break;
            }
        }
        // rebuild the tree
        if ($success) {
            require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.
            'com_hub2'.DS.'models'.DS.'category.php');
            $model = new Hub2ModelCategory();
            $model->rebuildOnExternalSave();
        }
        $return->success = $success;
        $return->msg = $db->getErrorMsg();
        return $return;
    }

    /**
     * updates user record on site
     * @param string $sql
     * @param string $encPassword1
     * @param string $encPassword2
     * @return SoapResponse
     public function updateUser($sql,$encPassword1, $encPassword2) {
     $return = new SoapResponse;
     if (!$this->authenticate($encPassword1)
     && !$this->authenticate($encPassword2)) {
     $return->success = false;
     $return->msg = "Password Error";
     return $return;
     }

     $db = &JFactory::getDBO();

     // insert user record to site
     $result = $db->Execute("replace into #__users " . $sql);

     if ($result == false) {
     $return->success = false;
     } else {
     $return->success = true;
     }

     $return->msg = $db->getErrorMsg();
     return $return;
     }
     */


    /**
     * updates user record on site
     * @param string[] $sql
     * @param string $encPassword1
     * @param string $encPassword2
     * @return SoapResponse
     public function updateAcl($sql,$encPassword1, $encPassword2) {
     $return = new SoapResponse;
     if (!$this->authenticate($encPassword1)
     && !$this->authenticate($encPassword2)) {
     $return->success = false;
     $return->msg = "Password Error";
     return $return;
     }

     $db = &JFactory::getDBO();

     $sql = $this->getSOAPArray(@$sql->stringArray);
     // insert acl record to site
     $result = $db->Execute("replace into #__core_acl_aro " . $sql[0]);

     // insert group(aro) mapping to site
     $result1 = $db->Execute("replace into #__core_acl_groups_aro_map " . $sql[1]);

     if ($result == false || $result1 == false) {
     $return->success = false;
     } else {
     $return->success = true;
     }

     $return->msg = $db->getErrorMsg();
     return $return;
     }
     */

    /**
     * Method to update/add multiple site parameters on site
     * @param string[] itemSQLarray array of itemSQL the replace portion of SQL command
     * @param string[] $valueSQLarray array of SQL to replace in the table
     * @param string encPassword1 pkey1 encrypted with a salt
     * @param string encPassword2 pkey2 encrypted with a salt
     * @return SoapResponse
     */
    function updateMultipleSiteparams($itemSQLarray,$valueSQLarray, $encPassword1, $encPassword2) {

        $return = new SoapResponse;
        if (!$this->authenticate($encPassword1) && !$this->authenticate($encPassword2)) {
            $return->success = false;
            $return->msg = "Password Error";
            return $return;
        }

        $db = &JFactory::getDBO();
        $success = true;
        $itemSQLarray = $this->getSOAPArray(@$itemSQLarray->stringArray);
        foreach ($itemSQLarray as $itemSQL) {
            if (!$db->Execute("REPLACE INTO #__hub2_siteparams ".$itemSQL)) {
                $success = false;
            }
        }
        $valueSQLarray = $this->getSOAPArray(@$valueSQLarray->stringArray);
        if ($success) {
            foreach ($valueSQLarray as $valueSQL) {
                if (!$db->Execute("REPLACE INTO #__hub2_siteparams_values ".$valueSQL)) {
                    $success = false;
                }
            }
        }
        $return->success = ($success !== false);
        if (!$success) {
            $return->msg = $db->getErrorMsg();
        }
        return $return;
    }

    /**
     * Method to update/add site parameter on site
     * @param int $paramId the parameter id
     * @param string itemSQL the replace portion of SQL command to create parameter
     * @param string $value the value media attached to the author
     * @param string encPassword1 pkey1 encrypted with a salt
     * @param string encPassword2 pkey2 encrypted with a salt
     * @return SoapResponse
     */
    function updateSiteparams($paramId, $itemSQL, $value, $encPassword1, $encPassword2) {

        $return = new SoapResponse;
        if (!$this->authenticate($encPassword1) && !$this->authenticate($encPassword2)) {
            $return->success = false;
            $return->msg = "Password Error";
            return $return;
        }

        $db = &JFactory::getDBO();
        $success = $db->Execute("REPLACE INTO #__hub2_siteparams ".$itemSQL);
        $db->setQuery("select id from #__hub2_sites LIMIT 1");
        $site_id = $db->loadResult();

        if ($success) {
            $sql = "REPLACE INTO #__hub2_siteparams_values ";
            $sql .= "(site_id, siteparam_id, value) VALUES ({$site_id},";
            $sql .= $paramId.",".$db->Quote($value).")";
            $success = $db->Execute( $sql ) ;
        }
        $return->success = ($success !== false);
        if (!$success) {
            $return->msg = $db->getErrorMsg();
        }
        return $return;
    }

    /**
     * Method to delete a site parameter
     * @param int paramId the ID of the parameter to delete
     * @param string $encPassword1 pkey1 encrypted with a salt
     * @param string $encPassword2 pkey2 encrypted with a salt
     * @return SoapResponse
     */
    function deleteSiteparams($paramId, $encPassword1, $encPassword2) {
        $return = new SoapResponse;
        if (!$this->authenticate($encPassword1) && !$this->authenticate($encPassword2)) {
            $return->success = false;
            $return->msg = "Password Error";
            return $return;
        }
        $db = &JFactory::getDBO();
        $db->setQuery("SELECT * FROM #__hub2_siteparams WHERE id=".$paramId);
        $db->query();

        if($db->getNumRows()>0) {
            $success = $db->Execute("DELETE FROM #__hub2_siteparams WHERE id=".$paramId);
            if ($success) {
                $success = $db->Execute(
                "DELETE FROM #__hub2_siteparams_values WHERE siteparam_id=".$paramId);
            }

            if (!$success) {
                $return->msg = $db->getErrorMsg();
                $return->success = false;
            } else {
                $return->success = true;
            }
        } else {
            $return->msg = "Failed to delete parameter from site. Parameter does not exit on site.";
            $return->success = false;
        }

        return $return;
    }

    /**
     * Method to delete a socialtokens
     * @param string $mediaType the Type of the parameter to delete
     * @param string $encPassword1 pkey1 encrypted with a salt
     * @param string $encPassword2 pkey2 encrypted with a salt
     * @return SoapResponse
     */
    public function deleteSocialtokens($mediaType, $encPassword1, $encPassword2) {

        $return = new SoapResponse;
        if (!$this->authenticate($encPassword1) && !$this->authenticate($encPassword2)) {
            $return->success = false;
            $return->msg = "Password Error";
            return $return;
        }
        $db = &JFactory::getDBO();
        $success = $db->Execute("DELETE FROM #__hub2_social_tokens WHERE media_type=".
        $db->Quote($mediaType));
        $return->success = ($success !== false);
        if (!$success) {
            $return->msg = $db->getErrorMsg();
        }
        return $return;
    }

    /**
     * Method to update a socialtokens on a site
     * @param string $itemSQL the replace portion of SQL command to create tag
     * @param string $encPassword1 pkey1 encrypted with a salt
     * @param string $encPassword2 pkey2 encrypted with a salt
     * @return SoapResponse
     */
    public function updateSocialtokens($itemSQL, $encPassword1, $encPassword2) {
        $return = new SoapResponse;
        if (!$this->authenticate($encPassword1) && !$this->authenticate($encPassword2)) {
            $return->success = false;
            $return->msg = "Password Error";
            return $return;
        }

        $db = &JFactory::getDBO();
        $success = $db->Execute("REPLACE INTO #__hub2_social_tokens ".$itemSQL);
        $return->success = ($success !== false);
        if (!$success) {
            $return->msg = $db->getErrorMsg();
        }
        return $return;
    }

    /**
     * Method to remove expired items
     * @param string $encPassword1 pkey1 encrypted with a salt
     * @param string $encPassword2 pkey2 encrypted with a salt
     * @return string
     */
    public function deleteExpiredItems($encPassword1, $encPassword2) {
        if (!$this->authenticate($encPassword1) && !$this->authenticate($encPassword2)) {
            $return = "Password Error";
            return $return;
        }
        // get content types
        $db = &JFactory::getDBO();
        // not to delete rss-items and appointments
        $sql = "SELECT name,id from #__hub2_content_types where
        name <> 'rss_items' AND name <> 'appointments'";
        $db->setQuery($sql);
        $types = $db->loadObjectList();
        $sql = "SELECT name from #__hub2_content_types where enable_relation_in_edit=0";
        $db->setQuery($sql);
        $unrelated = $db->loadResultArray();
        $jnow       =& JFactory::getDate();
        $now        = $jnow->toMySQL();

        // delete from individual tables and relation tables
        foreach ($types as $type) {
            $sql = "delete a,r1,r2,r3,r4,r5
from #__hub2_{$type->name} a
LEFT join #__hub2_item_tag_relations r1 ON a.head_id=r1.item_id
LEFT join #__hub2_item_topic_relations r2 ON a.head_id=r2.item_id
LEFT join #__hub2_item_category_relations r3 ON a.head_id=r3.item_id
LEFT join #__hub2_item_postcode_relations r4 ON a.head_id=r4.item_id
LEFT join #__hub2_item_media_relations r5 ON a.head_id=r5.item_id
where
(r1.type_id={$type->id} or r1.type_id is NULL) and
(r2.type_id={$type->id} or r2.type_id is NULL) and
(r3.type_id={$type->id} or r3.type_id is NULL) and
(r4.type_id={$type->id} or r4.type_id is NULL) and
(r5.type_id={$type->id} or r5.type_id is NULL) and
a.publish_down < ".$db->Quote($now);
            $db->Execute($sql);

            if (!in_array($type,$unrelated)) {
                $sql = "DELETE m FROM #__hub2_item_multirelations m
                left join #__hub2_{$type->name} a on m.head_id_1=a.head_id
                left join #__hub2_content_types c on m.type_id_2=c.id
                where type_id_1={$type->id} AND isNULL(a.head_id)
                AND c.enable_relation_in_edit=1";
                $db->Execute($sql);

                $sql = "DELETE m FROM #__hub2_item_multirelations m
                left join #__hub2_{$type->name} a on m.head_id_2=a.head_id
                left join #__hub2_content_types c on m.type_id_1=c.id
                where type_id_2={$type->id} AND isNULL(a.head_id)
                AND c.enable_relation_in_edit=1";
                $db->Execute($sql);
            }
        }

        // clean search tble except for appointments
        $sql = "SELECT id from #__hub2_content_types where
        name = 'appointments'";
        $db->setQuery($sql);
        $apTypeID = $db->loadResult();

        //clean common search table
        $db->Execute("DELETE FROM #__hub2_common_search
WHERE publish_down < ".$db->Quote($now). ' AND type_id <> '.$apTypeID);

        // clean cache
        jimport('joomla.filesystem.folder');
        $cache =& JFactory::getCache('');
        $cache->gc();

        // clear any other items
        $dispatcher = &JDispatcher::getInstance();
        $dispatcher->trigger('onItemDelete',array(0,1));
        return 'ok';
    }

    /**
     * Method to get schema of this site
     * @param string $encPassword1 pkey1 encrypted with a salt
     * @param string $encPassword2 pkey2 encrypted with a salt
     * @return string
     */
    public function getSchema($encPassword1, $encPassword2) {
        if (!$this->authenticate($encPassword1) && !$this->authenticate($encPassword2)) {
            $return = "Password Error";
            return $return;
        }
        $db = &JFactory::getDBO();
        require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_hub2'.
        DS.'helpers'.DS.'databaseDump.php');
        $result = Hub2AdminDatabaseDump::dumpSchema(true,true);
        $str = $result['schema'];
        $md5 = $result['md5'];
        $count = $result['count'];
        $config = &JFactory::getConfig();
        $tmpPath = $config->getValue('config.tmp_path','tmp');
        jimport('joomla.filesystem.path');
        $tmpPath = JPath::clean(JPATH_CONFIGURATION);
        // geta random filename
        $now = new JDate();
        $fname = $tmpPath.DS.'tmp'.DS.'schema.sql';
        if (file_put_contents($fname,$str)) {
            $url = str_replace(DS,'/',str_replace(JPATH_BASE.DS,'',$fname));
            return $url.'#'.$md5.'#'.$count;
        }
        return '';
    }

    /**
     * Method to get config of this site
     * @param string $encPassword1 pkey1 encrypted with a salt
     * @param string $encPassword2 pkey2 encrypted with a salt
     * @return string
     */
    public function getConfig($encPassword1, $encPassword2) {
        if (!$this->authenticate($encPassword1) && !$this->authenticate($encPassword2)) {
            $return = "Password Error";
            return $return;
        }
        $db = &JFactory::getDBO();
        require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_hub2'.
        DS.'helpers'.DS.'databaseDump.php');
        $result = Hub2AdminDatabaseDump::dumpConfig(true);
        $str = $result['config'];
        $md5 = $result['md5'];
        $config = &JFactory::getConfig();
        $tmpPath = $config->getValue('config.tmp_path','tmp');
        jimport('joomla.filesystem.path');
        $tmpPath = JPath::clean(JPATH_CONFIGURATION);
        // .str_replace(JPATH_ROOT,'',JPath::clean($tmpPath)));
        // geta random filename
        $now = new JDate();
        $fname = $tmpPath.DS.'tmp'.DS.'config.sql';
        if (file_put_contents($fname,$str)) {
            $url = str_replace(DS,'/',str_replace(JPATH_BASE.DS,'',$fname));
            return $url.'#'.$md5;
        }
        return '';
    }

    /**
     * Method to get UserId for a session
     * @param string $sessionID session ID to use
     * @param string $encPassword1 pkey1 encrypted with a salt
     * @param string $encPassword2 pkey2 encrypted with a salt
     * @return string id of the user
     */
    public function getUserId($sessionID,$encPassword1, $encPassword2) {
        if (!$this->authenticate($encPassword1) && !$this->authenticate($encPassword2)) {
            $return = "Password Error";
            return $return;
        }
        $conf =& JFactory::getConfig();
        $handler =  $conf->getValue('config.session_handler', 'none');
        $sessionStore = JSessionStorage::getInstance($handler, array());
        $data = $sessionStore->read($sessionID);
        session_decode($data);
        //error_log(@$_SESSION['__default']['user']->id);
        return @$_SESSION['__default']['user']->id;
    }

    private function executeUpdate($table, $sql, $encPassword1, $encPassword2) {
        $return = new SoapResponse;
        if (!$this->authenticate($encPassword1)
        && !$this->authenticate($encPassword2)) {
            $return->success = false;
            $return->msg = "Password Error";
            return $return;
        }

        $db = &JFactory::getDBO();
        $success = $db->Execute('REPLACE INTO ' . $table . ' ' . $sql);
        $return->success = ($success !== false);
        if (!$success) {
            $return->msg = $db->getErrorMsg();
        }
        return $return;
    }

    private function executeDelete($table, $key, $keyValue, $encPassword1, $encPassword2) {
        $return = new SoapResponse;
        if (!$this->authenticate($encPassword1)
        && !$this->authenticate($encPassword2)) {
            $return->success = false;
            $return->msg = "Password Error";
            return $return;
        }

        $db = &JFactory::getDBO();
        $success = $db->Execute('DELETE FROM ' . $table . ' WHERE ' . $key . ' = ' . $keyValue);

        $return->success = ($success !== false);
        if (!$success) {
            $return->msg = $db->getErrorMsg();
        }
        return $return;
    }

    private function setError(&$response, $msg) {
        $response->msg = $msg;
        $response->success = false;
    }

    /**
     * Method to update/add a ad on a site
     * @param string $itemSQL the replace portion of SQL command to create ad
     * @param int $cat_id the category id of this ad
     * @param string $encPassword1 pkey1 encrypted with a salt
     * @param string $encPassword2 pkey2 encrypted with a salt
     * @return SoapResponse
     */
    public function updateDjAd($itemSQL, $cat_id, $encPassword1, $encPassword2) {
        $return = new SoapResponse;
        if (!$this->authenticate($encPassword1) && !$this->authenticate($encPassword2)) {
            $return->success = false;
            $return->msg = "Password Error";
            return $return;
        }
        $db = &JFactory::getDBO();
        // check if category exists else add a bogus row which is unpublished
        $db->setQuery("select * FROM #__djcf_categories WHERE id=".$cat_id);
        $catObj = $db->loadObject();
        if (!$catObj) {
            $sql = "INSERT INTO #__djcf_categories (id,name,parent_id,description,";
            $sql .= "icon_url,published) VALUES ({$cat_id},'deleted',0,'','',0)";
            if (!$db->Execute($sql)) {
                $return->success = false;
                $return->msg = 'Category does not exist on site for this Ad';
            }
        }
        $success = $db->Execute("REPLACE INTO #__djcf_items ".$itemSQL);
        $return->success = ($success !== false);
        $return->msg="WSDL Update ok";
        if (!$success) {
            $return->msg = $db->getErrorMsg();
        }
        return $return;
    }

    /**
     * Method to delete a ad from site
     * @param int $itemId the ID of the ad to delete
     * @param string $encPassword1 pkey1 encrypted with a salt
     * @param string $encPassword2 pkey2 encrypted with a salt
     * @return SoapResponse
     */
    function deleteDjAd($itemId, $encPassword1, $encPassword2) {
        $return = new SoapResponse;
        if (!$this->authenticate($encPassword1) && !$this->authenticate($encPassword2)) {
            $return->success = false;
            $return->msg = "Password Error";
            return $return;
        }
        $db = &JFactory::getDBO();
        $db->setQuery("SELECT * FROM #__djcf_items WHERE id=".$itemId);
        $db->query();

        if($db->getNumRows()>0) {
            $success = $db->Execute("DELETE FROM #__djcf_items WHERE id=".$itemId);

            if (!$success) {
                $return->msg = $db->getErrorMsg();
                $return->success = false;
            } else {
                $return->success = true;
                $return->msg="WSDL Delete ok";
            }
        } else {
            $return->msg = "Ad does not exit on site.";
            $return->success = true;
        }

        return $return;
    }

    /**
     * Method to update/add a category on a site
     * @param string $itemSQL the replace portion of SQL command to create category
     * @param string $encPassword1 pkey1 encrypted with a salt
     * @param string $encPassword2 pkey2 encrypted with a salt
     * @return SoapResponse
     */
    public function updateDjCat($itemSQL, $encPassword1, $encPassword2) {
        $return = new SoapResponse;
        if (!$this->authenticate($encPassword1) && !$this->authenticate($encPassword2)) {
            $return->success = false;
            $return->msg = "Password Error";
            return $return;
        };
        $db = &JFactory::getDBO();
        // $success = $db->Execute("REPLACE INTO #__djcf_categories ".$itemSQL);
        $success = $db->Execute("INSERT INTO #__djcf_categories ".$itemSQL);
        $return->success = ($success !== false);
        $return->msg="WSDL Update ok";
        if (!$success) {
            $return->msg = $db->getErrorMsg();
        }
        return $return;
    }

    /**
     * Method to delete a Category from site
     * @param int $catId the ID of the category to delete
     * @param string $encPassword1 pkey1 encrypted with a salt
     * @param string $encPassword2 pkey2 encrypted with a salt
     * @return SoapResponse
     */
    function deleteDjCat($catId, $encPassword1, $encPassword2) {
        $return = new SoapResponse;
        if (!$this->authenticate($encPassword1) && !$this->authenticate($encPassword2)) {
            $return->success = false;
            $return->msg = "Password Error";
            return $return;
        }
        $db = &JFactory::getDBO();
        $db->setQuery("SELECT * FROM #__djcf_categories WHERE parent_id=".$catId);
        $db->query();

        if($db->getNumRows()>0) {
            $return->success = false;
            $return->msg = "Category Contains Child Items";
            return $return;
        } else {
            // delete expired items
            $now = new JDate();
            $date = $now->toFormat('%Y-%m-%d');
            $db->Execute("DELETE FROM #__djcf_items where cat_id=".
            $catId." AND date_exp < ".$db->Quote($date));

            $db->setQuery("SELECT * FROM #__djcf_items WHERE cat_id=".$catId);
            $db->query();
            if($db->getNumRows()>0) {
                $db->Execute("UPDATE #__djcf_categories set published=0 where id=".$catId);
                $return->success = true;
                $return->msg = "Category disabled on site";
                return $return;
            }
            $success = $db->Execute("DELETE FROM #__djcf_categories WHERE id=".$catId);

            if (!$success) {
                $return->msg = $db->getErrorMsg();
                $return->success = false;
            } else {
                $return->success = true;
                $return->msg="Category deleted on site";
            }
        }
        return $return;
    }

    /**
     * Method to clear the entire route cache
     * @param string $encPassword1 pkey1 encrypted with a salt
     * @param string $encPassword2 pkey2 encrypted with a salt
     * @return int 1 on success, 0 on failure
     */
    public function clearRouteCache($encPassword1, $encPassword2) {
        $return = new SoapResponse;
        if (!$this->authenticate($encPassword1) && !$this->authenticate($encPassword2)) {
            $return->success = 0;
            $return->msg = "Password Error";
            return serialize($return);
        }

        $cache =& JFactory::getCache('', 'callback', 'file');
        $config_cache_path = JPATH_CACHE;
        jimport('joomla.filesystem.folder');
        $folders = JFolder::folders($config_cache_path);
        for ($i=0, $n=count($folders); $i<$n; $i++) {
            $cache->clean($folders[$i]);
        }

        require_once(JPATH_SITE.DS.'components'.DS.'com_hub2'.DS.'models'.DS.'routecache.php');
        $model = & Hub2ModelRouteCache::getCacheInstance();
        $res = $model->clearEntireCache();
        if ($res) {
            return 1;
        } else {
            return 0;
        }
    }

    /**
     * Method to update/add a category on a site
     * @param string $configFile the replace portion of SQL command to create category
     * @param string $encPassword1 pkey1 encrypted with a salt
     * @param string $encPassword2 pkey2 encrypted with a salt
     * @return string
     */
    public function pushConfig($configFile, $encPassword1, $encPassword2) {
        $return = new SoapResponse;
        if (!$this->authenticate($encPassword1) && !$this->authenticate($encPassword2)) {
            $return->success = 0;
            $return->msg = "Password Error";
            return serialize($return);
        }
        $fname = $this->backupConfig();
        if (!$fname) {
            $return->success = 0;
            $return->msg = JText::_('BACKUP FAILED');
            return $return;
        }
        $stringSQL = file_get_contents($configFile);

        $db = &JFactory::getDBO();
        $db->setQuery($stringSQL);
        $result = $db->queryBatch();
        if (!$result) {
            // restore
            $stringSQL = file_get_contents($fname);
            $db->setQuery($stringSQL);
            $result = $db->queryBatch();

            $return->success = 0;
            $return->msg = $db->getErrorMsg();
            return $return;
        }
        /*
         $queries = $db->splitSQL($stringSQL);
         error_log(print_r($queries,true));
         foreach ($queries as $query) {
         if (!$db->Execute($query)) {
         $return->success = false;
         $return->msg = $db->getErrorMsg();
         return $return;
         }
         }*/
        $dispatcher = &JDispatcher::getInstance();
        $dispatcher->trigger('onMenuChange');

        $return->success = 1;// added By Sa
        $return->msg="WSDL Update ok";

        return serialize($return);
    }

    /**
     * Method to rebuild categories, branches, and products and services on site
     * @param string $encPassword1 pkey1 encrypted with a salt
     * @param string $encPassword2 pkey2 encrypted with a salt
     * @return string
     */
    public function rebuildBusinessIndex($encPassword1, $encPassword2) {
        $return = new SoapResponse;
        if (!$this->authenticate($encPassword1) && !$this->authenticate($encPassword2)) {
            $return->success = 0;
            $return->msg = "Password Error";
            return serialize($return);
        }

        $db = &JFactory::getDBO();
        $sql = "select bl_external_categories, bl_products_services,bl_brands from #__hub2_businesslistings";
        $db->setQuery($sql);

        $result = $db->loadObjectList();
        $categories = array();
        $products = array();
        $brands = array();
        foreach ($result as $row) {
            if (!empty($row->bl_external_categories)) {
                $c = preg_replace('/,\s+/i',',',$row->bl_external_categories);
                $cats = explode(',',$c);
                foreach ($cats as $cat) {
                    if (array_key_exists($cat,$categories)) {
                        $categories[$cat] += 1;
                    } else {
                        $categories[$cat] = 1;
                    }
                }
            }
            if (!empty($row->bl_products_services)) {
                $c = preg_replace('/,\s+/i',',',$row->bl_products_services);
                $prods = explode(',',$c);
                foreach ($prods as $prod) {
                    if (array_key_exists($prod,$products)) {
                        $products[$prod] += 1;
                    } else {
                        $products[$prod] = 1;
                    }
                }
            }
            if (!empty($row->bl_brands)) {
                $c = preg_replace('/,\s+/i',',',$row->bl_brands);
                $bra = explode(',',$c);
                foreach ($bra as $brand) {
                    if (array_key_exists($brand,$brands)) {
                        $brands[$brand] += 1;
                    } else {
                        $brands[$brand] = 1;
                    }
                }
            }
        }

        $sql = "TRUNCATE TABLE #__hub2_businesslistings_categories";
        $db->setQuery($sql);
        $db->query();
        foreach ($categories as $cat=>$num) {
            $sql = 'INSERT INTO #__hub2_businesslistings_categories (name,soundlike,count) VALUES';
            $sql .= '('.$db->Quote($cat).',soundex('.$db->Quote($cat).'),'.$num.')';
            $db->setQuery($sql);
            $db->query();
        }

        $sql = "TRUNCATE TABLE #__hub2_businesslistings_products_services";
        $db->setQuery($sql);
        $db->query();
        foreach ($products as $cat=>$num) {
            $sql = 'INSERT INTO #__hub2_businesslistings_products_services (name,soundlike,count) VALUES';
            $sql .= '('.$db->Quote($cat).',soundex('.$db->Quote($cat).'),'.$num.')';
            $db->setQuery($sql);
            $db->query();
        }

        $sql = "TRUNCATE TABLE #__hub2_businesslistings_brands";
        $db->setQuery($sql);
        $db->query();
        foreach ($brands as $cat=>$num) {
            $sql = 'INSERT INTO #__hub2_businesslistings_brands (name,soundlike,count) VALUES';
            $sql .= '('.$db->Quote($cat).',soundex('.$db->Quote($cat).'),'.$num.')';
            $db->setQuery($sql);
            $db->query();
        }

        $return = "1";
        return $return;
    }


    /**
     * Method to login a user from the Hub
     * @param int $userId ID of the user to login
     * @param string $acl serialized ACL object
     * @param string serialized user object
     * @param string $sessionID session ID to log the user into
     * @param string $encPassword1 pkey1 encrypted with a salt
     * @param string $encPassword2 pkey2 encrypted with a salt
     * @return SoapResponse
     */
    function ssoUserFromHub($userid,$acl,$user,$sessionID,$encPassword1,$encPassword2) {

        $response = new SoapResponse;
        if (!$this->authenticate($encPassword1) && !$this->authenticate($encPassword2)) {
            $response->success = false;
            $response->msg = "Password Error";
            return $response;
        }

        // check if the session still exists
        $storage = & JTable::getInstance('session');
        if (!$storage->load($sessionID)) {
            $response->success = false;
            $response->msg = 'Session does not exist';
            return $response;
        }
        $db = &JFactory::getDBO();

        require_once(JPATH_SITE.DS.'components'.DS.'com_hub2'.DS.
                'helpers'.DS.'user.php');
        // create the User if requried
        $user =  JArrayHelper::toObject(unserialize($user));
        $acl = unserialize($acl);
        Hub2UserHelper::createUser($user,$acl);

        $u = &JFactory::getUser($userid);
        if ($u->get('id') == $userid) {
            // login the user - put the data in the SSO
            $sql = "REPLACE INTO #__hub2_sso (session_id,userid) VALUES (".
            $db->Quote($sessionID).",".$userid.")";
            $db->setQuery($sql);
            if ($db->query()) {
                $response->success = true;
                return $response;
            } else {
                $response->success = false;
                $response->msg = $db->getErrorMsg();
                return $response;
            }
        }
        $response->success = false;
        $response->msg = 'User does not exist';
        return $response;
    }

    private function backupConfig() {
        $db = &JFactory::getDBO();
        require_once(JPATH_ADMINISTRATOR.DS.'components'.DS.'com_hub2'.
        DS.'helpers'.DS.'databaseDump.php');
        $result = Hub2AdminDatabaseDump::dumpConfig(true);
        $str = $result['config'];
        $md5 = $result['md5'];
        $config = &JFactory::getConfig();
        $tmpPath = $config->getValue('config.tmp_path','tmp');
        jimport('joomla.filesystem.path');
        $tmpPath = JPath::clean(JPATH_CONFIGURATION);
        // .str_replace(JPATH_ROOT,'',JPath::clean($tmpPath)));
        // geta random filename
        $now = new JDate();
        $fname = $tmpPath.DS.'tmp'.DS.'config_'.$now->toUnix().'.sql';
        if (file_put_contents($fname,$str)) {
            //error_log('here');
            $log = new Hub2TableSitemisc($db);
            $log->insert2log($fname, 'SOAP_CONFIG_BACKUP');
            return $fname;
        }
        return false;
    }

    private function &getSOAPArray($arrayObject) {
        $return = array();
        if (isset($arrayObject)) {
            // note: is array size is 1 then Array has the object directly
            // if size > 1 then it is an array
            if (is_array($arrayObject)) {
                $return = $arrayObject;
            } else {
                $return[] = $arrayObject;
            }
        }
        return $return;
    }

}