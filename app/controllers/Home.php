<?php


class Home extends BaseController
{
    public function index()
    {
        $newUser = [
            'nama_user' => 'fitri',
            'gmail_user' => 'fitri@gmail.com',
            'password_user' => 'demo1234'
        ];
        Flasher::setFlash('Data anda berhasil di hapus', 'success');
        $res = $this->model('User_model')->updateById(4, $newUser);
        if ($res) {
            redirect('home/page');
        }
    }
    public function page()
    {
        return view('home/index');
    }
}
