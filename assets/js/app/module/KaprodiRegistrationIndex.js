import mySwal from "../components/swal.js";
import myAPI from "../request/api.js";

export default (function () {
	// init scope
	let app = context.app;
	let table = app.find("#tableRegister");
	let SelectAcademic = app.find("select.get-academic").select2();
	let ButtonApproval = table.find("button.approval");
	let ButtonUpload = table.find("button.upload");
	let ButtonVerify = table.find("button.verify");

	// event
	ButtonApproval.click(actionButtonApprovalClick);
	ButtonUpload.click(actionButtonUploaClick);
	ButtonVerify.click(actionButtonVerifyClick);
	SelectAcademic.on("change", actionToAcademic);

	let data = {
		lecture: [],
	};

	function init() {
		table.DataTable({
			filter: true,
			fixedColumns: true,
		});
		fetchDataLecture();
		setDataSelectAcademicYear();
	}

	function actionButtonApprovalClick(e) {
		let currentButton = $(e.currentTarget);
		let status = currentButton.data("status");
		let htmlButton = ``;

		if (status == "Ketua") {
			htmlButton += `<button id="terima" class="btn btn-primary">Terima</button>
            <button id="tolak" class="btn btn-danger">Tolak</button>`;
		} else {
			htmlButton += `<button id="terima" class="btn btn-primary">Terima</button>
            <button id="cut" class="btn btn-secondary">Pindahkan</button>
            <button id="tolak" class="btn btn-danger">Tolak</button>`;
		}

		mySwal.approvalDialog("Aksi Persetujuan", htmlButton, function () {
			let modal = $(".swal2-modal");
			// terima
			modal.find("#terima").on("click", function () {
				mySwal.formInputCustomHtml(
					"Pilih Dosen Pembimbing",
					`
                <form >
                    <div class="form-group">
                        <select class="get-lecture form-control"
                        name="lecture_id" id="lecture">
                        <option value="">-- Pilih Dosen Pembimbing --</option>
                        </select>
                    </div>
                </form>`,
					function (ele) {
						$("#lecture").select2({
							placeholder: "Pilih Pembimbing",
							width: "100%",
							data: data.lecture,
							dropdownParent: $(".swal2-modal"),
						});
					},
					(result) => {
						if (result.isConfirmed) {
							let lecture = $("#lecture").find(":selected").val();
							myAPI.postApprovalRegistration(
								{
									lecture: lecture,
									grp_id: currentButton.data("group_id"),
									id: currentButton.data("reg_id"),
									grp_status: "diterima",
									redirect: ``,
								},
								(response) => {
									// success here
									console.log(response);
									document.location.href = response.redirect;
								},
								function (XMLHttpRequest, textStatus, errorThrown) {
									// hide loading here
								}
							);
						}
					}
				);
			});
			// cut
			modal.find("#cut").on("click", function () {
				let group_id = currentButton.data("group_id");
				myAPI.getLocationRegistrationNotInGroup(
					group_id,
					function (response) {
						mySwal.formInputCustomHtml(
							"Pilih Lokasi PKL",
							`
                        <form >
                            <div class="form-group">
                                <select class="form-control"
                                name="location_id" id="location">
                                <option value="">-- Pilih Lokasi PKL --</option>
                                </select>
                            </div>
                        </form>`,
							function (ele) {
								$("#location").select2({
									placeholder: "Pilih Lokasi PKL",
									width: "100%",
									data: response.data,
									dropdownParent: $(".swal2-modal"),
								});
							},
							(result) => {
								if (result.isConfirmed) {
									let location = $("#location").find(":selected").val();
									myAPI.postChangeLocation(
										{
											id: currentButton.data("reg_id"),
											join_to_id: location,
											redirect: ``,
										},
										(response) => {
											// success here
											swal.close();
											console.log(response);
											document.location.href = response.redirect;
										},
										function (XMLHttpRequest, textStatus, errorThrown) {
											// hide loading here
										}
									);
								}
							}
						);
					},
					function (XMLHttpRequest, textStatus, errorThrown) {
						// hide loading here
					}
				);
			});
			// tolak
			modal.find("#tolak").on("click", function () {
				myAPI.postApprovalRegistration(
					{
						grp_id: currentButton.data("group_id"),
						id: currentButton.data("reg_id"),
						grp_status: "ditolak",
						redirect: ``,
					},
					(response) => {
						// success here
						console.log(response);
						document.location.href = response.redirect;
					},
					function (XMLHttpRequest, textStatus, errorThrown) {
						// hide loading here
					}
				);
			});
		});
	}

	function actionButtonVerifyClick(e) {
		let currentButton = $(e.currentTarget);
		mySwal.verifyDialog("Yakin akan verifikasi data ini ?", (result) => {
			if (result.isConfirmed) {
				document.location.href = currentButton.data("url");
			}
		});
	}

	function actionButtonUploaClick(e) {
		let currentButton = $(e.currentTarget);
		mySwal.formCustomHtml(
			"Unggah surat balasan.",
			`
			<form class="mt-4" action="${currentButton.data(
				"url"
			)}" method="post" enctype="multipart/form-data">
				<input type="hidden" value="${currentButton.data(
					"group_id"
				)}" name="registration_group_id">
				<div class="form-group text-left row">
					<label for="letter_number" class="col-sm-4 col-form-label">No Surat</label>
					<div class="col-sm-8">
						<input type="text" class="form-control" placeholder="No surat balasan" name="letter_number" required>
					</div>
				</div>
				<div class="form-group text-left row">
					<label for="exampleFormControlFile1" class="col-sm-4 col-form-label">File</label>
					<div class="col-sm-8">
						<input type="file" class="form-control-file" id="file" placeholder="Password" id="exampleFormControlFile1" name="file" required>
						<small class="form-text text-muted"><span class="text-warning">* </span>File yang di upload berupa pdf</small>
					</div>
				</div>
				<div class="form-group text-left mt-5">
					<label for="btn" class="col-form-label"></label>
					<button type="submit" class="btn btn-primary">Upload</button>
				</div>
			</form>
		`,
			function (ele) {}
		);
	}

	function actionToAcademic(e) {
		let currentSelect = $(e.currentTarget);
		let currentOpt = currentSelect.find(":selected").val();
		document.location.href = currentSelect.data("url") + currentOpt;
	}

	function fetchDataLecture() {
		myAPI.getLecture(
			function (response) {
				// hide loading here
				data.lecture = [...response.data];
			},
			function (XMLHttpRequest, textStatus, errorThrown) {
				// hide loading here
			}
		);
	}

	function setDataSelectAcademicYear() {
		myAPI.getAcademicYear(
			function (response) {
				// hide loading here
				data.academicYear = [...response.data];
				data.academicYear.forEach((element) => {
					if (element.id === SelectAcademic.data("selected")) {
						SelectAcademic.append(
							new Option(element.text, element.id, true, true)
						);
					} else {
						SelectAcademic.append(new Option(element.text, element.id));
					}
				});
				SelectAcademic.select2();
			},
			function (XMLHttpRequest, textStatus, errorThrown) {
				// hide loading here
			}
		);
	}

	return {
		init,
	};
})();
