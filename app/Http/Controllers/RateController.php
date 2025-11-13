<?php
namespace App\Http\Controllers;
use App\Rate;
use App\Unit;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use Validator;
class RateController extends Controller

{

    public function index()
    {
        $rates = Rate::latest()->paginate(10);
        return view('rates.index', compact('rates'));
    }
    
    

    public function create()
    {
        return view('rates.create');
    }
    

                              
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'rate' => 'required|numeric|min:0',
            'sellingrate' => 'required|numeric|min:0',
            'currency' => 'required',
            'effective_date' => 'required|date',
            'description' => 'nullable|string'
        ]);


        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }
        
        

        Rate::create($request->all());
        return redirect()->route('rates.index')
        ->with('success', 'Rate created successfully.');
        
        
    }

    public function show(Rate $rate)
    {
    return view('rates.show', compact('rate'));
    }

    
    public function edit(Rate $rate)
    {
        return view('rates.edit', compact('rate'));
    }

    public function update(Request $request, Rate $rate)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'rate' => 'required|numeric|min:0',
            'currency' => 'required|string|size:3',
            'effective_date' => 'required|date',
            'description' => 'nullable|string'
        ]);
        
        

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        $rate->update($request->all());

        return redirect()->route('rates.index')
            ->with('success', 'Rate updated successfully.');
    }

    public function destroy(Rate $rate)
    {
        $rate->delete();

        return redirect()->route('rates.index')
            ->with('success', 'Rate deleted successfully.');
    }



    public function toggleStatus(Rate $rate)
   {
        $rate->update(['is_active' => !$rate->is_active]);

        $status = $rate->is_active ? 'activated' : 'deactivated';

        return redirect()->route('rates.index')
            ->with('success', "Rate {$status} successfully.");
    }


    public function getCurrentRates()
    {
        $rates = Rate::active()->current()->get();
        return response()->json($rates);
     }


    public function getRateByCode($code)
     {
        $rate = Rate::active()->current()->where('code', $code)->first();

        if (!$rate) {
            return response()->json(['error' => 'Rate not found'], 404);
        }

        return response()->json($rate);
    }
}

