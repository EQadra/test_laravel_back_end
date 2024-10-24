<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class OpenAIController extends Controller
{
    public function handleRequest(Request $request)
    {
        // Validación de entrada
        $request->validate(['prompt' => 'required|string']);

        // Hacer la llamada a OpenAI API
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
        ])->post('https://api.openai.com/v1/engines/davinci-codex/completions', [
            'prompt' => $request->prompt,
            'max_tokens' => 150,
        ]);

        return response()->json($response->json());
    }
}
