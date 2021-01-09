<?php

/**********************************************
 * User form for generating repeating events. *
 * Needs better structure                     *
 * Steve Brett November 2018                  *
 **********************************************/

namespace SHCalendar;

use SHCalendar\Rule;
use SHCalendar\RuleCreator;
use SHCalendar\Helpers;

// Composer
include 'vendor/autoload.php';

// Check if form has been submitted, regardless of method (button, return key, etc)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['singDate-day']) && strlen($_POST['singDate-day']) > 0) {
        $day = $_POST['singDate-day'];
    } else {
        $errors[] = 'Please enter a valid day. ';
    }
    if (isset($_POST['singDate-month']) && strlen($_POST['singDate-month']) > 0) {
        $month = $_POST['singDate-month'];
    } else {
        $errors[] = 'Please enter a valid month. ';
    }
    if (isset($_POST['singDate-year']) && strlen($_POST['singDate-year']) > 0) {
        $year = $_POST['singDate-year'];
    } else {
        $errors[] = 'Please enter a valid year. ';
    }

    if (isset($_POST['endDate-day'])) {
        $endDay = $_POST['endDate-day'];
    }
    if (isset($_POST['endDate-month'])) {
        $endMonth = $_POST['endDate-month'];
    }
    if (isset($_POST['endDate-year'])) {
        $endYear = $_POST['endDate-year'];
    }

    if (isset($endDay) && isset($endMonth) && isset($endYear)) {
        $endDateSet = true;

        if (empty($endDay) && empty($endMonth) && empty($endYear)) {
            $endDateSet = false;
        }

        // Add leading zeroes to day and month if necessary
        $endDay = sprintf("%02d", $endDay);
        $endMonth = sprintf("%02d", $endMonth);

        // Validate the date
        $endDateRaw = "$endYear-$endMonth-$endDay";
        $validEndDate = Helpers::validateDate($endDateRaw, "Y-m-d");
        if (!$validEndDate) {
            $errors[] = 'Please enter a valid end date. ';
        }
    }

    if (isset($day) && isset($month) && isset($year)) {
        // Add leading zeroes to day and month if necessary
        $day = sprintf("%02d", $day);
        $month = sprintf("%02d", $month);

        // Validate the date
        $startDateRaw = "$year-$month-$day";
        $validStartDate = Helpers::validateDate($startDateRaw, "Y-m-d");
        $formSuccess = true;

        if (!$validStartDate) {
            $formSuccess = false;
            $errors[] = 'Please enter a valid start date. ';
        }
    } else {
        $endDateSet = false;
        $formSuccess = false;
    }
} else {
    // Should this be a different name variable?
    $formSuccess = false;
}
?>
<!DOCTYPE html>
<html lang="en" class="govuk-template app-html-class">

<head>
    <meta charset="utf-8">
    <title>Sacred Harp calendar calculator</title>
    <!--[if !IE 8]><!-->
    <link rel="stylesheet" href="stylesheets/govuk-frontend-3.7.0.min.css">
    <!--<![endif]-->
    <!--[if IE 8]>
          <link rel="stylesheet" href="stylesheets/govuk-frontend-ie8-3.7.0.min.css">
        <![endif]-->
    <!--<link href="http://cdn.govstrap.io/v1/css/govstrap.min.css" rel="stylesheet"> -->
    <link rel="stylesheet" href="stylesheets/override.css">

</head>

