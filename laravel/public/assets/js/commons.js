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

$(function()
{
	var navbarSideClosed = localStorage.getItem("sidebarClosed") === "true";

	if (!navbarSideClosed)
		$("navbar.side").addClass("active");
	
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

	$("select.select2").select2();

	ValidateForms();
});

$(document).on("input change focus", "textarea", function()
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

$(document).on("submit", "form#form-delete-user", function(event)
{
	return FormConfirmation(event, ["Anda Yakin Akan Menghapus Data Pengguna Ini?", "Pastikan Data Pengguna Yang Anda Pilih Benar<br>Data Pengguna Yang Dihapus, Memungkinkan Juga Akan Menghapus Seluruh Data Yang Mencakup ID, Nama, Hak Akses, Dan Surat-Surat Yang Berhubungan Dengan Data Pengguna Serta Aksi Penghapusan Tidak Bisa Dibatalkan Atau Dikembalikan"], ["Batal", "Hapus"]);
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
	return UpdateQueryParam("type", this.value, ["page"]);
});

$(document).on("click", "button.navigator-button", function()
{
	return UpdateQueryParam("page", $(this).attr("data-link"));
});

$(document).on("click", "button#folder-link", function()
{
	return DialogMessage(0, ["Dokumen Tidak Tersedia", "Silakan Kirim Dokumen Berupa Link Google Drive Di Menu <a href='" + $(this).attr("data-link") + "'>" + "Persyaratan " + $(this).attr("data-text") + "</a>"], ["Kembali"]);
});

$(document).on("click", "button#folder-link-admin", function()
{
	return DialogMessage(0, ["Dokumen Tidak Tersedia", "Mahasiswa Belum Mengirim Dokumen Berupa Link Google Drive Di Menu Persyaratan " + $(this).attr("data-text")], ["Kembali"]);
});

$(document).on("keydown", "dialog form", function(event)
{
	if (event.key === "Enter")
	{
		if (event.target.tagName.toLowerCase() !== "textarea")
			event.preventDefault();
	}
});

$(document).on("click", "dialog.input-data .button.confirmation-close", function()
{
	RemoveDialog();
});

$(document).on("click", "dialog", function(event)
{
	if (event.target === this)
		RemoveDialog();
});