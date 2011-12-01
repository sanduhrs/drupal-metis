<?php
// $Id: metis_cck_inject_allowed.inc,v 1.0 2010/11/13 14:49:21 yan Exp $

/**
 * @file
 * Allowed values for the CCK field
 */

$field = variable_get('metis_cck_field', NULL); // The machine readable name of the field
$options = array();

// Check if node already has a code set in this field on node edit
if (arg(0) == 'node' && is_numeric(arg(1)) && arg(2) == 'edit') {

  $node = node_load(arg(1));

  if ($node->{$field}[0]['value']) {

    // If code is set, use it and exit
    $options[$node->{$field}[0]['value']] = t('Embed Metis code') . ' (' . $node->{$field}[0]['value'] . ')';

    return $options;

  }

}

if (!$node->{$field}[0]['value']) {

  $db_field = content_fields($field);
  $db_info = content_database_info($db_field);

  if ($db_info['columns']['value']['column']) {

    $query = "SELECT metis.code
              FROM metis
              LEFT JOIN %s ON ( %s.%s = metis.code )
              WHERE metis.used = 0
              AND %s.%s IS NULL
              LIMIT 1";


    $row = db_fetch_object(db_query($query, $db_info['table'], $db_info['table'], $db_info['columns']['value']['column'], $db_info['table'], $db_info['columns']['value']['column']));
    $options[$row->code] = t('Embed Metis code') . ' (' . $row->code . ')';

  }
}

return $options;