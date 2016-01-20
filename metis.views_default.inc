<?php

/**
 * @file
 * Default views for the metis module
 */

/**
 * Implements hook_views_default_views().
 */
function metis_views_default_views() {

  // Get metis field name(s).
  $fields = field_info_fields();
  $metis_fields = array();

  foreach ($fields as $key => $field) {
    if ($field['type'] == 'metis') {
      $metis_fields[] = $key;
    }
  }

  $views = array();

  // Provide one view per Metis field.
  foreach ($metis_fields as $field) {

    $view = new view();
    $view->name = 'metis_' . $field;
    $view->description = 'A list of nodes and their respective metis codes for the field ' . $field;
    $view->tag = '';
    $view->base_table = 'node';
    $view->human_name = '';
    $view->core = 7;
    $view->api_version = '3.0';
    $view->disabled = FALSE; /* Edit this to true to make a default view disabled initially */

    /* Display: Defaults */
    $handler = $view->new_display('default', 'Defaults', 'default');
    $handler->display->display_options['title'] = 'Overview of metis code\'s usage';
    $handler->display->display_options['use_ajax'] = TRUE;
    $handler->display->display_options['use_more_always'] = FALSE;
    $handler->display->display_options['use_more_text'] = 'Weiter';
    $handler->display->display_options['access']['type'] = 'perm';
    $handler->display->display_options['access']['perm'] = 'administer metis module';
    $handler->display->display_options['cache']['type'] = 'none';
    $handler->display->display_options['query']['type'] = 'views_query';
    $handler->display->display_options['query']['options']['distinct'] = TRUE;
    $handler->display->display_options['exposed_form']['type'] = 'basic';
    $handler->display->display_options['exposed_form']['options']['submit_button'] = 'Anwenden';
    $handler->display->display_options['exposed_form']['options']['reset_button_label'] = 'Zurücksetzen';
    $handler->display->display_options['exposed_form']['options']['exposed_sorts_label'] = 'Sortieren nach';
    $handler->display->display_options['pager']['type'] = 'full';
    $handler->display->display_options['pager']['options']['items_per_page'] = 30;
    $handler->display->display_options['pager']['options']['expose']['items_per_page_label'] = 'Beträge pro Seite';
    $handler->display->display_options['style_plugin'] = 'table';
    $handler->display->display_options['style_options']['columns'] = array(
      'nid' => 'nid',
      'created' => 'created',
      'title' => 'title',
      $field . '_code_public_2' => $field . '_code_public_2',
      $field . '_code_public' => $field . '_code_public_2',
      $field . '_code_public_1' => $field . '_code_public_1',
    );
    $handler->display->display_options['style_options']['default'] = 'nid';
    $handler->display->display_options['style_options']['info'] = array(
      'nid' => array(
        'sortable' => 1,
        'separator' => '',
      ),
      'created' => array(
        'sortable' => 1,
        'separator' => '',
      ),
      'title' => array(
        'sortable' => 1,
        'separator' => '',
      ),
      $field . '_code_public_2' => array(
        'sortable' => 1,
        'separator' => '<br />',
      ),
      $field . '_code_public' => array(
        'sortable' => 1,
        'separator' => '',
      ),
      $field . '_code_public_1' => array(
        'sortable' => 1,
        'separator' => '',
      ),
    );
    $handler->display->display_options['style_options']['sticky'] = TRUE;
    $handler->display->display_options['style_options']['order'] = 'desc';
    /* No results behavior: Global: Text area */
    $handler->display->display_options['empty']['area']['id'] = 'area';
    $handler->display->display_options['empty']['area']['table'] = 'views';
    $handler->display->display_options['empty']['area']['field'] = 'area';
    $handler->display->display_options['empty']['area']['empty'] = TRUE;
    $handler->display->display_options['empty']['area']['content'] = 'No metis codes found for this field.';
    $handler->display->display_options['empty']['area']['format'] = 'filtered_html';
    /* Relationship: Content: Author */
    $handler->display->display_options['relationships']['uid']['id'] = 'uid';
    $handler->display->display_options['relationships']['uid']['table'] = 'node';
    $handler->display->display_options['relationships']['uid']['field'] = 'uid';
    /* Field: Content: Nid */
    $handler->display->display_options['fields']['nid']['id'] = 'nid';
    $handler->display->display_options['fields']['nid']['table'] = 'node';
    $handler->display->display_options['fields']['nid']['field'] = 'nid';
    $handler->display->display_options['fields']['nid']['label'] = 'ID';
    /* Field: Content: Post date */
    $handler->display->display_options['fields']['created']['id'] = 'created';
    $handler->display->display_options['fields']['created']['table'] = 'node';
    $handler->display->display_options['fields']['created']['field'] = 'created';
    $handler->display->display_options['fields']['created']['label'] = 'Date';
    $handler->display->display_options['fields']['created']['date_format'] = 'short';
    $handler->display->display_options['fields']['created']['second_date_format'] = 'long';
    /* Field: Content: Title */
    $handler->display->display_options['fields']['title']['id'] = 'title';
    $handler->display->display_options['fields']['title']['table'] = 'node';
    $handler->display->display_options['fields']['title']['field'] = 'title';
    /* Field: User: Name */
    $handler->display->display_options['fields']['name']['id'] = 'name';
    $handler->display->display_options['fields']['name']['table'] = 'users';
    $handler->display->display_options['fields']['name']['field'] = 'name';
    $handler->display->display_options['fields']['name']['relationship'] = 'uid';
    /* Field: Content: Metis */
    $handler->display->display_options['fields'][$field]['id'] = $field;
    $handler->display->display_options['fields'][$field]['table'] = 'field_data_' . $field;
    $handler->display->display_options['fields'][$field]['field'] = $field;
    $handler->display->display_options['fields'][$field]['click_sort_column'] = 'code_public';
    $handler->display->display_options['fields'][$field]['type'] = 'metis_codes';
    /* Field: Content: Metis */
    $handler->display->display_options['fields'][$field . '_1']['id'] = $field . '_1';
    $handler->display->display_options['fields'][$field . '_1']['table'] = 'field_data_' . $field;
    $handler->display->display_options['fields'][$field . '_1']['field'] = $field;
    $handler->display->display_options['fields'][$field . '_1']['label'] = 'Show';
    $handler->display->display_options['fields'][$field . '_1']['click_sort_column'] = 'code_public';
    $handler->display->display_options['fields'][$field . '_1']['type'] = 'metis_show';
    /* Filter criterion: Content: Metis (code_public) */
    $handler->display->display_options['filters'][$field . '_code_public']['id'] = $field . '_code_public';
    $handler->display->display_options['filters'][$field . '_code_public']['table'] = 'field_data_' . $field;
    $handler->display->display_options['filters'][$field . '_code_public']['field'] = $field . '_code_public';
    $handler->display->display_options['filters'][$field . '_code_public']['operator'] = 'not empty';

    $views[$view->name] = $view;
  }

  return $views;

}
