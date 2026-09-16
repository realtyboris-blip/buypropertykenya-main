<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use Illuminate\Http\Request;

class FaqController extends Controller
{
    /**
     * Display a listing of FAQs
     */
    public function index()
    {
        // Get all active FAQs grouped by category
        $faqs = Faq::where('is_active', true)
            ->orderBy('sort_order')
            ->orderBy('category')
            ->get();

        // Group FAQs by category
        $groupedFaqs = $faqs->groupBy('category');

        // Get all categories for filter
        $categories = Faq::where('is_active', true)
            ->whereNotNull('category')
            ->distinct()
            ->pluck('category')
            ->toArray();

        return view('media.faqs', compact('groupedFaqs', 'categories'));
    }

    /**
     * Get FAQs by category (AJAX)
     */
    public function getByCategory(Request $request)
    {
        $category = $request->get('category');

        if ($category) {
            $faqs = Faq::where('is_active', true)
                ->where('category', $category)
                ->orderBy('sort_order')
                ->get();
        } else {
            $faqs = Faq::where('is_active', true)
                ->orderBy('sort_order')
                ->get();
        }

        return response()->json($faqs->map(function ($faq) {
            return array_merge($faq->toArray(), [
                'has_video' => $faq->hasVideo(),
                'video_embed_url' => $faq->getVideoEmbedUrl(),
            ]);
        }));
    }

    /**
     * Search FAQs (AJAX)
     */
    public function search(Request $request)
    {
        $query = $request->get('q');

        $faqs = Faq::where('is_active', true)
            ->where(function($q) use ($query) {
                $q->where('question', 'like', "%{$query}%")
                  ->orWhere('answer', 'like', "%{$query}%");
            })
            ->orderBy('sort_order')
            ->get();

        return response()->json($faqs->map(function ($faq) {
            return array_merge($faq->toArray(), [
                'has_video' => $faq->hasVideo(),
                'video_embed_url' => $faq->getVideoEmbedUrl(),
            ]);
        }));
    }
}