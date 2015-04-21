<?php

namespace Octo\Twitter\Block;

use Octo\Block;
use Octo\Store;

class Tweet extends Block
{
    public static function getInfo()
    {
        return ['title' => 'Latest Tweets', 'editor' => false, 'js' => []];
    }

    public function renderNow()
    {
        $this->renderTweets();
    }

    public function renderTweets()
    {
        if (isset($this->templateParams['count'])) {
            $limit = intval($this->templateParams['count']);
        }
        $tweets = Store::get('Tweet')->getAllForScope('user', $limit);
        $this->view->tweets = $tweets;
    }
}
