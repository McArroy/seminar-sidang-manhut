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

$(document).on("submit", "form#form-logout", function(event)
{
	return FormConfirmation(event, ["Anda Yakin Akan Mengakhiri Sesi?", "Sesi Anda Akan Berakhir Dan Silahkan Memulai Sesi Baru"], ["Batal", "Keluar"]);
});

$(document).on("submit", "form#form-delete", function(event)
{
	return FormConfirmation(event, ["Anda Yakin Akan Menghapus Data Ini?", "Pastikan Data Yang Anda Pilih Benar"], ["Batal", "Hapus"]);
});

$(document).on("submit", "form#form-letter", function(event)
{
	if (IsGoogleDriveUrl($("#link").val().trim()))
	{
		return FormConfirmation(event, ["Anda Yakin Akan Menyimpan Data Ini?", "Pastikan Data Yang Dimasukkan Benar"], ["Batal", "Simpan"]);
	}
	else
	{
		DialogMessage(0, ["Terjadi Kesalahan!", "Pastikan Link Yang Anda Masukkan Adalah Link GoogleDrive"], ["Kembali"]);

		return event.preventDefault();
	}
});

$(document).on("submit", "form#form-verification", function(event)
{
	return FormConfirmation(event, ["Apakah Anda Yakin Verifikasi Data Ini?", "Pastikan Data Yang Anda Pilih Benar"], ["Batal", "Verifikasi"]);
});

$(document).on("submit", "form#form-rejection", function(event)
{
	return FormConfirmation(event, ["Apakah Anda Yakin Tolak Data Ini?", "Pastikan Data Yang Anda Pilih Benar. Data Yang Ditolak Akan Hilang"], ["Batal", "Tolak"]);
});

$(document).on("click", "button#revision-seminar", function()
{
	return UpdateQueryParam("seminarcomment", $(this).attr("data-link"));
});

$(document).on("click", "button#revision-thesisdefense", function()
{
	return UpdateQueryParam("thesisdefensecomment", $(this).attr("data-link"));
});

$(document).on("input", "input#search", function()
{
	return UpdateQueryParam("search", this.value, ["page"]);
});

$(document).on("change", "select#semester", function()
{
	return UpdateQueryParam("semester", this.value, ["page"]);
});

$(document).on("change", "select#type", function()
{
	return UpdateQueryParam("type", this.value);
});

$(document).on("click", "button.navigator-button", function()
{
	return UpdateQueryParam("page", $(this).attr("data-link"));
});

$(document).on("click", "button#folder-link", function()
{
	return DialogMessage(0, ["Dokumen Tidak Tersedia", "Silakan Kirim Dokumen Berupa Link Google Drive Di Menu Persyaratan " + $(this).attr("data-text")], ["Kembali"]);
});

$(document).on("click", "button#folder-link-admin", function()
{
	return DialogMessage(0, ["Dokumen Tidak Tersedia", "Mahasiswa Belum Mengirim Dokumen Berupa Link Google Drive Di Menu Persyaratan " + $(this).attr("data-text")], ["Kembali"]);
});

$(document).on("click", "button#add-student", function()
{
	return UpdateQueryParam("addstudent", "");
});

$(document).on("click", "button#edit-student", function()
{
	return UpdateQueryParam("editstudent", $(this).attr("data-link"));
});

$(document).on("click", "button#add-lecturer", function()
{
	return UpdateQueryParam("addlecturer", "");
});

$(document).on("click", "button#edit-lecturer", function()
{
	return UpdateQueryParam("editlecturer", $(this).attr("data-link"));
});

$(document).on("click", "button#add-form-letter", function()
{
	return UpdateQueryParam("announcementform", $(this).attr("data-link"));
});

$(document).on("click", "dialog.input-data .button.confirmation-close", function()
{
	const url = new URL(window.location.href);
	const type = url.searchParams.get("type");
	let newUrl = window.location.pathname;

	if (type)
		newUrl += "?type=" + encodeURIComponent(type);

	window.location.href = newUrl;
});