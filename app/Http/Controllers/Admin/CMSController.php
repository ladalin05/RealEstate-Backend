<?php

namespace App\Http\Controllers\Admin;

use App\Models\UserManagement\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\DataTables\CMS\HeroDataTable;
use App\Models\CMS\CmsHomeHero;

class CMSController extends Controller
{
    public function cmsHero(HeroDataTable $dataTable) {
        return $dataTable->render('cms.hero.index');
    }

    
    public function CmsHeroCreate(Request $request)
    {
        try {
            if ($request->isMethod('get')) {
                $title = __('global.add_new');
                $form = new CmsHomeHero();
                $action = route('cms.hero.create');
                return response()->json([
                    'title' => $title,
                    'status' => 'success',
                    'message' => 'success',
                    'html' => view('cms.hero.form', compact('title', 'form', 'action'))->render(),
                    'modal' => 'action-modal',
                ]);
            }

            if ($request->isMethod('post')) {

                $request->validate([
                    'badge_en'           => 'nullable|string|max:255',
                    'badge_kh'           => 'nullable|string|max:255',
                    'title_main_en'      => 'nullable|string|max:255',
                    'title_main_kh'      => 'nullable|string|max:255',
                    'title_highlight_en' => 'nullable|string|max:255',
                    'title_highlight_kh' => 'nullable|string|max:255',
                    'subtitle_en'        => 'nullable|string',
                    'subtitle_kh'        => 'nullable|string',
                    'image'              => 'nullable|image|mimes:jpeg,png,webp|max:2048',
                    'status'             => 'required|in:0,1',
                ]);
                
                $imagePath = null;
                if ($request->hasFile('image')) {
                    $imagePath = $request->file('image')->store('cms/hero', 'public');
                    $imagePath = Storage::url($imagePath);
                }

                CmsHomeHero::create([
                    'id'                 => Str::uuid(),
                    'badge_en'           => $request->badge_en,
                    'badge_kh'           => $request->badge_kh,
                    'title_main_en'      => $request->title_main_en,
                    'title_main_kh'      => $request->title_main_kh,
                    'title_highlight_en' => $request->title_highlight_en,
                    'title_highlight_kh' => $request->title_highlight_kh,
                    'subtitle_en'        => $request->subtitle_en,
                    'subtitle_kh'        => $request->subtitle_kh,
                    'image_url'          => $imagePath,
                    'status'             => $request->status,
                ]);
            
                return response()->json([
                    'status'  => 'success',
                    'message' => __('global.create_type_successfully'),
                    'redirect' => route('cms.hero.index'),
                ]);
            }
            
            return response()->json([
                'status' => 'error',
                'message' => __('messages.405'),
            ]);
            
        } catch (\Throwable $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function CmsHeroUpdate(Request $request, string $id)
    {
        try {
            $hero = CmsHomeHero::findOrFail($id);
    
            if ($request->isMethod('get')) {
                $title  = __('global.edit');
                $form   = $hero;
                $action = route('cms.hero.update', $id);
                return response()->json([
                    'title'   => $title,
                    'status'  => 'success',
                    'message' => 'success',
                    'html'    => view('cms.hero.form', compact('title', 'form', 'action'))->render(),
                    'modal'   => 'action-modal',
                ]);
            }
    
            if ($request->isMethod('post')) {
    
                $request->validate([
                    'badge_en'           => 'nullable|string|max:255',
                    'badge_kh'           => 'nullable|string|max:255',
                    'title_main_en'      => 'nullable|string|max:255',
                    'title_main_kh'      => 'nullable|string|max:255',
                    'title_highlight_en' => 'nullable|string|max:255',
                    'title_highlight_kh' => 'nullable|string|max:255',
                    'subtitle_en'        => 'nullable|string',
                    'subtitle_kh'        => 'nullable|string',
                    'image'              => 'nullable|image|mimes:jpeg,png,webp|max:2048',
                    'status'             => 'required|in:0,1',
                ]);
    
                $imagePath = $hero->image_url;
                if ($request->hasFile('image')) {
                    $imagePath = $request->file('image')->store('cms/hero', 'public');
                    $imagePath = Storage::url($imagePath);
                }
    
                $hero->update([
                    'badge_en'           => $request->badge_en,
                    'badge_kh'           => $request->badge_kh,
                    'title_main_en'      => $request->title_main_en,
                    'title_main_kh'      => $request->title_main_kh,
                    'title_highlight_en' => $request->title_highlight_en,
                    'title_highlight_kh' => $request->title_highlight_kh,
                    'subtitle_en'        => $request->subtitle_en,
                    'subtitle_kh'        => $request->subtitle_kh,
                    'image_url'          => $imagePath,
                    'status'             => $request->status,
                ]);
    
                return response()->json([
                    'status'   => 'success',
                    'message'  => __('global.update_type_successfully'),
                    'redirect' => route('cms.hero.index'),
                ]);
            }
    
            return response()->json([
                'status'  => 'error',
                'message' => __('messages.405'),
            ]);
    
        } catch (\Throwable $e) {
            return response()->json([
                'status'  => 'error',
                'message' => $e->getMessage(),
            ], 500);
        }
    }

    public function pages($dataTable) {
        return $dataTable->render('property.properties.index');
    }
}