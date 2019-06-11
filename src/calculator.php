<?php
declare(strict_types=1);

namespace SHCalendar;

include 'dateFormula.php';
use SHCalendar\singingFormula;
use SHCalendar\interpretFormula;

$start = isset($_GET['start']) ? $_GET['start'] : '';
  $end = isset($_GET['end']) ? $_GET['end'] : '';

// maybe pass values to validator function in a try/catch
// instead of doing it here
$start_object = \DateTime::createFromFormat(\DateTimeInterface::ATOM, $start);
  $end_object = \DateTime::createFromFormat(\DateTimeInterface::ATOM, $end);

if ( ($start_object == false) || ($end_object == false) )
{
  echo 'Invalid date. ';
  echo 'Got START: ' . $start . ', END: ' . $end . PHP_EOL;
  echo '<br><br><a href="http://shcalendar.localhost/src/calculator.php?start=2019-05-23T23%3A14%3A57%2B02%3A00">calculator.php?start=2019-05-23T23%3A14%3A57%2B02%3A00</a>';
  exit();
}


// send dates to function here
?>

<?php $formulae = new singingFormula($start_object); ?>
<?php $formulae = $formulae->createFormulae(); ?>

<tr>
  <td></td>
  <td>
    <span>Choose the date formula that describes your singing.<br/>
    If you are not presented with a correct formula, please <a href="mailto:steve.brett.design@gmail.com">email me</a>.
    </span>
  </td>
</tr>
<?php foreach ($formulae as $k => $formula) {
    ?>
    <tr>
  <td></td>
  <td>
  <input class="govuk-radios__input" id="dateFormula-<?php echo $k; ?>" name="dateFormula" type="radio" value="<?php print_r(implode(",", $formula))?>">
  <label class="govuk-label govuk-radios__label" for="dateFormula-<?php echo $k; ?>">
    <?php   $output = new interpretFormula($formula, $start_object->format('Y'));
    echo $output->text() . PHP_EOL; ?>
  </label>
  </td>
</tr>
<?php
} ?>


