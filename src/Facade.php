<?php
namespace Lixus\ValueFirst;

use Illuminate\Support\Facades\Facade as IlluminateFacade;

/**
 * @method static Client withCredential(string $username, string $password, string $senderPhone)
 * @method static Response send(string $to, string $template, string $tag = null, string $dlUrl = null)
 * */
class Facade extends IlluminateFacade
{
    public static function getFacadeAccessor()
    {
        return "lixus.client";
    }
}