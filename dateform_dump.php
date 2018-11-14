
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <title>title</title>
    <!--<link rel="stylesheet" href="style.css">
    <script src="script.js"></script> -->
  </head>
  <body>

  <div class="govuk-form-group">
  <fieldset class="govuk-fieldset" aria-describedby="dob-hint" role="group">
    <legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
      <h1 class="govuk-fieldset__heading">
        What date is your singing?
      </h1>
    </legend>
    <span id="dob-hint" class="govuk-hint">
      For example, 12 6 2018. This can be any past or future singing that
      follows your formula. Do not use the date of a one-off exception: you can
      set this later.
    </span>
    <div class="govuk-date-input" id="dob">
      <div class="govuk-date-input__item">
        <div class="govuk-form-group">
          <label class="govuk-label govuk-date-input__label" for="dob-day">
            Day
          </label>
          <input class="govuk-input govuk-date-input__input govuk-input--width-2" id="dob-day" name="dob-day" type="number" pattern="[0-9]*">
        </div>
      </div>
      <div class="govuk-date-input__item">
        <div class="govuk-form-group">
          <label class="govuk-label govuk-date-input__label" for="dob-month">
            Month
          </label>
          <input class="govuk-input govuk-date-input__input govuk-input--width-2" id="dob-month" name="dob-month" type="number" pattern="[0-9]*">
        </div>
      </div>
      <div class="govuk-date-input__item">
        <div class="govuk-form-group">
          <label class="govuk-label govuk-date-input__label" for="dob-year">
            Year
          </label>
          <input class="govuk-input govuk-date-input__input govuk-input--width-4" id="dob-year" name="dob-year" type="number" pattern="[0-9]*">
        </div>
      </div>
    </div>
  </fieldset>
</div>

</body>
</html>
