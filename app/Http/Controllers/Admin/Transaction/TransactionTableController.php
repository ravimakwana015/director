<?php

namespace App\Http\Controllers\Admin\Transaction;

use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\Datatables\Datatables;
use App\Http\Controllers\Controller;
use App\Models\Transactions\Transactions;
use App\User;
use DB;
use Illuminate\Database\Eloquent\Collection;

class TransactionTableController extends Controller

{

    /**
     * getForDataTable
     *
     * @return void
     */
    public function getForDataTable($input)
    {
        $dataTableQuery = Transactions ::leftJoin('users', 'transactions.user_id', '=', 'users.id')
            -> where('users.deleted_at', null)
            -> select([
                'users.username as username',
                'users.id as id',
                'users.first_name as first_name',
                'users.last_name as last_name',
                // 'newspapers.title',
                'users.email as email',
                'transactions.created_at',
                'transactions.payment_status',
                'transactions.amount',
                'transactions.coupon',
                'transactions.user_id as user_id',
            ]);

        if (isset($input['month']) && $input['month'] != '') {
            $dataTableQuery -> whereMonth('transactions.created_at', '=', $input['month']);
        }

        if (isset($input['year']) && $input['year'] != '') {
            $dataTableQuery -> whereYear('transactions.created_at', '=', $input['year']);
        }

        if (isset($input['from_date']) && isset($input['to_date']) && $input['from_date'] != '' && $input['to_date'] != '') {
            $dataTableQuery -> whereBetween('transactions.created_at', array($input['from_date'], $input['to_date']));
        }

        if (isset($input['status']) && $input['status'] != '') {
            $dataTableQuery -> where('payment_status', '=', $input['status']);
        }

        if (isset($input['username']) && !empty($input['username'])) {
            $dataTableQuery -> where('users.username', '=', $input['username']);
        }
        return $dataTableQuery;
    }

    /**
     * Process ajax request.
     *
     * @return JsonResponse
     * @throws \Exception
     */

    public function getData(Request $request)
    {
        return Datatables ::make($this -> getForDataTable($request -> all()))
            -> escapeColumns(['id'])
            -> addColumn('created_at', function ($transcationreport) {
                return Carbon ::parse($transcationreport -> created_at) -> format('d/m/Y H:i:s');
            }) -> addColumn('updated_at', function ($transcationreport) {
                return Carbon ::parse($transcationreport -> updated_at) -> format('d/m/Y H:i:s');
            }) -> addColumn('username', function ($transcationreport) {
                return '<a href="' . route('users.show', $transcationreport -> id) . '">' . $transcationreport -> username . '</a>';
            }) -> addColumn('first_name', function ($transcationreport) {
                return $transcationreport -> first_name;
            }) -> addColumn('last_name', function ($transcationreport) {
                return $transcationreport -> last_name;
            }) -> addColumn('email', function ($transcationreport) {
                return $transcationreport -> email;
            }) -> addColumn('plan', function ($transcationreport) {
                return $transcationreport -> usersubscription -> stripe_plan;
            }) -> addColumn('payment_status', function ($transcationreport) {
                if ($transcationreport -> coupon == 1) {
                    return "<label class='label label-info'>Coupon Applied</label>";
                } elseif ($transcationreport -> coupon == 0 && $transcationreport -> payment_status == 1) {
                    return "<label class='label label-success'>Payment Paid</label>";
                }
            }) -> addColumn('amount', function ($transcationreport) {
                if ($transcationreport -> coupon == 1) {
                    return "N/A";
                } elseif ($transcationreport -> coupon == 0 && $transcationreport -> payment_status == 1) {
                    return $transcationreport ->amount;
                }
            }) -> make(true);
    }
}

?>
