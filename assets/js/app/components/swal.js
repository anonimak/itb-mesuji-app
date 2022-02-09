// toast
const Toast = Swal.mixin({
	toast: true,
	position: "top-end",
	showConfirmButton: false,
});

// Swal Module
var mySwal = {
	toast: function (title = "", icon = "info") {
		Toast.fire({
			icon,
			title,
			timer: 3000,
			timerProgressBar: true,
		});
	},
	showLoading: function (title = "Memuat data") {
		Toast.fire({
			title: title,
		});
		Toast.showLoading();
	},

	hideLoading: function () {
		Toast.close();
	},

	defaultDialog: function (text = "", callbackResult) {
		Swal.fire({
			text: text,
			icon: "info",
			showCancelButton: true,
			confirmButtonColor: "#3085d6",
			cancelButtonColor: "#d33",
			confirmButtonText: "Yakin",
		}).then(callbackResult);
	},

	deleteDialog: function (text = "", callbackResult) {
		Swal.fire({
			text: text,
			icon: "warning",
			showCancelButton: true,
			confirmButtonColor: "#3085d6",
			cancelButtonColor: "#d33",
			confirmButtonText: "Yakin",
		}).then(callbackResult);
	},
	verifyDialog: function (text = "", callbackResult) {
		Swal.fire({
			text: text,
			icon: "info",
			showCancelButton: true,
			confirmButtonColor: "#3085d6",
			cancelButtonColor: "#d33",
			confirmButtonText: "verifikasi",
		}).then(callbackResult);
	},
	// approvalDialog : function(text='', callbackResult){
	//     Swal.fire({
	//         text: text,
	//         icon: 'info',
	//         showDenyButton: true,
	//         confirmButtonColor: '#3085d6',
	//         confirmButtonText: 'Terima',
	//         denyButtonText: `Tolak`,
	//     }).then(callbackResult)
	// },
	approvalDialog: function (title = "", buttons, willOpen) {
		let containerbuttons = $("<div>").addClass("text-center");
		containerbuttons.append(buttons);
		Swal.fire({
			title: title,
			html: containerbuttons,
			icon: "info",
			willOpen: willOpen,
			showConfirmButton: false,
			showCancelButton: false,
		});
	},
	formInputCustomHtml: function (title, html, willOpen, callbackResult) {
		Swal.fire({
			title: title,
			type: "info",
			html: html,
			confirmButtonColor: "#3085d6",
			confirmButtonText: "Pilih",
			focusConfirm: true,
			willOpen: willOpen,
		}).then(callbackResult);
	},
	formCustomHtml: function (title, html, willOpen, callbackResult) {
		Swal.fire({
			title: title,
			type: "info",
			html: html,
			showConfirmButton: false,
			showCancelButton: false,
			willOpen: willOpen,
		}).then(callbackResult);
	},
};

export default mySwal;
