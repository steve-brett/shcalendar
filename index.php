<?php
include 'dateFormula.php';
// Check if form has been submitted, regardless of method (button, return key, etc)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    if(isset($_POST['singDate-day']) && strlen($_POST['singDate-day']) > 0) {
        $day = $_POST['singDate-day'];
    } else {
        $errors[] = 'Please enter a valid day.';
    }
    if(isset($_POST['singDate-month']) && strlen($_POST['singDate-month']) > 0) {
        $month = $_POST['singDate-month'];
    } else {
        $errors[]= 'Please enter a valid month.';
    }
    if(isset($_POST['singDate-year']) && strlen($_POST['singDate-year']) > 0) {
        $year = $_POST['singDate-year'];
    } else {
        $errors[] = 'Please enter a valid year.';
    }

    if(isset($day) && isset($month) && isset($year))
    {
      // Add leading zeroes to day and month if necessary
      $day = sprintf("%02d", $day);
      $month = sprintf("%02d", $month);

      // Validate the date
      $date = "$year-$month-$day";
      if(validateDate($date, "Y-m-d")) {
          $formSuccess = true;
      } else {
          $formSuccess = false;
          $errors[] = 'Please enter a valid date.';
      }
    } else {
        $formSuccess = false;
    }
} else {
  // Should this be a different name variable?
  $formSuccess = false;
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
<form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">

<?php if ($formSuccess === true) {
  // Start of second page section -------------------------------
  ?>
  <div class="govuk-form-group">
    <fieldset class="govuk-fieldset">
      <legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
        <h1 class="govuk-fieldset__heading">
        <?php
        $date = new DateTime($date, new DateTimeZone('UTC'));
        $formula = new singingFormula($date);
        $formula = $formula->createFormulae();
        echo $date->format('l jS F Y') . PHP_EOL;
        ?>
        </h1>
      </legend>

      <div class="govuk-radios govuk-radios--conditional" data-module="radios">
        <?php foreach ($formula as $k => $formulaOptions) {
            ?>
        <div class="govuk-radios__item">
          <input class="govuk-radios__input" id="date-formula-<?php echo $k; ?>" name="date-formula" type="radio" value="(<?php print_r(implode(",", $formulaOptions))?>)">
          <label class="govuk-label govuk-radios__label" for="date-formula-<?php echo $k; ?>">
            <?php   $output = new interpretFormula($formulaOptions, $date->format('Y'));
            echo $output->text() . PHP_EOL; ?>
          </label>
        </div>
        <?php
        } ?>
      </div>

    </fieldset>
  </div>

<p><a href="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">Choose a different date.</a></p>


<?php } else {
// Start of first page section -------------------------------
  ?>
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
          <input value="<?php if(isset($day)){echo $day;} ?>" class="input date-input__input input--width-2" id="singDate-day" name="singDate-day" type="number" pattern="[0-9]*">
        </div>
      </div>
      <div class="date-input__item">
        <div class="form-group">
          <label class="label date-input__label" for="singDate-month">
            Month
          </label>
          <input value="<?php if(isset($month)){echo $month;} ?>" class="input date-input__input input--width-2" id="singDate-month" name="singDate-month" type="number" pattern="[0-9]*">
        </div>
      </div>
      <div class="date-input__item">
        <div class="form-group">
          <label class="label date-input__label" for="singDate-year">
            Year
          </label>
          <input value="<?php if(isset($year)){echo $year;} ?>" class="input date-input__input input--width-4" id="singDate-year" name="singDate-year" type="number" pattern="[0-9]*">
        </div>
      </div>
    </div>
  </fieldset>
</div>
<?php
// End of first page section -------------------------------
} ?>

<?php if(isset($errors)) {
  foreach($errors as $error) {
      echo '<div class="alert alert-danger" role="alert">' . $error . '</div>';
  }
}?>
<button type="submit" class="btn btn-primary">
  Submit
</button>
</form>
</div>

</body>
</html>
