$(document).ready(function () {
	//TOAST ALERT
	const flashdata = $(".flashdata").data("flashdata");
	const type = $(".flashdata").data("type");
	if (flashdata) {
		$.toast({
			text: flashdata,
			showHideTransition: 'slide',
			icon: type,
			position: 'top-right'
		})
	}
	/* Student Start */
	const kabupten_id = $('#kabupaten_id').val();
	const company_id = $('#company_id').val();

	if (company_id) {
		$.ajax({
			type: "POST",
			url: base_url + "mahasiswa_company/getByIdCompany",
			data: {
				company_id: company_id
			},
			dataType: "JSON",
			success: function (response) {
				$('[name="province_id"]').val(response.province_id).trigger('change');
				$('[name="kabupaten"]').val(response.regency_id).trigger('change');
			}
		});
	}

	$('#province').change(function () {
		var id = $(this).val();
		if (kabupten_id) {
			data = {
				id: id,
				kabupaten: kabupten_id
			}
		} else {
			data = {
				id: id,
			}
		}
		$.ajax({
			type: "POST",
			url: base_url + "mahasiswa_company/regency",
			data: data,
			dataType: "JSON",
			success: function (response) {
				$('#regency').html(response);
			}
		});
	});

	$('.get-province').select2({
		placeholder: 'Cari nama provinsi'
	});

	$('.get-regency').select2({
		placeholder: 'Cari nama kabupaten'
	});

	$('.get-company').select2({
		placeholder: 'Cari nama perusahaan'
	});

	$('.get-student').select2({
		placeholder: 'Cari nama mahasiswa'
	});

	$('.get-academicyear').select2({
		placeholder: 'Pilih tahun akademik'
	});

	/* Student Start */

	function alertCustom(text, icon) {
		$.toast({
			text: text,
			showHideTransition: 'slide',
			icon: icon,
			position: 'top-right'
		})
	}

	//select2ajax
	function select2ajax(route, text) {
		$('.get-' + route + '').select2({
			placeholder: text,
			ajax: {
				url: `${base_url}mahasiswa/config/get${route}`,
				dataType: 'JSON',
				type: 'POST',
				data: function (params) {
					return {
						search: params.term
					}
				},
				processResults: function (data) {
					return {
						results: data
					}
				},
				cache: true
			},
			minimumInputLength: 3,
		})
	}

	//REGISTRATION
	select2ajax('companyregis', 'Cari perusahaan');
	var companyValue = null;
	var leaderValue = $('#leader').val();
	var prodiId = $('#prodi').val();
	$('#search-member-registration').on('click', () => {
		companyValue = $('.get-companyregis').val();
		if (companyValue === "") {
			alertCustom('Pilih perusahaan terlebih dahulu', 'warning');
		} else {
			$.ajax({
				url: `${base_url}mahasiswa/registration/member`,
				dataType: "JSON",
				method: "POST",
				data: {
					leaderId: leaderValue,
					prodiId: prodiId
				},
				beforeSend: () => {
					$('.loadinggif').removeClass('d-none');
				},
				success: (data) => {
					$('.dt-responsive').removeClass('d-none');
					$('.loadinggif').addClass('d-none');
					$("#member-table-list").DataTable();
					$('#get-data-member-registration').html(data);
				}
			});
		}
	});

	$('#btn-save-registration').on('click', () => {
		var quantity = $('#btn-save-registration').data('quantity');

		var startDate = $('#get-start-date').val();
		var finishDate = $('#get-finish-date').val();
		var companyId = $('.get-companyregis').val();
		var memberId = [];
		$(':checkbox:checked').each(function (i) {
			memberId[i] = $(this).val();
		});
		if (startDate === "" || finishDate === "" || companyId === "") {
			alertCustom('Lengkapi form pengisian', 'warning');
		} else {

			if (memberId.length + 1 < quantity) {
				alertCustom('jumlah per kelompok melebihi kuota', 'warning');
			} else {
				if (finishDate < startDate || finishDate == startDate) {
					alertCustom('Cek kembali pengisian tanggal', 'warning');
				} else {
					var data = {
						leaderId: leaderValue,
						companyId: companyId,
						memberId: memberId,
						startDate: startDate,
						finishDate: finishDate
					};
					$.ajax({
						url: `${base_url}mahasiswa/registration/addgroup`,
						dataType: "JSON",
						method: "POST",
						data: data,
						beforeSend: () => {
							alertCustom('Data sedang kami proses, mohon tunggu', 'success');
						},
						success: (data) => {
							if (data.message === 'success') {
								alertCustom('Data Berhasil di simpan, mohon tunggu sebentar', 'success');
								setTimeout(function () {
									window.location.href = `${base_url}config/historyadd`;
								}, 3000);
							} else if (data.message === 'failedmember') {
								alertCustomI('Penyimpanan data anggota gagal', 'warning');
								setTimeout(function () {
									window.location.href = `${base_url}mahasiswa/registration`;
								}, 3000);
							} else {
								alertCustom('Server sedang sibuk, silahkan coba lagi', 'error');
							}
						}
					});
				}
			}
		}
	});

	$('.modalLogId').on('click', function () {
		let logId = $(this).data('log');
		$('#modalLogIdLabel').html(`Daily Log ID ${logId}`);
		$.ajax({
			url: `${base_url}mahasiswa/daily/log/detail`,
			dataType: 'JSON',
			method: 'POST',
			data: {
				logId: logId
			},
			success: (data) => {
				if (data.status === 'ok') {
					$('.logIdResult').html(data.data);
				} else {
					alertCustom('Server sedang sibuk', 'warning');
				}
			}
		})
	});

	// check point
	$(".attendance").change(function () {
		$("#time_in, #time_out").hide()
		if ($(this).val() == "H") {
			$("#time_in, #time_out").show();
		} else {
			$("#time_in, #time_out").hide();
		}
	});

	// Reload page when get academic year id
	$("#academicyear").on("change", function () {
		var id = $(this).val();
		var menu = $(this).data("menu");
		document.location.href = `${base_url}dosen/${menu}/academic_year/${id}`
	});

	$("#academicyear").select2({
		ajax: {
			url: `${base_url}lecture_config/getAcademicYear`,
			type: "GET",
			dataType: 'JSON',
			delay: 250,
			data: function (params) {
				return {
					searchTerm: params.term
				};
			},
			processResults: function (response) {
				return {
					results: response
				};
			},
			cache: true
		}
	});

	$(".assesment").on('click', function () {
		allertError('isi laporan supervisi terlebih dahulu');
	});

	allertError = (text) => {
		Swal.fire({
			icon: 'error',
			title: 'Maaf...',
			text: `Menu ini belum bisa di akses, silahkan ${text} terlebih dahulu!`
		});
	}

	$(".verified").on('click', function () {
		var id = $(this).data("id");
		var uri = $(this).data("uri");
		var role = $(this).data("role");
		var menu = $(this).data("menu");
		var url = `${base_url}${role.toLowerCase()}/${menu}/verification/${id}:${uri}/`
		verification(url)
	});

	function verification(url) {
		Swal.fire({
			text: "Anda yakin ingin memverifikasi ini?",
			icon: 'info',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Verifikasi',
			cancelButtonText: 'Tolak',
			allowOutsideClick: false,
			allowEscapeKey: false
		}).then((result) => {
			var href = "";
			if (result.isConfirmed) {
				href = `${url}1`
			} else {
				href = `${url}0`
			}
			document.location.href = href;
		})
	}

	// get planning
	function select2WithCreation(route, text) {
		$('.get-' + route + '').select2({
			placeholder: text,
			ajax: {
				url: `${base_url}mahasiswa/config/get${route}`,
				dataType: 'JSON',
				type: 'POST',
				data: function (params) {
					return {
						search: params.term
					}
				},
				processResults: function (data) {
					return {
						results: data
					}
				},
				cache: true
			},
			tags: true,
		})
	}
	select2WithCreation('capaian', 'Cari capaian pembelajaran');
	select2WithCreation('subcapaian', 'Cari sub capaian pembelajaran');

	// Penialain Dosen :
	startCalc = () => {
		interval = setInterval("supevisi()", 1);
		interval2 = setInterval("guidanceD3()", 1);
		interval3 = setInterval("guidanceD4()", 1);
		interval4 = setInterval("testScore()", 1);
		interval5 = setInterval("supervisorScore()", 1);
	}

	supevisi = () => {
		a = document.supervisiForm.nilai_1.value;
		b = document.supervisiForm.nilai_2.value;
		c = document.supervisiForm.nilai_3.value;
		d = document.supervisiForm.jumlah_1.value = (a * 1) * (0.4 * 1);
		e = document.supervisiForm.jumlah_2.value = (b * 1) * (0.3 * 1);
		f = document.supervisiForm.jumlah_3.value = (c * 1) * (0.3 * 1);
		document.supervisiForm.total.value = (d * 1) + (e * 1) + (f * 1);
	}

	guidanceD3 = () => {
		a = document.guidanceFormD3.nilai_1.value;
		b = document.guidanceFormD3.nilai_2.value;
		c = document.guidanceFormD3.nilai_3.value;
		d = document.guidanceFormD3.jumlah_1.value = (a * 1) * (0.2 * 1);
		e = document.guidanceFormD3.jumlah_2.value = (b * 1) * (0.4 * 1);
		f = document.guidanceFormD3.jumlah_3.value = (c * 1) * (0.4 * 1);
		document.guidanceFormD3.total.value = (d * 1) + (e * 1) + (f * 1);
	}

	guidanceD4 = () => {
		a = document.guidanceFormD4.nilai_1.value;
		b = document.guidanceFormD4.nilai_2.value;
		c = document.guidanceFormD4.nilai_3.value;
		d = document.guidanceFormD4.nilai_4.value;
		e = document.guidanceFormD4.jumlah_1.value = (a * 1) * (0.2 * 1);
		f = document.guidanceFormD4.jumlah_2.value = (b * 1) * (0.25 * 1);
		g = document.guidanceFormD4.jumlah_3.value = (c * 1) * (0.3 * 1);
		h = document.guidanceFormD4.jumlah_4.value = (d * 1) * (0.25 * 1);
		document.guidanceFormD4.total.value = (e * 1) + (f * 1) + (g * 1) + (h * 1);
	}

	testScore = () => {
		a = document.testScore.nilai_1.value;
		b = document.testScore.nilai_2.value;
		c = document.testScore.nilai_3.value;
		d = document.testScore.nilai_4.value;
		e = document.testScore.jumlah_1.value = (a * 1) * (0.4 * 1);
		f = document.testScore.jumlah_2.value = (b * 1) * (0.30 * 1);
		g = document.testScore.jumlah_3.value = (c * 1) * (0.2 * 1);
		h = document.testScore.jumlah_4.value = (d * 1) * (0.1 * 1);
		document.testScore.total.value = (e * 1) + (f * 1) + (g * 1) + (h * 1);
	}

	supervisorScore = () => {
		a = document.supervisorForm.nilai_1.value;
		b = document.supervisorForm.nilai_2.value;
		c = document.supervisorForm.nilai_3.value;
		d = document.supervisorForm.nilai_4.value;
		e = document.supervisorForm.nilai_5.value;
		f = document.supervisorForm.nilai_6.value;
		g = document.supervisorForm.nilai_7.value;
		h = document.supervisorForm.jumlah_1.value = (a * 1) * (0.2 * 1);
		i = document.supervisorForm.jumlah_2.value = (b * 1) * (0.3 * 1);
		j = document.supervisorForm.jumlah_3.value = (c * 1) * (0.1 * 1);
		k = document.supervisorForm.jumlah_4.value = (d * 1) * (0.1 * 1);
		l = document.supervisorForm.jumlah_5.value = (d * 1) * (0.1 * 1);
		m = document.supervisorForm.jumlah_6.value = (d * 1) * (0.1 * 1);
		n = document.supervisorForm.jumlah_7.value = (d * 1) * (0.1 * 1);
		document.supervisorForm.total.value = (h * 1) + (i * 1) + (j * 1) + (k * 1) + (l * 1) + (m * 1) + (n * 1);
	}

	stopCalc = () => {
		clearInterval(interval);
		clearInterval(interval2);
		clearInterval(interval3);
		clearInterval(interval4);
		clearInterval(interval5);
	}

	$('.reception').on('click', function () {
		let myProdiId = $(this).data('id');
		$(".modal-body #prodi_id").val(myProdiId);
	});

	function deleteQuestion(url, text) {
		Swal.fire({
			text: text,
			icon: 'warning',
			showCancelButton: true,
			confirmButtonColor: '#3085d6',
			cancelButtonColor: '#d33',
			confirmButtonText: 'Yakin'
		}).then((result) => {
			if (result.isConfirmed) {
				document.location.href = url;
			}
		})
	}

	function buttonClickDelete(route, id) {
		var url = base_url + "supervisor/" + route + "/cancel/" + id;
		deleteQuestion(url, "Yakin akan membatalkan kesediaan menerima di prodi ini ?");
	}

	$(document).on('click', '.delete-reception', function () {
		var id = $(this).data('id');
		buttonClickDelete('report_reception', id);
	});
});
