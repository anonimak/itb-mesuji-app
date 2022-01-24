import mySwal from "../components/swal.js";

export default (function () {
	// init scope
	let app = context.app;
	let table = app.find("#tableKaprodi");
	let ButtonDelete = table.find("button.delete");
	let ButtonVerify = table.find("button.verify");
	// event
	ButtonDelete.click(actionButtonDeleteClick);
	ButtonVerify.click(actionButtonVerifyClick);

	function init() {
		table.DataTable({
			filter: true,
			fixedColumns: true,
		});
	}

	function actionButtonDeleteClick(e) {
		let currentButton = $(e.currentTarget);
		mySwal.deleteDialog("Yakin akan menghapus data ini ?", (result) => {
			if (result.isConfirmed) {
				document.location.href = currentButton.data("url");
			}
		});
	}

	function actionButtonVerifyClick(e) {
		let currentButton = $(e.currentTarget);
		mySwal.verifyDialog("Verifikasi data ini?", (result) => {
			if (result.isConfirmed) {
				document.location.href = currentButton.data("url");
			}
		});
	}

	return {
		init,
	};
})();
