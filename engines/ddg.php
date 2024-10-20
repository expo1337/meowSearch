<?php

  function get_text_results($query, $page)
  {
    $mh = curl_multi_init();
    $query_encoded = urlencode($query);
    $results = array();

    $url = "https://duckduckgo.com/html/?q=$query_encoded";
    
    $duck_ch = curl_init($url);
    curl_setopt($duck_ch, CURLOPT_FOLLOWLOCATION, true);
    curl_setopt($duck_ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36"); // Set User-Agent
    curl_setopt($duck_ch, CURLOPT_RETURNTRANSFER, true);
    curl_multi_add_handle($mh, $duck_ch);

    $url = null;    
    $running = null;

    do {
      curl_multi_exec($mh, $running);
    } while ($running);

    $response = curl_multi_getcontent($duck_ch);
      if (!$response || empty($response)) {
        echo "No response received.\n";
        return $results;
      }

      $dom = new DOMDocument();
      libxml_use_internal_errors(true);
      $dom->loadHTML($response);
      libxml_clear_errors();

      $xpath = new DOMXPath($dom);

      
      foreach ($xpath->query("//div[@id='links']//div[contains(@class, 'result')]") as $result) {
        $urlNode = $xpath->evaluate(".//a[@class='result__a']/@href", $result);
        if ($urlNode->length == 0) {
          continue;
        }
        $url = $urlNode[0]->textContent;
        $titleNode = $xpath->evaluate(".//h2[@class='result__title']", $result);
        $title = $titleNode->length > 0 ? $titleNode[0]->textContent : "No title";

        $descriptionNode = $xpath->evaluate(".//a[@class='result__snippet']", $result);
        $description = $descriptionNode->length > 0 ? $descriptionNode[0]->textContent : "No description";

        array_push($results, array(
          "title" => htmlspecialchars($title),
          "url" => htmlspecialchars($url),
          "base_url" => htmlspecialchars(get_base_url($url)),
          "description" => htmlspecialchars($description),
        ));
      }

      $results = array_map('unserialize', array_unique(array_map('serialize', $results)));

      curl_multi_remove_handle($mh, $duck_ch);
      curl_multi_close($mh);
      return $results;
  }

  function print_text_results($results)
  {
    $special = $results[0];
    if (array_key_exists("special_response", $special))
      {
        $response = $special["special_response"]["response"];
        $source = $special["special_response"]["source"];

        echo "<p class=\"special-result-container\">";
        if (array_key_exists("image", $special["special_response"]))
        {
          $image_url = $special["special_response"]["image"];
          echo "<img src=\"image_proxy.php?url=$image_url\">";
        }
        if ($source)
          echo "<a href=\"$source\" target=\"_blank\">$source</a>";
          echo "</p>";
          array_shift($results);
      }

      echo "<div class=\"result-container\">";
      foreach($results as $result)
      {
        $title = $result["title"];
        $url = $result["url"];
        $base_url = $result["base_url"];
        $description = $result["description"];

        echo "<div class=\"result-wrapper\">";
        echo "<a class=\"result-url\" href=\"$url\">";
        echo "$base_url";
        echo "<h2 class=\"result-title\">$title</h2>";
        echo "</a>";
        echo "<span class=\"result-text\">$description</span>";
        echo "</div>";
      }
      echo "</div>";
  }
?>