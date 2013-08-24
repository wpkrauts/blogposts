<?php # -*- coding: utf-8 -*-
/**
 * Check if cloning is possible for a given class or object.
 *
 * @link {http://wpkrauts.com/?p=573}
 * @param  string|object $class Class name or object
 * @return boolean
 */
function is_cloneable( $class )
{
	$rc = new ReflectionClass( $class );

	if ( ! $rc->hasMethod( '__clone' ) )
		return TRUE;

	return $rc->getMethod( '__clone' )->isPublic();
}