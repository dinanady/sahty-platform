<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Drug;
use App\Notifications\DrugStatusUpdated;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DrugApprovalController extends Controller
{
    public function index()
    {
        $drugs = Drug::with('submittedByCenter')
            ->where('approval_status', 'pending')
            ->get();

        return view('Admin.drugs.requests', compact('drugs'));
    }

    public function updateStatus(Request $request, Drug $drug)
    {
        $request->validate([
            'approval_status' => 'required|in:approved,rejected',
        ]);

        $drug->update([
            'approval_status' => $request->approval_status,
            'is_government_approved' => $request->approval_status === 'approved',
            'approved_at' => now(),
            'approved_by' => Auth::id(),
        ]);

        // إرسال إشعار للمركز الصحي
        if ($drug->healthCenter && $drug->healthCenter->user) {
            $drug->healthCenter->user->notify(new DrugStatusUpdated($drug));
        }

        return redirect()->back()->with('success', 'تم تحديث حالة الطلب');
    }

    public function approved()
    {
        $drugs = Drug::with('submittedByCenter')
            ->where('approval_status', 'approved')
            ->get();

        return view('Admin.drugs.approved', compact('drugs'));
    }

    public function rejected()
    {
        $drugs = Drug::with('submittedByCenter')
            ->where('approval_status', 'rejected')
            ->get();

        return view('Admin.drugs.rejected', compact('drugs'));
    }

}
