<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::post('/deploy', function(Request $request) {

    if (($signature = $request->headers->get('X-Hub-Signature')) == null) {
        abort(400, 'Signature header is not set.');
    }

    $signatureData = explode('=', $signature);

    if (count($signatureData) !== 2) {
        abort(400, 'Signature format is invalid.');
    }

    $webhookSignature = hash_hmac('sha1', $request->getContent(), config('services.deploy_key'));

    if (!hash_equals($webhookSignature, $signatureData[1])) {
        abort(401, 'Could not verify request signature ' . $signatureData[1]);
    }

    exec("sh " . base_path() . "/deploy.sh &");

    return response('deploying');
});
