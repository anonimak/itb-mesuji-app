<?php
defined('BASEPATH') or exit('No direct script access allowed');

$route['default_controller']                        = 'auth/login';

//AUTH
$route['auth']                                      = 'auth/login';
$route['auth/changedefault']                        = 'auth/changepasswordfromdefault';

/* ================ADMIN ROLE======================== */

//ADMIN
$route['admin/dashboard']                           = 'admin_dashboard/index';

//KONFIGURASI
//ACADEMIC YEAR
$route['admin/config/academic_year']                = 'admin_academic_year';
$route['admin/config/academic_year/add']            = 'admin_academic_year/create';
$route['admin/config/academic_year/edit/(:any)']    = 'admin_academic_year/update/$1';
$route['admin/config/academic_year/delete/(:any)']  = 'admin_academic_year/delete/$1';


//PERIODE
$route['admin/config/(:any)']                       = 'admin_periode/index/$1';
$route['admin/config/add/(:any)']                   = 'admin_periode/create/$1';
$route['admin/config/edit/(:any)/(:any)']           = 'admin_periode/update/$1/$2';

//MASTER DATA
//MAJOR
$route['admin/master/major']                        = 'admin_major';
$route['admin/master/major/add']                    = 'admin_major/create';
$route['admin/master/major/excel']                  = 'admin_major/exportExcel';
$route['admin/master/major/edit/(:any)']            = 'admin_major/update/$1';
$route['admin/master/major/delete/(:any)']          = 'admin_major/delete/$1';

//PRODI
$route['admin/master/prodi']                        = 'admin_study_program';
$route['admin/master/prodi/add']                    = 'admin_study_program/create';
$route['admin/master/prodi/excel']                  = 'admin_study_program/exportExcel';
$route['admin/master/prodi/edit/(:any)']            = 'admin_study_program/update/$1';
$route['admin/master/prodi/delete/(:any)']          = 'admin_study_program/delete/$1';

//MATAKULIAH
$route['admin/master/course']                        = 'admin_course';
$route['admin/master/course/add']                    = 'admin_course/create';
$route['admin/master/course/excel']                  = 'admin_course/exportExcel';
$route['admin/master/course/edit/(:any)']            = 'admin_course/update/$1';
$route['admin/master/course/delete/(:any)']          = 'admin_course/delete/$1';

//STUDENT
$route['admin/master/student']                      = 'admin_students';
$route['admin/master/student/add']                  = 'admin_students/create';
$route['admin/master/student/edit/(:any)']          = 'admin_students/update/$1';
$route['admin/master/student/delete/(:any)']        = 'admin_students/delete/$1';
$route['admin/master/student/import']               = 'admin_students/import'; //view
$route['admin/master/student/importstudent']        = 'admin_students/importstudent'; //action
$route['admin/master/student/export']               = 'admin_students/export'; //view
$route['admin/master/student/resetpassword/(:any)'] = 'admin_students/resetpassword/$1';
$route['admin/master/student/krs/(:any)']           = 'admin_students/krs/$1';

//LECTURE
$route['admin/master/lecture']                      = 'admin_lecture';
$route['admin/master/lecture/add']                  = 'admin_lecture/create';
$route['admin/master/lecture/edit/(:any)']          = 'admin_lecture/update/$1';
$route['admin/master/lecture/delete/(:any)']        = 'admin_lecture/delete/$1';
$route['admin/master/lecture/import']               = 'admin_lecture/import';
$route['admin/master/lecture/importlecture']        = 'admin_lecture/importlecture';
$route['admin/master/lecture/logo']                 = 'admin_lecture/uploadlogo';

$route['admin/master/lecture/getdata']                  = 'admin_lecture/getdata';
$route['admin/master/lecture/deletemultiple/(:any)']    = 'admin_lecture/deletemultiple/$1';

//KEUTA PRODI
$route['admin/master/head-of-program-study']        = 'Admin_head_program_study';
$route['admin/master/head-of-program-study/add']    = 'Admin_head_program_study/create';
$route['admin/master/head-of-program-study/update'] = 'Admin_head_program_study/update';

//ROOM
$route['admin/master/room']                         = 'admin_room';
$route['admin/master/room/add']                     = 'admin_room/create';
$route['admin/master/room/edit/(:any)']             = 'admin_room/update/$1';
$route['admin/master/room/delete/(:any)']           = 'admin_room/delete/$1';

//KEUTA PRODI
$route['admin/master/head-of-program-study']        = 'Admin_head_program_study';
$route['admin/master/head-of-program-study/add']    = 'Admin_head_program_study/create';
$route['admin/master/head-of-program-study/update'] = 'Admin_head_program_study/update';


$route['admin/master/users']                        = 'Admin_users';
$route['admin/master/users/update/(:any)/(:any)']   = 'Admin_users/update/$1/$2';




/* ================MAHASISWA ROLE======================== */
$route['mahasiswa/dashboard']                       = 'mahasiswa_dashboard';
$route['mahasiswa/profile']                         = 'mahasiswa_profile';




//CONFIG SELECT2
$route['config/getprodi']                           = 'Admin_config/getprodi';


/*=================END MAHASISWA ROLE======================*/

$route['404_override'] = 'err';
$route['translate_uri_dashes'] = FALSE;
