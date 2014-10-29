<?php

namespace Blog\Types;

use Doctrine\ODM\MongoDB\Types\Type;

/**
 * My custom datatype.
 */
class MongoDateType extends Type
{
    public function convertToPHPValue($value)
    {
        // Note: this function is only called when your custom type is used
        // as an identifier. For other cases, closureToPHP() will be called.
        return new \MongoDate($value->sec, $value->usec);
    }

    public function closureToPHP()
    {
        // Return the string body of a PHP closure that will receive $value
        // and store the result of a conversion in a $return variable
        return '$return = new \MongoDate($value->sec, $value->usec);';
    }

    public function convertToDatabaseValue($value)
    {
        // This is called to convert a PHP value to its Mongo equivalent
        return new \MongoDate($value->sec, $value->usec);
    }
}