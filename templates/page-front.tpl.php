<?php

/**
 * @file
 * Displays a single Drupal page.
 *
 * Available variables:
 *
 * General utility variables:
 * - $base_path: The base URL path of the Drupal installation. At the very
 *   least, this will always default to /.
 * - $css: An array of CSS files for the current page.
 * - $directory: The directory the theme is located in, e.g. themes/garland or
 *   themes/garland/minelli.
 * - $is_front: TRUE if the current page is the front page.
 * - $logged_in: TRUE if the user is registered and signed in.
 * - $is_admin: TRUE if the user has permission to access administration pages.
 *
 * Page metadata:
 * - $language: (object) The language the site is being displayed in.
 *   $language->language contains its textual representation.
 *   $language->dir contains the language direction. It will either be 'ltr' or
 *   'rtl'.
 * - $head_title: A modified version of the page title, for use in the TITLE
 *   element.
 * - $head: Markup for the HEAD element (including meta tags, keyword tags, and
 *   so on).
 * - $styles: Style tags necessary to import all CSS files for the page.
 * - $scripts: Script tags necessary to load the JavaScript files and settings
 *   for the page.
 * - $body_classes: A set of CSS classes for the BODY tag. This contains flags
 *   indicating the current layout (multiple columns, single column), the
 *   current path, whether the user is logged in, and so on.
 *
 * Site identity:
 * - $front_page: The URL of the front page. Use this instead of $base_path,
 *   when linking to the front page. This includes the language domain or
 *   prefix.
 * - $logo: The path to the logo image, as defined in theme configuration.
 * - $site_name: The name of the site, empty when display has been disabled in
 *   theme settings.
 * - $site_slogan: The slogan of the site, empty when display has been disabled
 *   in theme settings.
 * - $mission: The text of the site mission, empty when display has been
 *   disabled in theme settings.
 *
 * Navigation:
 * - $search_box: HTML to display the search box, empty if search has been
 *   disabled.
 * - $primary_links (array): An array containing primary navigation links for
 *   the site, if they have been configured.
 * - $secondary_links (array): An array containing secondary navigation links
 *   for the site, if they have been configured.
 *
 * Page content (in order of occurrence in the default page.tpl.php):
 * - $left: The HTML for the left sidebar.
 * - $breadcrumb: The breadcrumb trail for the current page.
 * - $title: The page title, for use in the actual HTML content.
 * - $help: Dynamic help text, mostly for admin pages.
 * - $messages: HTML for status and error messages. Should be displayed
 *   prominently.
 * - $tabs: Tabs linking to any sub-pages beneath the current page (e.g., the
 *   view and edit tabs when displaying a node).
 * - $content: The main content of the current Drupal page.
 * - $right: The HTML for the right sidebar.
 * - $node: The node object, if there is an automatically-loaded node associated
 *   with the page, and the node ID is the second argument in the page's path
 *   (e.g. node/12345 and node/12345/revisions, but not comment/reply/12345).
 *
 * Footer/closing data:
 * - $feed_icons: A string of all feed icons for the current page.
 * - $footer_message: The footer message as defined in the admin settings.
 * - $footer : The footer region.
 * - $closure: Final closing markup from any modules that have altered the page.
 *   This variable should always be output last, after all other dynamic
 *   content.
 *
 * @see template_preprocess()
 * @see template_preprocess_page()
 */
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
  "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?php print $language->language ?>" lang="<?php print $language->language ?>" dir="<?php print $language->dir ?>">

<head>
  <?php print $head; ?>
  <title><?php print $head_title; ?></title>
  <?php print $styles; ?>
  <!--[if lt IE 8]>
    <?php _mash_get_ie_styles(); ?>
  <![endif]-->
  <?php print $scripts; ?>
  <script type="text/javascript"><?php /* Needed to avoid Flash of Unstyled Content in IE */ ?> </script>
