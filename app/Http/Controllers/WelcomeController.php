<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\HeroSection;
use App\Models\MediaUpload;
use App\Models\Slider;
use App\Models\Student;
use App\Models\StudyDestination;
use App\Models\SubCategory;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    private const PHOTO_TYPES = ['jpg', 'jpeg', 'png', 'gif', 'bmp', 'tiff', 'webp', 'raw', 'heic', 'svg', 'google_drive'];
    private const VIDEO_TYPES = ['mp4', 'avi', 'mkv', 'mov', 'wmv', 'flv', 'webm', 'mpeg', 'mpg', '3gp', 'm4v', 'vob', 'ts', 'mts', 'm2ts', 'ogv', 'rm', 'rmvb', 'divx', 'asf', 'f4v', 'google_drive'];

    public function index()
    {
        $sliders = Slider::query()->latest()->get();

        $users = $this->mixOldAndNew(
            User::approved()
                ->orderBy('created_at')
                ->orderBy('id')
                ->get()
        );

        $students = $this->mixOldAndNew(
            Student::approved()
                ->orderBy('created_at')
                ->orderBy('id')
                ->get()
        );
        $heroSection = HeroSection::query()
            ->with(['flags' => fn ($query) => $query->where('is_active', true)->orderBy('sort_order')])
            ->where('is_active', true)
            ->latest('id')
            ->first();

        $countryFlags = $heroSection?->flags ?? collect();
        $studyDestinations = StudyDestination::query()
            ->where('is_active', true)
            ->orderBy('display_order')
            ->take(8)
            ->get();

        $photoCategories = Category::query()
            ->whereHas('categoryType', fn ($query) => $query->where('type', 'photo'))
            ->orderBy('name')
            ->take(4)
            ->get();

        $photoCategoriesCount = Category::query()
            ->whereHas('categoryType', fn ($query) => $query->where('type', 'photo'))
            ->count();

        $videoCategories = Category::query()
            ->whereHas('categoryType', fn ($query) => $query->where('type', 'video'))
            ->orderBy('name')
            ->take(4)
            ->get();

        $videoCategoriesCount = Category::query()
            ->whereHas('categoryType', fn ($query) => $query->where('type', 'video'))
            ->count();

        $mediaCount = MediaUpload::query()
            ->whereIn('file_type', array_merge(self::PHOTO_TYPES, self::VIDEO_TYPES))
            ->count();

        return view('welcome', compact(
            'sliders',
            'heroSection',
            'users',
            'students',
            'countryFlags',
            'studyDestinations',
            'photoCategories',
            'photoCategoriesCount',
            'videoCategories',
            'videoCategoriesCount',
            'mediaCount'
        ));
    }

    public function allPhotoCategories()
    {
        $categories = Category::query()
            ->whereHas('categoryType', fn ($query) => $query->where('type', 'photo'))
            ->orderBy('name')
            ->get();

        return view('front.all-gallery-categories', [
            'title' => 'All Photo Galleries',
            'kicker' => 'Media Archive',
            'description' => 'Browse all photo gallery categories from the university media archive.',
            'buttonText' => 'View Album',
            'routeName' => 'category.photos',
            'categories' => $categories,
        ]);
    }

    public function allVideoCategories()
    {
        $categories = Category::query()
            ->whereHas('categoryType', fn ($query) => $query->where('type', 'video'))
            ->orderBy('name')
            ->get();

        return view('front.all-gallery-categories', [
            'title' => 'All Video Galleries',
            'kicker' => 'Moving Stories',
            'description' => 'Browse all video gallery categories from the university media archive.',
            'buttonText' => 'Watch Collection',
            'routeName' => 'category.videos',
            'categories' => $categories,
        ]);
    }

    public function allStudents(Request $request)
    {
        $users = $this->paginateMixedCollection(
            User::approved()
                ->orderBy('created_at')
                ->orderBy('id')
                ->get(),
            20,
            $request
        );

        return view('front.all-students', compact('users'));
    }

    public function allPassedStudents(Request $request)
    {
        $students = $this->paginateMixedCollection(
            Student::approved()
                ->orderBy('created_at')
                ->orderBy('id')
                ->get(),
            50,
            $request
        );

        return view('front.all-passed-students', compact('students'));
    }

    private function mixOldAndNew(Collection $items): Collection
    {
        $ordered = $items->values();
        $mixed = collect();
        $start = 0;
        $end = $ordered->count() - 1;

        while ($start <= $end) {
            $mixed->push($ordered[$start]);

            if ($start !== $end) {
                $mixed->push($ordered[$end]);
            }

            $start++;
            $end--;
        }

        return $mixed;
    }

    private function paginateMixedCollection(Collection $items, int $perPage, Request $request): LengthAwarePaginator
    {
        $mixed = $this->mixOldAndNew($items);
        $page = LengthAwarePaginator::resolveCurrentPage();
        $currentItems = $mixed->forPage($page, $perPage)->values();

        return new LengthAwarePaginator(
            $currentItems,
            $mixed->count(),
            $perPage,
            $page,
            [
                'path' => $request->url(),
                'pageName' => 'page',
            ]
        );
    }

    public function showPhotos($id, $subCategoryId = null)
    {
        $category = Category::with('subCategories')->findOrFail($id);

        $photosQuery = MediaUpload::where('category_type_id', $category->category_type_id)
            ->whereIn('file_type', self::PHOTO_TYPES);

        if ($subCategoryId) {
            $photosQuery->where('sub_category_id', $subCategoryId);
        }

        $photos = $photosQuery->get();

        return view('category.photos', compact('category', 'photos', 'subCategoryId'));
    }

    public function showSubCategoryPhotos($category_id, $subcategory_id)
    {
        $category = Category::with('subCategories')->findOrFail($category_id);
        $photos = MediaUpload::where('category_type_id', $category->category_type_id)
            ->where('sub_category', $subcategory_id)
            ->whereIn('file_type', self::PHOTO_TYPES)
            ->get();
        $subCategory = SubCategory::findOrFail($subcategory_id);

        return view('category.subcategory_photos', compact('category', 'subCategory', 'photos'));
    }

    public function showVideos($id, $subCategoryId = null)
    {
        $category = Category::with('subCategories')->findOrFail($id);
        $videosQuery = MediaUpload::where('category_type_id', $category->category_type_id)
            ->whereIn('file_type', self::VIDEO_TYPES);

        if ($subCategoryId) {
            $videosQuery->where('sub_category_id', $subCategoryId);
        }

        $videos = $videosQuery->get();

        return view('category.videos', compact('category', 'videos', 'subCategoryId'));
    }

    public function showSubCategoryVideos($category_id, $subcategory_id)
    {
        $category = Category::with('subCategories')->findOrFail($category_id);
        $videos = MediaUpload::where('category_type_id', $category->category_type_id)
            ->where('sub_category', $subcategory_id)
            ->whereIn('file_type', self::VIDEO_TYPES)
            ->get();
        $subCategory = SubCategory::findOrFail($subcategory_id);

        return view('category.subcategory_videos', compact('category', 'subCategory', 'videos'));
    }
}
