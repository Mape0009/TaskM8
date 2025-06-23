<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PinCode;
use App\Models\Mail;

class PinCodeController extends Controller
{
    public function generatePinCode(Request $request)
    {
        $request->validate([
            'mailId' => 'required|exists:mails,id',
        ]);

        $pinCode = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);

        $pinCodeModel = new PinCode();
        $pinCodeModel->pincode = $pinCode;
        $pinCodeModel->mailId = $request->mailId;
        $pinCodeModel->createdAt = now();
        $pinCodeModel->save();

        return response()->json(['pincode' => $pinCode], 201);
    }

    public function verifyPinCode(Request $request)
    {
        $request->validate([
            'mailId' => 'required|exists:mails,id',
            'pincode' => 'required|string|size:4',
        ]);

        $pinCode = PinCode::where('mailId', $request->mailId)
                          ->where('pincode', $request->pincode)
                          ->first();

        if ($pinCode) {
            $pinCode->delete();
            return response()->json(['message' => 'Pin code is valid'], 200);
        }

        return response()->json(['message' => 'Invalid pin code'], 404);
    }

    public function expirePinCodes()
    {
        $expiredPinCodes = PinCode::where('createdAt', '<', now()->subDays(7))->get();

        foreach ($expiredPinCodes as $pinCode) {
            $pinCode->delete();
        }

        return response()->json(['message' => 'Expired pin codes deleted'], 200);
    }
}
