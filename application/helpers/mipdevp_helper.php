<?php

function pageAuth($page  = "", $data = "")
{
    $ci = get_instance();
    // manggil header 
    $ci->load->view('auth/auth_header', $data);
    //manggil page
    $ci->load->view('/' . $page, $data);
    //manggil footer
    $ci->load->view('auth/auth_footer', $data);
}

function pageBackend($role = '', $page = '', $data = '')
{
    $ci = get_instance();
    $ci->load->view('template/header', $data);
    $ci->load->view('template/topbar', $data);
    $ci->load->view('template/' . strtolower($role . '_sidebar'), $data);
    $ci->load->view('' . $page, $data);
    $ci->load->view('template/footer');
}

function keyencrypt()
{
    return '123Daftar@@';
}

function encodeEncrypt($id)
{
    $ci = get_instance();
    return $ci->encrypt->encode($id, keyencrypt());
}

function decodeEncrypt($id)
{
    $ci = get_instance();
    return $ci->encrypt->decode($id, keyencrypt());
}

function sessionAdmin($role)
{
    $ci = get_instance();
    return $ci->session->userdata($role);
}

function __check_validation($type)
{
    switch ($type) {
        case 'signin':
            $rules = [
                [
                    'field' => 'username',
                    'label' => 'Username',
                    'rules' => 'required'
                ],
                [
                    'field' => 'password',
                    'label' => 'Password',
                    'rules' => 'required'
                ]
            ];
            break;
    }
    return $rules;
}

function cek_login($role = null)
{
    $role = ucfirst($role);
    $ci = get_instance();
    if (!$ci->session->userdata('username')) {
        redirect('auth');
    }

    if ($role) {
        $roleActive = $ci->session->userdata('role');
        if ($roleActive != $role) {
            redirect('auth');
        }
    }
}


// tambahan Jonatan
function outputJson($response, $status_header = 200)
{
    $ci = get_instance();
    $ci->output
        ->set_status_header($status_header)
        ->set_content_type('application/json')
        ->set_output(json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
}

function pretty_dump($data)
{
    echo '<pre>' . var_export($data, true) . '</pre>';
    die();
}

function unsetFlash()
{
    if (isset($_SESSION['success'])) {
        unset($_SESSION['success']);
    }
    if (isset($_SESSION['error'])) {
        unset($_SESSION['error']);
    }
}
