<?php
/**
 *  The message object for comment web services representing result of adding
 *  a comment to the Hub
 */
class Hub2CommentMessage {
    /**
     *
     * @var string
     * 0 if comment has been added, -1 otherwise
     */
    var $responseCode = null;

    /**
     * @var integer
     * id of the comment added in #__hub2_comments
     */
    var $commentID = 0;

    /**
     *
     * @var string
     * Store comment being added
     */
    var $body = null;

    /**
     * @var string
     * Error message if any, null if comment is added successfully
     */
    var $error = null;
}