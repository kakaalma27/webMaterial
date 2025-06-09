<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');
        
        $payments = Payment::with('user')
            ->when($search, function ($query) use ($search) {
                $query->where('name', 'like', '%'.$search.'%')
                      ->orWhere('number', 'like', '%'.$search.'%')
                      ->orWhereHas('user', function ($userQuery) use ($search) {
                          $userQuery->where('name', 'like', '%'.$search.'%');
                      });
            })
            ->latest()
            ->paginate(10);

        return view('main.payment.index', compact('payments', 'search'));
    }

public function store(Request $request)
{
    $validated = $request->validate([
        'name' => 'required|string|max:255',
        'number' => 'required|string|max:255',
        'status' => 'required|in:0,1',
    ]);

    $validated['status'] = (int) $validated['status'];
    $validated['user_id'] = auth()->id();

    Payment::create($validated);

    return redirect()->route('payment.index')
        ->with('success', 'Payment method added successfully.');
}

    public function edit(Payment $payment)
    {
        return response()->json($payment);
    }

    public function update(Request $request, Payment $payment)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'number' => 'required|string|max:255',
            'status' => 'required|in:0,1',
        ]);
    $validated['status'] = (int) $validated['status'];
    $validated['user_id'] = auth()->id();
        $payment->update($validated);

        return redirect()->route('payment.index')
            ->with('success', 'Payment method updated successfully.');
    }

    public function destroy(Payment $payment)
    {
        $payment->delete();
        return redirect()->route('payment.index')
            ->with('success', 'Payment method deleted successfully.');
    }
}