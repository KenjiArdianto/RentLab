<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Activitylog\Models\Activity;

class AdminLogsController extends Controller
{
    /**
     * Display a listing of the resource.
     * 
     * 
     */
    public function index(Request $request)
    {
        //
        $query = Activity::latest();
        $search = $request->get('search');

        // split search by comma
        if ($search) {
            $pairs = explode(',', $search);

            foreach ($pairs as $pair) {
                if (!str_contains($pair, '=')) continue;

                [$key, $value] = array_map('trim', explode('=', $pair, 2));

                // handle log_id
                if ($key === 'log_id' || $key === 'id_log') {
                    $query->where('id', $value);
                }
                // handle log_name
                else if ($key === 'log_name' || $key === 'nama_log') {
                    $query->where('log_name', 'like', '%' . $value . '%');
                }
                // handle description
                else if ($key === 'description' || $key === 'deskripsi') {
                    $query->where('description', $value);
                }
                // handle subject_type
                else if ($key === 'subject_type' || $key === 'tipe_subjek') {
                    $query->where('subject_type', $value);
                }
                // handle event
                else if ($key === 'event' || $key === 'peristiwa') {
                    $query->where('event', $value);
                }
                // handle subject_id
                else if ($key === 'subject_id' || $key === 'id_subjek') {
                    $query->where('subject_id', $value);
                }
                // handle causer_type
                else if ($key === 'causer_type' || $key === 'tipe_penyebab') {
                    $query->where('causer_type', $value);
                }
                // handle causer_id
                else if ($key === 'causer_id' || $key === 'id_penyebab') {
                    $query->where('causer_id', $value);
                }
                // handle properties
                else if ($key === 'properties' || $key === 'properti') {
                    $query->where('properties', $value);
                }
                // handle batch_uuid
                else if ($key === 'batch_uuid' || $key === 'uuid_batch') {
                    $query->where('batch_uuid', $value);
                }
                // handle created_at
                else if ($key === 'created_at' || $key === 'dibuat_pada') {
                    if (str_contains($value, '_')) {
                        // Full datetime search
                        $datetime = str_replace('_', ' ', $value);
                        $parsedDatetime = \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $datetime);
                        $query->where('created_at', $parsedDatetime);
                    } else {
                        // Date-only search
                        $query->whereDate('created_at', $value);
                    }
                }

            }
        }


        $logs = $query->paginate(100)->appends(['search' => $search]);;
        \activity('admin_logs_index')
        ->causedBy(Auth::user())
        ->withProperties([
            'ip' => $request->ip(),
            'filters' => $request->query('search'),
            'result_count' => $logs->total(),
            'user_agent' => $request->userAgent(),
        ])
        ->log('Admin viewed logs list' . ($request->has('search') ? ' with filters' : ''));

        return view('admin.logs', compact('logs'));
        
    }
}
