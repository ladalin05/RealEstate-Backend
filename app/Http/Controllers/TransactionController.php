<?php

namespace App\Http\Controllers;

use App\Models\Property;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\DataTables\TransactionDataTable;
use App\Models\PaymentGateway;


class TransactionController extends Controller
{
    /**
     * Display a listing of transactions.
     */
    public function index(TransactionDataTable $dataTable)
    {
        return $dataTable->render('pages.transaction.index');
    }


}
