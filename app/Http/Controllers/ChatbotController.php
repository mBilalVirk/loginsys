<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatbotController extends Controller
{
    public function chat(Request $request)
    {
        $request->validate([
            'messages' => 'required|array',
            'messages.*.role' => 'required|in:user,assistant',
            'messages.*.content' => 'required|string',
        ]);

       $apiKey = env('OPENROUTER_API_KEY');

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $apiKey,
            'Content-Type' => 'application/json',
        ])->post('https://openrouter.ai/api/v1/chat/completions', [
            'model' => 'openai/gpt-3.5-turbo',
            'messages' => array_merge(
                [['role' => 'system', 'content' => ' You are a helpful assistant for our social media app called "SocialApp".
            
            Here are frequently asked questions you must answer accurately:
            
            Q: How do I create a post?
            A: Click the "+" button on the home page, write your content, and click Post.
            
            Q: How do I add a friend?
            A: Go to their profile and click the "Add Friend" button.
            
            Q: How do I delete my account?
            A: Go to Settings > Account > Delete Account.
            
            Q: How do I change my password?
            A: Go to Settings > Security > Change Password.
            
            Q: How do I report someone?
            A: Go to their profile, click the 3 dots menu, and select Report.
            
            Q: How do I make my profile private?
            A: Go to Settings > Privacy > Account Privacy > set to Private.
            
            Always be friendly and concise. If you do not know the answer, say
            "Please contact our support team at support@socialapp.com".
        ']],
                $request->messages  // ✅ full conversation history
            ),
        ]);

        \Log::info('OpenRouter Status: ' . $response->status());
        \Log::info('OpenRouter Body: ' . $response->body());

        if ($response->failed()) {
            return response()->json([
                'error' => 'AI unavailable',
                'detail' => $response->json(),
            ], 500);
        }

        $reply = $response->json()['choices'][0]['message']['content'] ?? 'Sorry, no response.';

        return response()->json(['reply' => $reply]);
    }
}