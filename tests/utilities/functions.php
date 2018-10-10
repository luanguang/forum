<?php

function create($class, $attributes = [], $times = null)
{
    $class = 'App\\Model\\' . $class;
    return factory($class, $times)->create($attributes);
}

function make($class, $attributes = [], $times = null)
{
    $class = 'App\\Model\\' . $class;
    return factory($class, $times)->make($attributes);
}

function raw($class, $attributes = [], $times = null)
{
    $class = 'App\\Model\\' . $class;
    return factory($class, $times)->raw($attributes);
}
