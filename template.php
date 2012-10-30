<?php

/**
  * Implementation of $mash_breadcrumb().
  * This is usede to set the breadcrumbs.
  */

function mash_breadcrumb($breadcrumb){
  if (!empty($breadcrumb)) {
    return implode(' » ', $breadcrumb);
//    "<ul><li>" . implode("</li><li class=\"sep\">→</li><li>", $breadcrumb) . "</li>\n</ul>"
  }
  else return '<a href="'.$base_path.'">Home</a>';
}

/**
* Return a themed breadcrumb trail.
*
* @param $breadcrumb
* An array containing the breadcrumb links.
* @return a string containing the breadcrumb output.
*/
/*
function mash_breadcrumb($breadcrumb) {
  if (!empty($breadcrumb)) {
    $breadcrumb[] = drupal_get_title();
    array_shift($breadcrumb);
    return '<div class="path"><p><span>'.t('You are here').'</span>'. implode(' / ', $breadcrumb) .'</p></div>';
  }
}
*/

/**
  * Implementation of $mash_preprocess_$page().
  * Variables that are set here are availabe to the .tpl files
  *
  * @param $vars
  *   A keyed array of variables that are passed from the page.
  */
function mash_preprocess_page(&$vars) {

  // Set stylesheets for IE
  $vars['ie_styles'] = _mash_get_ie_styles();

  // Set menu tree on left sidebar
  $tree = menu_tree_page_data('primary-links') + menu_tree_page_data('secondary-links');

  foreach($tree as $key => $mi) {
    if ($mi['link']['in_active_trail'] && $tree[$key]['below']) {
      $menu = menu_tree_output($tree[$key]['below']);
      $tree_title = $tree[$key]['link']['title'];
    }
  }

  if (!empty($menu)) {
    $vars['tree_title'] = $tree_title;
    $vars['links_tree'] = $menu;
  }
  else {
    $vars['links_tree'] = '';
  }
  $vars['content_class'] = (empty($menu)) ? 'grid-12' : 'grid-9';

  $vars['primary_links'] = menu_tree_output(menu_tree_all_data('primary-links'));
  $vars['secondary_links'] = menu_tree_output(menu_tree_all_data('secondary-links'));
  $vars['user_name'] = _mash_userName($vars['user']);
  $node = $vars['node'];
  if ($node->comment_count != "0") {
    $comments = $node->links;
    unset($comments['statistics_counter']);
    $output = theme('links', $comments);
  }
  else $output = '';
  $vars['add_comment'] = $output;
  $vars['logo_name'] = (!empty($site_name)) ? t('Home') : $vars['site_name'];
}

/**
 * Implementation of $mash_preprocess_$node().
 * Variables set here are availabel to the node.tpl files.
 *
 * @param $vars
 *   A keyed array of variables that are passed from the node.
 */
function mash_preprocess_node(&$vars) {
  $node = $vars['node'];
  $node_type = $node->type;
  switch($node_type) {
    case "profile":
      $vars['node_phone'] = _mash_field_output($node->field_phone);
      break;
  }
}

/**
 * Checks for empty string that only contain whitespace (such as tabs or spaces)
 *
 * @param $string
 *   The string to be processed.
 * @return
 *   TRUE if empty.
 */
function _mash_check_not_empty($string)
{
  $string = str_replace("  ","",$string);
  $string = trim($string);
  return (isset($string) && strlen($string)); // var is set and not an empty string ''
}

/**
 * Returns the first term from a given vocabulary
 *
 * @param $vid
 *   The vocabulary to be checked.
 * @return $firstTerm
 *   The first term of this vocabulary.
 */
function _mash_get_first_term($vid)
{
  $tree = taxonomy_get_tree($vid);
  //This loads the first term in the given vocabulary as the term to be checked against.
  $arr = reset($tree);
  return $arr->name;
}

/**
 * Returns the first vocabulary
 *
 * @return $firstVocab
 *   The id of the first vocabulary.
 */
function _mash_get_first_vocabulary()
{
  $tree = taxonomy_get_vocabularies();
  //This loads the first vocabulary.
  $arr = reset($tree);
  return ($arr->vid);
}

/**
 * Returns a formatted output for different CCK fields
 *
 * @param $node_field
 *   The CCK field to be processed.
 * @return
 *   Formatted output.
 */
function _mash_field_output($node_field)
{
  unset($node_output);
  foreach($node_field as $item) :
    $node_output .= $item['view']."<br>";
  endforeach;
  return $node_output;
}

/**
 * Format the "Submitted by username on date/time" for each node.
 *
 * @param $node
 *   The node to be processed.
 * @return
 *   A string containing the "submitted by" text.
 */
