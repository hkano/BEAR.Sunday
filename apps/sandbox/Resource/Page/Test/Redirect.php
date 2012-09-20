<?php
/**
 * App resource
 *
 * @package    sandbox
 * @subpackage resource
 */
namespace sandbox\Resource\Page\Test;

use BEAR\Framework\Resource\AbstractPage as Page;

/**
 * Redirect page
 */
class Redirect extends Page
{
    /**
     * Get
     */
    public function onGet()
    {
        $this->code = 302;
        $this->headers = ['Location' => '/'];

        return $this;
    }
}
