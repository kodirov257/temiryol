<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Users\CreateRequest;
use App\Http\Requests\Admin\Users\UpdateRequest;
use App\Models\User\Profile;
use App\Models\User\User;
use App\Services\Manage\User\UserService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class UserController extends Controller
{
    private UserService $service;

    public function __construct(UserService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request): View
    {
        $query = User::orderByDesc('id');

        if (!empty($value = $request->get('id'))) {
            $query->where('id', $value);
        }

        if (!empty($value = $request->get('name'))) {
            $query->where('name', 'LIKE', '%' . $value . '%');
        }

        if (!empty($value = $request->get('email'))) {
            $query->where('email', 'LIKE', '%' . $value . '%');
        }

        $value = $request->get('status');
        if (!empty($value) || $value === '0') {
            $query->where('status', $value);
        }

        if (!empty($value = $request->get('role'))) {
            $query->where('role', $value);
        }

        $users = $query->paginate(20)
            ->appends('id', $request->get('id'))
            ->appends('name', $request->get('name'))
            ->appends('email', $request->get('email'))
            ->appends('status', $request->get('status'))
            ->appends('role', $request->get('role'));

        $statuses = User::statusesList();
        $roles = User::rolesList();

        return view('admin.users.index', compact('users', 'statuses', 'roles'));
    }

    public function create(): View
    {
        $roles = User::rolesList();
        $statuses = User::statusesList();
        $genders = Profile::gendersList();

        return view('admin.users.create', compact('roles', 'statuses', 'genders'));
    }

    public function store(CreateRequest $request): RedirectResponse
    {
        try {
            $genre = $this->service->store($request);
            session()->flash('message', 'запись обновлён ');
            return redirect()->route('dashboard.users.show', $genre);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function show(User $user): View
    {
        return view('admin.users.show', compact('user'));
    }

    public function edit(User $user): View
    {
        $roles = User::rolesList();
        $statuses = User::statusesList();
        $genders = Profile::gendersList();
        return view('admin.users.edit', compact('user', 'roles', 'statuses', 'genders'));
    }

    public function update(UpdateRequest $request, User $user): RedirectResponse
    {
        try {
            $this->service->update($user->id, $request);
            session()->flash('message', 'запись обновлён ');
            return redirect()->route('dashboard.users.show', $user);
        } catch (\Exception|\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function destroy(User $user): RedirectResponse
    {
        if (!Auth::user()->isAdmin()) {
            return redirect()->route('dashboard.users.index');
        }

        try {
            $this->service->remove($user->id);

            session()->flash('message', 'запись обновлён ');
            return redirect()->route('dashboard.users.index');
        } catch (\Exception|\Throwable $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function removeAvatar(User $user): JsonResponse
    {
        if ($this->service->removeAvatarDirectory($user->id)) {
            return response()->json('The avatar is successfully deleted!');
        }
        return response()->json('The avatar is not deleted!', 400);
    }
}
