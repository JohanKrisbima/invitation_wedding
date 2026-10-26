<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Template;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class TemplateController extends Controller
{
    /**
     * Display a listing of templates with statistics and search filters.
     */
    public function index(Request $request): View
    {
        $query = Template::withCount(['orders', 'invitations']);

        // Search by name, slug, category, or description
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'ilike', "%{$search}%")
                    ->orWhere('slug', 'ilike', "%{$search}%")
                    ->orWhere('category', 'ilike', "%{$search}%")
                    ->orWhere('description', 'ilike', "%{$search}%");
            });
        }

        // Filter by category
        if ($category = $request->input('category')) {
            if ($category !== 'all') {
                $query->where('category', $category);
            }
        }

        // Filter by status
        if ($status = $request->input('status')) {
            if ($status === 'active') {
                $query->where('is_active', true);
            } elseif ($status === 'inactive') {
                $query->where('is_active', false);
            }
        }

        // Stat metrics
        $totalTemplates = Template::count();
        $activeTemplates = Template::where('is_active', true)->count();
        $totalCategories = Template::distinct('category')->count('category');
        $mostPopular = Template::withCount('invitations')
            ->orderByDesc('invitations_count')
            ->first();

        // Unique categories list for dropdown filter
        $categories = Template::select('category')
            ->distinct()
            ->orderBy('category')
            ->pluck('category');

        $templates = $query->latest()->paginate(12)->withQueryString();

        return view('admin.templates.index', compact(
            'templates',
            'totalTemplates',
            'activeTemplates',
            'totalCategories',
            'mostPopular',
            'categories'
        ));
    }

    /**
     * Store a newly created template in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['nullable', 'string', 'max:255', 'unique:templates,slug'],
            'category' => ['required', 'string', 'max:100'],
            'price' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string', 'max:1000'],
            'thumbnail' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'name.required' => 'Nama template wajib diisi.',
            'slug.unique' => 'Slug template sudah digunakan. Gunakan slug lain.',
            'category.required' => 'Kategori template wajib diisi.',
            'price.required' => 'Harga template wajib diisi.',
            'price.numeric' => 'Harga harus berupa angka yang valid.',
            'thumbnail.image' => 'File thumbnail harus berupa gambar.',
            'thumbnail.max' => 'Ukuran thumbnail maksimal 2MB.',
        ]);

        $slug = !empty($validated['slug'])
            ? Str::slug($validated['slug'])
            : Str::slug($validated['name']);

        // Pastikan slug unik jika auto-generate
        $originalSlug = $slug;
        $counter = 1;
        while (Template::where('slug', $slug)->exists()) {
            $slug = "{$originalSlug}-{$counter}";
            $counter++;
        }
        $validated['slug'] = $slug;

        $validated['is_active'] = $request->boolean('is_active', true);

        // Upload Thumbnail jika disertakan
        if ($request->hasFile('thumbnail')) {
            $validated['thumbnail'] = $request->file('thumbnail')->store('templates', 'public');
        }

        Template::create($validated);

        return redirect()->route('admin.templates.index')
            ->with('success', "Template '{$validated['name']}' berhasil ditambahkan ke katalog!");
    }

    /**
     * Update the specified template in storage.
     */
    public function update(Request $request, Template $template): RedirectResponse
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', Rule::unique('templates')->ignore($template->id)],
            'category' => ['required', 'string', 'max:100'],
            'price' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string', 'max:1000'],
            'thumbnail' => ['nullable', 'image', 'mimes:jpeg,png,jpg,webp', 'max:2048'],
            'is_active' => ['nullable', 'boolean'],
        ], [
            'name.required' => 'Nama template wajib diisi.',
            'slug.required' => 'Slug template wajib diisi.',
            'slug.unique' => 'Slug template sudah digunakan. Gunakan slug lain.',
            'category.required' => 'Kategori template wajib diisi.',
            'price.required' => 'Harga template wajib diisi.',
            'price.numeric' => 'Harga harus berupa angka yang valid.',
            'thumbnail.image' => 'File thumbnail harus berupa gambar.',
            'thumbnail.max' => 'Ukuran thumbnail maksimal 2MB.',
        ]);

        $validated['slug'] = Str::slug($validated['slug']);
        $validated['is_active'] = $request->boolean('is_active');

        // Handle upload thumbnail baru
        if ($request->hasFile('thumbnail')) {
            // Hapus thumbnail lama jika ada
            if ($template->thumbnail && Storage::disk('public')->exists($template->thumbnail)) {
                Storage::disk('public')->delete($template->thumbnail);
            }
            $validated['thumbnail'] = $request->file('thumbnail')->store('templates', 'public');
        }

        $template->update($validated);

        return redirect()->route('admin.templates.index')
            ->with('success', "Template '{$template->name}' berhasil diperbarui!");
    }

    /**
     * Toggle the active status of a template.
     */
    public function toggleStatus(Request $request, Template $template): JsonResponse|RedirectResponse
    {
        $template->is_active = !$template->is_active;
        $template->save();

        $statusText = $template->is_active ? 'Aktif' : 'Nonaktif';
        $message = "Status template '{$template->name}' berhasil diubah menjadi {$statusText}.";

        if ($request->wantsJson() || $request->ajax()) {
            return response()->json([
                'success' => true,
                'is_active' => $template->is_active,
                'message' => $message,
            ]);
        }

        return redirect()->route('admin.templates.index')->with('success', $message);
    }

    /**
     * Remove the specified template from storage if not currently in use.
     */
    public function destroy(Request $request, Template $template): JsonResponse|RedirectResponse
    {
        if (!$template->canBeDeleted()) {
            $errorMsg = "Template '{$template->name}' tidak dapat dihapus karena masih digunakan oleh pesanan atau undangan pernikahan.";

            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMsg,
                ], 422);
            }

            return redirect()->route('admin.templates.index')->with('error', $errorMsg);
        }

        // Hapus thumbnail jika ada
        if ($template->thumbnail && Storage::disk('public')->exists($template->thumbnail)) {
            Storage::disk('public')->delete($template->thumbnail);
        }

        $name = $template->name;
        $template->delete();

        $successMsg = "Template '{$name}' berhasil dihapus dari katalog.";

        if (request()->wantsJson() || request()->ajax()) {
            return response()->json([
                'success' => true,
                'message' => $successMsg,
            ]);
        }

        return redirect()->route('admin.templates.index')->with('success', $successMsg);
    }
}
