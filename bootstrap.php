<?php

namespace Nos\BlogNews;

/**
 * retourne le nom complet (avec ns) de la class $classname dans le ns $self
 * @param  objet  $self      appartenant au bon namespace
 * @param  string $className la classe que l'on recherche
 * @return string            le nom de la classe complète
 */
function namespacize($self, $className)
{
    return \Inflector::get_namespace(get_class($self)).$className;
}

// On garde ?
function forge($self, $className, $args = array())
{
    $args = \Arr::merge(array(
        'data' => array(),
        'new'  => true,
        'view' => null
    ), $args);

    $class      = namespacize($self, $className);

    return $class::forge($args['data'], $args['new'], $args['view']);
}

\Module::load('noviusos_comments');