<body class="govuk-template__body app-body-class">

    <div class="govuk-width-container">
        <main class="govuk-main-wrapper app-main-class" id="main-content" role="main">

            <?php if (!isset($dateFormula)) : ?>
                <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
                    <?php if ($formSuccess === true) :?>
                        <?php
                        // Start of second page section -------------------------------
                        $startDate = new \DateTime($startDateRaw, new \DateTimeZone('UTC'));
                        if ($endDateSet) {
                            $endDate = new \DateTime($endDateRaw, new \DateTimeZone('UTC'));
                        } else {
                            $endDate = null;
                        }

                        $creator = new RuleCreator;
                        $formulae = $creator->create($startDate, $endDate);
                        ?>
                        <div class="govuk-form-group">
                            <fieldset class="govuk-fieldset">
                                <legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
                                    <h1 class="govuk-fieldset__heading govuk-heading-xl">
                                        <?php
                                        if (!is_null($endDate)) {
                                            echo $startDate->format('j –') . PHP_EOL;
                                            echo $endDate->format('j F Y') . PHP_EOL;
                                        } else {
                                            echo $startDate->format('l j F Y') . PHP_EOL;
                                        }
                                        ?>
                                    </h1>
                                </legend>
                                <span id="dateFormula-hint" class="govuk-hint">
                                    Choose the date formula that describes your singing.<br />
                                    If you are not presented with a correct formula, please <a href="mailto:steve.brett.design@gmail.com">email me</a>.
                                </span>
                                <div class="govuk-radios govuk-radios--conditional" data-module="radios">
                                    <?php foreach ($formulae as $k => $formula) : ?>

                                        <div class="govuk-radios__item">
                                            <input class="govuk-radios__input dateFormula" id="dateFormula-<?php echo $k; ?>" data-key="<?php echo $k; ?>" name="dateFormula" type="radio" value="<?php echo htmlspecialchars(json_encode($formula), ENT_QUOTES, 'UTF-8'); ?>">
                                            <label class="govuk-label govuk-radios__label" for="dateFormula-<?php echo $k; ?>">
                                                <?php
                                                $rule = new Rule;
                                                echo $rule->readable($formula);
                                                ?>
                                            </label>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </fieldset>
                        </div>


                        <section class="results">
                            <h2 class="govuk-heading-l">Next five singings:</h2>

                            <?php foreach ($formulae as $k => $formula) : ?>
                                <div id="results__item--<?php echo $k; ?>" class="govuk-grid-row results__item" hidden>
                                    <div class="govuk-grid-column-two-thirds">
                                    <dl class="govuk-summary-list">                                        <?php
                                        try {
                                            $years = $rule->getDates($formula, 5);
                                            foreach ($years as $occurrence) {
                                                ?>
                                                  <div class="govuk-summary-list__row">
                                                    <dt class="govuk-summary-list__key">
                                                    <?php echo Helpers::formatYearRange($occurrence['start'], $occurrence['end']); ?>
                                                    </dt>
                                                    <dd class="govuk-summary-list__value">
                                                    <?php echo Helpers::formatDateRange($occurrence['start'], $occurrence['end']); ?>
                                                    </dd>
                                                </div>
                                                <?php
                                            }
                                        } catch (\Exception $e) {
                                            ?>
                                            <section class="govuk-error-summary">
                                                <h2 class="govuk-error-summary__title">
                                                    Not yet available
                                                </h2>
                                                <div class="govuk-error-summary__body">
                                                    <p>
                                                        <?php echo $e->getMessage(); ?>
                                                    </p>
                                                </div>
                                            </section>
                                            <?php
                                        }
                                        ?>
                                    </dl>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </section>

                        <p><a href="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" class="govuk-link">Choose a different date.</a></p>


                    <?php else : ?>
                        <?php
                        // Start of first page section -------------------------------
                        ?>
                        <div class="govuk-form-group<?php if (isset($errors)) {
                            echo ' govuk-form-group--error';
                        } ?>">
                            <fieldset class="govuk-fieldset" aria-describedby="singDate-hint" role="group">
                                <legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
                                    <h1 class="govuk-fieldset__heading govuk-heading-xl">
                                        What date is your singing?
                                    </h1>
                                </legend>
                                <span id="singDate-hint" class="govuk-hint">
                                    For example, 28 7 2018. This can be any past or future singing that
                                    follows your formula.
                                </span>
                                <span id="singDate-error" class="govuk-error-message">
                                    <?php if (isset($errors)) {
                            foreach ($errors as $error) {
                                echo $error;
                            }
                        } ?>
                                </span>
                                <div class="govuk-date-input govuk-form-inline govuk-!-margin-bottom-5" id="singDate">
                                    <div class="govuk-date-input__item">
                                        <div class="govuk-form-group">
                                            <label class="govuk-label govuk-date-input__label" for="singDate-day">
                                                Day
                                            </label>
                                            <input value="<?php if (isset($day)) {
                            echo $day;
                        } ?>" class="govuk-input govuk-date-input__input govuk-input--width-2" id="singDate-day" name="singDate-day" type="number" pattern="[0-9]*">
                                        </div>
                                    </div>
                                    <div class="govuk-date-input__item">
                                        <div class="govuk-form-group">
                                            <label class="govuk-label govuk-date-input__label" for="singDate-month">
                                                Month
                                            </label>
                                            <input value="<?php if (isset($month)) {
                            echo $month;
                        } ?>" class="govuk-input govuk-date-input__input govuk-input--width-2" id="singDate-month" name="singDate-month" type="number" pattern="[0-9]*">
                                        </div>
                                    </div>
                                    <div class="govuk-date-input__item">
                                        <div class="govuk-form-group">
                                            <label class="govuk-label govuk-date-input__label" for="singDate-year">
                                                Year
                                            </label>
                                            <input value="<?php if (isset($year)) {
                            echo $year;
                        } ?>" class="govuk-input govuk-date-input__input govuk-input--width-4" id="singDate-year" name="singDate-year" type="number" pattern="[0-9]*">
                                        </div>
                                    </div>
                                </div>

                                <span class="govuk-hint">
                                    Optionally, you can add an end date for multi-day singings.
                                </span>

                                <div class="govuk-date-input govuk-form-inline" id="endDate">
                                    <div class="govuk-date-input__item">
                                        <div class="govuk-form-group">
                                            <label class="govuk-label govuk-date-input__label" for="endDate-day">
                                                Day
                                            </label>
                                            <input value="<?php if (isset($endDay)) {
                            echo $endDay;
                        } ?>" class="govuk-input govuk-date-input__input govuk-input--width-2" id="endDate-day" name="endDate-day" type="number" pattern="[0-9]*">
                                        </div>
                                    </div>
                                    <div class="govuk-date-input__item">
                                        <div class="govuk-form-group">
                                            <label class="govuk-label govuk-date-input__label" for="endDate-month">
                                                Month
                                            </label>
                                            <input value="<?php if (isset($endMonth)) {
                            echo $endMonth;
                        } ?>" class="govuk-input govuk-date-input__input govuk-input--width-2" id="endDate-month" name="endDate-month" type="number" pattern="[0-9]*">
                                        </div>
                                    </div>
                                    <div class="govuk-date-input__item">
                                        <div class="govuk-form-group">
                                            <label class="govuk-label govuk-date-input__label" for="endDate-year">
                                                Year
                                            </label>
                                            <input value="<?php if (isset($endYear)) {
                            echo $endYear;
                        } ?>" class="govuk-input govuk-date-input__input govuk-input--width-4" id="endDate-year" name="endDate-year" type="number" pattern="[0-9]*">
                                        </div>
                                    </div>
                                </div>
                            </fieldset>
                        </div>

                        <button type="submit" class="govuk-button">
                            Submit
                        </button>

                        <?php
                        // End of first page section -------------------------------
                        ?>
                    <?php endif;?>
                </form>
            <?php endif; ?>

        </main>
    </div>

    <script src="javascript/govuk-frontend-2.3.0.min.js"></script>
    <script>
        window.GOVUKFrontend.initAll();
    </script>
    <script>
        function showResults(option) {
            let key = option.dataset.key;
            const results = document.querySelectorAll('.results__item');
            const result = document.querySelector('#results__item--' + key);

            results.forEach(element => element.setAttribute('hidden', 'true'));
            result.removeAttribute('hidden');
        }
        const options = document.querySelectorAll('.dateFormula');
        options.forEach(item => {
            item.addEventListener('click', event => {
                showResults(event.target);
            })
        });

    </script>
</body>

</html>