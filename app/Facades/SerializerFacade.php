<?php


namespace App\Facades;


use Symfony\Component\Serializer\Serializer;

class SerializerFacade extends \Illuminate\Support\Facades\Facade
{
    /**
     * {@inheritDoc}
     */
    protected static function getFacadeAccessor()
    {
        return Serializer::class;
    }
}
