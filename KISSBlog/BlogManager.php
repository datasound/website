<?php
namespace KISSBlog;

use \ParsedownExtra;

/* General Blog Functions */
class BlogManager {

  private $articleDir;
  private $postPerPage;
  private $siteUrl;
  private $cache;
  private $pageDir;
  private $authors;

  public function __construct($config) {
    $this->articleDir = dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . $config->blog->posts->dir . DIRECTORY_SEPARATOR;
    $this->postPerPage = $config->blog->posts->perpage;
    $this->siteUrl = $config->blog->url;
    $this->pageDir = $config->blog->pages->dir;
    $this->authors = $config->blog->authors;
  }

  public function get_page($pageName) {
    $filePath = dirname(dirname(__FILE__)) . DIRECTORY_SEPARATOR . $this->pageDir . DIRECTORY_SEPARATOR . $pageName . ".md";
    $page = new \stdClass;
    $parsedown = new ParsedownExtra();
    if(!file_exists($filePath))
      return;
    $pageContent = file_get_contents($filePath);
    $matches = array();
    preg_match('/[\s\S]*[-]+:endmetadata:[-]+/', $pageContent, $matches);
    # if we have metadata defined
    if(count($matches)>0){
      $metadata = preg_replace('/[-]+:endmetadata:[-]+/', "", $matches[0]);
      $page->metas = json_decode($metadata);
      $pageContent = preg_replace('/[\s\S]*[-]+:endmetadata:[-]+/', "", $pageContent);
      $content = $parsedown->text($pageContent);
      $page->title = $page->metas->title;
    } else {
      // Get the contents and convert it to HTML
      $content = $parsedown->text($pageContent);
      // Extract the title and body
      $arr = explode('</h1>', $content);
      $page->title = str_replace('<h1>','',$arr[0]);
      $content = $arr[1];
    }
    $page->url = $this->siteUrl . $pageName;
    $page->body = $content;
    return $page;
  }

  public function get_post_links() {
    $posts = $this->get_post_names();
    $links = array();
    foreach($posts as $k=>$v){
      $link = new \stdClass;
      $link->path = $v;
      $arr = explode('_', $v);
      $link->name = str_replace('.md', '', $arr[1]);
      $timestr = str_replace($this->articleDir,'',$arr[0]);
      $bits = explode('-', $timestr);
      $link->year = $bits[0];
      $link->month = $bits[1];
      $link->day = $bits[2];
      $date = strtotime($timestr);
      $link->url = $this->siteUrl . date('Y/m', $date).'/'.str_replace('.md','',$arr[1]);
      $links[] = $link;
    }
    return $links;
  }

  public function get_post_names() {
    $_cache = array();
    if(empty($_cache)){
      // Get the names of all the
      // posts (newest first):
      $_cache = array_reverse(glob($this->articleDir . "*.md"));
    }
    return $_cache;
  }

  public function get_posts($page = 1, $perpage = 0){
    if($perpage == 0){
      $perpage = $this->postPerPage;
    }
    $posts = $this->get_post_names();
    // Extract a specific page with results
    $posts = array_slice($posts, ($page-1) * $perpage, $perpage);
    $tmp = array();

    foreach($posts as $k=>$v){
      $post = new \stdClass;
      $parsedown = new ParsedownExtra();
      // Extract the date
      $arr = explode('_', $v);
      $post->date = strtotime(str_replace($this->articleDir,'',$arr[0]));

      // The post URL
      $post->url = $this->siteUrl . date('Y/m', $post->date).'/'.str_replace('.md','',$arr[1]);

      $postContent = file_get_contents($v);
      $matches = array();
      preg_match('/[\s\S]*[-]+:endmetadata:[-]+/', $postContent, $matches);
      # if we have metadata defined
      if(count($matches)>0){
        $toCache = $matches[0]."\n";
        $metadata = preg_replace('/[-]+:endmetadata:[-]+/', "", $matches[0]);
        $post->metas = json_decode($metadata);
        if(isset($post->metas->title))
          $post->title = $post->metas->title;
        if(isset($post->metas->author)) {
          if($this->authors->{$post->metas->author})
            $post->metas->author = $this->authors->{$post->metas->author};
        }
        $postContent = preg_replace('/[\s\S]*[-]+:endmetadata:[-]+/', "", $postContent);
        // Get the contents and convert it to HTML
        $content = $parsedown->text($postContent);
      } else {
        // Get the contents and convert it to HTML
        $content = $parsedown->text($postContent);
        // Extract the title and body
        $arr = explode('</h1>', $content);
        $post->title = str_replace('<h1>','',$arr[0]);
        $content = $arr[1];
      }
      $post->body = $content;
      $tmp[] = $post;
    }
    return $tmp;
  }

  // Find post by year, month and name
  function find_post($year, $month, $name){
    foreach($this->get_post_names() as $index => $v){
      if( strpos($v, "$year-$month") !== false && strpos($v, $name.'.md') !== false){
        // Use the get_posts method to return
        // a properly parsed object
        $arr = $this->get_posts($index+1,1);
        return $arr[0];
      }
    }
    return false;
  }

  // Helper function to determine whether
  // to show the pagination buttons
  public function has_pagination($page = 1){
    $total = count($this->get_post_names());
    return array(
      'prev'=> $page > 1,
      'prevpage'=>$page-1,
      'next'=> $total > $page*$this->postPerPage,
      'nextpage'=>$page+1
    );
  }


  // Turn an array of posts into a JSON
  function generate_json($posts){
    return json_encode($posts);
  }

}
