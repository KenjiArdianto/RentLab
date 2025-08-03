<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Payment;

class AdminPaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     * 
     * 
     */
    public function index(Request $request)
    {
        //
        $query = Payment::latest();
        $search = $request->get('search');

        // split search by comma
        if ($search) {
            $pairs = explode(',', $search);

            foreach ($pairs as $pair) {
                if (!str_contains($pair, '=')) continue;

                [$key, $value] = array_map('trim', explode('=', $pair, 2));

                // handle payment_id
                if ($key === 'payment_id' || $key === 'id_pembayaran') {
                    $query->where('id', $value);
                }
                // handle url
                else if ($key === 'url') {
                    $query->where('url', 'like', '%' . $value . '%');
                }
                // handle external_id
                else if ($key === 'external_id' || $key === 'id_eksternal') {
                    $query->where('external_id', 'like', '%' . $value . '%');
                }
                // handle amount
                else if ($key === 'amount' || $key === 'jumlah') {
                    $query->where('amount', 'like', '%' . $value . '%');
                }
                // handle status
                else if ($key === 'status') {
                    $query->where('status', 'like', '%' . $value . '%');
                }
                // handle paid_at
                else if ($key === 'paid_at' || $key === 'dibayar_pada') {
                    if (str_contains($value, '_')) {
                        // Full datetime search
                        $datetime = str_replace('_', ' ', $value);
                        $parsedDatetime = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $datetime);
                        $query->where('paid_at', $parsedDatetime);
                    } else {
                        // Date-only search
                        $query->whereDate('paid_at', $value);
                    }
                }
                // handle payment_method
                else if ($key === 'payment_method' || $key === 'metode_pembayaran') {
                    $query->where('payment_method', 'like', '%' . $value . '%');
                }
                // handle payment_channel
                else if ($key === 'payment_channel' || $key === 'kanal_pembayaran') {
                    $query->where('payment_channel', 'like', '%' . $value . '%');
                }

            }
        }


        $payments = $query->paginate(100)->appends(['search' => $search]);;
        \activity('admin_payment_index')
        ->causedBy(Auth::user())
        ->withProperties([
            'ip' => $request->ip(),
            'filters' => $request->query('search'),
            'result_count' => $payments->total(),
            'user_agent' => $request->userAgent(),
        ])
        ->log('Admin viewed payment list' . ($request->has('search') ? ' with filters' : ''));

        return view('admin.payments', compact('payments'));
        
    }
}
