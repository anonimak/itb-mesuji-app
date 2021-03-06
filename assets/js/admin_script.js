$(document).ready(function () {
	//QUESTION ALERT
	function alertCustom(text, icon) {
		$.toast({
			text: text,
			showHideTransition: "slide",
			icon: icon,
			position: "top-right",
		});
	}

	function deleteQuestion(url, text) {
		Swal.fire({
			text: text,
			icon: "warning",
			showCancelButton: true,
			confirmButtonColor: "#3085d6",
			cancelButtonColor: "#d33",
			confirmButtonText: "Yakin",
		}).then((result) => {
			if (result.isConfirmed) {
				document.location.href = url;
			}
		});
	}

	function buttonClickDelete(route, id) {
		var url = base_url + "admin/master/" + route + "/delete/" + id;
		deleteQuestion(url, "Yakin akan menghapus data ini ?");
	}

	function buttonClickDeleteAcademicYear(route, id) {
		var url = base_url + "admin/config/" + route + "/delete/" + id;
		deleteQuestion(url, "Yakin akan menghapus data ini ?");
	}

	//select2ajax
	function select2ajax(route, text) {
		$(".get-" + route + "").select2({
			placeholder: text,
			ajax: {
				url: `${base_url}config/get${route}`,
				dataType: "JSON",
				type: "POST",
				data: function (params) {
					return {
						search: params.term,
					};
				},
				processResults: function (data) {
					return {
						results: data,
					};
				},
				cache: true,
			},
			minimumInputLength: 3,
		});
	}

	function verificationQuestion(url) {
		Swal.fire({
			// text: text,
			icon: "info",
			showCancelButton: true,
			confirmButtonColor: "#3085d6",
			cancelButtonColor: "#d33",
			confirmButtonText: "Terima",
			cancelButtonText: "Tolak",
			allowOutsideClick: false,
			allowEscapeKey: false,
		}).then((result) => {
			var href = "";
			if (result.isConfirmed) {
				href = `${url}1`;
				// document.location.href = url;
			} else {
				href = `${url}0`;
				// alert('false');
			}
			document.location.href = href;
		});
	}

	/*ADMIN PAGE */
	//TA
	$(document).on("click", ".delete-ta", function () {
		var id = $(this).data("id");
		buttonClickDeleteAcademicYear("academic_year", id);
	});

	//Major
	$(document).on("click", ".delete-major", function () {
		var id = $(this).data("id");
		buttonClickDelete("major", id);
	});

	//PS
	$(".get-major").select2({
		placeholder: "Cari nama jurusan",
	});

	$(document).on("click", ".delete-ps", function () {
		var id = $(this).data("id");
		buttonClickDelete("prodi", id);
	});

	//COURSE
	$(".get-program-study").select2({
		placeholder: "Cari nama program studi",
	});

	$(document).on("click", ".delete-course", function () {
		var id = $(this).data("id");
		buttonClickDelete("course", id);
	});

	//STUDENT
	select2ajax("prodi", "Cari nama jurusan atau prodi");
	
	$("#verifed-krs").on('click', function(){
		var krsId = $(this).data('id');
		var url		= `${base_url}admin/master/student/krsverifed/${krsId}`;
		deleteQuestion(url,'Akan Verifikasi KRS ini ?')
	})

	$("#reset-krs").on('click', function(){
		var krsId = $(this).data('id');
		var url		= `${base_url}admin/master/student/krsreset/${krsId}`;
		deleteQuestion(url,'Akan Reset Ulang KRS ini ?')
	})

	//CARI DOSEN PEMBIMBING
	
	$(".get-dosen-pembimbing").select2({
		placeholder: "Cari nama dosen pembimbing",
	});
	

	$('.get-program-study').change(function() {
		var prodiId = $(this).val();
		var lectureId	= $('#lectureid').val();
		$.ajax({
				type: "POST",
				url: `${base_url}/admin/master/student/getDosenPembimbing`,
				data: {
					prodiId: prodiId,
					lectureId:lectureId
				},
				dataType: "JSON",
				success: function(response) {
						$('.get-dosen-pembimbing').html(response);
				}
		});
	});

	var studentId = $('#studentId').val();
	var url				= `${base_url}admin/master/student/byid/${studentId}`;
	
	$.ajax({
			type: "GET",
			url: url,
			dataType: "JSON",
			success: function(response) {
				console.log(response);
				$('.get-program-study').val(response.prodi_id).trigger('change');
				$('.get-dosen-pembimbing').val(response.lecture_id).trigger('change');
			}
	});
	

	
	$(document).on("click", ".delete-student", function () {
		var id = $(this).data("id");
		buttonClickDelete("student", id);
	});

	//LECTURE
	$(document).on("click", ".delete-lecture", function () {
		var id = $(this).data("id");
		buttonClickDelete("lecture", id);
	});

	
	//COMPANY
	select2ajax("regency2", "Cari nama kabupaten atau provinsi");
	$(document).on("click", ".delete-company", function () {
		var id = $(this).data("id");
		buttonClickDelete("company", id);
	});

	

	$("#academicyearyearpkladmin").select2({
		placeholder: "Cari tahun akademik",
	});

	$("#academicyearyearpkladmin").on("change", function () {
		var id = $(this).val();
		document.location.href = `${base_url}admin/master/pkl/${id}`;
	});

	$("#academicyearpudir").select2({
		placeholder: "Cari tahun akademik",
	});

	$("#academicyearpudir").on("change", function () {
		var id = $(this).val();
		document.location.href = `${base_url}pudir/pkl/${id}`;
	});

	$("#academicyearketuplak").select2({
		placeholder: "Cari tahun akademik",
	});

	$("#academicyearketuplak").on("change", function () {
		var id = $(this).val();
		document.location.href = `${base_url}ketuplak/pkl/${id}`;
	});

	$(document).on("click", ".deleteletterconfig", function () {
		var id = $(this).data("id");
		buttonClickDelete("letter", id);
	});

	//FILE UPLOAD
	(function ($) {
		"use strict";
		$(function () {
			$(".file-upload-browse").on("click", function () {
				var file = $(this)
					.parent()
					.parent()
					.parent()
					.find(".file-upload-default");
				file.trigger("click");
			});
			$(".file-upload-default").on("change", function () {
				$(this)
					.parent()
					.find(".form-control")
					.val(
						$(this)
						.val()
						.replace(/C:\\fakepath\\/i, "")
					);
			});
		});
	})(jQuery);

	//REGISTRATION

	$("#tableMember").DataTable({
		searching: true,
	});
	//GET LEADER BY PRODI
	let API = {
		getData: function (params) {
			return {
				placeholder: "Cari nama mahasiswa",
				ajax: {
					url: `${base_url}config/getleader`,
					dataType: "JSON",
					type: "POST",
					data: params,
					processResults: function (data) {
						return {
							results: data,
						};
					},
					cache: true,
				},
				minimumInputLength: 3,
			};
		},
	};

	// select2ajax('leader','Cari mahasiswa atau prodi');

	// Notify any JS components that the value changed)
	$(".get-leader").select2(
		API.getData(function (params) {
			var prodiId = $(".get-prodi").val();
			return {
				search: params.term,
				prodiId,
			};
		})
	);

	select2ajax("companies", "Cari perusahaan");

	$(".verficationprocess").on("click", function () {
		var id = $(this).data("id");
		var uri = $(this).data("uri");
		var url = `${base_url}admin/registrations/verification/${id}:${uri}/`;
		verificationQuestion(url);
	});

	$(".get-lecture-supervisor").select2({
		placeholder: "Cari nama Dosen",
	});

	$(".moveGroup").on("click", function () {
		let id = $(this).data("student");
		$("#id").val(id);
	});

	$(".get-another-group").select2({
		placeholder: "Cari nama Ketua group",
	});

	$(".modalgroupid").on("click", function () {
		let groupId = $(this).data("group");
		$("#modalGroupIdLabel").html(`GROUP ID ${groupId}`);
		$.ajax({
			url: `${base_url}admin/registrations/historydetail`,
			dataType: "JSON",
			method: "POST",
			data: {
				groupId: groupId,
			},
			success: (data) => {
				if (data.status === "ok") {
					$(".groupIdResult").html(data.data);
				} else {
					alertCustom("Server sedang sibuk", "warning");
				}
			},
		});
	});

	$(".supervisorModal").on("click", function () {
		let id = $(this).data("id");
		$("#registration-id").val(id);
	});

	//END REGISTRATION

	//ROOM
	$(document).on("click", ".delete-room", function () {
		var id = $(this).data("id");
		buttonClickDelete("room", id);
	});

	//LETTER CONFIG
	$logo = $("#logo_demo").croppie({
		enableExif: true,
		viewport: {
			width: 256,
			height: 256,
			type: "square", //square
		},
		boundary: {
			width: 300,
			height: 300,
		},
	});
	$("#corporate-logo").on("change", function () {
		var type = $(this).data("type");
		var id = $(this).data("id");
		var reader = new FileReader();
		reader.onload = function (event) {
			$logo
				.croppie("bind", {
					url: event.target.result,
				})
				.then(function () {
					console.log("jQuery bind complete");
				});
		};
		reader.readAsDataURL(this.files[0]);
		cropLogoCroppie(type, id);
	});

	function cropLogoCroppie(type, id) {
		$("#uploadimageModal").modal("show");
		$(".id-corp").val(id);
		$(".type-corp").val(type);
	}

	$(".crop_image_logo").on("click", function () {
		$logo
			.croppie("result", {
				type: "canvas",
				size: "viewport",
			})
			.then(function (response) {
				var dataJsonImage = {
					image: response,
				};
				$.ajax({
					url: `${base_url}admin/letter/logo`,
					type: "POST",
					data: dataJsonImage,
					dataType: "JSON",
					success: function (data) {
						if (data.status == "success") {
							$("#uploadimageModal").modal("hide");
							$(".logo").val(data.filename);
							$(".image-preview__image").attr(
								"src",
								base_url + "assets/img/logo/" + data.filename
							);
							$(".image-preview__image").attr("style", "");
							$(".image-preview__default_text").attr("style", "display:none");
							// $('.image-preview__image')
							// window.setTimeout(function () {
							// 	window.location.reload();
							// }, 4000);
						} else {
							message(
								"Server sedang sibuk, silahkan coba beberapa saat lagi",
								"error"
							);
						}
					},
				});
			});
	});

	$("#document").select2({
		placeholder: "Cari dokumen",
	});
	/*END ADMIN PAGE */

	// ===============================MAJOR PAGE

	$(".kompensasi").click(function () {
		let ul = get_parent_ul(this);
		let li = ul.find("li.bebastanggungan");
		let data = {};
		if ($(this).is(":checked")) {
			li.removeClass("d-none");
			data = {
				studentId: ul.data("id"),
				kompensasi: 1,
			};
		} else {
			li.addClass("d-none");
			data = {
				studentId: ul.data("id"),
				kompensasi: 0,
			};
		}
		ajaxVerification(data);
	});

	$(document).on("click", ".bebastanggunganceklist", function () {
		let ul = get_parent_ul(this);
		let li = ul.find("li.kelulusan");
		let data = {};
		if ($(this).is(":checked")) {
			li.removeClass("d-none");
			data = {
				studentId: ul.data("id"),
				bebastanggungan: 1,
			};
		} else if ($(this).is(":not(:checked)")) {
			li.addClass("d-none");
			data = {
				studentId: ul.data("id"),
				bebastanggungan: 2,
			};
		}
		ajaxVerification(data);
	});

	$(document).on("click", ".kelulusanceklist", function () {
		let ul = get_parent_ul(this);
		let li = ul.find("li.kehadiran");
		let data = {};
		if ($(this).is(":checked")) {
			li.removeClass("d-none");
			data = {
				studentId: ul.data("id"),
				kelulusan: 1,
			};
		} else if ($(this).is(":not(:checked)")) {
			li.addClass("d-none");
			data = {
				studentId: ul.data("id"),
				kelulusan: 2,
			};
		}
		ajaxVerification(data);
	});

	$(document).on("click", ".kehadiranceklist", function () {
		let ul = get_parent_ul(this);
		let data = {};
		if ($(this).is(":checked")) {
			data = {
				studentId: ul.data("id"),
				kehadiran: 1,
			};
		} else if ($(this).is(":not(:checked)")) {
			data = {
				studentId: ul.data("id"),
				kehadiran: 2,
			};
		}
		ajaxVerification(data);
	});

	$(document).on("click", ".kehadiranadmin", function () {
		let ul = get_parent_ul(this);
		let data = {};
		if ($(this).is(":checked")) {
			data = {
				studentId: ul.data("id"),
				kehadiranadmin: 1,
			};
		} else if ($(this).is(":not(:checked)")) {
			data = {
				studentId: ul.data("id"),
				kehadiranadmin: 2,
			};
		}
		ajaxVerification(data);
	});

	function get_parent_ul(componnent) {
		let ul = $(componnent).closest("ul");
		return ul;
	}

	const ajaxVerification = (data) => {
		$.ajax({
			url: `${base_url}config/verificationdata`,
			dataType: "JSON",
			method: "POST",
			data: data,
			success: (data) => {
				console.log(data);
			},
		});
	};
	// ===============================END MAJOR PAGE

	//====================================KAJUR PAGE
	$(".get-kajur").select2({
		placeholder: "Cari nama Dosen atau Jurusan",
	});

	$(".kajur-checkbox").on("click", function () {
		var id = $(this).data("id");
		if ($(this).is(":checked")) {
			data = {
				id: id,
				status: 1,
			};
		} else if ($(this).is(":not(:checked)")) {
			data = {
				id: id,
				status: 0,
			};
		}

		$.ajax({
			url: `${base_url}admin/master/head-of-program/update`,
			dataType: "JSON",
			method: "POST",
			data: data,
			success: (data) => {
				if (data === "0") {
					alertCustom("Server sedang sibuk", "warning");
				}
			},
		});
	});
	//================================END KAJUR PAGE

	//====================================KAJUR PAGE
	$(".get-kaprodi").select2({
		placeholder: "Cari nama Dosen atau Jurusan",
	});



	$(document).on("change", ".kaprodi-checkbox", function () {
		var id = $(this).data("id");
		if ($(this).is(":checked")) {
			data = {
				id: id,
				status: 1,
			};
		} else if ($(this).is(":not(:checked)")) {
			data = {
				id: id,
				status: 0,
			};
		}

		$.ajax({
			url: `${base_url}admin/master/head-of-program-study/update`,
			dataType: "JSON",
			method: "POST",
			data: data,
			success: (data) => {
				if (data === "0") {
					alertCustom("Server sedang sibuk", "warning");
				}
			},
		});
	});

	//RESET PASSWORD Mahasiswa
	$(document).on("click", ".reset-password", function () {
		var id = $(this).data("id");
		Swal.fire({
			text: 'Reset Password Ke 123456',
			icon: "info",
			showCancelButton: true,
			confirmButtonColor: "#3085d6",
			cancelButtonColor: "#d33",
			confirmButtonText: "Ya, Reset Saja",
			cancelButtonText: "Tidak Jadi",
			allowOutsideClick: false,
			allowEscapeKey: false,
		}).then((result) => {
			if (result.isConfirmed) {
				url = `${base_url}admin/master/student/resetpassword/${id}`
				document.location.href = url;
			}
		});
	})

	$("#academicyearyearpkl").on("change", function () {
		var id = $(this).val();
		document.location.href = `${base_url}major/persentasepkl/${id}`;
	});

	$("#academicyearyearpkl").select2({
		placeholder: "Cari nama Dosen atau Jurusan",
	});
	//================================END KAJUR PAGE
});
