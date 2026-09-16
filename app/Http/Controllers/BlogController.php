<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\BlogCategory;
use Illuminate\Http\Request;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $query = Blog::with(['category', 'author', 'tags'])
            ->published()
            ->orderBy('published_at', 'desc');
        
        // Filter by category
        if ($request->filled('category')) {
            $category = BlogCategory::where('slug', $request->category)->first();
            if ($category) {
                $query->where('blog_category_id', $category->id);
            }
        }
        
        $blogs = $query->paginate(9);
        $categories = BlogCategory::active()->orderBy('sort_order')->get();
        
        return view('blog.index', compact('blogs', 'categories'));
    }
    
    public function show($slug)
    {
        // Try to find by slug exactly as provided
        $blog = Blog::with(['category', 'author', 'tags'])
            ->where('slug', $slug)
            ->where('status', 'published')
            ->first();
        
        // If not found, try to find by slug with the -1 pattern removed
        if (!$blog) {
            // Remove trailing -number from slug
            $cleanSlug = preg_replace('/-\d+$/', '', $slug);
            if ($cleanSlug !== $slug) {
                $blog = Blog::with(['category', 'author', 'tags'])
                    ->where('slug', 'like', $cleanSlug . '%')
                    ->where('status', 'published')
                    ->first();
            }
        }
        
        // If not found by slug and slug is numeric, try by ID
        if (!$blog && is_numeric($slug)) {
            $blog = Blog::with(['category', 'author', 'tags'])
                ->where('id', (int)$slug)
                ->where('status', 'published')
                ->first();
        }
        
        // If still not found, abort with 404
        if (!$blog) {
            abort(404, 'Blog post not found');
        }
        
        // Increment views
        $blog->incrementViews();
        
        // Related posts (same category)
        $relatedPosts = Blog::published()
            ->where('id', '!=', $blog->id)
            ->when($blog->blog_category_id, function($query) use ($blog) {
                return $query->where('blog_category_id', $blog->blog_category_id);
            })
            ->orderBy('published_at', 'desc')
            ->take(3)
            ->get();
        
        return view('blog.show', compact('blog', 'relatedPosts'));
    }
}