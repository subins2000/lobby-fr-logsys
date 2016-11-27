<?php
require_once $this->dir . "/src/inc/partial/layout.php";
use \Lobby\App\fr_logsys\Fr\LS;
?>
<div class='contentLoader'>
  <h1>Tokens</h1>
  <p>Tokens are used while user forgets password or on 2 step verification.</p>
  <?php
  if($this->set){
    $this->load();

    if(isset($_POST['clear_tokens'])){
      $sql = $this->dbh->prepare("TRUNCATE TABLE `resetTokens`");
      $sql->execute();
      echo sme("Tokens Cleared", "All tokens were cleared from the table");
    }

    $_GET['start'] = isset($_GET['start']) ? $_GET['start'] : 0;

    $sql = $this->dbh->prepare("SELECT * FROM `resetTokens` LIMIT :start, 10");
    $sql->bindParam(":start", $_GET['start'], \PDO::PARAM_INT);
    $sql->execute();

    if($sql->rowCount() == 0){
      echo sme("No Tokens", "There are currently no tokens stored in the table.");
    }else{
      ?>
      <table>
        <thead>
          <th width='10%'>User ID</th>
          <th width='20%'>Username</th>
          <th width='30%'>Name</th>
          <th width='20%'>Token</th>
          <th title='YYYY-MM-DD HH:MM:SS' width='20%'>Created</th>
        </thead>
        <tbody>
      <?php
      while($r = $sql->fetch()){
  ?>
        <tr>
          <td><?php echo $r['uid'];?></td>
          <td><?php echo LS::getUser("username", $r['uid']);?></td>
          <td><?php echo LS::getUser("name", $r['uid']);?></td>
          <td><?php echo $r['token'];?></td>
          <td><?php echo $r['requested'];?></td>
        </tr>
  <?php
      }
      echo "</tbody></table>";
      echo "<form id='clear_form' action='". $this->url ."/admin/tokens' method='POST'><input type='hidden' name='clear_tokens'/><a class='btn red' onclick=\"confirm('Are you sure you want to delete all tokens') ? $('#workspace #clear_form').submit() : '';\">Clear Tokens</a></form>";
    }
  }else{
  ?>
    <a href='<?php echo $this->url;?>/admin/config' class='btn red'>Setup logSys Admin</a>
  <?php
  }
  ?>
</div>
<?php require_once $this->dir . "/src/inc/partial/layout_footer.php";?>

