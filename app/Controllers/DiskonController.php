<?php

namespace App\Controllers;

use App\Models\DiskonModel;

class DiskonController extends BaseController
{
    protected $diskonModel;

    public function __construct()
    {
        $this->diskonModel = new DiskonModel();
    }

    public function index()
    {
        if (session()->get('role') != 'admin') return redirect()->to('/');
        $data['diskon'] = $this->diskonModel->findAll();
        return view('v_diskon', $data);
    }

    public function create()
    {
        if (session()->get('role') != 'admin') return redirect()->to('/');
        return view('diskon');
    }

    public function store()
    {
        if (session()->get('role') != 'admin') return redirect()->to('/');

        $tanggal = $this->request->getPost('tanggal');
        $nominal = $this->request->getPost('nominal');


        if ($this->diskonModel->where('tanggal', $tanggal)->first()) {
            return redirect()->back()->with('error', 'Diskon untuk tanggal ini sudah ada!');
        }

        $this->diskonModel->save([
            'tanggal' => $tanggal,
            'nominal' => $nominal,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
        ]);

        if ($tanggal === date('Y-m-d')) {
        session()->set('diskon_nominal', $nominal);
        }

        return redirect()->to('diskon')->with('success', 'Diskon berhasil ditambahkan!');
    }

    public function edit($id)
    {
        if (session()->get('role') != 'admin') return redirect()->to('/');
        $data['diskon'] = $this->diskonModel->find($id);
        return view('diskon', $data);
    }

   public function update($id)
{
    if (session()->get('role') != 'admin') return redirect()->to('/');

    $nominalBaru = $this->request->getPost('nominal');

    $this->diskonModel->update($id, [
        'nominal' => $nominalBaru,
        'updated_at' => date('Y-m-d H:i:s'),
    ]);

    $diskon = $this->diskonModel->find($id);
    if ($diskon && $diskon['tanggal'] === date('Y-m-d')) {
        session()->set('diskon_nominal', $nominalBaru);
    }

    return redirect()->to('diskon')->with('success', 'Diskon berhasil diupdate!');
}

    public function delete($id)
{
    $diskon = $this->diskonModel->find($id);

    
    if ($diskon) {
        $this->diskonModel->delete($id);

        if ($diskon['tanggal'] == date('Y-m-d')) {
            session()->remove('diskon_nominal');
        }

        return redirect()->to('diskon')->with('success', 'Diskon berhasil dihapus.');
    }

    return redirect()->to('diskon')->with('error', 'Diskon tidak ditemukan.');
}
}