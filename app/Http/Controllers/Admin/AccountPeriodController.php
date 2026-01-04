<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AccountPeriod;
use App\Services\AccountPeriodService;
use Illuminate\Http\Request;
use Carbon\Carbon;

/**
 * @property AccountPeriodService $service
 */

class AccountPeriodController extends Controller
{
    public function __construct(AccountPeriodService $service){
        $this->service = $service;
    }

    public function index(){
        return view('admin.account.close');

    }

    /**
     * Close preview screen
     */
    public function closePreview()
    {
        $period = AccountPeriod::where('user_id', auth()->id())
            ->where('status', 'open')
            ->firstOrFail();

        // 🚫 Prevent early closing
        if (!$this->service->isClosable($period)) {
            abort(403, 'Account period cannot be closed yet.');
        }

        $summary = $this->service->calculatePeriod($period);

        return view('admin.account.close', compact('period', 'summary'));
    }

    /**
     * Confirm closing
     */
    public function closeConfirm(Request $request)
    {
        $period = AccountPeriod::where('user_id', auth()->id())
            ->where('status', 'open')
            ->firstOrFail();

        if (!$this->service->isClosable($period)) {
            abort(403, 'Account period cannot be closed yet.');
        }

        // Close + Create next
        $this->service->closePeriod($period);
        $this->service->createNextPeriod($period);

        return redirect()
            ->route('admin.account.close')
            ->with('success', 'Account period closed successfully.');
    }

}
