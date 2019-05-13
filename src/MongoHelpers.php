<?php

namespace VIITech\Helpers;

use Exception;
use Illuminate\Support\Facades\DB;

class MongoHelpers
{

    /**
     * Unset Key From Mongodb Collection
     * @param string $db_connection database connection
     * @param string $collection_name collection/table name
     * @param string $item_key Find item key
     * @param string $item_value Find item value
     * @param string $remove_key unset the key
     * @return boolean
     */
    static function unsetKeyFromMongodbCollection($db_connection, $collection_name, $item_key, $item_value, $remove_key)
    {
        try {
            return DB::connection($db_connection)->collection($collection_name)->where($item_key, $item_value)->unset($remove_key);
        } catch (Exception $e) {
            return false;
        }
    }
}