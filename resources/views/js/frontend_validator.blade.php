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
      case "rangew":
        isValid = (value > set.minw || value < set.maxw);
        return isValid;
        break;
      case "minw":
        isValid = (value > set.minw);
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
      case "rangew":
        if (value <= set.minw || value >= set.maxw) {
          isValid = [false, "Row " + (tableRow + 1) + " and column " + ruleset.column + " has a value that is out of the numeric range [" + set.minw + ", " + set.maxw + ", both numbers exclusive]"];
          return false;
        }
        break;
      case "minw":
        if (value <= set.minw) {
          isValid = [false, "Row " + (tableRow + 1) + " and column " + ruleset.column + " must be greater than " + set.minw];
          return false;
        }
        break;
    }
  });

  return (isValid.length > 0 ? isValid : [true, ""]);
};

/* multiValidatorGeneral
 * Returns an array with a boolean containing a validation result and a message in case the validation fails
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

    if (isValid === null && value !== null && value !== "" && value !== undefined) {
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
        case "rangew":
          if (value <= set.minw || value >= set.maxw) {
            isValid = [false, "The field " + ruleset.column + " has a value that is out of the numeric range [" + set.minw + ", " + set.maxw + ", both numbers exclusive]"];
            return false;
          }
          break;
        case "minw":
          if (value <= set.minw) {
            isValid = [false, "The field " + ruleset.column + " must be greater than " + set.minw];
            return false;
          }
          break;
        case "selection":
          if (!set.selections.includes(value)) {
            isValid = [false, "The field " + ruleset.column + " has a value that is not part of the allowed selection"];
            return false;
          }
          break;
        case "selectionmultiple":
          for (var i = 0; i < value.length; i++) {
            if (!set.selections.includes(value[i])) {
              isValid = [false, "The field " + ruleset.column + " has a set or values that are not part of the allowed selection"];
              break;
              return false;
            }
          }
          break;
      }
    }
  });

  return (isValid !== null ? isValid : [true, ""]);
};

/* validateTable
 * Returns an array which contains either an error message string or an object with a set of error messages
 * params {tableName: string, tableData: array, tableRuleset: array}
 * returns {array}
*/
function validateTable(tableName, tableData, tableRuleset, action = "run", isRequired = true) {
  var message = "";
  var tableLength = tableData.length;
  var rowValidation = [];
  var errorMessages = [];

  if (tableLength < 1 && action == "run" && isRequired) {
    message = "The table " + tableName + " is empty. Please check your data";
    return [message];
  } else if (tableLength > 0) {
    var tableColumnLength = tableData[0].length;

    for (var i = 0; i < tableLength; i++) {
      for (var j = 0; j < tableColumnLength; j++) {
        var rowValidation = multiValidatorTable(tableData[i][j], tableName, i, tableRuleset[j]);
        if (!rowValidation[0]) {
          errorMessages.push(rowValidation[1]);
        }
      }
    }
  }

  if (errorMessages.length > 0) {
    return [{message: "The table " + tableName + " has validation errors (click to expand)", errors: errorMessages}];
  } else {
    return [];
  }
}

/* validateField
 * Validates an individual field in the form
 * params {action: string, titleTab: string, tabTitle: string, validationMessages: array, value: mixed, ruleset: object}
*/
function validateField(action, titleTab, tabTitle, validationMessages, value, ruleset) {
  var generalValidator = multiValidatorGeneral(action, value, ruleset);
  if (!generalValidator[0]) {
    if (titleTab == "") {
      titleTab = tabTitle;
      validationMessages = validationMessages.concat(titleTab);
    }
    validationMessages = validationMessages.concat(generalValidator[1]);
  }

  return [titleTab, validationMessages];
}
</script>