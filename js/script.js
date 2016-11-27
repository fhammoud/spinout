// JavaScript Document
$(document).ready(function(e) {
	$('.submit-button').hover(function(e) {
        $(this).toggleClass('submit-button-hover');
    });
	
	$('#company-table .my-row').hover(function(e) {
        $(this).toggleClass('my-row-hover');
    });
	
	var $form = $('.my-form'),
    	$summands = $form.find('.toadd'),
    	$sumDisplay = $('.total-amount');

	$form.delegate('.toadd', 'keyup', function ()
	{
    	var sum = 0;
    	$summands.each(function ()
    	{
        	var value = Number($(this).val());
        	if (!isNaN(value)) sum += value;
    	});

    	$sumDisplay.text('JD ' + sum);
	});
});