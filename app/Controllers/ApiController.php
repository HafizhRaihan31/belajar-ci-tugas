<?php

namespace App\Controllers;

use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\RESTful\ResourceController;

use App\Models\UserModel; 
use App\Models\TransactionModel;
use App\Models\TransactionDetailModel;

class ApiController extends ResourceController
{
    protected $apikey;
    protected $user;
    protected $transaction;
    protected $transaction_detail;

    public function __construct()
    {
        // Ambil API key dari file .env
        $this->apikey = env('API_KEY', 'default_key'); // Bisa diganti default_key jika kosong
        $this->user = new UserModel();
        $this->transaction = new TransactionModel();
        $this->transaction_detail = new TransactionDetailModel();
    }

    /**
     * Endpoint utama (GET /api)
     *
     * @return ResponseInterface
     */
    public function index()
    {
        $data = [ 
            'results' => [],
            'status' => ["code" => 401, "description" => "Unauthorized"]
        ];

        // Ambil semua header
        $headers = $this->request->headers();

        // Ubah jadi array key => value
        array_walk($headers, function (&$value, $key) {
            $value = $value->getValue();
        });

        // Cek apakah header Key ada dan cocok
        if (array_key_exists("Key", $headers) && $headers["Key"] == $this->apikey) {
            $penjualan = $this->transaction->findAll();

            // Tambahkan detail tiap transaksi
            foreach ($penjualan as &$pj) {
                $pj['details'] = $this->transaction_detail
                    ->where('transaction_id', $pj['id'])
                    ->findAll();
            }

            $data['status'] = ["code" => 200, "description" => "OK"];
            $data['results'] = $penjualan;
        }

        return $this->respond($data);
    }

    public function show($id = null)
    {
        //
    }

    public function new()
    {
        //
    }

    public function create()
    {
        //
    }

    public function edit($id = null)
    {
        //
    }

    public function update($id = null)
    {
        //
    }

    public function delete($id = null)
    {
        //
    }
}