</head>
<body class="<?php print $body_classes; ?>">
  <div id="skip-links">
    <a href="#content">Skip to main content</a>
    <a href="#search">Skip to search</a>
  </div>
  <div id="page-wrapper">
    <!-- BEGIN Header -->
    <div id="header">
      <div id="header-screen" class="container-12">
        <div id="logo-title" class="grid-7">
          <?php if (!empty($logo)): ?>
            <!-- logo -->
            <div id="logo">
              <h1><a href="<?php print check_url($front_page); ?>" title="<?php print $logo_name; ?> " rel="home"><img src="<?php print $logo; ?>" alt="<?php print $logo_name; ?>" /></a></h1>
            </div>
          <?php endif; ?>
          <!-- Site Name -->
          <?php if (!empty($site_name)): ?>
            <h1 id="site-name">
              <a href="<?php print $front_page ?>" title="<?php print t('Home'); ?>" rel="home"><span><?php print $site_name; ?></span></a>
            </h1>
          <?php endif; ?>
        </div>
        <!-- /logo-title -->
        <!-- Search -->
        <?php if (!empty($search_box)): ?>
          <div id="search-box" class="grid-3">
            <?php print $search_box; ?>
          </div>
        <?php endif; ?>
        <!-- Site Slogan-->
        <?php if (!empty($site_slogan)): ?>
            <div id="site-slogan" class="grid-6"><?php print $site_slogan; ?></div>
        <?php endif; ?>
        <!-- Mission -->
        <?php if (!empty($mission)): ?>
          <div id="mission" class="grid-6"><?php print $mission; ?></div>
        <?php endif; ?>
        <div class="clear"></div>
      </div>
      <div class="clear"></div>
      <?php if ($header): ?>
        <div id="header-region" class="grid-12">
          <?php print $header; ?>
        </div>
      <?php endif; ?>
    </div>
    <!-- END Header -->
    <!-- BEGIN Preface -->
    <div id="preface">
      <div id="navigation" class="container-12">
        <?php if (!empty($primary_links)): ?>
          <div id="primary-links" class="grid-12 clear-block">
            <?php print theme('links', $primary_links, array('class' => 'links primary-links')); ?>
				  </div>
        <?php endif; ?>
        <?php if (!empty($breadcrumb)): ?><div id="breadcrumb" class="grid-12"><?php print $breadcrumb; ?></div><?php endif; ?>
        <div class="clear"></div>
        <?php if ($preface): ?>
          <?php print $preface; ?>
        <?php endif; ?>
      </div>
    </div>
    <!-- END Preface -->
    <!-- BEGIN Content Area -->
    <div id="main-container" class="clear-block">
    <div id="main-wrapper" class="container-12">
      <!-- Region: Content -->
      <div id="content-wrapper" class="grid-12">
        <!-- title -->
        <?php (!empty($title)): ?>
          <h1 class="content-title"><?php print $title; ?></h1>
        <?php endif; ?>
        <!-- tabs -->
        <?php if (!empty($tabs)): ?>
          <div class="tabs">
            <?php print $tabs; ?>
          </div>
        <?php endif; ?>
        <!-- help -->
        <?php if (!empty($help)): print $help; endif; ?>
        <!-- messages -->
        <?php if (!empty($messages)): print $messages; endif; ?>
      </div>
      <!-- Main content -->
      <div id="main-content">
        <?php if (!empty($links_tree) || !empty($left)): ?>
        <div id="left" class="grid-3">
          <!-- Primary Menu Sublinks -->
          <?php if ($primary_links): ?>
            <div id="primary-sub-menu">
              <h3><?php print $tree_title; ?></h3>
              <?php print $links_tree; ?>
            </div>
          <?php endif; ?>
          <!-- Left Column -->
          <?php if ($left): ?>
            <?php print $left; ?>
          <?php endif; ?>
        </div>
        <?php endif; ?>
        <div id="content" class="<?php print $content_class; ?> clear-block">
            <?php print $content; ?>
        </div> <!-- /content-content -->
        <?php if (!empty($right)): ?>
          <div id="right" class="grid-3 column sidebar">
            <!-- Right Column -->
            <?php print $right; ?>
          </div>
          <!-- /sidebar-right -->
        <?php endif; ?>
        <?php print $feed_icons; ?>
      </div>
      <div class="clear"></div>
    </div>
    </div>
    <!-- END Content Area -->
    <!-- BEGIN Footer -->
    <div id="footer" class="container-12">
      <!-- Region: Footer -->
      <?php if (!empty($footer)): ?>
        <div id="footer-region" class="grid-12">
          <?php print $footer; ?>
        </div>
      <?php endif; ?>
      <!-- Footer secondary links -->
      <?php if (!empty($secondary_links)): ?>
        <div id="secondary" class="grid-12 clear-block">
          <?php print theme('links', $secondary_links, array('class' => 'links secondary-links')); ?>
        </div>
      <?php endif; ?>
      <!-- Footer message and copyright -->
      <div id="footer-message" class="grid-12">
        <?php print $footer_message; ?>
      </div>
      <span id="copyright" class="grid-12">Copyright &copy; <?php date_default_timezone_set('America/New_York'); echo date("Y"); ?> by the President and Fellows of Harvard College</span>
      <div class="clear"></div>
    </div>
    <!-- END Footer -->
    <?php print $closure; ?>
  </div>
  <!-- /page -->
</body>
</html>
