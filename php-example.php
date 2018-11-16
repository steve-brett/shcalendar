<?php
  // The global $_POST variable allows you to access the data sent with the POST method by name
  // To access the data sent with the GET method, you can use $_GET
$say = $to = "";
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $say = htmlspecialchars($_POST['say']);
    $to  = htmlspecialchars($_POST['to']);
}
?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>PHP form submission example</title>
    <style>
      form {
        width: 420px;
      }
      div {
        margin-bottom: 20px;
      }
      label {
        display: inline-block;
        width: 240px;
        text-align: right;
        padding-right: 10px;
      }
      button, input {
        float: right;
      }
    </style>
  </head>
  <body>
    <form method="post" action="php-example.php">
      <div>
        <label for="say">What greeting do you want to say?</label>
        <input name="say" id="say" value="Hi">
      </div>
      <div>
        <label for="to">Who do you want to say it to?</label>
        <input name="to" value="Mom">
      </div>
      <div>
        <button>Send my greetings</button>
      </div>
    </form>
    <p><?php   echo  $say, ' ', $to; ?>
  </body>
</html>
