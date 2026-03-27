<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use App\Models\ChatbotMessage;
use App\Models\AiSetting;

class ChatbotController extends Controller
{
    public function chat(Request $request)
    {
        $request->validate([
            'messages' => 'required|array',
            'messages.*.role' => 'required|in:user,assistant',
            'messages.*.content' => 'required|string',
        ]);

        // ✅ Fetch AI settings dynamically
        $aiSettings = \App\Models\AiSetting::first(); // Assuming you have a single row
        $assistantName = $aiSettings->assistant_name ?? 'AI Assistant';
        $systemPrompt = $aiSettings->system_prompt ?? 'You are a helpful assistant.';

        // Optionally prepend welcome message to first interaction
        $welcomeMessage = $aiSettings->welcome_message ?? "Hi! I'm your AI assistant 🤖 How can I help you to?";

        // Add welcome message if first message
        if (empty($request->messages)) {
            $request->messages[] = [
                'role' => 'assistant',
                'content' => $welcomeMessage,
            ];
        }

        // ✅ Prepare messages for OpenRouter AI
        $messagesForAI = array_merge(
            [
                [
                    'role' => 'system',
                    'content' => $systemPrompt,
                ],
            ],
            $request->messages,
        );

        // Call OpenRouter API
        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . config('services.openrouter.key'),
            'Content-Type' => 'application/json',
        ])->post('https://openrouter.ai/api/v1/chat/completions', [
            'model' => 'openai/gpt-3.5-turbo',
            'messages' => $messagesForAI,
        ]);

        if ($response->failed()) {
            return response()->json(
                [
                    'error' => 'AI unavailable',
                    'detail' => $response->json(),
                ],
                500,
            );
        }

        $reply = $response->json()['choices'][0]['message']['content'] ?? 'Sorry, no response.';

        // ✅ Save user message + bot reply to DB
        $lastUserMessage = collect($request->messages)->last();

        ChatbotMessage::insert([
            [
                'user_id' => auth()->id(),
                'role' => 'user',
                'message' => $lastUserMessage['content'],
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'user_id' => auth()->id(),
                'role' => 'assistant',
                'message' => $reply,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        return response()->json(['reply' => $reply]);
    }

    // ✅ Fetch chat history
    public function history()
    {
        $messages = ChatbotMessage::where('user_id', auth()->id())
            ->orderBy('created_at')
            ->get(['id', 'role', 'message']);

        return response()->json($messages);
    }

    // ✅ Clear chat history
    public function clearHistory()
    {
        ChatbotMessage::where('user_id', auth()->id())->delete();

        return response()->json(['message' => 'History cleared']);
    }

    // chatbot setting
    public function loadSettings()
    {
        $settings = AiSetting::first(); // we only store one row
        return response()->json([
            'success' => true,
            'settings' => $settings,
        ]);
    }

    // Save AI settings
    public function saveSettings(Request $request)
    {
        $request->validate([
            'assistant_name' => 'required|string|max:255',
            'welcome_message' => 'nullable|string',
            'system_prompt' => 'nullable|string',
        ]);

        $settings = AiSetting::first();
        if (!$settings) {
            $settings = AiSetting::create($request->all());
        } else {
            $settings->update($request->all());
        }

        return response()->json([
            'success' => true,
            'message' => 'AI settings saved successfully!',
            'settings' => $settings,
        ]);
    }
    public function getSettings()
{
    $settings = AiSetting::first();

    return response()->json([
        'success' => true,
        'data' => [
            'assistant_name'  => $settings->assistant_name  ?? 'AI Assistant',
            'welcome_message' => $settings->welcome_message ?? "Hi! I'm your AI assistant 🤖 How can I help you today?",
            'system_prompt'   => $settings->system_prompt   ?? 'You are a helpful assistant.',
            'model'           => $settings->model           ?? 'openai/gpt-3.5-turbo',
        ]
    ]);
}
}