function mash_node_submitted($node) {
  return t('Submitted by !username on @datetime',
    array(
      '!username' => _mash_userName($node), //theme('username', $node),
      '@datetime' => format_date($node->created),
    ));
}

/**
 * Format the "Submitted by username on date/time" for each node.
 *
 * @param $comment
 *   The comment to be processed.
 * @return
 *   A string containing the "submitted by" text.
 */
function mash_comment_submitted($comment) {
  return t('<div class="comments-user">!username</div> @datetime',
    array(
      '!username' => _mash_userName($comment),
      '@datetime' => format_date($comment->timestamp),
    ));
}

/**
 * Returns the name of the user currently logged in.
 *
 * @return $vars
 *   The name (if available) or the username of the user logged in.
 */
function _mash_userName($user) {
  $profile =  profile_load_profile($user);
  $fname = $user->profile_fname;
  $lname = $user->profile_lname;
  if (_mash_check_not_empty($fname) || _mash_check_not_empty($lname))
    $output = $fname . ' ' . $lname;
  else
    $output = $user->name;
  return $output;
}

/**
 * Override or insert PHPtheme variables into the search_theme_form theme.
 *
 * @param $vars
 *   A sequential array of variables to pass to the theme theme.
 * @param $hook
 *   The name of the theme function being called (not used in this case.)
 */
function mash_preprocess_search_theme_form(&$vars, $hook) {

  // Remove the "Search this site" label from the form.
  $vars['form']['search_theme_form']['#title'] = t('');

  // Set a default value for text inside the search box field.
  $vars['form']['search_theme_form']['#value'] = t('Search');

  // Add a custom class and placeholder text to the search box.
  $vars['form']['search_theme_form']['#attributes'] = array('class' => 'NormalTextBox txtSearch',
                                                              'onfocus' => "if (this.value == 'Search') {this.value = '';}",
                                                              'onblur' => "if (this.value == '') {this.value = 'Search';}");

  // Change the size of the search box
  $vars['form']['search_theme_form']['#size'] = 11;

  // Change the text on the submit button
  unset($vars['form']['submit']['#value']);
  $vars['form']['submit']['#value'] = t('Go');

  // Rebuild the rendered version (search form only, rest remains unchanged)
  unset($vars['form']['search_theme_form']['#printed']);
  $vars['search']['search_theme_form'] = drupal_render($vars['form']['search_theme_form']);

  // Rebuild the rendered version (submit button, rest remains unchanged)
/*  unset($vars['form']['submit']);*/
  unset($vars['form']['submit']['#printed']);
  $vars['search']['submit'] = drupal_render($vars['form']['submit']);

  // Collect all form elements to make it easier to print the whole form.
  $vars['search_form'] = implode($vars['search']);
}

function mash_preprocess_comment(&$vars) {

  // Add a time decay class.
  $decay = _mash_get_comment_decay($vars['node']->nid, $vars['comment']->timestamp);
  $vars['attr']['class'] .= " decay-{$decay['decay']}";

  // If subject field not enabled, replace the title with a number.
  if (!variable_get("comment_subject_field_{$vars['node']->type}", 1)) {
    $vars['title'] = l("#{$decay['order']}", "node/{$vars['node']->nid}", array('fragment' => "comment-{$vars['comment']->cid}"));
  }
}

/**
 * Return both an order (e.g. #1 for oldest to #n for the nth comment)
 * and a decay value (0 for newest, 10 for oldest) for a given comment.
 */
function _mash_get_comment_decay($nid, $timestamp) {
  static $timerange;
  if (!isset($timerange[$nid])) {
    $range = array();
    $result = db_query("SELECT timestamp FROM {comments} WHERE nid = %d ORDER BY timestamp ASC", $nid);
    $i = 1;
    while ($row = db_fetch_object($result)) {
      $timerange[$nid][$row->timestamp] = $i;
      $i++;
    }
  }

  if (!empty($timerange[$nid][$timestamp])) {
    $decay = max(array_keys($timerange[$nid])) - min(array_keys($timerange[$nid]));
    $decay = $decay > 0 ? ((max(array_keys($timerange[$nid])) - $timestamp) / $decay) : 0;
    $decay = floor($decay * 10);
    return array('order' => $timerange[$nid][$timestamp], 'decay' => $decay);
  }

  return array('order' => 1, 'decay' => 0);
}

/**
 * Implementation of mash_settings() function.
 *
 * @param $saved_settings
 *   array An array of saved settings for this theme.
 * @return
 *   array A form array.
 */
function mash_settings($saved_settings) {
  // Return the additional form widgets
  return $form;
}

/**
 * Ensure messages are always lists (even when there is only one single message).
 */
