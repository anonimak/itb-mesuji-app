// API Request Ajax
var myAPI = {
	// ajax
	getRegency: function (success, error) {
		$.ajax({
			type: "GET",
			url: base_url + "prodi/pkl_location/regency",
			dataType: "JSON",
			success,
			error,
		});
	},
	getProvince: function (success, error) {
		$.ajax({
			type: "GET",
			url: base_url + "prodi/pkl_location/province",
			dataType: "JSON",
			success,
			error,
		});
	},
	getLecture: function (success, error) {
		$.ajax({
			type: "GET",
			url: base_url + "prodi/pkl_registrasi/lecture",
			dataType: "JSON",
			success,
			error,
		});
	},
	getAcademicYear: function (success, error) {
		$.ajax({
			type: "GET",
			url: base_url + "prodi/pkl_registrasi/academic_year",
			dataType: "JSON",
			success,
			error,
		});
	},
	getLocationRegistrationNotInGroup: function (param, success, error) {
		$.ajax({
			type: "GET",
			url: base_url + "prodi/pkl_registrasi/location/" + param,
			dataType: "JSON",
			success,
			error,
		});
	},
	postApprovalRegistration: function (data, success, error) {
		$.ajax({
			type: "POST",
			url: base_url + "prodi/pkl_registrasi/approval",
			dataType: "JSON",
			data: data,
			success,
			error,
		});
	},
	postChangeLocation: function (data, success, error) {
		$.ajax({
			type: "POST",
			url: base_url + "prodi/pkl_registrasi/location",
			dataType: "JSON",
			data: data,
			success,
			error,
		});
	},
};

export default myAPI;
