<?php

function create($class, $attributes = [])
{
    $class = 'App\\Model\\' . $class;
    return factory($class)->create($attributes);
}

function make($class, $attributes = [])
{
    $class = 'App\\Model\\' . $class;
    return factory($class)->make($attributes);
}

function raw($class, $attributes = [])
{
    $class = 'App\\Model\\' . $class;
    return factory($class)->raw($attributes);
}
