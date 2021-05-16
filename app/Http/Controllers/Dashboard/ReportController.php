<?php

namespace App\Http\Controllers\Dashboard;

use App\Cash;
use App\Crop;
use App\Http\Controllers\Controller;
use App\Report;
use App\Table;
use App\Town;
use App\Unit;
use App\Zone;
use Facade\Ignition\Tabs\Tab;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $from = $request->from . ' 00:00:01';
        $to = $request->to . ' 23:59:59';

        $tables = Table::with('fields')->get();

        if ($request->reportType == 'state') {
            $towns = Town::where('state_id', $request->state)->get()->pluck('id');
            $units = Unit::whereIn('town_id', $towns)->get()->pluck('id');
            $zones = Zone::whereIn('unit_id', $units)->get()->pluck('id');
        } elseif($request->reportType == 'town') {
            $units = Unit::where('town_id', $request->town)->get()->pluck('id');
            $zones = Zone::whereIn('unit_id', $units)->get()->pluck('id');
        } elseif($request->reportType == 'unit') {
            $zones = Zone::where('unit_id', $request->unit)->get()->pluck('id');
        } elseif($request->reportType == 'zone') {
            $zones = Zone::where('id', $request->zone)->get()->pluck('id');
        }

        if ($request->reportCategory == '1') {

            if ($request->supportType == 1) {
                $rows = Cash::whereIn('zone_id', $zones)->whereBetween('created_at', [$from, $to])->get();
            } elseif ($request->supportType == 2) {
                $rows = Crop::whereIn('zone_id', $zones)->where('crop_id', $request->supply)->whereBetween('created_at', [$from, $to])->get();
            }
            $data = [];
            foreach ($tables as $table) {
                foreach ($table->fields as $field) {
                    $count = 0;
                    $cost = 0;
                    foreach ($rows as $row) {
                        if ($row->field_id == $field->id) {
                            $count++;
                            if ($request->supportType == 1) {
                                $cost += $row->amount;
                            } elseif($request->supportType == 2) {
                                $cost += $row->quantity;
                            }
                        }
                    }
                    $data[$table->id][$field->id]['count'] = $count;
                    $data[$table->id][$field->id]['cost'] = $cost;
                }
            }
            return $data;
        } elseif ($request->reportCategory == '2') {
            //
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function show(Report $report)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function edit(Report $report)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Report $report)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function destroy(Report $report)
    {
        //
    }
}
