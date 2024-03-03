<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bid;
use App\Models\Domain;
use App\Events\BidCreated;
use Illuminate\Support\Facades\DB;
use App\Enums\DomainStatus;

class BidController extends Controller
{
    /**
     * Create a new bid.
     *
     * @param  \Illuminate\Http\Request  $request
     */
    public function create(Request $request)
    {
        $request->validate([
            'domain_id' => 'required|exists:domains,id',
            'amount' => 'required|numeric|min:1',
        ]);

        $domain = Domain::findOrFail($request->domain_id);

        // Check if the domain is not in the 'Upcoming' status
        if ($domain->status == DomainStatus::UPCOMING) {
            return response()->json(['error' => 'Cannot bid on a domain with Upcoming status.'], 400);
        }

        // Calculate the deposit amount based on the deposit type i.e. FIXED or PERCENTAGE
        $depositAmount = $domain->deposit_type == DomainDepositType::FIXED
            ? $domain->deposit_amount
            : ($domain->deposit_amount / 100) * $request->amount;

        // Check if the user has enough balance in their wallet
        if ($request->user()->wallet < $depositAmount) {
            return response()->json(['error' => 'Insufficient balance in wallet.'], 400);
        }

        DB::beginTransaction();

        try {
            // Deduct the deposit from the user's wallet
            $request->user()->decrement('wallet', $depositAmount);

            // Create the new bid
            $bid = new Bid([
                'domain_id' => $request->domain_id,
                'user_id' => $request->user()->id,
                'amount' => $request->amount,
            ]);

            $bid->save();

            // Dispatch the BidCreated event
            event(new BidCreated($bid));

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();

            return response()->json(['error' => 'Failed to place bid.'], 500);
        }
    }
}
