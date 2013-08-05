=== Search via Searchbox-Server ===
Contributors: www.searchbox-server.com
Author URI: http://www.searchbox-server.com
Plugin URI: http://wordpress.org/extend/plugins/advanced-search-by-my-solr-server/
Tags: solr, search, search results, search integration, custom search, better search, search replacement, category search, comment search, tag search, page search, post search, search highlight, seo
Requires at least: 3.0.0
Tested up to: 3.5.2
Stable tag: 2.1.0


A WordPress plugin that replaces the default WordPress search with a lot of benefits


== Description ==

In order to make Search via Searchbox-Server plugin work, you need to either subscribe to searchbox-server.com or have a Solr server installed and configured with the provided schema.xml file. 


= What Advanced Search via Searchbox-Server plugin does ? =

Search via Searchbox-Server plugin replaces the default WordPress search. Features and benefits include:

*   Index pages, posts and custom post types
*   Enable search and faceting on fields such as tags, categories, author, page type, custom fields and custom taxonomies
*   Add special template tags so you can create your own custom result pages to match your theme.
*   Search term suggestions (AutoComplete)
*   Provides better search results based on relevancy
*   Create custom summaries with the search terms highlighted
*   Completely integrated into default WordPress theme and search widget.
*   Configuration options allow you to select pages to ignore


== Installation ==

= Prerequisite = 

A Solr server 3.6.0 or greater installed and configured with the provided schema.xml file. This file is configured for English content. Update this file according to your content language.

In order to have spell checking work, in your solrconfig.xml file, check :

1. the spellchecker component have to be correctly configured :

    <lst name="spellchecker">
      <str name="name">default&lt;/str>
      <str name="field">spell&lt;/str>
      <str name="spellcheckIndexDir"&gt;spellchecker&lt;/str>
      <str name="buildOnOptimize"&gt;true&lt;/str>
    </lst>
   
2. the request handler includes the spellchecker component

     <arr name="last-components">
       <str>spellcheck</str>
     </arr>
    
If you are using "Solr for Wordpress" or "Advanced Search by My-Solr-Server" Wordpress plugin, deactivate and uninstall them.


= Installation =

1. Upload the `searchbox-server-search` folder to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go in Advanced Search by My Solr Server settings page ("Search via Searchbox-Server"), configure the plugin and Load your blog content in Solr ("Load Content" button)

= Customize the plugin display =

The plugin uses the template files located in the `template` directory. You can implement your own template files by copying theses files with a new name terminating by "_custom" (for instance, the file mss_search.php is copied as mss_search_custom.php). These new files can be located in the plugin's template directory or in your theme's main directory.


== Frequently Asked Questions ==

= What version of WordPress does Advanced Search by My Solr Server plugin work with? =

Advanced Search by My Solr Server plugin works with WordPress 3.0.0 and greater.

= What version of Solr does Advanced Search by My Solr Server plugin work with? =

Advanced Search by My Solr Server plugin works with Solr 3.6.x or 4.0.x;

= How to manage Custom Post type, custom taxonomies and custom fields? =

Advanced Search by My Solr Server plugin was tested with:
* "Custom Post Type UI" plugin for Custom Post type and custom taxonomies management 
* "Custom Field Template" plugin for custom fields management
* WP-Types plugin for Custom Post type and custom taxonomies management and for custom fields management

== Changelog ==

= 2.1.1 =

 * Removing shortcodes from indexed data
 * Replacing query parser dismax with edismax

= 2.1.0 =

* update schema.xml for Solr 3.6.x
* add schema.xml for Solr 4.0.x
* SolrPhpClient upgrade for Solr 4.0.x compatibility

= 2.0.4 =

* Search result display bug fix