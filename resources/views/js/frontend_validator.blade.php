<script type="text/javascript">
/* Generic frontend validation functions */

/* multiValidatorHandsonTable
 * Returns a boolean with a check for specific rule
 * params {value: mixed, ruleset: object}
 * returns {boolean}
*/
function multiValidatorHandsonTable(value, ruleset)
{
  var isValid = false;

  $.each(ruleset.rules, function (key, set) {
    switch (set.rule) {
      case "any":
        isValid = true;
        return false;
        break;
      case "numeric":
        isValid = $.isNumeric(value);
        return isValid;
        break;
      case "range":
        isValid = (value >= set.min && value <= set.max);
        return isValid;
        break;
    }
  });

  return isValid;
};

/* multiValidatorTable
 * Returns an array with a boolean with a validation result and a message in case the validation fails
 * params {value: mixed, tableName: string, tableRow: int, ruleset: object}
 * returns {array}
*/
function multiValidatorTable(value, tableName, tableRow, ruleset)
{
  var isValid = [];

  $.each(ruleset.rules, function (key, set) {
    switch (set.rule) {
      case "any":
        return false;
        break;
      case "required":
        if (value === null || value === "") {
          isValid = [false, "Row " + (tableRow + 1) + " and column " + ruleset.column + " has an empty value"];
          return false;
        }
        break;
      case "numeric":
        if (!$.isNumeric(value)) {
          isValid = [false, "Row " + (tableRow + 1) + " and column " + ruleset.column + " has a non numeric value"];
          return false;
        }
        break;
      case "range":
        if (value < set.min || value > set.max) {
          isValid = [false, "Row " + (tableRow + 1) + " and column " + ruleset.column + " has a value that is out of the numeric range [" + set.min + ", " + set.max + "]"];
          return false;
        }
        break;
    }
  });

  return (isValid.length > 0 ? isValid : [true, ""]);
};

/* multiValidatorGeneral
 * Returns an array with a boolean with a validation result and a message in case the validation fails
 * params {action: string, value: mixed, tableName: string, tableRow: int, ruleset: object}
 * returns {array}
*/
function multiValidatorGeneral(action, value, ruleset)
{
  var isValid = null;

  $.each(ruleset.rules, function (key, set) {
    if (action === "run") {
      switch (set.rule) {
        case "any":
          isValid = [true, ""];
          return false;
          break;
        case "required":
          if (value === null || value === "") {
            isValid = [false, "The field " + ruleset.column + " has an empty value"];
            return false;
          }
          break;
        case "requiredselect":
          if (value === null || value === "") {
            isValid = [false, "There is no " + ruleset.column + " selected"];
            return false;
          }
          break;
      }
    }

    if (isValid === null && value !== null && value !== "") {
      switch (set.rule) {
        case "numeric":
          if (!$.isNumeric(value)) {
            isValid = [false, "The field " + ruleset.column + " has a non numeric value"];
            return false;
          }
          break;
        case "range":
          if (value < set.min || value > set.max) {
            isValid = [false, "The field " + ruleset.column + " has a value that is out of the numeric range [" + set.min + ", " + set.max + "]"];
            return false;
          }
          break;
      }
    }
  });

  return (isValid !== null ? isValid : [true, ""]);
};
</script>