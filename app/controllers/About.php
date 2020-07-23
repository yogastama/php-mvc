<?php


class About extends BaseController
{
    public function index($nama = '', $pekerjaan)
    {
        echo "Hello, $nama saya adalah seorang $pekerjaan";
    }
    public function page()
    {
    }
}
