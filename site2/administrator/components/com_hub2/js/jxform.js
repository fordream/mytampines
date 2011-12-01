// requires form-validation.js
function submitbutton(task) {
    var form = document.adminForm;
    if (task == "cancel" || myValidate(form)) {
        submitform(task);
    }
}

function trim(str, chars) {
    return ltrim(rtrim(str, chars), chars);
}

function ltrim(str, chars) {
    chars = chars || "\\s";
    return str.replace(new RegExp("^[" + chars + "]+", "g"), "");
}

function rtrim(str, chars) {
    chars = chars || "\\s";
    return str.replace(new RegExp("[" + chars + "]+$", "g"), "");
}

function addTitleOnBlurHandler(fromField,toField) {
    jQuery("input[name='"+fromField+"']").bind('blur', function() {
        var titleValue = jQuery(this).val();
        var aliasValue = jQuery("input[name='"+toField+"']").val();
        if (aliasValue=='') {
            aliasValue = titleValue.replace(
                    /[\s\-]+/g,'-').replace(/&/g,'and').replace(/[^A-Z0-9\-\_]/ig,'').toLowerCase();
        }
        jQuery("input[name='"+toField+"']").val(aliasValue);
    });
}
