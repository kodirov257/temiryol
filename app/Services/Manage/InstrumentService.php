<?php

namespace App\Services\Manage;

use App\Helpers\ImageHelper;
use App\Http\Requests\Admin\Instruments\CreateRequest;
use App\Http\Requests\Admin\Instruments\UpdateRequest;
use App\Models\Instrument;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class InstrumentService
{
    private ?int $nextId = null;

    /**
     * @throws \Throwable
     */
    public function create(CreateRequest $request): Instrument
    {
        $instrument =  Instrument::make([
            'name_uz' => $request->name_uz,
            'name_uz_cy' => $request->name_uz_cy,
            'name_ru' => $request->name_ru,
            'name_en' => $request->name_en,
            'description_uz' => $request->description_uz,
            'description_uz_cy' => $request->description_uz_cy,
            'description_ru' => $request->description_ru,
            'description_en' => $request->description_en,
            'quantity' => $request->quantity,
            'weight' => $request->weight,
            'department_id' => $parentDepartment->department_id ?? $request->department_id,
            'slug' => $request->slug,
        ]);

        if ($request->photo) {
            $photoName = ImageHelper::getRandomName($request->photo);
            $instrument->id = $this->getNextId();
            $instrument->photo = $photoName;
            $instrument->saveOrFail();

            $this->uploadPhoto($this->getNextId(), $request->photo, $photoName);
        } else {
            $instrument->saveOrFail();
        }

        return $instrument;
    }

    public function update(int $id, UpdateRequest $request): void
    {
        $instrument = Instrument::findOrFail($id);

        $photoName = $request->photo ? ImageHelper::getRandomName($request->photo) : null;

        $instrument->update([
            'name_uz' => $request->name_uz,
            'name_uz_cy' => $request->name_uz_cy,
            'name_ru' => $request->name_ru,
            'name_en' => $request->name_en,
            'description_uz' => $request->description_uz,
            'description_uz_cy' => $request->description_uz_cy,
            'description_ru' => $request->description_ru,
            'description_en' => $request->description_en,
            'quantity' => $request->quantity,
            'weight' => $request->weight,
            'photo' => $photoName ?? $instrument->photo,
            'department_id' => $parentDepartment->department_id ?? $request->department_id,
            'slug' => $request->slug,
        ]);

        if ($photoName) {
            Storage::disk('public')->deleteDirectory('/files/' . ImageHelper::FOLDER_INSTRUMENTS . '/' . $instrument->id);
            $this->uploadPhoto($instrument->id, $request->photo, $photoName);
        }
    }

    public function getNextId(): int
    {
        if (!$this->nextId) {
            $nextId = DB::select("select nextval('instruments_id_seq')");
            return $this->nextId = (int)$nextId['0']->nextval;
        }
        return $this->nextId;
    }

    public function removePhoto(int $id): bool
    {
        $instrument = Instrument::findOrFail($id);
        return Storage::disk('public')->deleteDirectory('/files/' . ImageHelper::FOLDER_INSTRUMENTS . '/' . $instrument->id) && $instrument->update(['file' => null]);
    }

    private function uploadPhoto(int $instrumentId, UploadedFile $file, string $imageName): void
    {
        ImageHelper::saveThumbnail($instrumentId, ImageHelper::FOLDER_INSTRUMENTS, $file, $imageName);
        ImageHelper::saveOriginal($instrumentId, ImageHelper::FOLDER_INSTRUMENTS, $file, $imageName);
    }
}
