<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeaderMenu;
use App\Support\PublicSiteData;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class HeaderMenuController extends Controller
{
    public function index()
    {
        $menus = HeaderMenu::query()
            ->with('parent')
            ->orderBy('parent_id')
            ->orderBy('sort_order')
            ->paginate(20);

        return view('admin.homepage.menus.index', compact('menus'));
    }

    public function create()
    {
        $parents = HeaderMenu::query()->whereNull('parent_id')->orderBy('sort_order')->get();

        return view('admin.homepage.menus.create', compact('parents'));
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);
        HeaderMenu::create($data);
        PublicSiteData::clearCache();

        return redirect()->route('admin.homepage.menus.index')->with('success', 'Header menu created successfully.');
    }

    public function edit(HeaderMenu $menu)
    {
        $parents = HeaderMenu::query()
            ->whereNull('parent_id')
            ->where('id', '!=', $menu->id)
            ->orderBy('sort_order')
            ->get();

        return view('admin.homepage.menus.edit', compact('menu', 'parents'));
    }

    public function update(Request $request, HeaderMenu $menu)
    {
        $data = $this->validated($request, $menu);
        $menu->update($data);
        PublicSiteData::clearCache();

        return redirect()->route('admin.homepage.menus.index')->with('success', 'Header menu updated successfully.');
    }

    public function destroy(HeaderMenu $menu)
    {
        HeaderMenu::query()->where('parent_id', $menu->id)->delete();
        $menu->delete();
        PublicSiteData::clearCache();

        return redirect()->route('admin.homepage.menus.index')->with('success', 'Header menu deleted successfully.');
    }

    private function validated(Request $request, ?HeaderMenu $menu = null): array
    {
        return $request->validate([
            'parent_id' => [
                'nullable',
                Rule::exists('header_menus', 'id'),
            ],
            'title' => 'required|string|max:100',
            'url' => 'required|string|max:255',
            'sort_order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
            'open_in_new_tab' => 'nullable|boolean',
        ]) + [
            'is_active' => $request->boolean('is_active'),
            'open_in_new_tab' => $request->boolean('open_in_new_tab'),
            'sort_order' => (int) $request->input('sort_order', 0),
        ];
    }
}
