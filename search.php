<?php 
  require "misc/header.php";
  require "util.php";
?>

<title>
<?php
  $query = htmlspecialchars(trim($_REQUEST["q"]));
  echo $query;
?> - Search</title>
</head>
  <body>
    <form class="sub-search-container" method="get" autocomplete="off">
      <div class="search-wrapper">
        <h1 class="logo-small"><a class="no-decoration" href="./">Search</a></h1>
        <input class="search-input search-secondary" type="text" name="q" value="<?php echo htmlspecialchars($query); ?>">
        
      </div>
        <?php
          $query_encoded = urlencode($query);
            if (1 > strlen($query) || strlen($query) > 256)
              {
                header("Location: ./");
                die();
              }
        ?>
        <br>
        <?php
          $type = isset($_REQUEST["t"]) ? (int) $_REQUEST["t"] : 0;
          echo "<button class=\"hide\" name=\"t\" value=\"$type\"/></button>";
        ?>
        <button type="submit" class="hide"></button>
        <input type="hidden" name="p" value="0">
        <div class="sub-search-button-wrapper">
          <?php
            //$categories = array("general", "images", "videos", "torrents", "tor");
            $categories = array("general", "images", "tor");
            foreach ($categories as $category)
            {
              $category_index = array_search($category, $categories);
              echo "<a class=\"buttons\" " . (($category_index == $type) ? "class=\"active\" " : "") . "href=\"/search.php?q=" . $query . "&p=0&t=" . $category_index . "\"><img src=\"assets/images/" . $category . ".png\" class=\"icons\" alt=\"" . $category . " result\" />" . ucfirst($category)  . "</a>";
            }
          ?>
        </div>
        <hr>
        </form>

        <?php
          $page = isset($_REQUEST["p"]) ? (int) $_REQUEST["p"] : 0;
          $start_time = microtime(true);
          switch ($type)
          {
            case 0:
              $query_parts = explode(" ", $query);
              $last_word_query = end($query_parts);
              require "engines/ddg.php";
              $results = get_text_results($query, $page);
              print_elapsed_time($start_time);
              print_text_results($results);
              break;
            
            case 1:
              echo "NOT IMPLEMENTED YET";
              // $query_parts = explode(" ", $query);
              // $last_word_query = end($query_parts);
              // require "engines/ddg_tor.php";
              // $results = get_text_results($query, $page);
              // //print_elapsed_time($start_time);
              // print_text_results($results);
              break;

            case 2:
              echo "NOT IMPLEMENTED YET";
            //   require "engines/qwant/image.php";
            //   //$results = get_image_results($query_encoded, $page);
            //   //print_elapsed_time($start_time);
            //   //print_image_results($results);
              break;
          }
        ?>

<?php require "misc/footer.php"; ?>
