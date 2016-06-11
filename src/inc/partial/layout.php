<?php
$this->addStyle("admin.css");
$this->addStyle("jquery.fancybox.css");
$this->addScript("jquery.fancybox.js");
$this->addScript("chart.min.js");
$this->addScript("admin.js");
?>
<div class='leftpane'>
  <center>
    <h3>logSys<br/><span style='font-size: 12px;'><?php echo $this->manifest['version'];?><a style='display: block;' href='http://subinsb.com/php-logsys?utm_source=lobby_logsys.admin' target='_blank'>Documentation</a></span></h3>
  </center>
  <div>
    <?php
    $links = array(
      "/admin" => "Home",
      "/admin/users" => "Users",
      "/admin/tokens" => "Tokens",
      "/admin/stats" => "Stats",
      "/admin/config" => "Settings"
    );
    foreach($links as $link => $name){
      echo $this->l($link, $name, (\Lobby::curPage() == "/app/fr-logsys$link" ? "class='active'" : ""));
    }
    ?>
  </div>
</div>
<div class='rightpane'>
