/**
 * Admin panel scripts
 */
$(document).ready(function () {

});

/**
 * Add input with click on button
 * @param wrapSelector
 */
function addInput(wrapSelector) {
    if (wrapSelector === '.add-input-wrap') {
        $(wrapSelector).find('input')
            .filter(':last')
            .after('<input type="text" class="form-control" name="prop_enums_add[]" value="">');
    }
}
