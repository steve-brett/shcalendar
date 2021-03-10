<?php
// Composer
include '../vendor/autoload.php';
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
        <?php include 'demo.php'; ?>
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