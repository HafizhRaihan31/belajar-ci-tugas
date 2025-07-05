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

    function __construct()
    {
        $this->apikey = env('API_KEY'); // pastikan sudah ada di .env
        $this->user = new UserModel();
        $this->transaction = new TransactionModel();
        $this->transaction_detail = new TransactionDetailModel();
    }

    public function index()
    {
        $data = [
            'results' => [],
            'status' => ["code" => 401, "description" => "Unauthorized"]
        ];

        $key = $this->request->getHeaderLine('key'); // case-insensitive

        if ($key && $key == $this->apikey) {
            $penjualan = $this->transaction->findAll();

            foreach ($penjualan as &$pj) {
                $details = $this->transaction_detail->where('transaction_id', $pj['id'])->findAll();
                $pj['details'] = $details;

                $jumlahItem = 0;
                foreach ($details as $d) {
                    $jumlahItem += $d['jumlah'];
                }
                $pj['jumlah_item'] = $jumlahItem;
            }

            $data['status'] = ["code" => 200, "description" => "OK"];
            $data['results'] = $penjualan;
        }

        return $this->respond($data);
    }
}
