<?php require "misc/header.php"; ?>
    <title>Search</title>
  </head>
  <body>
    <form class="search-container" action="search.php" method="get" autocomplete="off">
      <h1 class="logo-main">Search</h1>
        <input class="search-input" type="text" name="q" autofocus/>
        <input type="hidden" name="p" value="0"/>
        <input type="hidden" name="t" value="0"/>
        <input type="submit" class="hide"/>
      <div class="search-button-wrapper">
        <button class="search-button" name="t" value="0" type="submit">Search the web</button>
        <button class="search-button" name="t" value="1" type="submit">Search with TOR</button>
      </div>
    </form>
<?php require "misc/footer.php"; ?>
