<?php
/**
 * @version		$Id: $
 * @package		com_hub2
 * @copyright	(C) 2010 HyperLocalizer Pty Ltd. All rights reserved.
 * @license		HyperLocalizer proprietary.
 */

class HubCommentDTO {

    /**
     *
     * @var integer
     */
    public $id;
    /**
     *
     * @var integer
     */
    public $type_id;
    /**
     *
     * @var integer
     */
    public $item_id;
    /**
     *
     * @var integer
     */
    public $head_id;
    /**
     *
     * @var integer
     */
    public $site_id;
    /**
     *
     * @var integer
     */
    public $user_id;
    /**
     *
     * @var string
     */
    public $name;
    /**
     *
     * @var string
     */
    public $email;
    /**
     *
     * @var string
     */
    public $subject;
    /**
     *
     * @var string
     */
    public $comment;
    /**
     *
     * @var string
     */
    public $ip_address;
    /**
     *
     * @var string
     */
    public $link;
    /**
     *
     * @var integer
     */
    public $status;
     /**
     *
     * @var string
     */
    public $created;
        /**
     *
     * @var string
     */
    public $moderated;
        /**
     *
     * @var integer
     */
    public $moderated_by;
        /**
     *
     * @var string
     */
    public $moderator_comments;

}