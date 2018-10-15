<?php include 'dateFormula.php'; ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>Singing day formula calcuator</title>
    <!--<link rel="stylesheet" href="style.css">
    <script src="script.js"></script> -->
  </head>
  <body>

    <div class="govuk-form-group">
      <fieldset class="govuk-fieldset">
        <legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
          <h1 class="govuk-fieldset__heading">
          <?php
          $testdate = '2014-01-01';
          $testFormula = createFormula(date($testdate));
          echo date("l jS F Y", strtotime($testdate));
          ?>
          </h1>
        </legend>

        <div class="govuk-radios govuk-radios--conditional" data-module="radios">
          <?php foreach ($testFormula as $k => $formulaOptions) { ?>
          <div class="govuk-radios__item">
          	<input class="govuk-radios__input" id="date-formula-<?php echo $k; ?>" name="date-formula" type="radio" value="(<?php print_r(implode(",",$formulaOptions))?>)">
          	<label class="govuk-label govuk-radios__label" for="date-formula-<?php echo $k; ?>">
              <?php echo printFormula($formulaOptions);?>
          	</label>
          </div>
          <?php } ?>
        </div>

      </fieldset>
    </div>

<div class="govuk-form-group">
  <fieldset class="govuk-fieldset">
    <legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
      <h1 class="govuk-fieldset__heading">
      <?php
      $testdate = '2018-05-12';
      $testFormula = createFormula(date($testdate));
      echo date("l jS F Y", strtotime($testdate));
      ?>
      </h1>
    </legend>

    <div class="govuk-radios govuk-radios--conditional" data-module="radios">
      <?php foreach ($testFormula as $k => $formulaOptions) { ?>
      <div class="govuk-radios__item">
      	<input class="govuk-radios__input" id="date-formula-<?php echo $k; ?>" name="date-formula" type="radio" value="(<?php print_r(implode(",",$formulaOptions))?>)">
      	<label class="govuk-label govuk-radios__label" for="date-formula-<?php echo $k; ?>">
          <?php echo printFormula($formulaOptions);?>
      	</label>
      </div>
      <?php } ?>
    </div>

  </fieldset>
</div>

<div class="govuk-form-group">
  <fieldset class="govuk-fieldset">
    <legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
      <h1 class="govuk-fieldset__heading">
      <?php
      $testdate = '2018-06-30';
      $testFormula = createFormula(date($testdate));
      echo date("l jS F Y", strtotime($testdate));
      ?>
      </h1>
    </legend>

    <div class="govuk-radios govuk-radios--conditional" data-module="radios">
      <?php foreach ($testFormula as $k => $formulaOptions) { ?>
      <div class="govuk-radios__item">
      	<input class="govuk-radios__input" id="date-formula-<?php echo $k; ?>" name="date-formula" type="radio" value="(<?php print_r(implode(",",$formulaOptions))?>)">
      	<label class="govuk-label govuk-radios__label" for="date-formula-<?php echo $k; ?>">
          <?php echo printFormula($formulaOptions);?>
      	</label>
      </div>
      <?php } ?>
    </div>

  </fieldset>
</div>

<div class="govuk-form-group">
  <fieldset class="govuk-fieldset">
    <legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
      <h1 class="govuk-fieldset__heading">
      <?php
      $testdate = '2018-07-01';
      $testFormula = createFormula(date($testdate));
      echo date("l jS F Y", strtotime($testdate));
      ?>
      </h1>
    </legend>

    <div class="govuk-radios govuk-radios--conditional" data-module="radios">
      <?php foreach ($testFormula as $k => $formulaOptions) { ?>
      <div class="govuk-radios__item">
      	<input class="govuk-radios__input" id="date-formula-<?php echo $k; ?>" name="date-formula" type="radio" value="(<?php print_r(implode(",",$formulaOptions))?>)">
      	<label class="govuk-label govuk-radios__label" for="date-formula-<?php echo $k; ?>">
          <?php echo printFormula($formulaOptions);?>
      	</label>
      </div>
      <?php } ?>
    </div>

  </fieldset>
</div>

</body>
</html>
