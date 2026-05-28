<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Mpdf\Mpdf;

class StudentByReligionController extends Controller
{
    public function index(Request $request)
    {
        [$users, $religions, $countries, $selectedReligion, $selectedCountry] = $this->filteredDataset($request);
        $structuredData = $this->structureByBlock($users);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.partials.students_by_religion_cards', compact('structuredData', 'selectedReligion', 'selectedCountry'))->render(),
                'pdf_url' => route('students.by.religion.pdf', ['religion' => $selectedReligion, 'country' => $selectedCountry]),
            ]);
        }

        return view('admin.students_by_religion', compact('structuredData', 'countries', 'religions', 'selectedReligion', 'selectedCountry'));
    }

    public function downloadPdf(Request $request)
    {
        [, , , $selectedReligion, $selectedCountry] = $this->filteredDataset($request);
        [$users] = $this->filteredDataset($request);
        $structuredData = $this->structureByBlock($users);
        $reportEntries = $this->buildReportEntries($structuredData);

        $html = view('admin.students_by_floor_pdf', compact(
            'reportEntries',
            'selectedReligion',
            'selectedCountry'
        ))->render();

        $mpdf = new Mpdf([
            'format' => 'A4',
            'orientation' => 'L',
            'margin_top' => 14,
            'margin_right' => 10,
            'margin_bottom' => 14,
            'margin_left' => 10,
        ]);

        $mpdf->SetTitle('Students By Floor Report');
        $mpdf->WriteHTML($html);
        $mpdf->SetDisplayMode('fullpage');

        $filename = 'students-by-floor-report.pdf';

        return response($mpdf->Output($filename, 'S'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ]);
    }

    public function showBlock(Request $request, $block)
    {
        [, $religions, $countries] = $this->filteredDataset($request);
        [$users] = $this->filteredDataset($request);
        $structuredData = $this->structureByBlock($users);

        if (!isset($structuredData[$block])) {
            abort(404, 'Block not found');
        }

        $floors = $structuredData[$block];
        $religion = $request->input('religion');
        $country = $request->input('country');

        if ($request->ajax()) {
            return response()->json([
                'html' => view('admin.partials.student_by_block_cards', compact('floors'))->render(),
            ]);
        }

        return view('admin.student_by_block', compact('block', 'floors', 'religion', 'country', 'religions', 'countries'));
    }

    private function filteredDataset(Request $request): array
    {
        $countries = User::distinct()->pluck('country');
        $religions = User::distinct()->pluck('religion');
        $selectedReligion = $request->input('religion');
        $selectedCountry = $request->input('country');

        $query = User::query();

        if ($selectedReligion) {
            $query->where('religion', $selectedReligion);
        }

        if ($selectedCountry) {
            $query->where('country', $selectedCountry);
        }

        $users = $query->get();

        return [$users, $religions, $countries, $selectedReligion, $selectedCountry];
    }

    private function buildReportEntries(array $structuredData): array
    {
        $entries = [];

        foreach ($structuredData as $block => $floors) {
            foreach ($floors as $floor => $rooms) {
                foreach ($rooms as $roomNumber => $students) {
                    foreach ($students as $student) {
                        $entries[] = [
                            'block' => $block,
                            'floor' => $floor,
                            'room_number' => $roomNumber,
                            'name' => $student->full_name,
                            'country' => $student->country,
                            'religion' => $student->religion,
                        ];
                    }
                }
            }
        }

        return $entries;
    }

    private function structureByBlock($users)
    {
        $blocks = [
            '1st Block' => [],
            '2nd Block' => [],
            '3rd Block' => [],
            '4th Block' => []
        ];

        foreach ($users as $user) {
            $blockNumber = (int) substr($user->room_number, 0, 1);
            $floorNumber = (int) substr($user->room_number, 1, 1);

            $blockKey = match ($blockNumber) {
                1 => '1st Block',
                2 => '2nd Block',
                3 => '3rd Block',
                4 => '4th Block',
                default => 'Others'
            };

            $floorKey = match ($floorNumber) {
                1 => '1st Floor',
                2 => '2nd Floor',
                3 => '3rd Floor',
                4 => '4th Floor',
                5 => '5th Floor',
                6 => '6th Floor',
                7 => '7th Floor',
                8 => '8th Floor',
                9 => '9th Floor',
                default => 'Unknown Floor'
            };

            $blocks[$blockKey][$floorKey][$user->room_number][] = $user;
        }

        return $blocks;
    }
}
