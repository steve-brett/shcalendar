<?php

/**********************************************
 * User form for generating repeating events. *
 * Needs better structure                     *
 * Steve Brett November 2018                  *
 **********************************************/

namespace SHCalendar;

use SHCalendar\Rule;
use SHCalendar\RuleCreator;

include 'src/Rule.php';
include 'src/RuleCreator.php';
// For validateDate()
include 'src/handyFunctions.php';
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

    if (isset($day) && isset($month) && isset($year)) {
        // Add leading zeroes to day and month if necessary
        $day = sprintf("%02d", $day);
        $month = sprintf("%02d", $month);

        // Validate the date
        $date = "$year-$month-$day";
        if (validateDate($date, "Y-m-d")) {
            $formSuccess = true;
        } else {
            $formSuccess = false;
            $errors[] = 'Please enter a valid date. ';
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
<html lang="en" class="govuk-template app-html-class">

<head>
    <meta charset="utf-8">
    <title>Sacred Harp calendar calculator</title>
    <!--[if !IE 8]><!-->
    <link rel="stylesheet" href="stylesheets/govuk-frontend-2.3.0.min.css">
    <!--<![endif]-->
    <!--[if IE 8]>
          <link rel="stylesheet" href="stylesheets/govuk-frontend-ie8-2.3.0.min.css">
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
                        $date = new \DateTime($date, new \DateTimeZone('UTC'));
                        $creator = new RuleCreator;
                        $formulae = $creator->create($date);
                        ?>
                        <div class="govuk-form-group">
                            <fieldset class="govuk-fieldset">
                                <legend class="govuk-fieldset__legend govuk-fieldset__legend--xl">
                                    <h1 class="govuk-fieldset__heading govuk-heading-xl">
                                        <?php
                                        echo $date->format('l jS F Y') . PHP_EOL;
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
                                <div id="results__item--<?php echo $k; ?>" class="results__item" hidden>
                                    <ol class="govuk-list govuk-list--bullet">
                                        <?php 
                                        try 
                                        {
                                            $rrule = new \RRule\RRule($rule->rfc5545($formula) . ';COUNT=5' );
                                            foreach ($rrule as $occurrence ) {
                                                echo '<li>' . $occurrence->format('j M Y') . '</li>',"\n";
                                            }
                                        } 
                                        catch (\Exception $e) 
                                        {
                                            echo $e;
                                        }
                                        ?>
                                    </ol>
                                    <pre>
                                        <?php var_dump($formula); ?>
                                        <?php var_dump($rule->rfc5545($formula)); ?>
                                    </pre>
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
                                <div class="govuk-date-input govuk-form-inline" id="singDate">
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