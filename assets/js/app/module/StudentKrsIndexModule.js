import mySwal from "../components/swal.js";
import krsAPI from "../request/krsAPI.js";

export default (function () {
	// init scope
	let body = context.body;
	let app = context.app;

	// component
	let TableCourseTaken = app.find("#table-course-taken");
	let TableCourse = app.find("#table-course");
	let ModalChooseCourse = app.find("#modal-choose-course");
	let ButtonAddCourse = ModalChooseCourse.find("button.submit-choose-course");
	let ButtonSubmitKrs = app.find("button.submit-krs");
	let ButtonModalCourse = app.find("button#btn-modal-course");

	// set to datatable
	let dataTableCourseTaken = TableCourseTaken.DataTable({ searching: false });
	let dataTableCourse = TableCourse.DataTable({ searching: true });
	// delegation component
	body.on("click", ".btn-remove-item-course-taken", actionRemoveTakenCourse);

	// delegation component
	body.on("change", ".checkbox-item-course", function (e) {
		let self = $(e.currentTarget);
		let id = self.data("id");
		let coursefiltered = data.courses.find((item) => item.id == id);
		if (self.is(":checked")) {
			let credit = data.creditBasket + parseInt(coursefiltered.sks);
			if (credit >= data.limitCredit) {
				self.prop("checked", false);
				mySwal.toast(
					"jumlah SKS yang dipilih melebihi limit kredit.",
					"warning"
				);
			} else {
				data.courseListBasket = [...data.courseListBasket, coursefiltered.id];
				data.creditBasket = credit;
			}
		} else {
			data.creditBasket = data.creditBasket - parseInt(coursefiltered.sks);
			data.courseListBasket = data.courseListBasket.filter(
				(item) => item != id
			);
		}
		console.log(data.creditBasket);
		if (data.creditBasket > 0) {
			ButtonAddCourse.prop("disabled", false);
		} else {
			ButtonAddCourse.prop("disabled", true);
		}
		context.populate("credit-basket", data.creditBasket);
	});

	// event
	ButtonAddCourse.click(actionAddCourse);
	ButtonSubmitKrs.click(actionSubmitKrs);
	ModalChooseCourse.on("shown.bs.modal", actionShowModalChooseCourse).on(
		"hide.bs.modal",
		function () {
			TableCourse.find("tbody").html("");
			data.courseListBasket = [];
			data.creditBasket = 0;
		}
	);
	// data
	let data = {
		krsInfo: {},
		academicYear: [],
		courseTaken: [],
		courses: [],
		courseListBasket: [],
		limitCredit: 0,
		creditBasket: 0,
		selectedItemCourse: [],
		selectedAcademicYearid: null,
	};

	// init app
	function init() {
		// call if has id form
		if (app.length) {
			mySwal.showLoading();
			loadKRSInfo();
		}
	}

	function loadKRSInfo() {
		krsAPI.getKRSInfo(
			function (response) {
				// hide loading here
				mySwal.hideLoading();
				data.krsInfo = { ...response.data };
				data.courseTaken = [...data.krsInfo.course_takens];
				// generateRowsTable
				context.populatetoDom(data.krsInfo);
				generateRowsTableCourseTaken();
				// check status jika bukan edited
				if (data.krsInfo.status != "edit") {
					ButtonAddCourse.hide();
					ButtonModalCourse.hide();
					ButtonSubmitKrs.hide();
					let strStatus =
						data.krsInfo.status == "unverified" ? "Submitted" : "Verified";
					context.populate("status", strStatus);
				}
				if (data.courseTaken.length <= 0) {
					ButtonSubmitKrs.prop("disabled", true);
				} else {
					ButtonSubmitKrs.prop("disabled", false);
				}
			},
			function (XMLHttpRequest, textStatus, errorThrown) {
				// hide loading here
				mySwal.hideLoading();
			}
		);
	}

	function generateRowsTableCourseTaken() {
		console.log(data.courseTaken);
		let rows = ``;
		if (data.courseTaken.length > 0) {
			data.courseTaken.forEach((item, index) => {
				rows += `<tr>`;
				rows += `<td>${index + 1}</td>`;
				rows += `<td>${item.code}</td>`;
				rows += `<td>${item.name}</td>`;
				rows += `<td>${item.semester}</td>`;
				rows += `<td>${item.sks}</td>`;
				rows += `<td>-</td>`;
				if (data.krsInfo.status != "edit") {
					rows += `<td>-</td>`;
				} else {
					rows += `<td>
							<a href="#" class="text-primary" id="dropdownMenuButton" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
								<i class="fas fa-ellipsis-v"></i>
							</a>
							<div class="dropdown-menu" aria-labelledby="dropdownMenuButton">
								<a class="dropdown-item btn-remove-item-course-taken" href="#" data-id="${item.id}">Hapus</a>
							</div>
						</td>`;
				}
				rows += `</tr>`;
			});
		}
		TableCourseTaken.find("tbody").html(rows);
	}

	function generateRowsTableCourse() {
		let rows = ``;
		if (data.courses.length > 0) {
			data.courses.forEach((item, index) => {
				rows += `<tr>`;
				rows += `<td>${index + 1}</td>`;
				rows += `<td>${item.code}</td>`;
				rows += `<td>${item.name}</td>`;
				rows += `<td>${item.semester}</td>`;
				rows += `<td>${item.sks}</td>`;
				rows += `<td>
							<div class="form-check">
								<input type="checkbox" class="form-check-input checkbox-item-course" data-id="${item.id}">
							</div>
						</td>`;
				rows += `</tr>`;
			});
		}
		TableCourse.find("tbody").html(rows);
	}

	// method actions
	function actionShowModalChooseCourse() {
		ButtonAddCourse.prop("disabled", true);
		mySwal.showLoading();
		krsAPI.getKRSCourses(
			function (response) {
				// hide loading here
				mySwal.hideLoading();
				data.courses = [...response.data];
				data.limitCredit = response.limitCredit;
				generateRowsTableCourse();
				context.populate("limit-credit", data.limitCredit);
				context.populate("credit-basket", data.creditBasket);
			},
			function (XMLHttpRequest, textStatus, errorThrown) {
				// hide loading here
				mySwal.hideLoading();
			}
		);
	}

	function actionAddCourse() {
		mySwal.defaultDialog("Yakin akan menambah data ini ?", (result) => {
			if (result.isConfirmed) {
				mySwal.showLoading();
				krsAPI.postAddCourse(
					{
						course_ids: JSON.stringify(data.courseListBasket),
					},
					(response) => {
						mySwal.hideLoading();
						if (response.status == "OK")
							mySwal.toast("berhasil menambah matakuliah.", "success");
						loadKRSInfo();
						ModalChooseCourse.modal("hide");
					},
					function (XMLHttpRequest, textStatus, errorThrown) {
						// hide loading here
						mySwal.hideLoading();
					}
				);
			}
		});
	}

	function actionSubmitKrs() {
		mySwal.defaultDialog(
			"Setelah menekan tombol yakin, data akan tersubmit dan anda tidak bisa mengubahnya lagi.",
			(result) => {
				if (result.isConfirmed) {
					mySwal.showLoading();
					krsAPI.postSubmitKrs(
						data.krsInfo.id,
						(response) => {
							mySwal.hideLoading();
							if (response.status == "OK")
								mySwal.toast("krs berhasil disubmit", "success");
							loadKRSInfo();
						},
						function (XMLHttpRequest, textStatus, errorThrown) {
							// hide loading here
							mySwal.hideLoading();
						}
					);
				}
			}
		);
	}

	function actionRemoveTakenCourse(e) {
		let self = $(e.currentTarget);
		let id = self.data("id");
		mySwal.deleteDialog("Yakin akan menghapus data ini ?", (result) => {
			if (result.isConfirmed) {
				krsAPI.removeCourse(
					id,
					function (response) {
						// hide loading here
						mySwal.hideLoading();
						if (response.status == "OK") {
							mySwal.toast("berhasil menghapus matakuliah.", "success");
							data.courseTaken = data.courseTaken.filter(
								(item) => item.id != id
							);
							generateRowsTableCourseTaken();
						}
					},
					function (XMLHttpRequest, textStatus, errorThrown) {
						// hide loading here
						mySwal.hideLoading();
					}
				);
			}
		});
	}

	// method actions end

	return {
		init,
	};
})();
