$(window).on("load", function()
{
	DeactivateLoadingAnimation();
});

$(window).on("beforeunload", function()
{
	RemoveDialog();
	ActivateLoadingAnimation();
});

$(window).on("pageshow", function(event)
{
	if (event.originalEvent.persisted)
		DeactivateLoadingAnimation();
});

$(document).ready(function()
{
	$("[autofocus]").each(function()
	{
		var $Input = $(this);
		$Input.focus();

		if (this.setSelectionRange && $Input.val().length)
		{
			var Len = $Input.val().length;
			this.setSelectionRange(Len, Len);
		}
	});
});

$(document).on("keydown", function(e)
{
	if (e.key === "Escape")
		RemoveDialog();

	if (e.key === "Enter")
		e.stopPropagation();
});

$(document).on("input", "textarea", function()
{
	AutoResizeTextarea($(this));
});