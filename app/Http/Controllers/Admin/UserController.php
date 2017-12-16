<?php

namespace App\Http\Controllers\Admin;

use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    use \App\FilterController;
    use \App\SearchController;
    use \App\UserTrait;
    use RegistersUsers;

    public $indexRoute = 'admin.user.index';
    public $prefix = 'User';

    /**
     * Display a listing of the resource
     *
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function index(Request $request)
    {
        if (Auth::user()->is_admin == 0)
            return redirect()->route('admin.index');

        $users = new User();

        // Filter
        $users = $this->filterExec($request, $users);

        // Search
        $users = $this->searchByTitle($request, $users);

        $users = $users->paginate(20);

        return view('admin.users.index', [
            'users' => $users,
            'searchText' => $this->searchText,
            'filter' => $this->arFilter,
            'sort' => $this->sortVal
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Auth::user()->is_admin == 0)
            return redirect()->route('admin.index');

        return view('admin.users.create', [
            'users' => User::orderby('name', 'asc')->select(['id', 'name'])->get(),
            'user' => [],
            'delimiter' => ''
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $requestData = $this->getRequestData($request);

        $user = User::create($requestData);

        $request->session()->flash('success', 'Пользователь добавлен');
        return redirect()->route('admin.user.edit', $user);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        if (Auth::user()->is_admin == 0)
            return redirect()->route('admin.index');

        return view('admin.users.edit', [
            'users' => User::orderby('name', 'asc')->select(['id', 'name'])->get(),
            'user' => $user,
            'delimiter' => '-'
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        if ($request->delete)
            return $this->destroy($request, $user);

        $requestData = $this->getRequestData($request);

        $user->update($requestData);

        $request->session()->flash('success', 'Пользователь отредактирован');

        if ($request->saveFromList)
            return redirect()->route('admin.user.index');

        return redirect()->route('admin.user.edit', $user);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param Request $request
     * @param User $user
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(Request $request, User $user)
    {
        // Change fields created_by and modify_by for items, categories, reviews
        $this->changeCreatedModifyBy($user->id);

        User::destroy($user->id);
        $request->session()->flash('success', 'Пользователь удален');
        return redirect()->route('admin.user.index');
    }

    /**
     * Get request and check
     *
     * @param Request $request
     * @return array
     */
    public function getRequestData(Request $request)
    {
        $requestData = $request->all();

        if (empty($requestData['password'])) {
            $obUser = User::where('id', $requestData['id'])->get();
            $requestData['password'] = $obUser->pluck('password')[0];
        }
        else
            $requestData['password'] = bcrypt($requestData['password']);

        return $requestData;
    }
}
