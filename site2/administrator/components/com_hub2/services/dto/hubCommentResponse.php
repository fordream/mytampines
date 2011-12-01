<?php
require_once(dirname(__FILE__).DS.'hubCommentMessage.php');

/**
 *  The response object for comment web services
 */
class Hub2CommentResponse {
    /**
     *
     * @var Hub2CommentMessage[]
     */
    var $msg = array();
    /**
     *
     * @var boolean
     * True if all comments have been successfully created and added to Hub database;
     * false otherwise
     */
    var $success = false;
}