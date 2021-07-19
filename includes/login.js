// *****************************************************
/**
 * @param element
 * @param regEx
 * @param message
 */
function validation(element, regEx, message)
{
    ok = false;
    if (!$('#' + $(element).attr('id')).is(':disabled'))
    {
        if (!regEx.test($(element).val()))
        {
            $('#' + $(element).attr('id') + '_exception').html(message);
            $('#' + $(element).attr('id')).css('border-color', '#F00');
        } else
        {
            $('#' + $(element).attr('id') + '_exception').html('');
            $('#' + $(element).attr('id')).css('border-color', '#BBB');
            ok = true;
        }
    }
    return ok;
}

// *************************************************************************
/**
 *
 */
$(function ()
{

// *****************************************************
    /**
     *
     */
    $('#email').on('change', function ()
    {
        validation(this, /^[a-zA-Z0-9_\.\-]*@[a-zA-Z0-9\.\-]+\.[a-zA-Z]{2,4}$/, 'Invalid email address!');
    });

// *****************************************************
    /**
     *
     */
/*    
    $('#password').on('change', function ()
    {
        validation(this, /^[a-zA-Z0-9]{4,20}$/, 'Invalid password!');
    });
*/
// *****************************************************

});
