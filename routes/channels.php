<?php

/*

| Register all of the event broadcasting channels that your
| application supports.
|
*/

Broadcast::channel('App.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});