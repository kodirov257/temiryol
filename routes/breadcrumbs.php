<?php

use App\Models\Department;
use App\Models\Organization;
use App\Models\Region;
use App\Models\User\User;
use Diglactic\Breadcrumbs\Generator as Crumbs;

Breadcrumbs::for('home', function (Crumbs $crumbs) {
    $crumbs->push(trans('adminlte.home'), route('home'));
});

Breadcrumbs::for('login', function (Crumbs $crumbs) {
});


################################### Admin

Breadcrumbs::for('dashboard.home', function (Crumbs $crumbs) {
    $crumbs->push(trans('adminlte.home'), route('dashboard.home'));
});

// Users

Breadcrumbs::for('dashboard.users.index', function (Crumbs $crumbs) {
    $crumbs->parent('dashboard.home');
    $crumbs->push(trans('menu.users'), route('dashboard.users.index'));
});

Breadcrumbs::for('dashboard.users.create', function (Crumbs $crumbs) {
    $crumbs->parent('dashboard.users.index');
    $crumbs->push(trans('adminlte.create'), route('dashboard.users.create'));
});

Breadcrumbs::for('dashboard.users.show', function (Crumbs $crumbs, User $user) {
    $crumbs->parent('dashboard.users.index');
    $crumbs->push($user->name, route('dashboard.users.show', $user));
});

Breadcrumbs::for('dashboard.users.edit', function (Crumbs $crumbs, User $user) {
    $crumbs->parent('dashboard.users.show', $user);
    $crumbs->push(trans('adminlte.edit'), route('dashboard.users.edit', $user));
});


// Regions

Breadcrumbs::for('dashboard.regions.index', function (Crumbs $crumbs) {
    $crumbs->parent('dashboard.home');
    $crumbs->push(trans('menu.regions'), route('dashboard.regions.index'));
});

Breadcrumbs::for('dashboard.regions.create', function (Crumbs $crumbs) {
    $crumbs->parent('dashboard.regions.index');
    $crumbs->push(trans('adminlte.create'), route('dashboard.regions.create'));
});

Breadcrumbs::for('dashboard.regions.show', function (Crumbs $crumbs, Region $region) {
    if ($parent = $region->parent) {
        $crumbs->parent('dashboard.regions.show', $parent);
    } else {
        $crumbs->parent('dashboard.regions.index');
    }
    $crumbs->push($region->name, route('dashboard.regions.show', $region));
});

Breadcrumbs::for('dashboard.regions.edit', function (Crumbs $crumbs, Region $region) {
    $crumbs->parent('dashboard.regions.show', $region);
    $crumbs->push(trans('adminlte.edit'), route('dashboard.regions.edit', $region));
});


// Organizations

Breadcrumbs::for('dashboard.organizations.index', function (Crumbs $crumbs) {
    $crumbs->parent('dashboard.home');
    $crumbs->push(trans('menu.organizations'), route('dashboard.organizations.index'));
});

Breadcrumbs::for('dashboard.organizations.create', function (Crumbs $crumbs) {
    $crumbs->parent('dashboard.organizations.index');
    $crumbs->push(trans('adminlte.create'), route('dashboard.organizations.create'));
});

Breadcrumbs::for('dashboard.organizations.show', function (Crumbs $crumbs, Organization $organization) {
    if ($parent = $organization->parent) {
        $crumbs->parent('dashboard.organizations.show', $parent);
    } else {
        $crumbs->parent('dashboard.organizations.index');
    }
    $crumbs->push($organization->name, route('dashboard.organizations.show', $organization));
});

Breadcrumbs::for('dashboard.organizations.edit', function (Crumbs $crumbs, Organization $organization) {
    $crumbs->parent('dashboard.organizations.show', $organization);
    $crumbs->push(trans('adminlte.edit'), route('dashboard.organizations.edit', $organization));
});


// Departments

Breadcrumbs::for('dashboard.departments.index', function (Crumbs $crumbs) {
    $crumbs->parent('dashboard.home');
    $crumbs->push(trans('menu.departments'), route('dashboard.departments.index'));
});

Breadcrumbs::for('dashboard.departments.create', function (Crumbs $crumbs) {
    $crumbs->parent('dashboard.departments.index');
    $crumbs->push(trans('adminlte.create'), route('dashboard.departments.create'));
});

Breadcrumbs::for('dashboard.departments.show', function (Crumbs $crumbs, Department $department) {
    if ($parent = $department->parent) {
        $crumbs->parent('dashboard.departments.show', $parent);
    } else {
        $crumbs->parent('dashboard.departments.index');
    }
    $crumbs->push($department->name, route('dashboard.departments.show', $department));
});

Breadcrumbs::for('dashboard.departments.edit', function (Crumbs $crumbs, Department $department) {
    $crumbs->parent('dashboard.departments.show', $department);
    $crumbs->push(trans('adminlte.edit'), route('dashboard.departments.edit', $department));
});

Breadcrumbs::for('dashboard.departments.employees.add.form', function (Crumbs $crumbs, Department $department) {
    $crumbs->parent('dashboard.departments.show', $department);
    $crumbs->push(trans('adminlte.department.add_employee'), route('dashboard.departments.employees.add.form', $department));
});
