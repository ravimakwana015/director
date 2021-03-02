<?php

namespace App\Http\Controllers\Admin\Transaction;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use DB;
use Carbon\Carbon;
use App\Models\Transactions\Transactions;


class TransactionController extends Controller
{
    public function transactionReports()
    {
        $users = Transactions ::join('users', 'transactions.user_id', '=', 'users.id')
            -> where('users.deleted_at', null)
            -> selectRaw('MONTH(transactions.created_at) AS month,YEAR(transactions.created_at) AS year ,transactions.payment_status AS status') -> get() -> toarray();

        $months = [];
        $year = [];
        $status = [];
        foreach ($users as $key => $value) {
            $months[$value['month']] = $value['month'];
            $year[$value['year']] = $value['year'];
            $status[$value['status']] = $value['status'];
        }
        if (\Request ::route() -> getName() == 'transaction.data') {
            return view('admin.Transaction-Report.transcationdata', compact('months', 'year', 'status'));
        } else {
            return view('admin.Transaction-Report.index', compact('months', 'year', 'status'));
        }

    }
}
