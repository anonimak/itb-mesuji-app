// API Request Ajax
var krsAPI = {
	// ajax
	getKRSInfo: function (success, error) {
		$.ajax({
			type: "GET",
			url: base_url + "mahasiswa/api/krs/krs-info",
			dataType: "JSON",
			success,
			error,
		});
	},

	getKRSCourses: function (success, error) {
		$.ajax({
			type: "GET",
			url: base_url + "mahasiswa/api/krs/course",
			dataType: "JSON",
			success,
			error,
		});
	},

	postSubmitKrs: function (param, success, error) {
		$.ajax({
			type: "GET",
			url: base_url + "mahasiswa/api/krs/submit-krs/" + param,
			dataType: "JSON",
			success,
			error,
		});
	},

	postAddCourse: function (data, success, error) {
		$.ajax({
			type: "POST",
			url: base_url + "mahasiswa/api/krs/add-course",
			dataType: "JSON",
			data: data,
			success,
			error,
		});
	},

	removeCourse: function (param, success, error) {
		$.ajax({
			type: "GET",
			url: base_url + "mahasiswa/api/krs/remove-course/" + param,
			dataType: "JSON",
			success,
			error,
		});
	},
};

export default krsAPI;
