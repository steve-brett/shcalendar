<?php

if(isset($_POST['action']) && $_POST['action'] == 'Submit') {

  if(isset($_POST['singDate']) && strlen($_POST['singDate']) > 0) {

    $singDate = $_POST['singDate'];
}
  if(isset($_POST['singDate-day']) && $_POST['singDate-day'] > 0) {

    $singDateDay = $_POST['singDate-day'];
}


  if(isset($singDate) && isset($singDateDay))
  {
    $output = $singDateDay;
    $form_success = true;

  } else {

    $form_success = false;
    $output = 'Error: no values passed';
  }
}
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Sacred Harp calendar calculator</title>
    <link href="https://design-system.service.gov.uk/stylesheets/main-2beab115c64c3ab6a1bc2c3d53d93233.css" rel="stylesheet" media="all">
    <link href="http://cdn.govstrap.io/v1/css/govstrap.min.css" rel="stylesheet">
    <!--<link rel="stylesheet" href="style.css">
    <script src="script.js"></script> -->
  </head>
  <body>
<div class="container">
<form method="post" action="">
  <div class="form-group">
  <fieldset class="fieldset" aria-describedby="singDate-hint" role="group">
    <legend class="fieldset__legend fieldset__legend--xl">
      <h1 class="fieldset__heading">
        What date is your singing?
      </h1>
    </legend>
    <span id="singDate-hint" class="help-block">
      For example, 12 6 2018. This can be any past or future singing that
      follows your formula.<br/>
      Do not use the date of a one-off exception: you can set this later.
    </span>
    <div class="date-input form-inline" id="singDate">
      <div class="date-input__item">
        <div class="form-group">
          <label class="label date-input__label" for="singDate-day">
            Day
          </label>
          <input class="input date-input__input input--width-2" id="singDate-day" name="singDate-day" type="number" pattern="[0-9]*">
        </div>
      </div>
      <div class="date-input__item">
        <div class="form-group">
          <label class="label date-input__label" for="singDate-month">
            Month
          </label>
          <input class="input date-input__input input--width-2" id="singDate-month" name="singDate-month" type="number" pattern="[0-9]*">
        </div>
      </div>
      <div class="date-input__item">
        <div class="form-group">
          <label class="label date-input__label" for="singDate-year">
            Year
          </label>
          <input class="input date-input__input input--width-4" id="singDate-year" name="singDate-year" type="number" pattern="[0-9]*">
        </div>
      </div>
    </div>
  </fieldset>
</div>
<button type="submit" class="btn btn-primary">
  Submit
</button>
</form>
<?php if(isset($output)) {
  echo '<p>' . $output . '</p>';
}?>
</div>
</body>
</html>
