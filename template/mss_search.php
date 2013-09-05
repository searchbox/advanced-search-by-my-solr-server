<?php
/*
  Template Name: Search
 */

function selectTag($sort, $order, $thisSpan)
{
  if ($sort . $order == $thisSpan) {
    return 'selected';
  }
  return '';
}
?>
<?php get_header(); ?>
<div id="content" class="solr-content">


  <form role="search" method="get" id="searchform" class="searchform solr-search-form" action="/" style="text-align:center">
    <div>
      <label for="s">Search</label>
      <input type="text" name="s" id="s" style="width:350px" value="<?php echo (isset($_GET["s"]) && $_GET["s"]) ? $_GET["s"] : '' ?>"/>
      <input type="submit" id="searchsubmit" value="Search"/>
    </div>
  </form>

  <div class="solr clearfix">

    <?php $results = mss_search_results(); ?>  

    <div class="solr-central-col clearfix">
      <div class="solr-search">
        <?php
        if ($results['qtime']) {
          printf("<label class='solr-response-time'>Response time: <span id=\"qrytime\">{$results['qtime']}</span> s</label>");
        }
        ?>

        &nbsp;

        <ul class="solr-facets">
          <li class="solr-active">
            <ol>
              <?php
              if ($results['facets']['selected']) {
                foreach ($results['facets']['selected'] as $selectedfacet) {
                  printf("<li><span></span><a href=\"%s\">%s&nbsp;<b>x</b></a></li>", $selectedfacet['removelink'], $selectedfacet['name']);
                }
              }
              ?>
            </ol>
          </li>
        </ul>

      </div>

      <?php
      if ($results['dym'] && $results['hits'] > 0) {
        printf("<div class='solr-spellcheck'>Did you mean: <a href='%s'>%s</a> ?</div>", $results['dym']['link'], $results['dym']['term']);
      }
      ?>

    </div>

      <?php
      if ($results['facets']['output']) {
        ?>
      <div class="solr-col-2">
          <?php
        } else {
          ?>
        <div class="solr-col-2 nofacets">
  <?php
}
?>
        <div class="solr-results-header clearfix">
          <div class="solr-results-header-l">

            <?php
            if ($results['hits'] && $results['query'] && $results['qtime']) {
              if ($results['firstresult'] === $results['lastresult']) {
                printf("Displaying result %s of <span id='resultcnt'>%s</span> hits", $results['firstresult'], $results['hits']);
              } else {
                printf("Displaying results %s-%s of <span id='resultcnt'>%s</span> hits", $results['firstresult'], $results['lastresult'], $results['hits']);
              }
            }
            ?>

          </div>

<?php
$sort = (isset($_GET['sort'])) ? $_GET['sort'] : 'score';
$order = (isset($_GET['order'])) ? $_GET['order'] : 'desc';
?>


          <div class="solr-results-header-r">
            <label for="select-sort">Sort By:</label>
            <select id="select-sort" onchange="window.location.href=this.options[this.selectedIndex].value">
              <option value="<?php echo $results['sorting']['scoredesc'] ?>" <?php echo selectTag($sort, $order, 'scoredesc'); ?>>Relevancy</option>
              <option value="<?php echo $results['sorting']['datedesc'] ?>" <?php echo selectTag($sort, $order, 'datedesc'); ?>>Newest</option>
              <option value="<?php echo $results['sorting']['dateasc'] ?>" <?php echo selectTag($sort, $order, 'dateasc'); ?>>Oldest</option>
              <option value="<?php echo $results['sorting']['numcommentsdesc'] ?>" <?php echo selectTag($sort, $order, 'numcommentsdesc'); ?>>Most Comments</option>
              <option value="<?php echo $results['sorting']['numcommentsasc'] ?>" <?php echo selectTag($sort, $order, 'numcommentsasc'); ?>>Least Comments</option>
            </select>
          </div>
        </div>

        <div class="solr-results">

          <?php
          if ($results['hits'] === "0") {
            printf("<div class='solr_noresult'>
										<h2>Sorry, no results were found. %s</h2>
										<h3>Perhaps you mispelled your search query, or need to try using broader search terms.</h3>
										<p>For example, instead of searching for 'Apple iPhone 3.0 3GS', try something simple like 'iPhone'.</p>
									</div>\n", ($results['dym'] ? sprintf("Did you mean: <a href='%s'>%s</a>?</div>", $results['dym']['link'], $results['dym']['term']) : ""));
          } else {
            printf("<ol>\n");
            foreach ($results['results'] as $result) {
              printf("<li onclick=\"window.location='%s'\">\n", $result['permalink']);
              printf("<h2><a href='%s'>%s</a></h2>\n", $result['permalink'], $result['title']);
              printf("<p>%s <a href='%s'>(comment match)</a></p>\n", $result['teaser'], $result['comment_link']);
              printf("<label> By <a href='%s'>%s</a> in %s %s - <a href='%s'>%s comments</a></label>\n", $result['authorlink'], $result['author'], get_the_category_list(', ', '', $result['id']), date('m/d/Y', strtotime($result['date'])), $result['comment_link'], $result['numcomments']);
              printf("</li>\n");
            }
            printf("</ol>\n");
          }
          ?>

          <?php
          if ($results['pager'] && count($results['pager']) > 1) {
            printf("<div class='solr-pagination'>");
            $itemlinks = array();
            $pagecnt = 0;
            $pagemax = 10;
            $next = '';
            $prev = '';
            $found = false;
            foreach ($results['pager'] as $pageritm) {
              if ($pageritm['link']) {
                if ($found && $next === '') {
                  $next = $pageritm['link'];
                } else if ($found == false) {
                  $prev = $pageritm['link'];
                }

                $itemlinks[] = sprintf("<a href='%s'>%s</a>", $pageritm['link'], $pageritm['page']);
              } else {
                $found = true;
                $itemlinks[] = sprintf("<a class='solr-pagination-on' href='%s'>%s</a>", $pageritm['link'], $pageritm['page']);
              }

              $pagecnt += 1;
              if ($pagecnt == $pagemax) {
                break;
              }
            }

            if ($prev !== '') {
              printf("<a href='%s'>Previous</a>", $prev);
            }

            foreach ($itemlinks as $itemlink) {
              echo $itemlink;
            }

            if ($next !== '') {
              printf("<a href='%s'>Next</a>", $next);
            }

            printf("</div>\n");
          }
          ?>


        </div>	
      </div>

<?php if ($results['facets']['output']): ?>
        <div class="solr-col-1">
          <ul class="solr-facets">

            <?php
            foreach ($results['facets'] as $facet) {
              if (isset($facet['name'])) {
                printf("<li>\n<h3>%s</h3>\n", $facet['name']);
                mss_print_facet_items($facet["items"], "<ol>", "</ol>", "<li>", "</li>", "<li><ol>", "</ol></li>", "<li>", "</li>");
                printf("</li>\n");
              }
            }
            ?>
          </ul>
        </div>
  <?php endif; ?>
    </div>

  </div>
<?php get_footer(); ?>