function mash_status_messages($display = NULL) {
  $output = '';
  foreach (drupal_get_messages($display) as $type => $messages) {
    $output .= "<div class=\"messages $type\">\n";
    $output .= "<ul>\n<li>" . implode("</li>\n<li>", $messages) . "</li>\n</ul>\n";
    $output .= "</div>\n";
  }
  return $output;
}

/**
 * Generates IE CSS links for LTR and RTL languages.
 */
function _mash_get_ie_styles() {
  drupal_add_css(drupal_get_path('theme','mash').'/css/fix-ie.css'); // load the css
}

/**
 * Return code that emits an feed icon.
 * Make this function more accessible.
 * @param $url
 *   The url of the feed.
 * @param $title
 *   A descriptive title of the feed.
  */
function mash_feed_icon($url, $title) {
  $text = t('Subscribe to @feed-title', array('@feed-title' => $title));
  if ($image = theme('image', 'misc/feed.png', $text)) {
    return '<a href="' . check_url($url) . '" title="' . $text . '" class="feed-icon">' . $image . '</a>';
  }
}

/**
* Search results override
*/
function mash_box($title, $content, $region = 'content') {
  if ($title == 'Your search yielded no results') {
    $title = t("No results found.");
    $content = '<p>' . t("Sorry, but we were unable to find what you were looking for. Try a different search?") . '</p>';
    $content .= '<ul>';
    $content .= '<li>' . t("Make sure all words are spelled correctly.") . '</li>';
    $content .= '<li>' . t("Try different keywords") . '</li>';
    $content .= '<li>' . t("Try more general keywords.") . '</li>';
    $content .= '</ul>';
  }
  $output = '<h2 class="title">'. $title .'</h2><div>'. $content .'</div>';
  return $output;
}

/**
 * Process variables for search-results.tpl.php.
 *
 * The $variables array contains the following arguments:
 * - $results
 * - $type
 *
 * @see search-results.tpl.php
 */
function mash_preprocess_search_result(&$variables) {
  $result = $variables['result'];
  $variables['url'] = check_url($result['link']);
  $variables['title'] = check_plain($result['title']);
  $info = array();
  if (!empty($result['date'])) {
    $info['date'] = format_date($result['date'], 'small');
  }
  if (isset($result['extra']) && is_array($result['extra'])) {
    $info = array_merge($info, $result['extra']);
  }
  // Check for existence. User search does not include snippets.
  $variables['snippet'] = isset($result['snippet']) ? $result['snippet'] : '';
  // Provide separated and grouped meta information..
  $variables['info_split'] = $info;
  $variables['info'] = implode(' - ', $info);
  // Provide alternate search result template.
  $variables['template_files'][] = 'search-result-'. $variables['type'];
}

/**
 * Return a themed list of items.
 *
 * @param $items
 *   An array of items to be displayed in the list. If an item is a string,
 *   then it is used as is. If an item is an array, then the "data" element of
 *   the array is used as the contents of the list item. If an item is an array
 *   with a "children" element, those children are displayed in a nested list.
 *   All other elements are treated as attributes of the list item element.
 * @param $title
 *   The title of the list.
 * @param $type
 *   The type of list to return (e.g. "ul", "ol")
 * @param $attributes
 *   The attributes applied to the list element.
 * @return
 *   A string containing the list output.
 */
function mash_item_list($items = array(), $title = NULL, $type = 'ul', $attributes = NULL) {

  if ($attributes['class'] = 'pager')
  {
  $output = '<h2 class="element-invisible">Pages</h2><div class="item-list">';

  }else
  {
  $output = '<div class="item-list">';

  }

  if (isset($title)) {
    $output .= '<h3>'. $title .'</h3>';
  }

  if (!empty($items)) {
    // $output .= "<$type". drupal_attributes($attributes) .'>';
    $output .= "<$type>";
    $num_items = count($items);
    foreach ($items as $i => $item) {
      $attributes = array();
      $children = array();
      if (is_array($item)) {
        foreach ($item as $key => $value) {
          if ($key == 'data') {
            $data = $value;
          }
          elseif ($key == 'children') {
            $children = $value;
          }
          else $attributes[$key] = $value;
        }
      }
      else {
        $data = $item;
      }
      if (count($children) > 0) {
        $data .= theme_item_list($children, NULL, $type, $attributes); // Render nested list
      }
      if ($i == 0) {
        $attributes['class'] = empty($attributes['class']) ? 'first' : ($attributes['class'] .' first');
      }
      if ($i == $num_items - 1) {
        $attributes['class'] = empty($attributes['class']) ? 'last' : ($attributes['class'] .' last');
      }
      $output .= '<li'. drupal_attributes($attributes) .'>'. $data ."</li>\n";
    }
    $output .= "</$type>";
  }
  $output .= '</div>';
  return $output;
}
