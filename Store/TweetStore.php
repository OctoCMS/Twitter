<?php

/**
 * Tweet store for table: tweet
 */

namespace Octo\Twitter\Store;

use b8\Database;
use Octo;
use Octo\Twitter\Model\Tweet;

/**
 * Tweet Store
 * @uses Octo\Twitter\Store\Base\TweetStoreBase
 */
class TweetStore extends Base\TweetStoreBase
{
	/**
     * Retrieve a tweet by a Twitter ID for a particular scope
     *
     * @param $twitterId
     * @param $scope
     * @return Tweet|null
     */
    public function getByTwitterIdForScope($twitterId, $scope)
    {
        $query = 'SELECT tweet.* FROM tweet WHERE twitter_id = :twitter_id AND scope = :scope LIMIT 1';
        $stmt = Database::getConnection('read')->prepare($query);
        $stmt->bindParam(':twitter_id', $twitterId);
        $stmt->bindParam(':scope', $scope);

        if ($stmt->execute()) {
            $res = $stmt->fetch(\PDO::FETCH_ASSOC);
            if ($res) {
                return new Tweet($res);
            } else {
                return null;
            }
        } else {
            return null;
        }
    }

    /**
     * Retrieve all tweets for a particular scope
     *
     * @param $scope
     * @param $limit
     * @return Tweet|null
     */
    public function getAllForScope($scope, $limit)
    {
        $query = 'SELECT tweet.* FROM tweet WHERE scope = :scope ORDER BY posted DESC';
        if (isset($limit)) {
            $query .= ' LIMIT ' . $limit;
        }
        $stmt = Database::getConnection('read')->prepare($query);
        $stmt->bindParam(':scope', $scope);

        if ($stmt->execute()) {
            $res = $stmt->fetchAll(\PDO::FETCH_ASSOC);

            $map = function ($item) {
                return new Tweet($item);
            };
            $rtn = array_map($map, $res);
            return $rtn;
        } else {
            return null;
        }
    }

    public function getLatestTweet()
    {
        $query = 'SELECT tweet.* FROM tweet ORDER BY posted DESC LIMIT 1';

        $stmt = Database::getConnection('read')->prepare($query);

        if ($stmt->execute()) {
            $res = $stmt->fetch(\PDO::FETCH_ASSOC);
            return new Tweet($res);
        } else {
            return null;
        }
    }
}
