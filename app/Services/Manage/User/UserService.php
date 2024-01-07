<?php

namespace App\Services\Manage\User;

use App\Models\User\Profile;
use App\Models\User\User;
use App\Helpers\ImageHelper;
use App\Http\Requests\Admin\Users\CreateRequest;
use App\Http\Requests\Admin\Users\UpdateRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UserService
{
    /**
     * @throws \Throwable
     */
    public function store(CreateRequest $request): User
    {
        DB::beginTransaction();
        try {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => bcrypt($request->password),
                'role' => $request->role,
                'status' => User::STATUS_ACTIVE,
            ]);

            $imageName = null;
            if ($request->avatar) {
                $imageName = ImageHelper::getRandomName($request->avatar);
                $this->uploadAvatar($user->id, $request->avatar, $imageName);
            }

            Profile::create([
                'user_id' => $user->id,
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'birth_date' => $request->birth_date,
                'gender' => $request->gender,
                'address' => $request->address,
                'avatar' => $imageName,
            ]);

            DB::commit();

            return $user;
        }catch (\Exception|\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * @throws \Throwable
     */
    public function update($id, UpdateRequest $request): User
    {
        $user = User::findOrFail($id);

        DB::beginTransaction();
        try {
            $attributes = array_merge([
                'name' => $request->name,
                'email' => $request->email,
                'role' => $request->role,
                'status' => $request->status,
            ], $request->password ? ['password' => bcrypt($request->password)] : []);

            $user->update($attributes);

            if (!$user->profile) {
                $user->profile()->create([
                    'first_name' => $request->first_name,
                    'last_name' => $request->last_name,
                    'birth_date' => $request->birth_date,
                    'gender' => $request->gender,
                    'address' => $request->address,
                ]);
            }

            if (!$request->avatar) {
                $user->profile->update([
                    'first_name' => $request->first_name ?? $user->profile->first_name,
                    'last_name' => $request->last_name ?? $user->profile->last_name,
                    'birth_date' => $request->birth_date ?? $user->profile->birth_date,
                    'gender' => $request->gender ?? $user->profile->gender,
                    'address' => $request->address ?? $user->profile->address,
                ]);
            } else {
                $imageName = null;
                if ($request->avatar) {
                    Storage::disk('public')->deleteDirectory('/files/' . ImageHelper::FOLDER_PROFILES . '/' . $user->id);
                    $imageName = ImageHelper::getRandomName($request->avatar);

                    $this->uploadAvatar($user->id, $request->avatar, $imageName);
                }

                $user->profile->update([
                    'first_name' => $request->first_name ?? $user->profile->first_name,
                    'last_name' => $request->last_name ?? $user->profile->last_name,
                    'birth_date' => $request->birth_date ?? $user->profile->birth_date,
                    'gender' => $request->gender ?? $user->profile->gender,
                    'address' => $request->address ?? $user->profile->address,
                    'avatar' => $imageName ?? $user->profile->avatar,
                ]);
            }

            DB::commit();

            return $user;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * @throws \Throwable
     */
    public function remove(int $id): void
    {
        $user = User::findOrFail($id);
        DB::beginTransaction();
        try {
            $user->delete();

            $this->deleteAvatarDirectory($id);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    public function removeAvatarDirectory(int $id): bool
    {
        $user = User::findOrFail($id);

        return $this->deleteAvatarDirectory($user->id) && $user->profile->update(['avatar' => null]);
    }

    public function deleteAvatarDirectory(int $id): bool
    {
        return Storage::disk('public')->deleteDirectory('/files/' . ImageHelper::FOLDER_PROFILES . '/' . $id);
    }

    private function uploadAvatar(int $userId, UploadedFile $file, string $imageName): void
    {
        ImageHelper::saveThumbnail($userId, ImageHelper::FOLDER_PROFILES, $file, $imageName);
        ImageHelper::saveOriginal($userId, ImageHelper::FOLDER_PROFILES, $file, $imageName);
    }
}
